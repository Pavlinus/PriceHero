<?php

/**
* Контроллер панели администратора
* @author Pavel Kovyrshin
* @date 30.07.2016
*/

include_once "/model/M_ControlPanel.php";
include_once "/model/M_Auth.php";
include_once "/model/M_Game.php";
include_once "/model/M_Catalog.php";
include_once "/model/M_CheapHolidays.php";
include_once "/model/M_Leaders.php";

class C_Admin extends C_Base
{
    private $cPanel;
    private $auth;
    private $parser;
    private $fields;
    private $search;
    private $mGame;
    private $mCatalog;
    private $mCheapHolidays;
    private $mLeaders;

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

        if($this->auth == null)
        {
            $this->auth = M_Auth::Instance();
        }
        
        if($this->search == null)
        {
            $this->search = M_Search::Instance();
        }
        
        if($this->mGame == null)
        {
            $this->mGame = M_Game::Instance();
        }
        
        if($this->mCatalog == null)
        {
            $this->mCatalog = M_Catalog::Instance();
        }

        if($this->mCheapHolidays == null)
        {
            $this->mCheapHolidays = M_CheapHolidays::Instance();
        }

        if($this->mLeaders == null)
        {
            $this->mLeaders = M_Leaders::Instance();
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
            $platforms = $this->fields->getFields('t_platform');
            
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
                        'gameList' => $gamesList,
                        'platforms' => $platforms
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
                header("Location: index.php?c=suckmyadmincock");
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
     * Загрузка картинки
     */
    public function action_uploadImageAjax()
    {
        $uploadPath = $_SERVER[DOCUMENT_ROOT] . '/upload/images/';
        $default = $_SERVER[DOCUMENT_ROOT] . '/images/game_default.png';
        
        if(!isset($_FILES['gameImage']))
        {
            return $default;
        }
        
        $uploadFile = $uploadPath . $_FILES['gameImage']['name'];
        move_uploaded_file($_FILES['gameImage']['tmp_name'], $uploadFile);
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
                header("Location: index.php?c=suckmyadmincock");
            }
        }
    }
    
    
    /**
    * Обработка запроса на изменение данных игры
    * @return
    */
    public function action_editGameAjax()
    {
        if($this->isPost())
        {
           $saveResult = $this->cPanel->saveGameData();
           
           if($saveResult && !empty($saveResult))
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

    
    /**
     * Обработка поиска игры
     */
    public function action_findGameAjax()
    {
        if($this->isPost())
        {
            $gamesList = $this->search->searchGame();
            
            if(!$gamesList)
            {
                $gamesList = array();
            }
            else
            {
                $gamesList = $this->mGame->getTblGameList($gamesList);
            }

            echo $this->Template(
                    'view/v_admin_search_result.php', 
                    array(
                        'gameList' => $gamesList
                    )
            );
            exit();
        }
    }
    
    
    /**
     * Выхоод из учетной записи
     */
    public function action_logout()
    {
        $this->auth->logout();
        header("Location: index.php?c=suckmyadmincock");
    }

    
    /**
     * Обработка фильтрации данных
     */
    public function action_filter()
    {
        $platforms = $this->fields->getFields('t_platform');
        $arPlatform = array(1);    // выбираем PC по умолчанию
        $where = '';
        
        if(isset($_POST['platformId']) && !empty($_POST['platformId']))
        {
            $arPlatform = $_POST['platformId'];
            
            $platformStr = "(" . implode(",", $arPlatform) . ")";
            $where = 'WHERE Total.platform_id IN '.$platformStr;
        }
        
        $gamesList = $this->cPanel->getFilteredGamesList($where);
        
        echo $this->Template('view/v_admin_filter_result.php', 
                array(
                    'gameList' => $gamesList,
                    'platforms' => $platforms
                )
        );
        exit();
    }
    
    
    public function action_removeGame()
    {
        if(isset($_POST['gameId']))
        {
            echo $this->mGame->delGame($_POST['gameId']);
        }
        else
        {
            echo 0;
        }
        exit();
    }


    /**
    * <p>Вывод интерфейса добавления игры</p>
    * @return
    */
    public function action_cheapHolidays()
    {
        if(isset($_COOKIE['user']) && $_COOKIE['user'] == '007')
        {
            $gamesList = $this->cPanel->getGamesListAll();
            $arGamesId = $this->mCheapHolidays->getGameIdList();
            $holidaysGames = $this->mGame->getTblGameList($arGamesId);
            
            $this->content = $this->Template(
                "view/v_admin_cheap_holidays.php", 
                array(
                    'gamesList' => $gamesList,
                    'holidaysGames' => $holidaysGames
                )
            );
        }
    }


    /**
    * <p>Вывод интерфейса добавления игры</p>
    * @return
    */
    public function action_cheapHolidaysAjax()
    {
        if(isset($_COOKIE['user']) && $_COOKIE['user'] == '007')
        {
            echo $this->mCheapHolidays->save($_POST['id_list']);
            exit();
        }
    }


    /**
    * <p>Вывод интерфейса добавления игры</p>
    * @return
    */
    public function action_leaders()
    {
        if(isset($_COOKIE['user']) && $_COOKIE['user'] == '007')
        {
            $gamesList = $this->cPanel->getGamesListAll();
            $arGamesId = $this->mLeaders->getGameIdList();
            $leaders = $this->mGame->getTblGameList($arGamesId);
            
            $this->content = $this->Template(
                "view/v_admin_leaders.php", 
                array(
                    'gamesList' => $gamesList,
                    'leaders' => $leaders
                )
            );
        }
    }


    /**
    * <p>Вывод интерфейса добавления игры</p>
    * @return
    */
    public function action_leadersAjax()
    {
        if(isset($_COOKIE['user']) && $_COOKIE['user'] == '007')
        {
            echo $this->mLeaders->save($_POST['id_list']);
            exit();
        }
    }
}

