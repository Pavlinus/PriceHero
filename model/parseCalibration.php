<?php
include_once('simple_html_dom.php');

$link = 'http://www.gameray.ru/dishonored-2/?partner=209';

$html = file_get_html($link);
$matches = null;

foreach($html->find('span[itemprop="price"]') as $span)
{
    preg_match('/(\d)+/', $span->outertext, $matches);
}

print_r($matches);

