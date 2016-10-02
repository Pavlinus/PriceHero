<?php

/**
* <p>Класс выполнения авторизации пользователя</p>
* @author Pavel Kovyrshin
* @date 03.09.2016
*/

include_once "M_MSQL.php";

class M_Platform
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
            self::$instance = new M_Platform();
        }

        return self::$instance;
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
        
        $platformsStr = '(' . implode(',', $platforms) . ')';
        
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
     * Получение ID платформ
     * @return array массив ID платформ
     */
    public function getPlatformId()
    {
        $query = "SELECT platform_id FROM t_platform";
        $rows = $this->msql->Select($query);
        $result = array();
        
        foreach($rows as $row)
        {
            $result[] = $row['platform_id'];
        }
        
        return $result;
    }
}