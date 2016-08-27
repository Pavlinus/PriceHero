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
    private $parser;


    public function __construct()
    {
        if($this->cPanel == null)
        {
            $this->cPanel = M_ControlPanel::Instance();
        }
        
        if($this->parser == null)
        {
            $this->parser = M_PriceParser::Instance();
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
            $gamesList = array();
            
            if(isset($_REQUEST['page']))
            {
                $gamesList = $this->cPanel->getGamesList($_REQUEST['page']);
            }
            else
            {
                $gamesList = $this->cPanel->getGamesList(1);
            }
            
            $this->content = $this->Template(
                    "view/v_admin.php", 
                    array(
                        'gameList' => $gamesList
                    )
            );
        }
        else
        {
            $this->content = $this->Template(
                    "view/v_admin_auth.php", 
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
    * <p>Вывод интерфейса добавления игры</p>
    * @return
    */
    public function action_addGame()
    {
        if(isset($_COOKIE['user']) && $_COOKIE['user'] == '007')
        {
            $this->content = $this->Template("view/v_admin_add_game.php", 
                    array());
        }
    }
    
    
    /**
    * <p>Обработка запроса на добавление игры</p>
    * @return
    */
    public function action_addGameAjax()
    {
        if($this->isPost())
        {
            $gameId = $this->cPanel->addGame();
            $linksId = array();

            if($gameId)
            {
                $linksId = $this->cPanel->addLink();
                $priceList = $this->parser->parse($linksId);
                $priceId = $this->cPanel->addPrice($priceList);
                $totalId = $this->cPanel->addTotal($gameId, $linksId, $priceId);
            }
            
            if($totalId)
            {
                echo "Супер! Игра успешно добавлена";
                exit();
            }
            else
            {
                echo "Не удалось добавить игру (";
                exit();
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

