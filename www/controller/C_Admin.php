<?php

/**
* <p>Контроллер панели администратора</p>
* @author Pavel Kovyrshin
* @date 30.07.2016
*/

include_once "../model/M_ControlPanel.php";
include_once "../model/M_Filter.php";
include_once "../model/M_Auth.php";

class C_Admin extends C_Base
{
	private $cPanel;
	private $filter;
	private $auth;
	
	
	public function __construct()
	{
		if($cPanel == null)
		{
			$cPanel = M_ControlPanel::Instance();
		}
		
		if($filter == null)
		{
			$filter = M_Filter::Instance();
		}
		
		if($auth == null)
		{
			$auth = M_Auth::Instance();
		}
	}
	
	
	/**
	* <p>Главное окно панели управления, либо форма авторизации.</p>
	*/
	public function action_index()
	{
		if($_COOKIE['user'] == '007')
		{
			$content = $this->Template("../view/v_admin.php", array());
		}
		else
		{
			echo $this->Template("../view/v_admin_auth.php", array());
		}
	}
	
	
	/**
	* <p>Запуск процесса авторизации.</p>
	*/
	public function action_auth()
	{
		if($this->isPost())
		{
			$authRes = $auth->authorize($_REQUEST['login'], $_REQUEST['password']);
			
			if($authRes)
			{
				// TODO ====================================
			}
		}
	}
	
	
	public function action_addGame()
	{
		
	}
	
	
	public function action_removeGame($gameId)
	{
		
	}
	
	
	public function action_saveGame($gameId)
	{
		
	}
	
	
	public function action_filter()
	{
		
	}
}

?>