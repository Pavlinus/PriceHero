<?php

abstract class C_Controller 
{
	protected abstract function renderPage();
	protected abstract function renderContent();
	
	public function Request($action)
	{
		$this->$action();
		$this->renderPage();
	}
	
	public function RequestAjax($action)
	{
		$this->$action();
		$this->renderContent();
	}
	
	protected function IsGet()
	{
		return $_SERVER['REQUEST_METHOD'] == 'GET';
	}
	
	protected function IsPost()
	{
		return $_SERVER['REQUEST_METHOD'] == 'POST';
	}
	
	protected function Template($fileName, $vars = array())
	{
		foreach ($vars as $k => $v)
		{
			$$k = $v;
		}
		
		ob_start();
		include $fileName;
		return ob_get_clean();
	}	
	
	public function __call($name, $params){
        $error = "Не удалось загрузить страницу";
		//$this->content = $this->Template('view/v_error.php', array('error' => $error));
		echo $error . "<br>".$name."<br>".$params;
		return;
	}
}
