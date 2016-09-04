<?php

/**
* <p>Класс выполнения авторизации пользователя</p>
* @author Pavel Kovyrshin
* @date 30.07.2016
*/

include_once "M_MSQL.php";

class M_Auth
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
			self::$instance = new M_Auth();
		}
		
		return self::$instance;
	}
	
	
	/**
	* <p>Авторизация пользователя</p>
	* @param login Логин
	* @param password Пароль
	* @return TRUE если найдена запись в БД, иначе FALSE
	*/
	public function authorize($login, $password)
	{
		if(strlen($login) == 0 || strlen($password) == 0)
		{
			return false;
		}
		
		$login = mysqli_real_escape_string(
			$this->msql->GetConnectionLink(), $login);
		$password = md5($password);
		$password = mysqli_real_escape_string(
			$this->msql->GetConnectionLink(), $password);
		
		$query = "SELECT user_id, priority FROM t_user ";
		$query .= "WHERE login = '{$login}' AND password = '{$password}'";
		
		$authRes = $this->msql->Select($query);
		
		if(!$authRes)
		{
			return false;
		}
		
		if(isset($authRes[0]['priority']) && 
			$authRes[0]['priority'] == '007')
		{
			setcookie('user', '007', time() + 3600);
		}
		
		return $authRes;
	}
}

?>