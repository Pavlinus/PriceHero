<?php

/**
* <p>Класс выполнения авторизации пользователя</p>
* @author Pavel Kovyrshin
* @date 03.09.2016
*/

include_once "M_MSQL.php";

class M_Game
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
            self::$instance = new M_Game();
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
        $image = htmlspecialchars($_POST['image']);

        $object = array(
            'name' => $name,
            'genre_id' => $genre,
            'image' => $image);

        return $this->msql->Insert('t_game', $object);
    }

    
    /**
     * <p>Добавляет ключевые слова из названия игры</p>
     */
    public function addGameKeywords($gameId)
    {
        $name = htmlspecialchars($_POST['name']);
        
        $gameWords = explode(" ", $name);
        $offset = 5;    // 5 столбцов таблицы `t_keywords`
        $keysArray = array_slice($gameWords, 0, $offset); // массив слов

        $object = array();
        $index = 'key_';
        $count = 1;
        
        foreach($keysArray as $key)
        {
            $val = strtolower($key);
            $keyValue = str_replace(':', '', $val);
            $object[$index . $count] = htmlspecialchars($keyValue);
            $count += 1;
            
            if($count > 6)
            {
                break;
            }
        }
        
        $object['game_id'] = $gameId;

        $this->msql->Insert('t_keywords', $object);
    }
    
    
    /**
    * <p>Получение данных о игре из `t_game`</p>
    * 
    */
    public function getTblGame($id)
    {
        if($id == null || !is_numeric($id))
        {
            return false;
        }
        
        $query = "SELECT * FROM t_game WHERE game_id=$id";
        
        return $this->msql->Select($query);
    }
    
    
    /**
    * <p>Получение списка игр из `t_game`</p>
    * 
    */
    public function getTblGameList($idArray)
    {
        if($idArray == null || empty($idArray))
        {
            return array();
        }

        $idStr = "(" . implode(",", $idArray) . ")";
        
        $query = "SELECT * FROM t_game WHERE game_id IN $idStr";

        return $this->msql->Select($query);
    }
    
    
    /**
    * Обновляет данные игры в `t_game`
    * @return boolean True если сохранение прошло, иначе False
    */
    public function updateTblGame()
    {
        $id = htmlspecialchars($_REQUEST['gameId']);
        $name = htmlspecialchars($_REQUEST['name']);
        $genre = htmlspecialchars($_REQUEST['genre']);
        
        if(isset($_REQUEST['image']) && $_REQUEST['image'] != null)
        {
            $image = htmlspecialchars($_REQUEST['image']);
            $object = array(
                'name' => $name,
                'genre_id' => $genre,
                'image' => $image
            );
        }
        else
        {
            $object = array(
                'name' => $name,
                'genre_id' => $genre
            );
        }
        
        
        $where = "game_id=$id";

        return $this->msql->Update('t_game', $object, $where);
    }
}