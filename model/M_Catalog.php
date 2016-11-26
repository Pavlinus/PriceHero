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
    private $arSteamSiteId;
    
    /* количество выводимых обновлений на странице */
    const UPDATES_ON_PAGE = 12;
    const ROWS_LIMIT = 100;

    public function __construct()
    {
        $this->msql = M_MSQL::Instance();
        $this->arSteamSiteId = array(1,3,8,15);
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


    public function getSteamSiteIdArray()
    {
        return $this->arSteamSiteId;
    }

    
    /**
     * Извлекает данные недавно добавленных игр
     * @param int $offset смещение для начала выборки
     * @return array массив данных игр
     */
    public function getLastUpdates($offset = 0, $priceFrom = 1, $priceTo = 10000)
    {
        $priceId = $this->getPriceUpdates($offset, $priceFrom, $priceTo);

        $and = array(
            'total.platform_id' => array(1),
        );
        $additional = " AND Price.new_price >= $priceFrom AND Price.new_price <= $priceTo";

        /* Учитываем установленные фильтры */
        if(isset($_POST['platformId']) && !empty($_POST['platformId']))
        {
            $and['total.platform_id'] = $_POST['platformId'];
        }
        
        if(isset($_POST['genreId']) && !empty($_POST['genreId']))
        {
            $and['Genre.genre_id'] = $_POST['genreId'];
        }

        if(isset($_POST['steamId']) && $_POST['steamId'] != '')
        {
            if($_POST['steamId'] == 'keys')
            {
                $and['Link.site_id'] = $this->arSteamSiteId;
            }
        }
        
        return $this->getGames($priceId, 'total.price_id', $and, $additional);
    }
    
    
    /**
     * Получаем ID последних добавленных игр
     * @param int $offset начало выборки
     * @return array массив ID игр
     */
    public function getLastAddedGamesId($offset, $priceFrom, $priceTo)
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
        
        /* определяем жанр  */
        if(isset($_POST['genreId']))
        {
            $where .= "AND Game.genre_id IN ("
                    . implode(",", $_POST['genreId']) . ") ";
        }

        if(isset($_POST['steamId']) && $_POST['steamId'] != '')
        {
            if($_POST['steamId'] == 'keys')
            {
                $where .= "AND Link.site_id IN ("
                    . implode(",", $this->arSteamSiteId) . ") ";
            }
        }
        
        /* если пользователь авторизован, исключаем из выборки 
         * отслеживаемые им игры */
        $authCond = $this->getAuthorizedUserCondition();
        
        if($where != '' && $authCond['where'] != '')
        {
            $where .= ' AND ' . $authCond['where'];
        }

        $where .= " AND Price.new_price >= $priceFrom AND Price.new_price <= $priceTo ";
        
        $query =  "SELECT DISTINCT Game.game_id, Total.platform_id FROM t_game Game "
                . "LEFT JOIN t_total Total ON (Total.game_id=Game.game_id) "
                . "LEFT JOIN t_price Price ON (Price.price_id=Total.price_id) "
                . "LEFT JOIN t_link Link ON (Link.link_id=Total.link_id) "
                . $authCond['leftJoin']
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
     * Формирование условия выборки, где вхождения отслеживаемых
     * игр исключаются
     * @return array массив данных с условиями
     */
    private function getAuthorizedUserCondition()
    {
        $trackerJoin = '';
        $where = '';
        
        if(isset($_POST['platformId']) || isset($_POST['genreId']))
        {
            $filter = true;
        }
        else
        {
            $filter = false;
        }
        
        if(isset($_COOKIE['user_id']))
        {
            $userId = htmlspecialchars($_COOKIE['user_id']);
            $trackerJoin = 
                      "LEFT JOIN t_tracker Tracker "
                    . "ON (Tracker.game_id = Total.game_id "
                    . "AND Tracker.user_id = $userId ";
            
            if(!$filter)
            {
                $trackerJoin .= " AND Tracker.platform_id = Total.platform_id ";
            }
            
            $trackerJoin .= ")";
            
            $where .= ' Tracker.user_id IS NULL ';
        }
        
        return array(
            'leftJoin' => $trackerJoin,
            'where' => $where
        );
    }
    
    
    /**
     * Извлекает последние изменения цен (минимальное значение)
     * @param int $offsetVal смещение для начала выборки
     * @return array массив ID обновленных цен
     */
    public function getPriceUpdates($offsetVal = 0, $priceFrom = 1, $priceTo = 10000)
    {
        $offset = htmlspecialchars($offsetVal);
        
        if(!is_numeric($offset))
        {
            $offset = 0;
        }
        
        /* получаем ID последних добавленных игр */
        $arGamesId = $this->getLastAddedGamesId($offset, $priceFrom, $priceTo);
        if(empty($arGamesId))
        {
            return array();
        }
        
        $lowestPrice = $this->getLowestPriceId($arGamesId, array(), $priceFrom, $priceTo);

        return array_slice($lowestPrice, 0, self::UPDATES_ON_PAGE);
    }
    
    
    /**
     * Извлекаем минимальную цену набора игр
     * @param array $arGamesId массив ID игр
     * @return array массив ID цен
     */
    public function getLowestPriceId($arGamesId, $arPlatform = array(),
        $priceFrom = 1, $priceTo = 10000)
    {
        if(empty($arGamesId))
        {
            return array();
        }
        
        $inPlatformId = '';
        if(!empty($arPlatform))
        {
            $platformStr = "(" . implode(",", $arPlatform) . ")";
            $platformSet = htmlspecialchars($platformStr);
            $inPlatformId = "AND Total.platform_id IN $platformSet";
        }

        $inLinkId = '';
        if(isset($_POST['steamId']) && $_POST['steamId'] != '')
        {
            if($_POST['steamId'] == 'keys')
            {
                $steamId = "(" . implode(",", $this->arSteamSiteId) . ")";
                $inLinkId = "AND Link.site_id IN $steamId";
            }
        }
        
        $inGamesId = "(". implode(",", $arGamesId) .")";
        
        $query  = "SELECT Price.new_price as price, Game.game_id as gameId, "
                . "Price.price_id, Price.lastUpdate, Total.platform_id as platformId "
                . "FROM t_total Total "
                . "LEFT JOIN t_game Game USING(game_id) "
                . "LEFT JOIN t_price Price USING(price_id) "
                . "LEFT JOIN t_link Link USING(link_id) "
                . "WHERE Price.new_price <> 0 AND Total.game_id IN $inGamesId $inPlatformId "
                . "AND Price.new_price >= $priceFrom AND Price.new_price <= $priceTo $inLinkId"
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
        
        /* получаем все id платформ */
        $mPlatform = M_Platform::Instance();
        $arPlatform = $mPlatform->getPlatformId();
        
        if(isset($_POST['platformId']))
        {
            $arPlatform = $_POST['platformId'];
        }
	else
	{
	    $arPlatform = array(1);
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
                
                $priceId[] = $row['price_id'];
                
                /*
                if(count($priceId) < self::UPDATES_ON_PAGE)
                {
                    $priceId[] = $row['price_id'];
                }
                else
                {
                    break;
                }
                 * 
                 */
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
        $query  = "SELECT Game.name as game, Genre.name as genre, Platform.name as platform, Platform.platform_id, "
                . "Link.site_id as site_id, Price.new_price as price, Link.link, Game.image, ";
        $query .= "Game.game_id, Tracker.tracker_id FROM t_total total ";
        $query .= "LEFT JOIN t_game Game ON (Game.game_id = total.game_id) ";
        $query .= "LEFT JOIN t_genre Genre ON (Genre.genre_id = Game.genre_id) ";
        $query .= "LEFT JOIN t_platform Platform ON (Platform.platform_id = total.platform_id) ";
        $query .= "LEFT JOIN t_price Price ON (Price.price_id = total.price_id) ";
        $query .= "LEFT JOIN t_link Link ON (Link.link_id = total.link_id) ";
        $query .= "LEFT JOIN t_tracker Tracker ON (Tracker.game_id = total.game_id AND Tracker.user_id = $userId "
                . "AND Tracker.platform_id = total.platform_id) ";
        
        if($and && !empty($and) && is_array($and))
        {
            foreach($and as $key => $cond)
            {
                $andStr .= htmlspecialchars("AND $key IN (" . implode(",", $cond) . ") ");
            }
        }
        else if($and != '')
        {
            $andStr = $and;
        }   

        $query .= "WHERE $where IN $arrStr $andStr $additional";
        //$query = htmlspecialchars($query);
        //echo $query;
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
    
    
    /**
     * Получаем список схожих предложений
     * @return array массив предложений
     */
    public function getSimilarOffer()
    {
        if(isset($_POST['game_id']) &&
           isset($_POST['platform_id']) &&
           isset($_POST['site_id']) &&
           isset($_POST['price_from']))
        {
            $gameId = htmlspecialchars($_POST['game_id']);
            $platformId = htmlspecialchars($_POST['platform_id']);
            $siteId = htmlspecialchars($_POST['site_id']);
            $priceFrom = htmlspecialchars($_POST['price_from']);
            $steamCondition = '';

            if(isset($_POST['steam']) && $_POST['steam'] != '')
            {
                $in = implode(", ", $this->arSteamSiteId);
                $steamCondition = " AND Link.site_id IN ($in) ";
            }
            
            $query =  "SELECT Link.link as link, Site.name as site, "
                    . "Price.new_price as price "
                    . "FROM t_total Total "
                    . "LEFT JOIN t_link Link ON(Link.link_id=Total.link_id) "
                    . "LEFT JOIN t_site Site ON(Site.site_id=Link.site_id) "
                    . "LEFT JOIN t_price Price ON(Price.price_id=Total.price_id) "
                    . "WHERE Total.game_id=$gameId AND "
                    . "Total.platform_id=$platformId AND "
                    . "Site.site_id<>$siteId AND "
                    . "Price.new_price<>0 AND Price.new_price >= $priceFrom $steamCondition"
                    . "ORDER BY price ASC";
            //echo $query;
            $rows = $this->msql->Select($query);
            
            return $rows;
        }
    }
}
