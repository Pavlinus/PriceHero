<?php

	function __autoload($classname)
	{
		// Если загружается класс контроллера
		if(substr($classname, 0, 2) == "C_")
		{
			include_once("controller/$classname.php");
		}
		else
		{
			include_once("model/$classname.php");
		}
	}
	
	$action = 'action_';
	$action .= (isset($_GET['act'])) ? $_GET['act'] : 'index';
	
	if(!isset($_GET['c']))
	{
		$ctrl = 'index';
	}
	else
	{
		$ctrl = $_GET['c'];
	}
	
	if(!isset($_GET['act']))
	{
		$act = 'index';
	}
	else
	{
		$act = $_GET['act'];
	}
	switch ($ctrl)
	{
		case 'index':
		default:
			$controller = new C_Index();
	}
	
	header('Content-type: text/html; charset=utf-8');
	
	if($ctrl == 'search' && $act == 'search')
	{
		$controller->RequestAjax($action);
	}
	else
	{
		$controller->Request($action);
	}
?>