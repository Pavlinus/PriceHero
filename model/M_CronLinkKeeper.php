<?php

include_once "M_MSQL.php";
include_once "M_Link.php";
include_once "M_Price.php";


/* лимит выборки цен */
define(STEP, 10);
define(STANDBY, 30);


/**
 * Извлекаем ID ссылок
 * @param object $msql экземпляр класса M_MSQL
 * @return array массив всех ссылок
 */
function getLinksId($msql)
{
    $query =  "SELECT link_id, link FROM t_link";
    return $msql->Select($query);
}


function checkLink404($arLink)
{
    $mLink = M_Link::Instance();
    $mPrice = M_Price::Instance();
    $badLinks = array();
    foreach($arLink as $link)
    {
        //echo "Checking " . $link['link'] . "\n";
        $handle = curl_init($link['link']);
        curl_setopt($handle,  CURLOPT_RETURNTRANSFER, TRUE);
        $response = curl_exec($handle);

        $httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);
        if($httpCode == 404) 
        {
            echo "[-] ". $link['link']. " : BAD LINK\n";
            $badLinks[] = $link['link_id'];
            continue;
        }

        curl_close($handle);
    }

    if(count($badLinks) > 0)
    {
        $arPrice = $mLink->deleteTblLink($badLinks);
        $mPrice->deleteTblPrice($arPrice);
    }
}


$msql = M_MSQL::Instance();

// Сопоставленный массив ID ссылок и ID цен
$linksArray = getLinksId($msql);

$offset = 0;
$totalLinks = count($linksArray);
echo 'Total links: ' . $totalLinks . "\n\n";

again:
$toCheck = array_slice($linksArray, $offset, STEP);
checkLink404($toCheck);
$offset += STEP;

sleep(STANDBY);

if($offset < $totalLinks)
{
    goto again;
}
