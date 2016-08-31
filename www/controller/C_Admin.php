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
    private $fields;


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
        
        if($this->fields == null)
        {
            $this->fields = M_Fields::Instance();
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
                header("Location: index.php?c=admin");
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
            $genres = $this->fields->getFields('t_genre');
            $platforms = $this->fields->getFields('t_platform');
            $sites = $this->fields->getFields('t_site');
            
            $this->content = $this->Template("view/v_admin_add_game.php", 
                    array(
                        'genres' => $genres,
                        'platforms' => $platforms,
                        'sites' => $sites
                    ));
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
            $this->cPanel->addGameKeywords($gameId);
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
    
    
    /**
    * <p>Вывод интерфейса редактирования игры</p>
    * @return
    */
    public function action_editGame()
    {
        if(isset($_COOKIE['user']) && $_COOKIE['user'] == '007')
        {
            if(isset($_REQUEST['id']))
            {
                $genres = $this->fields->getFields('t_genre');
                $platforms = $this->fields->getFields('t_platform');
                $sites = $this->fields->getFields('t_site');
                $gameData = $this->cPanel->getGameDataToEdit($_REQUEST['id']);
                
                if(!$gameData)
                {
                    echo "Не удалось загрузить данные";
                    exit();
                }
                
                $this->content = $this->Template(
                        "view/v_admin_edit_game.php", 
                        array(
                            'gameData' => $gameData,
                            'genres' => $genres,
                            'platforms' => $platforms,
                            'sites' => $sites
                        )
                );
            }
            else
            {
                header("Location: index.php?c=admin");
            }
        }
    }
    
    
    /**
    * <p>Обработка запроса на изменение данных игры</p>
    * @return
    */
    public function action_editGameAjax()
    {
        if($this->isPost())
        {
           $saveResult = $this->cPanel->saveGameData();
           
           if($saveResult || $saveResult == array())
           {
               echo "Супер! Игра успешно сохранена";
               exit();
           }
           else
           {
               echo "Изменения не сохранены";
               exit();
           }
        }
    }


    public function action_removeGame()
    {

    }


    public function action_saveGame()
    {

    }


    public function action_filter()
    {

    }
}

