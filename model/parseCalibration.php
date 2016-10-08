<?php
include_once('simple_html_dom.php');

$link = 'http://steampay.com/game/mafia-3';
$html = file_get_html($link);
$matches = null;
$price = '';

foreach($html->find('span.price') as $span)
{
    preg_match('/\d+[\s]{0,1}\d*/', $span->outertext, $matches);
}

print_r($matches);

