<?php

class Filter
{
	private $msql;
	private static $instance;
	
	function __constructor()
	{
		$msql = M_MSQL::Instance();
	}
	
	public static function Instance()
	{
		if(self::$instance == null)
		{
			self::$instance = new Filter();
		}
		
		return self::$instance;
	}
	
	public function getCategoryList($category = 'newPrice')
	{
		if($category == 'newPrice')
		{
			$query = "SELECT * FROM t_price ORDER BY update DESC";
			$piceList = $msql->Select($query);
		}
		else
		{
			$query = "SELECT * FROM t_total WHERE game_id IN (SELECT game_id FROM ";
			$query .=  "t_game WHERE genre_id IN (SELECT genre_id FROM t_genre WHERE"; 
			$query .= "name = `{$category}`))";
		}
		
	}
}

?>