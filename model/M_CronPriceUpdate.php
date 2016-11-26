<?php

include_once "M_MSQL.php";
include_once "M_PriceParser.php";
include_once "M_Price.php";
//include_once 'M_CronUpdateLogger.php';


/* шаг цикла */
define(STEP, 10);

/* время между запусками циклов (60 сек) */
define(STANDBY, 60);


function getData($msql)
{
    $query =  "SELECT Price.price_id, Price.new_price, Price.old_price, Link.link_id, Link.link "
            . "FROM t_total Total "
            . "LEFT JOIN t_link Link ON (Link.link_id=Total.link_id) "
            . "LEFT JOIN t_price Price ON (Price.price_id=Total.price_id) "
            . "ORDER BY Price.lastUpdate ASC";
    return $msql->Select($query);
}

$msql = M_MSQL::Instance();
//$logger = M_CronUpdateLogger::Instance();
$arData = getData($msql);
$totalDataItems = count($arData);
$offset = 0;

echo "Total table items: " . $totalDataItems . "\n";

loop:

$dataSlice = array_slice($arData, $offset, STEP);
$arLinksId = array();
foreach($dataSlice as $item)
{
    $arLinksId[]['linkId'] = $item['link_id'];
}

// Парсим цены
$priceParser = M_PriceParser::Instance();
$priceParsed = $priceParser->parse($arLinksId);

// Формируем прайс лист
$priceList = array();
foreach($priceParsed as $price)
{
    if($price['price'] == 0)
    {
        foreach($arData as $dataItem)
        {
            if($dataItem['link_id'] == $price['linkId'])
            {
                $priceList[ $price['linkId'] ] = $dataItem['new_price'];
                //$logger->addLog($price['link_id']);
            }
        }
    }
    else
    {
        $priceList[ $price['linkId'] ] = $price['price'];
    }
}

$arNewPrice = array();
// Создаем массив array( id цены => новая цена )
foreach($dataSlice as $dataItem)
{
    $arNewPrice[ $dataItem['price_id'] ] = $priceList[ $dataItem['link_id'] ];
}

$arOldPrice = array();
// формируем старые `new_price`
foreach($dataSlice as $item)
{
    $arOldPrice[ $item['price_id'] ] = $item['new_price'];
}

echo "<pre>";
print_r($arNewPrice);
echo "</pre>";

// Обновляем цены
$mPrice = M_Price::Instance();
$mPrice->updatePrice($arNewPrice, $arOldPrice);
// $mPrice->endPrice();

$offset += STEP;

//sleep(STANDBY);

if($offset < $totalDataItems)
{
    goto loop;
}

echo "[+] Items updated: " . $offset . "\n";