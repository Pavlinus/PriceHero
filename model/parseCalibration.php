<?php
include_once('simple_html_dom.php');

$link = 'http://game.93w.ru/game/main/football-manager-2016.html';
$html = file_get_html($link);
$matches = null;
$price = '';

foreach($html->find('div.ribbon2 b') as $span)
{
	echo $span->outertext;
	$val = str_replace(' ', '', $span->outertext);
    preg_match('/\d+/', $val, $matches);
}

print_r($matches);

