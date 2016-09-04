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
     * 
     * @return type
     */
    public function getLastUpdates()
    {
        $query = "SELECT price_id FROM t_price ORDER BY lastUpdate DESC LIMIT 10";
        $rows = $this->msql->Select($query);
        $priceId = array();
        
        if(!$rows)
        {
            return array();
        }
        
        foreach($rows as $row)
        {
            $priceId[] = $row['price_id'];
        }
        
        $this->getGamesByPriceId($priceId);
    }
    
    public function getGamesByPriceId($priceId)
    {
        $priceStr = "(" . implode(",", $priceId) . ")";
        $query = "SELECT Game.name as game, Genre.name as genre, Platform.name as platform, ";
        $query .= "Price.new_price as price, Link.link FROM t_total total ";
        $query .= "LEFT JOIN t_game Game ON (Game.game_id = total.game_id) ";
        $query .= "LEFT JOIN t_genre Genre ON (Genre.genre_id = Game.genre_id) ";
        $query .= "LEFT JOIN t_platform Platform ON (Platform.platform_id = total.platform_id) ";
        $query .= "LEFT JOIN t_price Price ON (Price.price_id = total.price_id) ";
        $query .= "LEFT JOIN t_link Link ON (Link.link_id = total.link_id) ";
        $query .= "WHERE total.price_id IN $priceStr";
        
        $rows = $this->msql->Select($query);
        
        echo "<pre>";
        print_r($rows);
        echo "</pre>";
    }
}