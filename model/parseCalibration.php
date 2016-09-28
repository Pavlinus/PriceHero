<?php
include_once('simple_html_dom.php');

$link = 'http://store.steampowered.com/app/443810/';
$html = file_get_html($link);
$matches = null;
$price = '';

echo "<pre>" . $html . "</pre>";

$arResult = $html->find('div.discount_final_price');
        
if(empty($arResult) || $arResult == null)
{
    $arResult = $html->find('div.price');
}

if(empty($arResult) || $arResult == null)
{
    $arResult = $html->find('div.game_purchase_price');
}


foreach($arResult as $span)
{
    preg_match('/(\d)+/', $span->outertext, $matches);
    break;
}

//print_r($matches);

