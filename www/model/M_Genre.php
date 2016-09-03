<?php

/**
* <p>Класс выполнения авторизации пользователя</p>
* @author Pavel Kovyrshin
* @date 03.09.2016
*/

include_once "M_MSQL.php";

class M_Genre
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
            self::$instance = new M_Genre();
        }

        return self::$instance;
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
}