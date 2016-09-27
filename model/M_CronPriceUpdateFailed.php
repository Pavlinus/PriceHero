<?php

include_once "M_MSQL.php";
include_once "M_PriceParser.php";
include_once "M_Price.php";
include_once "M_CronUpdateLogger.php";


/* лимит выборки ссылок */
define(LIMIT, 5);


/**
 * Извлекаем все ID ссылок
 * @param object $msql экземпляр класса M_MSQL
 * @return array массив всех ссылок
 */
function getLinks($msql, $offset)
{
    $query = "SELECT link_id as linkId FROM t_cronLogger LIMIT $offset, " . LIMIT;
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


/**
 * Подсчет общего количества ссылок
 * @param type $msql
 * @return type
 */
function countLinks($msql)
{
    $query = "SELECT COUNT(link_id) as links FROM t_cronLogger";
    return $msql->Select($query);
}


$msql = M_MSQL::Instance();

$res = countLinks($msql);
$totalLinks = (int)$res[0]['links'];
$offset = 0;

$parsedLinkId = array();

$time_start = time();

for($i = 0; $offset < $totalLinks; $i++)
{
    $loop_start = time();
    
    $arLinks = getLinks($msql, $offset);
    
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
        $parsedLinkId[] = $link['linkId'];
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
    
    $offset = ($i + 1) * LIMIT;
    
    $loop_end = time();
    $time = $loop_end - $loop_start;
    echo "Loop " . $i . ": " . $time . " sec.\n ";
}

/* удаляем обновленные записи из логов */
$cronLogger = M_CronUpdateLogger::Instance();
$cronLogger->deleteLog($parsedLinkId);

/* оповещение на email если остались фейлы */
$countLogs = $cronLogger->countLogs();
if($countLogs > 0)
{
    $cronLogger->sendNotification();
}

$time_end = time();

$time = $time_end - $time_start;

echo "-- Total time: " . $time . " sec. --";


