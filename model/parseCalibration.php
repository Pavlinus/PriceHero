<?php
include_once('simple_html_dom.php');

$link = 'https://www.gamebuy.ru/xboxone/game/nba-2k17-xbox-one';

$html = file_get_html($link);
$matches = null;

foreach($html->find('div.panel-col-last span.uc-price') as $span)
{
	echo $span->outertext;
	$val = str_replace(' ', '', $span->outertext);
    preg_match('/\d+/', $val, $matches);
    break;
}

print_r($matches);

