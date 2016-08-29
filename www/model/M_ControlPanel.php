<?php

class M_ControlPanel {

    private static $instance;
    private $msql;
    private $parser;

    public function __construct() 
    {
        $this->msql = M_MSQL::Instance();
        
        if($this->parser == null)
        {
            $this->parser = M_PriceParser::Instance();
        }
    }

    /**
     * <p>Работает с экземпляром класса</p>
     * @return Экземпляр класса
     */
    public static function Instance() 
    {
        if (self::$instance == null) 
        {
            self::$instance = new M_ControlPanel();
        }

        return self::$instance;
    }

    /**
     * <p>Добавляет данные игры в БД</p>
     * @return Id новой игры в БД
     */
    public function addGame()
    {
        $name = htmlspecialchars($_POST['name']);
        $genre = htmlspecialchars($_POST['genre']);

        $object = array(
            'name' => $name,
            'genre_id' => $genre,
            'image' => '/images/default.jpg');

        return $this->msql->Insert('t_game', $object);
    }

    /**
     * <p>Добавляет новую ссылку в БД</p>
     * @return Id новой(ых) ссылки(ок), иначе false
     */
    public function addLink() 
    {
        $linksData = array();

        if (isset($_POST['links'])) 
        {
            foreach ($_POST['links'] as $item) 
            {
                $object = array(
                    'site_id' => htmlspecialchars($item['service']),
                    'link' => htmlspecialchars($item['link']),
                );

                $id = $this->msql->Insert('t_link', $object);

                if ($id) 
                {
                    $linksData[] = array(
                        'linkId' => $id,
                        'site_id' => htmlspecialchars($item['service']),
                        'platform' => htmlspecialchars($item['platform'])
                    );
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }

        return $linksData;
    }

    /**
     * <p>Добавляет запись об игре в сводную таблицу.</p>
     * @param gameId - идентификатор новой игры
     * @param linksArr - массив ID новых ссылок
     * @param priceArr - массив ID цен
     * @return Массив ID новых записей `t_total`, иначе false
     */
    public function addTotal($gameId, $linksArr, $priceArr) 
    {
        if(empty($linksArr) || empty($priceArr))
        {
            return array();
        }
        
        $totalIdArr = array();

        foreach ($linksArr as $linkItem) 
        {
            $object = array(
                'game_id' => $gameId,
                'platform_id' => $linkItem['platform'],
                'link_id' => $linkItem['linkId'],
                'price_id' => $priceArr[ $linkItem['linkId'] ]);

            $id = $this->msql->Insert('t_total', $object);

            if (!$id) {
                return false;
            }

            $totalIdArr[] = $id;
        }

        return $totalIdArr;
    }

    
    /**
     * <p>Добавляет запись о цене.</p>
     * @param $priceList - массив цен
     * @return Массив ID новых записей `t_price`
     */
    public function addPrice($priceList) 
    {
        $date = date("Y-m-d");
        $priceIdArray = array();

        foreach ($priceList as $priceItem)
        {
            $object = array(
                'new_price' => $priceItem['price'],
                'old_price' => $priceItem['price'],
                'lastUpdate' => $date
            );
            
            $id = $this->msql->Insert('t_price', $object);
            $priceIdArray[ $priceItem['linkId'] ] = $id;
        }

        return $priceIdArray;
    }
    
    
    /**
    * <p>Список игр (постраничный вывод).</p>
    */
    public function getGamesList($page)
    {
        $itemsPerPage = 25;
        $startFrom = $itemsPerPage * ($page - 1);
        $query = "SELECT * FROM t_game LIMIT $startFrom, $itemsPerPage";
        
        return $this->msql->Select($query);
    }
    
    
    /**
    * Получение данных о игре для редактирования
    * @param int $id ID игры
    * @return array Массив данных игры
    */
    public function getGameDataToEdit($id)
    {
      if($id == null || $id == '')
      {
        return false;  
      }

      $gameId = htmlspecialchars($id);
      $query = "SELECT * FROM t_total WHERE game_id=$gameId";
      $rows = $this->msql->Select($query);
      
      if(!$rows)
      {
          return false;
      }

      $platformsArray = array();    // массив ID платформ
      $linksArray = array();        // массив ID ссылок
      $linkToPlatform = array();    // связывает тип платформы и ссылку
      
      foreach($rows as $row)
      {
          $linkId = htmlspecialchars($row['link_id']);
          $platformId = htmlspecialchars($row['platform_id']);
          $linkToPlatform[$linkId] = $platformId;
          $linksArray[] = $linkId;
          $platformsArray[] = $platformId;
      }

      $gameData = $this->getTblGame($rows[0]['game_id']);
      if(!$gameData)
      {
          return false;
      }
      
      $linksData = $this->getTblLinkMerged($linksArray);
      if(!$linksData)
      {
          return false;
      }
      
      $platformsData = $this->getTblPlatform($platformsArray);
      if(!$platformsData)
      {
          return false;
      }
      
      $genreData = $this->getTblGenre($gameData[0]['genre_id']);
      if(!$genreData)
      {
          return false;
      }
      
      return array(
          'gameId' => $gameData[0]['game_id'],
          'gameName' => $gameData[0]['name'],
          'genreData' => $genreData,
          'links' => $linksData,
          'linkToPlatform' => $linkToPlatform
      );
    }
    
    
    /**
    * <p>Получение данных о игре из `t_game`</p>
    * 
    */
    public function getTblGame($id)
    {
        if($id == null || !is_numeric($id))
        {
            return false;
        }
        
        $query = "SELECT * FROM t_game WHERE game_id=$id";
        
        return $this->msql->Select($query);
    }
    
    
    /**
    * Получение объединенных данных о ссылке из `t_link` и `t_site`
    * @param array $links Строка ID извлекаемых ссылок
    * @return array Массив значений ссылок и сервисов, иначе false
    */
    public function getTblLinkMerged($links)
    {
        if(empty($links))
        {
            return false;
        }
        
        $linksStr = '(' . implode($links) . ')';
        
        $query = " SELECT * FROM t_link "
                . "LEFT JOIN t_site ON t_site.site_id=t_link.site_id "
                . "WHERE link_id IN $linksStr";

        return $this->msql->Select($query);
    }
    
    
    /**
    * Получение данных о платформе из `t_platform`
    * @param array $platforms Строка ID извлекаемых платформ
    * @return array Массив значений платформ, иначе false
    */
    public function getTblPlatform($platforms)
    {
        if(empty($platforms))
        {
            return false;
        }
        
        $platformsStr = '(' . implode($platforms) . ')';
        
        $query = "SELECT * FROM t_platform WHERE platform_id IN $platformsStr";
        $rows = $this->msql->Select($query);
        
        if(!$rows)
        {
            return false;
        }
        
        $result = array();
        
        foreach($rows as $row)
        {
            $result[ $row['platform_id'] ] = $row['name'];
        }
        
        return $result;
    }
    
    
    /**
    * Получение данных о жанре из `t_genre`
    * @param int $genreId ID жанра
    * @return array Массив значений жанра, иначе false
    */
    public function getTblGenre($genreId)
    {
        if($genreId == null || !is_numeric($genreId))
        {
            return false;
        }
        
        $id = htmlspecialchars($genreId);
        $query = "SELECT * FROM t_genre WHERE genre_id=$id";
        $rows = $this->msql->Select($query);
        
        if(!$rows)
        {
            return false;
        }
        
        return $rows[0];
    }
    
    
    /**
    * Сохранение данных формы редактирования игры
    * @return boolean True если сохранение прошло, иначе False
    */
    public function saveGameData()
    {
        if(!isset($_REQUEST['gameId']) || $_REQUEST['gameId'] == null)
        {
            return false;
        }
        
        // Обновление таблицы `t_game`
        $this->updateTblGame();
        
        // Добавление новых данных
        $linksId = $this->addLink();
        $priceList = $this->parser->parse($linksId);
        $priceId = $this->addPrice($priceList);
        $totalId = $this->addTotal($_REQUEST['gameId'], $linksId, $priceId);
        
        return $totalId;
    }
    
    
    /**
    * Обновляет данные игры в `t_game`
    * @return boolean True если сохранение прошло, иначе False
    */
    private function updateTblGame()
    {
        $id = htmlspecialchars($_REQUEST['gameId']);
        $name = htmlspecialchars($_REQUEST['name']);
        $genre = htmlspecialchars($_REQUEST['genre']);
        $object = array(
            'name' => $name,
            'genre_id' => $genre
        );
        $where = "game_id=$id";

        return $this->msql->Update('t_game', $object, $where);
    }
    
    

}
