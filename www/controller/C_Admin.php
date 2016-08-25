<?php

/**
* <p>Контроллер панели администратора</p>
* @author Pavel Kovyrshin
* @date 30.07.2016
*/

include_once "/model/M_ControlPanel.php";
include_once "/model/M_Filter.php";
include_once "/model/M_Auth.php";

class C_Admin extends C_Base
{
	private $cPanel;
	private $filter;
	private $auth;
	
	
	public function __construct()
	{
		if($this->cPanel == null)
		{
			$this->cPanel = M_ControlPanel::Instance();
		}
		
		if($this->filter == null)
		{
			//$filter = M_Filter::Instance();
		}
		
		if($this->auth == null)
		{
			$this->auth = M_Auth::Instance();
		}
	}
	
	
	/**
	* <p>Главное окно панели управления, либо форма авторизации.</p>
	*/
	public function action_index()
	{
		if($this->isAdmin())
		{
			$this->content = $this->Template("view/v_admin.php", 
				array());
		}
		else
		{
			$this->content = $this->Template("view/v_admin_auth.php", 
				array());
		}
	}
	
	
	/**
	* <p>Запуск процесса авторизации.</p>
	*/
	public function action_auth()
	{
		if($this->isPost())
		{
			$authRes = $this->auth->authorize($_REQUEST['au_login'], 
				$_REQUEST['au_password']);
			
			if($authRes && $this->isAdmin($authRes[0]['priority']))
			{
				$this->content = $this->Template("view/v_admin.php", 
					array());
			}
			else
			{
				$error = 'Неверный логин или пароль';
				$this->content = $this->Template("view/v_admin_auth.php", 
					array('error' => $error));
			}
		}
	}
	
	
	/**
	* <p>Проверка на адмиина</p>
	* @param priority приоритет пользователя (права доступа)
	* @return TRUE если админ, иначе FALSE
	*/
	public function isAdmin($priority = false)
	{
		if($priority === '007')
		{
			return true;
		}
		
		if(isset($_COOKIE['user']) && $_COOKIE['user'] == '007')
		{
			return true;
		}
		
		return false;
	}
	
	
	/**
	* <p>Добавление игры</p>
	* @return
	*/
	public function action_addGame()
	{
		
		if($this->isPost())
		{
			$gameId = $this->cPanel->addGame();
			$linksId = array();
			$totalId = array();
			
			if($gameId)
			{
				$linksId = $this->cPanel->addLink();
				
				if(!empty($linksId))
				{
					$totalId = $this->cPanel->addTotal($gameId, $linksId);
				}
				
				// TODO: создать новую таблицу хранения новый ID t_total для t_price
				if(!empty($totalId))
				{
					
				}
			}
			
		}
		else
		{
			if(isset($_COOKIE['user']) && $_COOKIE['user'] == '007')
			{
				$this->content = $this->Template("view/v_admin_add_game.php", 
					array());
			}
		}
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

