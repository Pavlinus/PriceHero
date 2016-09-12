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
    private $catalog;


    public function __construct()
    {
        $this->msql = M_MSQL::Instance();
        
        if($this->catalog == null)
        {
            $this->catalog = M_Catalog::Instance();
        }
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
     * Извлекает игры, привязанные к пользователю
     * @return array Массив ID игр и ID платформ, иначе false
     */
    public function getUserGames()
    {
        if(!isset($_COOKIE['user_id']))
        {
            return false;
        }
        
        if(isset($_POST['platformId']) && !empty($_POST['platformId']))
        {
            $and  = " AND platform_id IN ";
            $and .= "(" . implode(",", $_POST['platformId']) . ")";
        }
        else
        {
            $and = '';
        }
        
        $userId = htmlspecialchars($_COOKIE['user_id']);
        $query = "SELECT game_id, platform_id FROM t_tracker ";
        $query .= "WHERE user_id=$userId $and";
        $rows = $this->msql->Select($query);
        $arGames = array();

        if($rows)
        {
            foreach ($rows as $row)
            {
                $arGames[] = array(
                    'game_id' => $row['game_id'],
                    'platform_id' => $row['platform_id']
                );
            }
        }
        
        return $arGames;
    }
    
    
    /**
     * Извлекает массив данных игр пользователя
     * @return array массив данных игр
     */
    public function getGamesList()
    {
        $arGames = $this->getUserGames();
        $arResult = array();
        
        foreach($arGames as $arItem)
        {
            $arId = array($arItem['game_id']);
            $where = 'Game.game_id';
            $and = array(
                'Platform.platform_id' => array($arItem['platform_id'])
            );
            
            $row = $this->catalog->getGames($arId, $where, $and);
            
            if(!empty($row))
            {
                $arResult[] = $row[0];
            }
        }

        return $arResult;
    }
}