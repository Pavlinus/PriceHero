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
     * @return array массив данных игр
     */
    public function getLastUpdates()
    {
        $priceId = $this->getPriceUpdates();
        $and = array(
            'Platform.platform_id' => array(1)
        );
        
        return $this->getGames($priceId, 'total.price_id', $and);
    }
    
    
    /**
     * Извлекает последние изменения цен
     * @return array массив ID обновленных цен
     */
    public function getPriceUpdates()
    {
        $query  = "SELECT Price.new_price as price, Game.game_id as gameId, ";
        $query .= "Price.price_id FROM t_total Total ";
        $query .= "LEFT JOIN t_game Game USING(game_id) ";
        $query .= "LEFT JOIN t_price Price USING(price_id) ";
        $query .= "ORDER BY price ASC LIMIT 15";
        
        $rows = $this->msql->Select($query);
        $priceAssoc = array();
        $priceId = array();
        
        if(!$rows)
        {
            return array();
        }
        
        foreach($rows as $row)
        {
            if(!isset($priceAssoc[ $row['gameId'] ]))
            {
                $priceAssoc[ $row['gameId'] ] = 1;
                $priceId[] = $row['price_id'];
            }
        }
        
        return $priceId;
    }
    
    
    /**
     * Список данных об играх для отображения
     * @param array $arrId массив ID элементов
     * @param string $where поле таблицы
     * @param array $and дополнительное условие выборки
     * @return array массив данных об играх
     */
    public function getGames($arrId, $where, $and = false)
    {
        $userId = 0;
        
        if(isset($_COOKIE['user_id']))
        {
            $userId = htmlspecialchars($_COOKIE['user_id']);
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
        
        $query .= "WHERE $where IN $arrStr $andStr";

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