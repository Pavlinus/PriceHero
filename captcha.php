<?php
	session_start();
 
	// создаем случайное число и сохраняем в сессии
 
	$randomnr = rand(1000, 9999);
	$_SESSION['randomnr2'] = md5($randomnr);
 
	//создаем изображение
	$im = imagecreatetruecolor(130, 41);
 
	//цвета:
	$white = imagecolorallocate($im, 255, 255, 255);
	$grey = imagecolorallocate($im, 29, 41, 62);
	$green = imagecolorallocate($im, 94, 148, 90);
 
	imagefilledrectangle($im, 0, 0, 200, 41, $green);
 
	//путь к шрифту:
 
	$font = 'fonts/Roboto-BoldItalic.ttf';
 
	//рисуем текст:
	imagettftext($im, 35, 0, 22, 24, $grey, $font, $randomnr);
 
	imagettftext($im, 35, 0, 15, 26, $white, $font, $randomnr);
 
	// предотвращаем кэширование на стороне пользователя
	header("Expires: Wed, 1 Jan 1997 00:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
 
	//отсылаем изображение браузеру
	header ("Content-type: image/gif");
	imagegif($im);
	imagedestroy($im);
