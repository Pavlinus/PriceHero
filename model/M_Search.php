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
	
	
        /**
         * Поиск ID игр, соответствующих ключевым словам
         * @return boolean
         */
	public function searchGame($gameName = '', $strict = false)
	{
        if(isset($_REQUEST['name']) && $_REQUEST['name'] != null)
        {
            $gameName = $_REQUEST['name'];
        }
        
        $keywords = $this->getKeywordsArray($gameName);
        
        if(!empty($keywords))
        {
            //return $this->searchGameId($keywords, $strict);
            return $this->searchGameId($gameName, $strict);
        }
        
        return false;
	}

        /**
         * Поиск id игр, имена которых соответствуют ключевым словам
         * @param array $keywords ключевые слова названия игры
         * @return array массив id игр
         */
         private function searchGameId($gameName, $strict = false)
        {
            $tblKeyNum = 5;     // 5 ключей в таблице `t_keywords`
            //$keys = "(" . implode(",", $keywords) . ")";
            $gameName = " LIKE '%" . trim(htmlspecialchars($gameName)) . "%'";
            $sql = "SELECT game_id FROM t_game WHERE name $gameName";

            $rows = $this->msql->Select($sql);
            $ids = array();
            
            if(empty($rows))
            {
                return $ids;
            }
            
            foreach($rows as $value)
            {
                $ids[] = $value['game_id'];
            }

            return $ids;
        }
        
        
        /**
         * Поиск id игр, имена которых соответствуют ключевым словам
         * @param array $keywords ключевые слова названия игры
         * @return array массив id игр
         */
        // private function searchGameId($keywords, $strict = false)
        // {
        //     $tblKeyNum = 5;     // 5 ключей в таблице `t_keywords`
        //     $keys = "(" . implode(",", $keywords) . ")";
        //     $query = "SELECT game_id FROM t_keywords WHERE ";
        //     $mode = "%";
            
        //     if($strict)
        //     {
        //         $mode = "";
        //     }

        //     for($i = 1; $i <= $tblKeyNum && $i <= $tblKeyNum; $i++)
        //     {
        //         //$query .= "key_".$i." IN ".$keys;
        //         foreach ($keywords as $key => $value) 
        //         {
        //             $query .= "key_".$i." LIKE '".$value."$mode'";
        //             if($key < count($keywords) - 1)
        //             {
        //                 $query .= " OR ";
        //             }
        //         }

        //         if($i != $tblKeyNum)
        //         {
        //             $query .= " OR ";
        //         }
        //     }

        //     $sql = htmlspecialchars($query);
        //     $rows = $this->msql->Select($sql);
        //     $ids = array();
            
        //     if(empty($rows))
        //     {
        //         return $ids;
        //     }
            
        //     foreach($rows as $value)
        //     {
        //         $ids[] = $value['game_id'];
        //     }

        //     return $ids;
        // }
        
        
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
                $val = strtolower($word);
                $word = htmlspecialchars($val);
            }
            
            return $keywords;
        }
}