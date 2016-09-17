<?php

include_once "M_MSQL.php";
include_once "M_PriceParser.php";
include_once "M_Price.php";

/**
 * Извлекаем все ID ссылок
 * @param object $msql экземпляр класса M_MSQL
 * @return array массив всех ссылок
 */
function getLinks($msql)
{
    $query = "SELECT link_id as linkId FROM t_link";
    return $msql->Select($query);
}


/**
 * Извлекаем ID всех цен
 * @param object $msql экземпляр класса M_MSQL
 * @return array массив ID цен
 */
function getPriceId($msql)
{
    $query = "SELECT price_id, link_id FROM t_total";
    $rows = $msql->Select($query);
    $arResult = array();
    
    foreach($rows as $row)
    {
        $arResult[ $row['link_id'] ] = $row['price_id'];
    }
    
    return $arResult;
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

// Берем все ссылки
$msql = M_MSQL::Instance();
$arLinks = getLinks($msql);


// Парсим цены
$priceParser = M_PriceParser::Instance();
$priceParsed = $priceParser->parse($arLinks);


// Формируем прайс лист
$priceList = array();
foreach($priceParsed as $price)
{
    $priceList[ $price['linkId'] ] = $price['price'];
}


// Формируем массив ID ссылок
$arLinksId = array();
foreach($arLinks as $link)
{
    $arLinksId[] = $link['linkId'];
}


$arPriceId = getPriceId($msql, $arLinksId);
$arNewPrice = array();

// Создаем массив array( id цены => новая цена )
foreach($arPriceId as $linkId => $priceId)
{
    $arNewPrice[ $priceId ] = $priceList[ $linkId ];
}

// Получаем старые цены
$arOldPrice = getOldPrice($msql);

// Обновляем цены
$mPrice = M_Price::Instance();
$mPrice->updatePrice($arNewPrice, $arOldPrice);