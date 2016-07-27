<?php
	include_once('simple_html_dom.php');

	$html = file_get_html('http://steambuy.com/steam/call-of-duty-infinite-warfare/');

	
	foreach($html->find('span.tovar-price') as $span)
	{
		preg_match('/(\d)+/', $span->outertext, $matches);
	}
	
	echo $matches[0];
?>