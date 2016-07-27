<?php

class M_MSQL
{
	private static $instance;
	private $connLink;
	
	public static function Instance()
	{	
		if (self::$instance == null)
		{
			self::$instance = new M_MSQL();
		}
		
		return self::$instance;
	}
	
	private function __construct()
	{
		$this->connect_db();
	}
	

	public function Select($query)
	{
		$result = mysqli_query($this->connLink, $query);
		
		if (!$result)
			die(mysql_error());
		
		$n = mysqli_num_rows($result);
		$arr = array();
	
		for($i = 0; $i < $n; $i++)
		{
			$row = mysqli_fetch_assoc($result);		
			$arr[] = $row;
		}
		return $arr;				
	}
	

	public function Insert($table, $object)
	{			
		$columns = array(); 
		$values = array(); 
	
		foreach ($object as $key => $value)
		{
			$key = mysqli_real_escape_string($this->connLink, $key . '');
			$columns[] = $key;
			
			if ($value === null)
			{
				$values[] = 'NULL';
			}
			else
			{
				$value = mysqli_real_escape_string($this->connLink, $value . '');
				$values[] = "'$value'";
			}
		}
		
		$columns_s = implode(',', $columns); 
		$values_s = implode(',', $values);  
			
		$query = "INSERT INTO $table ($columns_s) VALUES ($values_s)";
		$result = mysqli_query($this->connLink, $query);
								
		if (!$result)
			die(mysqli_error($this->connLink));
			
		return mysqli_insert_id($this->connLink);
	}
	

	public function Update($table, $object, $where)
	{
		$sets = array();
	
		foreach ($object as $key => $value)
		{
			$key = mysqli_real_escape_string($this->connLink, $key . '');
			
			if ($value === null)
			{
				$sets[] = "$value=NULL";			
			}
			else
			{
				$value = mysqli_real_escape_string($this->connLink, $value . '');
				$sets[] = "$key='$value'";			
			}			
		}
		$sets_s = implode(',', $sets);			
		$query = "UPDATE $table SET $sets_s WHERE $where";
		$result = mysqli_query($this->connLink, $query);
		
		if (!$result)
			die(mysqli_error($this->connLink));
		return mysqli_affected_rows($this->connLink);	
	}
	
	
	public function Delete($table, $where)
	{
		$query = "DELETE FROM $table WHERE $where";		
		$result = mysqli_query($this->connLink, $query);
						
		if (!$result)
			die(mysqli_error($this->connLink));
		return mysqli_affected_rows($this->connLink);
	}
	
	private function connect_db() 
	{
		$hostname = 'localhost'; 
		$username = 'zyqjkokx_pavlin'; 
		$password = '3S3o5Y9h';
		$dbName = 'zyqjkokx_creata';
	
		setlocale(LC_ALL, 'ru_RU.UTF-8');
		mb_internal_encoding('UTF-8');
	
		$this->connLink = mysqli_connect($hostname, $username, $password) or die(''); 
		mysqli_query($this->connLink, 'SET NAMES utf8');
		mysqli_select_db($this->connLink, $dbName) or die('');
	}
	
	public function GetConnectionLink()
	{
		return $this->connLink;
	}
}