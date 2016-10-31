<?php
include_once('simple_html_dom.php');

$link = 'http://gamerepublic.ru/catalog.aspx/games/PC/42190';
$html = file_get_html($link);
$matches = null;
$price = '';

foreach($html->find('span.catalog_price_big') as $span)
{
    preg_match('/(\d)+/', $span->outertext, $matches);
    
}
echo "--";
print_r($matches);

