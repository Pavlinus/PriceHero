<?php
include_once('simple_html_dom.php');

$link = 'https://ad.admitad.com/g/af8ef42a17d9b214c029e8b31ead25/?ulp=http%3A%2F%2Fwww.gamepark.ru%2Fplaystation4%2Fgames%2FCallofDutyaerialWarfarePS4%2F';
$html = file_get_html($link);
$matches = null;
$price = '';

foreach($html->find('div#eprice') as $span)
{
	echo $span->outertext;
	$val = str_replace(' ', '', $span->outertext);
    preg_match('/\d+/', $val, $matches);
}

print_r($matches);

