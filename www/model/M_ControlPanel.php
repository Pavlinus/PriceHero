<?php

class M_ControlPanel
{
	private static $instance;
	private $msql;
	
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
			self::$instance = new M_ControlPanel();
		}
		
		return self::$instance;
	}
	
	
	/**
	* <p>Добавляет данные игры в БД</p>
	* @return Id новой игры в БД
	*/
	public function addGame()
	{
		$name = htmlspecialchars($_POST['name']);
		$genre = htmlspecialchars($_POST['genre']);
		
		$object = array(
			'name' => $name,
			'genre_id' => $genre,
			'image' => '/images/default.jpg');
		
		return $this->msql->INSERT('t_game', $object);
	}
	
	
	/**
	* <p>Добавляет новую ссылку в БД</p>
	* @return Id новой(ых) ссылки(ок), иначе false
	*/
	public function addLink()
	{
		$linksData = array();
		
		if(isset($_POST['links']))
		{
			foreach($_POST['links'] as $item)
			{
				$object = array(
					'site_id' => htmlspecialchars($item['service']),
					'link' => htmlspecialchars($item['link']),
				);
				
				$id = $this->msql->INSERT('t_link', $object);
				
				if($id)
				{
					$linksData[] = array(
						'linkId' => $id,
						'platform' => htmlspecialchars($item['platform'])
					);
				}
				else
				{
					return false;
				}
			}
		}
		else
		{
			return false;
		}
		
		return $linksData;
	}
	
	
	/**
	* <p>Добавляет запись об игре в сводную таблицу.</p>
	* @param gameId - идентификатор новой игры
	* @param linksArr - массив ID новых ссылок
	* @return Массив ID новых записей `t_total`, иначе false
	*/
	public function addTotal($gameId, $linksArr)
	{
		$totalIdArr = array();
		
		foreach($linksArr as $linkItem)
		{
			$object = array(
				'game_id' => $gameId,
				'platform_id' => $linkItem['platform'],
				'link_id' => $linkItem['linkId']);
				
			$id = $this->msql->INSERT('t_total', $object);
			
			if(!$id)
			{
				return false;
			}
			
			$totalIdArr[] = $id;
		}
		
		return $totalIdArr;
	}
}
