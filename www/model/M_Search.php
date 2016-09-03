<?php

/**
* <p>Класс выполнения авторизации пользователя</p>
* @author Pavel Kovyrshin
* @date 03.09.2016
*/

include_once "M_MSQL.php";

class M_Search
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
			self::$instance = new M_Search();
		}
		
		return self::$instance;
	}
	
	
	public function searchGame()
	{
            if(isset($_REQUEST['name']) && $_REQUEST['name'] != null)
            {
                $keywords = $this->getKeywordsArray($_REQUEST['name']);
                
                if(!empty($keywords))
                {
                    $gameIds = $this->searchGameId($keywords);
                    $mGame = M_Game::Instance();
                    $games = $mGame->getTblGameList($gameIds);
                }
                
                return $games;
            }
            else
            {
                return false;
            }
	}
        
        
        
        /**
         * Поиск id игр, имена которых соответствуют ключевым словам
         * @param array $keywords ключевые слова названия игры
         * @return array массив id игр
         */
        private function searchGameId($keywords)
        {
            $tblKeyNum = 5;     // 5 ключей в таблице `t_keywords`
            $keys = "(" . implode(",", $keywords) . ")";
            $query = "SELECT game_id FROM t_keywords WHERE ";

            for($i = 1; $i <= count($keywords) && $i <= $tblKeyNum; $i++)
            {
                $query .= "key_".$i." IN ".$keys;

                if($i != count($keywords))
                {
                    $query .= " OR ";
                }
            }
            
            return $this->msql->Select($query);
        }
        
        
        /**
         * Создает массив ключевых слов
         * @param string $str Строка с ключевыми словами
         * @return array Массив ключевых слов
         */
        private function getKeywordsArray($str)
        {
            $keywords = explode(" ", $str);
                
            foreach($keywords as &$word)
            {
                $word = htmlspecialchars("'".$word."'");
            }
            
            return $keywords;
        }
}