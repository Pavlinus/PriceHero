<?php


class M_ControlPanel {

    private static $instance;
    private $msql;
    private $parser;
    private $mGame;
    private $mGenre;
    private $mLink;
    private $mPlatform;
    private $mPrice;
    private $mTotal;

    public function __construct() 
    {
        $this->msql = M_MSQL::Instance();
        $this->mGame = M_Game::Instance();
        $this->mGenre = M_Genre::Instance();
        $this->mLink = M_Link::Instance();
        $this->mPlatform = M_Platform::Instance();
        $this->mPrice = M_Price::Instance();
        $this->mTotal = M_Total::Instance();
        
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
    * <p>Список игр (постраничный вывод).</p>
    */
    public function getGamesList($page)
    {
        $itemsPerPage = 25;
        $startFrom = $itemsPerPage * ($page - 1);
        $query = "SELECT * FROM t_game ORDER BY name LIMIT $startFrom, $itemsPerPage";
        
        return $this->msql->Select($query);
    }
    
    
    /**
     * Получаем фильтрованный по платформе список игр
     * @param type $where
     * @return type
     */
    public function getFilteredGamesList($where = '')
    {
        $itemsPerPage = 15;

        $query =  "SELECT DISTINCT Game.game_id, Game.name as game "
                . "FROM t_total Total "
                . "LEFT JOIN t_game Game ON (Game.game_id = Total.game_id) "
                . "LEFT JOIN t_platform Platform ON (Platform.platform_id = Total.platform_id) "
                . "$where"
                . "ORDER BY Game.name LIMIT $itemsPerPage";
        
        return $this->msql->Select($query);
    }
    
    
    /**
    * Получение данных о игре для редактирования
    * @param int $id ID игры
    * @return array Массив данных игры
    */
    public function getGameDataToEdit($id)
    {
      if($id == null || $id == '' || !is_numeric($id))
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

      $gameData = $this->mGame->getTblGame($rows[0]['game_id']);
      if(!$gameData)
      {
          return false;
      }

      $linksData = $this->mLink->getTblLinkMerged($linksArray);
      if(!$linksData)
      {
          return false;
      }
      
      $platformsData = $this->mPlatform->getTblPlatform($platformsArray);
      if(!$platformsData)
      {
          return false;
      }
      
      $genreData = $this->mGenre->getTblGenre($gameData[0]['genre_id']);
      if(!$genreData)
      {
          return false;
      }
      
      return array(
          'gameId' => $gameData[0]['game_id'],
          'gameName' => $gameData[0]['name'],
          'gameImage' => $gameData[0]['image'],
          'genreData' => $genreData,
          'links' => $linksData,
          'linkToPlatform' => $linkToPlatform
      );
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
        
        // Удаление ссылок
        $priceIdArray = $this->mLink->deleteTblLink();
        
        $this->mPrice->deleteTblPrice($priceIdArray);
        
        // Обновление ссылок
        $this->mLink->updateLink();
        
        // Обновление таблицы `t_game`
        $this->mGame->updateTblGame();
        
        if(!isset($_POST['links']) || empty($_POST['links']))
        {
            return array('ok');
        }
        
        // Добавление новых данных
        $linksId = $this->addLink();
        
        if(!$linksId)
        {
            return array();
        }
        
        $priceList = $this->parser->parse($linksId);
        $priceId = $this->addPrice($priceList);
        $totalId = $this->addTotal($_REQUEST['gameId'], $linksId, $priceId);
        
        return $totalId;
    }
    
    
    public function addGame()
    {
        $gameId = $this->mGame->addGame();
        $this->mGame->addGameKeywords($gameId);
        
        return $gameId;
    }
    
    
    public function addLink()
    {
        return $this->mLink->addLink();
    }
    
    
    public function addPrice($priceList)
    {
        return $this->mPrice->addPrice($priceList);
    }
    
    
    public function addTotal($gameId, $linksId, $priceId)
    {
        return $this->mTotal->addTotal($gameId, $linksId, $priceId);
    }
}
