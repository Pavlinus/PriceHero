<?php

/**
* <p>Класс выполнения авторизации пользователя</p>
* @author Pavel Kovyrshin
* @date 03.09.2016
*/

include_once "M_MSQL.php";

class M_Total
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
            self::$instance = new M_Total();
        }

        return self::$instance;
    }
	
	
    /**
     * <p>Добавляет запись об игре в сводную таблицу.</p>
     * @param gameId - идентификатор новой игры
     * @param linksArr - массив ID новых ссылок
     * @param priceArr - массив ID цен
     * @return Массив ID новых записей `t_total`, иначе false
     */
    public function addTotal($gameId, $linksArr, $priceArr) 
    {
        if(empty($linksArr) || empty($priceArr))
        {
            return array();
        }
        
        $totalIdArr = array();

        foreach ($linksArr as $linkItem) 
        {
            $object = array(
                'game_id' => $gameId,
                'platform_id' => $linkItem['platform'],
                'link_id' => $linkItem['linkId'],
                'price_id' => $priceArr[ $linkItem['linkId'] ]);

            $id = $this->msql->Insert('t_total', $object);

            if (!$id) {
                return false;
            }

            $totalIdArr[] = $id;
        }

        return $totalIdArr;
    }
}