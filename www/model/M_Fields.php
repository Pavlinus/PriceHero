<?php

/**
* <p>Класс извлечения данных для выпадающего меню</p>
* @author Pavel Kovyrshin
* @date 28.08.2016
*/

include_once "M_MSQL.php";

class M_Fields
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
			self::$instance = new M_Fields();
		}
		
		return self::$instance;
	}
	
        
        /**
	* <p>Получает массив значений таблицы</p>
	* @return Возвращает массив значений таблицы `table`
	*/
	public function getFields($table)
	{
            $query = "SELECT * FROM $table";
            
            return $this->msql->Select($query);
        }
}