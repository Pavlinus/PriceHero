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
     * Извлекает данные игр, цены которых были недавно обновлены
     * @param int $offset смещение для начала выборки
     * @return array массив данных игр
     */
    public function getLastUpdates($offset = 0)
    {
        $priceId = $this->getPriceUpdates($offset);
        $and = array(
            'Platform.platform_id' => array(1)
        );
        
        /* Учитываем установленные фильтры */
        if(isset($_POST['platformId']) && !empty($_POST['platformId']))
        {
            $and['Platform.platform_id'] = $_POST['platformId'];
        }
        
        if(isset($_POST['genreId']) && !empty($_POST['genreId']))
        {
            $and['Genre.genre_id'] = $_POST['genreId'];
        }
        
        return $this->getGames($priceId, 'total.price_id', $and);
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
        
        $range = $this->getDateRange();
        $offset *= self::UPDATES_ON_PAGE;
        
        $query  = "SELECT Price.new_price as price, Game.game_id as gameId, ";
        $query .= "Price.price_id, Price.lastUpdate FROM t_total Total ";
        $query .= "LEFT JOIN t_game Game USING(game_id) ";
        $query .= "LEFT JOIN t_price Price USING(price_id) ";
        $query .= "WHERE Price.lastUpdate BETWEEN "
                  ."'".$range['leftDate']."' AND '".$range['curDate']."' ";
        $query .= "ORDER BY price ASC LIMIT ".$offset.",".self::UPDATES_ON_PAGE;
        
        $rows = $this->msql->Select($query);
        $priceAssoc = array();
        $priceId = array();
        
        if(!$rows)
        {
            return array();
        }
        
        foreach($rows as $row)
        {
            /* если игры нет в итоговом списке */
            if(!isset($priceAssoc[ $row['gameId'] ]))
            {
                $priceAssoc[ $row['gameId'] ] = 1;
                $priceId[] = $row['price_id'];
            }
        }
        
        return $priceId;
    }
    
    
    /**
     * Формируем временной диапазон
     * @return array массив граничных значений дат
     */
    private function getDateRange()
    {
        $leftDate = new DateTime();
        $currentDate = new DateTime();
        $year = $currentDate->format('Y');
        $month = $currentDate->format('m');
        $day = $currentDate->format('d');
        $dayOffset = 1;
        $format = 'Y-m-d H:i:s';
        
        if($day != 1)
        {
            $day -= $dayOffset;
        }
        
        $leftDate->setDate($year, $month, $day);
        
        $cDate = $currentDate->format($format);
        $lDate = $leftDate->format($format);
        
        return array(
            'curDate' => $cDate,
            'leftDate' => $lDate);
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
        
        if($and)
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