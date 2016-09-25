<?php

/**
* <p>Класс вывода каталога игр</p>
* @author Pavel Kovyrshin
* @date 04.09.2016
*/

include_once "M_MSQL.php";

class M_Catalog
{
    private $msql;
    private static $instance;
    
    /* количество выводимых обновлений на странице */
    const UPDATES_ON_PAGE = 9;
    const ROWS_LIMIT = 100;

    public function __construct()
    {
        $this->msql = M_MSQL::Instance();
    }


    /**
    * <p>Работает с экземпляром класса</p>
    * @return Экземпляр класса
    */
    public static function Instance()
    {
        if(self::$instance == null)
        {
            self::$instance = new M_Catalog();
        }

        return self::$instance;
    }

    
    /**
     * Извлекает данные недавно добавленных игр
     * @param int $offset смещение для начала выборки
     * @return array массив данных игр
     */
    public function getLastUpdates($offset = 0)
    {
        $priceId = $this->getPriceUpdates($offset);
        $and = array(
            'total.platform_id' => array(1)
        );
        
        /* Учитываем установленные фильтры */
        if(isset($_POST['platformId']) && !empty($_POST['platformId']))
        {
            $and['total.platform_id'] = $_POST['platformId'];
        }
        
        if(isset($_POST['genreId']) && !empty($_POST['genreId']))
        {
            $and['Genre.genre_id'] = $_POST['genreId'];
        }
        
        return $this->getGames($priceId, 'total.price_id', $and);
    }
    
    
    /**
     * Получаем ID последних добавленных игр
     * @param int $offset начало выборки
     * @return array массив ID игр
     */
    public function getLastAddedGamesId($offset)
    {
        $start = $offset * self::UPDATES_ON_PAGE;
        $where = '';
        
        /* определяем платформу  */
        if(isset($_POST['platformId']))
        {
            $where = "WHERE Total.platform_id IN ("
                    . implode(",", $_POST['platformId']) . ") ";
        }
        else
        {
            $where = "WHERE Total.platform_id=1 ";
        }
        
        $query =  "SELECT DISTINCT Game.game_id, Total.platform_id FROM t_game Game "
                . "LEFT JOIN t_total Total ON (Total.game_id=Game.game_id) "
                . $where
                . "ORDER BY Game.game_id DESC "
                . "LIMIT " . $start . "," . self::UPDATES_ON_PAGE . " ";
        $rows = $this->msql->Select($query);
        $arGamesId = array();
        
        foreach($rows as $row)
        {
            $arGamesId[] = $row['game_id'];
        }

        return $arGamesId;
    }
    
    
    /**
     * Извлекает последние изменения цен (минимальное значение)
     * @param int $offsetVal смещение для начала выборки
     * @return array массив ID обновленных цен
     */
    public function getPriceUpdates($offsetVal = 0)
    {
        $offset = htmlspecialchars($offsetVal);
        
        if(!is_numeric($offset))
        {
            $offset = 0;
        }
        
        /* получаем ID последних добавленных игр */
        $arGamesId = $this->getLastAddedGamesId($offset);
        if(empty($arGamesId))
        {
            return array();
        }

        return $this->getLowestPriceId($arGamesId);
    }
    
    
    /**
     * Извлекаем минимальную цену набора игр
     * @param array $arGamesId массив ID игр
     * @return array массив ID цен
     */
    public function getLowestPriceId($arGamesId)
    {
        if(empty($arGamesId))
        {
            return array();
        }
        
        $inGamesId = "(". implode(",", $arGamesId) .")";
        
        $query  = "SELECT Price.new_price as price, Game.game_id as gameId, "
                . "Price.price_id, Price.lastUpdate, Total.platform_id as platformId "
                . "FROM t_total Total "
                . "LEFT JOIN t_game Game USING(game_id) "
                . "LEFT JOIN t_price Price USING(price_id) "
                . "WHERE Total.game_id IN $inGamesId "
                . "ORDER BY price ASC ";
        
        $rows = $this->msql->Select($query);
        
        return $this->getPriceId($rows);
    }
    
    
    /**
     * Формируем массив ID цен
     * @param array $rows выборка цен из БД
     */
    private function getPriceId($rows)
    {
        $priceAssoc = array();
        $priceId = array();
        $arPlatform = array(1);
        
        if(isset($_POST['platformId']))
        {
            $arPlatform = $_POST['platformId'];
        }
        
        /* сохраняем ID цен в зависимости от игры и платформы */
        foreach($rows as $row)
        {
            /* если игры нет в итоговом списке, либо другая платформа */
            if(!isset($priceAssoc[ $row['gameId'] ]) ||
                !isset($priceAssoc[ $row['gameId'] ][ $row['platformId'] ]))
            {
                $priceAssoc[ $row['gameId'] ][ $row['platformId'] ] = 1;
                
                /* если не соответствует платформа */
                if(!in_array($row['platformId'], $arPlatform))
                {
                    continue;
                }
                
                if(count($priceId) < self::UPDATES_ON_PAGE)
                {
                    $priceId[] = $row['price_id'];
                }
                else
                {
                    break;
                }
            }
        }
        
        return $priceId;
    }
    
    
    /**
     * Список данных об играх для отображения
     * @param array $arrId массив ID элементов
     * @param string $where поле таблицы
     * @param array $and дополнительное условие выборки
     * @param array $additional дополнительные параметры
     * @return array массив данных об играх
     */
    public function getGames($arrId, $where, $and = false, $additional = '')
    {
        $userId = 0;
        
        if(isset($_COOKIE['user_id']))
        {
            $userId = htmlspecialchars($_COOKIE['user_id']);
        }
        
        if(empty($arrId))
        {
            return array();
        }
        
        $andStr = "";
        $arrStr = "(" . implode(",", $arrId) . ")";
        $query  = "SELECT Game.name as game, Genre.name as genre, Platform.name as platform, Platform.platform_id, ";
        $query .= "Price.new_price as price, Link.link, Game.image, Game.game_id, Tracker.tracker_id FROM t_total total ";
        $query .= "LEFT JOIN t_game Game ON (Game.game_id = total.game_id) ";
        $query .= "LEFT JOIN t_genre Genre ON (Genre.genre_id = Game.genre_id) ";
        $query .= "LEFT JOIN t_platform Platform ON (Platform.platform_id = total.platform_id) ";
        $query .= "LEFT JOIN t_price Price ON (Price.price_id = total.price_id) ";
        $query .= "LEFT JOIN t_link Link ON (Link.link_id = total.link_id) ";
        $query .= "LEFT JOIN t_tracker Tracker ON (Tracker.game_id = total.game_id AND Tracker.user_id = $userId) ";
        
        if($and && !empty($and))
        {
            foreach($and as $key => $cond)
            {
                $andStr .= "AND $key IN (" . implode(",", $cond) . ") ";
            }
        }
        
        $query .= "WHERE $where IN $arrStr $andStr $additional";
        $rows = $this->msql->Select($query);
        
        if(!$rows)
        {
            return array();
        }
        else
        {
            return $rows;
        }
    }
}