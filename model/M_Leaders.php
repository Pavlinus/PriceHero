<?php

/**
* <p>Класс выполнения авторизации пользователя</p>
* @author Pavel Kovyrshin
* @date 03.09.2016
*/

include_once "M_MSQL.php";

class M_Leaders
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
            self::$instance = new M_Leaders();
        }

        return self::$instance;
    }
	

    public function getGameIdList()
    {
        $query = "SELECT game_id FROM t_leaders";
        $res = $this->msql->Select($query);
        $idList = array();
        if($res)
        {
            foreach($res as $item)
            {
                $idList[] = $item['game_id'];
            }
        }

        return $idList;
    }


    private function delete($arId)
    {
        $in = implode(",", $arId);
        $where = "game_id IN ($in)";
        $this->msql->Delete('t_leaders', $where);

        if(mysql_error() != '')
        {
            return 0;
        }
        return 1;
    }


    private function add($arId)
    {
        $object = array();
        foreach($arId as $value)
        {
            $object['game_id'] = $value;
            $this->msql->Insert('t_leaders', $object);

            if(mysql_error() != '')
            {
                return 0;
            }
        }

        return 1;
    }
	

    public function save($arGameId)
    {
        $arGamesIdList = $this->getGameIdList();

        if(!is_array($arGameId))
        {
            $arGameId = array();
        }

        
        $arDiff = array_diff($arGamesIdList, $arGameId);
        if(!empty($arDiff))
        {
            $this->delete($arDiff);
        }

        
        $arDiff = array_diff($arGameId, $arGamesIdList);
        if(!empty($arDiff))
        {
            return $this->add($arDiff);
        }

        return 1;
    }
}