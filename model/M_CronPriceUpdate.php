<?php

include_once "M_MSQL.php";
include_once "M_PriceParser.php";
include_once "M_Price.php";


/* лимит выборки цен */
define(LIMIT, 5);


/**
 * Извлекаем ID ссылок
 * @param object $msql экземпляр класса M_MSQL
 * @return array массив всех ссылок
 */
function getLinksId($msql, $arPriceId)
{
    $arLinks = array();
    $mPrice = M_Price::Instance();
    
    foreach($arPriceId as $priceId)
    {
        $query =  "SELECT link_id, price_id FROM t_total "
            . "WHERE price_id=$priceId";
        $res = $msql->Select($query);

        // удаляем несуществующие цены
        if(empty($res))
        {
            $mPrice->deleteTblPrice(array($priceId));
        }
        else
        {
            $arLinks[ $res[0]['price_id'] ] = $res[0]['link_id'];
        }
    }
    
    return $arLinks;
}


/**
 * Извлекаем старые цены
 * @param object $msql экземпляр класса M_MSQL
 * @return array массив старых цен
 */
function getOldPrice($msql)
{
    $query = "SELECT price_id, new_price FROM t_price";
    $rows = $msql->Select($query);
    $arResult = array();
    
    foreach($rows as $row)
    {
        $arResult[ $row['price_id'] ] = $row['new_price'];
    }
    
    return $arResult;
}


function getPrice($msql)
{
    $query =  "SELECT * FROM t_price "
            . "ORDER BY lastUpdate ASC LIMIT " . LIMIT;

    return $msql->Select($query);
}

$msql = M_MSQL::Instance();

// ID цен для обновления
$arPrice = getPrice($msql);
$arPriceId = array();    
foreach($arPrice as $item)
{
    $arPriceId[] = $item['price_id'];
}

//print_r($arPriceId);

// Сопоставленный массив ID ссылок и ID цен
$arUnion = getLinksId($msql, $arPriceId);
$arLinksId = array();

foreach($arUnion as $item)
{
    $arLinksId[]['linkId'] = $item;
}

// Парсим цены
$priceParser = M_PriceParser::Instance();
$priceParsed = $priceParser->parse($arLinksId);


// Формируем прайс лист
$priceList = array();
foreach($priceParsed as $price)
{
    $priceList[ $price['linkId'] ] = $price['price'];
}

$arNewPrice = array();

// Создаем массив array( id цены => новая цена )
foreach($arUnion as $priceId => $linkId)
{
    $arNewPrice[ $priceId ] = $priceList[ $linkId ];
}


// формируем старые `new_price`
foreach($arPrice as $item)
{
    $arOldPrice[ $item['price_id'] ] = $item['new_price'];
}

// Обновляем цены
$mPrice = M_Price::Instance();
$mPrice->updatePrice($arNewPrice, $arOldPrice);
