<?php
include_once('simple_html_dom.php');

$link = 'http://gamerepublic.ru/catalog.aspx/games/XboxOne/41996';
$html = file_get_html($link);
$matches = null;
$price = '';

foreach($html->find('span.catalog_price_big') as $span)
{
	echo $span->outertext;
	$val = str_replace(' ', '', $span->innertext);
    preg_match('/\d+/', $span->innertext, $matches);
    break;
}

print_r($matches);

