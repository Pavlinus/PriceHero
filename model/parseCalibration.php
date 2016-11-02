<?php
include_once('simple_html_dom.php');

$link = 'https://www.gamebuy.ru/xboxone/game/doom-2016-xbox-one-1';
$html = file_get_html($link);
$matches = null;
$price = '';

foreach($html->find('div.panel-col-last span.uc-price') as $span)
{
	echo $span->outertext;
	$val = str_replace(' ', '', $span->outertext);
    preg_match('/\d+/', $val, $matches);
}

print_r($matches);

