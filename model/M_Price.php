<?php

/**
* <p>Класс выполнения авторизации пользователя</p>
* @author Pavel Kovyrshin
* @date 03.09.2016
*/

include_once "M_MSQL.php";

class M_Price
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
            self::$instance = new M_Price();
        }

        return self::$instance;
    }
	
	
    /**
     * <p>Добавляет запись о цене.</p>
     * @param $priceList - массив цен
     * @return Массив ID новых записей `t_price`
     */
    public function addPrice($priceList) 
    {
        $date = date("Y-m-d");
        $priceIdArray = array();

        foreach ($priceList as $priceItem)
        {
            $object = array(
                'new_price' => $priceItem['price'],
                'old_price' => $priceItem['price'],
                'lastUpdate' => $date
            );
            
            $id = $this->msql->Insert('t_price', $object);
            $priceIdArray[ $priceItem['linkId'] ] = $id;
        }

        return $priceIdArray;
    }
    
    
    /**
    * Удаляет данные цены
    */
    public function deleteTblPrice($priceIdArray)
    {
        if(empty($priceIdArray))
        {
            return false;
        }
        
        foreach($priceIdArray as $price)
        {
            $this->msql->Delete('t_price', "price_id=$price");
        }
    }


    /**
    * Удаляет данные цены из таблицы t_total
    */
    public function deleteTblTotalPrice($priceIdArray)
    {
        if(empty($priceIdArray))
        {
            return false;
        }
        
        foreach($priceIdArray as $price)
        {
            $this->msql->Delete('t_total', "price_id=$price");
        }
    }
    
    
    /**
     * Обновление цены
     * @param array $arNewPrice массив новых цен
     * @param array $arOldPrice массив старых цен
     */
    public function updatePrice($arNewPrice, $arOldPrice)
    {
        $date = date("Y-m-d");

        foreach($arNewPrice as $priceId => $priceValue)
        {   
            if(!is_numeric($priceValue))
            {
                continue;
            }
            
            $object = array(
                'old_price' => $arOldPrice[ $priceId ],
                'new_price' => $priceValue,
                'lastUpdate' => $date
            );
            $where = "price_id=$priceId";
            
            $this->msql->Update('t_price', $object, $where);
        }
    }
}