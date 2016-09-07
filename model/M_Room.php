<?php

/**
* <p>Класс выполнения авторизации пользователя</p>
* @author Pavel Kovyrshin
* @date 03.09.2016
*/

include_once "M_MSQL.php";

class M_Room
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
            self::$instance = new M_Room();
        }

        return self::$instance;
    }
	
    
    /**
     * Извлекает ID игр, привязанных к пользователю
     * @return array Массив ID игр, иначе false
     */
    public function getUserGamesId()
    {
        if(!isset($_COOKIE['user_id']))
        {
            return false;
        }
        
        $userId = $_COOKIE['user_id'];
        $query = "SELECT game_id FROM t_tracker WHERE user_id=$userId";
        $rows = $this->msql->Select($query);
        $arGameId = array();
        
        if($rows)
        {
            foreach ($rows as $row)
            {
                $arGameId[] = $row['game_id'];
            }
        }
        
        return $arGameId;
    }
}