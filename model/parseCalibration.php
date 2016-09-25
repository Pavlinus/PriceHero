<?php
include_once('simple_html_dom.php');

$link = 'http://roxen.ru/games/tom_clancys_splinter_cell/tom-clancy-s-rainbow-six-siege/';
$html = file_get_html($link);
$matches = null;
$price = '';

foreach($html->find('span.r-curr-price') as $span)
{
    preg_match('/(\d)+/', $span->outertext, $matches);
    break;
}

print_r($matches);

