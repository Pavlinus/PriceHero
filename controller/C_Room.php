<?php

/**
* <p>Контроллер панели администратора</p>
* @author Pavel Kovyrshin
* @date 30.07.2016
*/

include_once "/model/M_Room.php";
include_once "/model/M_Search.php";
include_once "/model/M_Auth.php";
include_once "/model/M_Catalog.php";
include_once "/model/M_Tracker.php";

class C_Room extends C_Base
{
    private $auth;
    private $search;
    private $room;
    private $catalog;
    private $fields;
    private $tracker;

    public function __construct()
    {
        if($this->auth == null)
        {
            $this->auth = M_Auth::Instance();
        }
        
        if($this->search == null)
        {
            $this->search = M_Search::Instance();
        }
        
        if($this->room == null)
        {
            $this->room = M_Room::Instance();
        }
        
        if($this->catalog == null)
        {
            $this->catalog = M_Catalog::Instance();
        }
        
        if($this->fields == null)
        {
            $this->fields = M_Fields::Instance();
        }
        
        if($this->tracker == null)
        {
            $this->tracker = M_Tracker::Instance();
        }
    }


    /**
    * <p>Главное окно панели управления, либо форма авторизации.</p>
    */
    public function action_index()
    {
        if(isset($_COOKIE['user']) && isset($_COOKIE['user_id']))
        {
            $arGames = $this->room->getGamesList();
            $platforms = $this->fields->getFields('t_platform');
            
            $this->content = $this->Template(
                "view/v_game_room.php", 
                array(
                    'gameList' => $arGames,
                    'platforms' => $platforms
                )
            );
        }
        else
        {
            $this->content = $this->Template(
                "view/v_user_auth.php", 
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

            if($authRes)
            {
                header("Location: index.php?c=room");
            }
            else
            {
                $error = 'Неверный логин или пароль';
                $this->content = $this->Template("view/v_user_auth.php", 
                        array('error' => $error));
            }
        }
    }


    /**
     * Выход из учетной записи
     */
    public function action_logout()
    {
        if($this->auth->logout())
        {
            header("Location: index.php");
        }
        else 
        {
            $error = 'Вы неавторизованы, либо сессия уже закончена';
            $this->content = $this->Template("view/v_user_auth.php", 
                    array('error' => $error));
        }
    }
    
    
    /**
     * Фильтрация контента
     */
    function action_filter()
    {
        if(isset($_COOKIE['user']) && isset($_COOKIE['user_id']))
        {
            $arGames = $this->room->getGamesList();
            $platforms = $this->fields->getFields('t_platform');
            
            echo $this->Template(
                "view/v_room_filter_result.php", 
                array(
                    'gameList' => $arGames,
                    'platforms' => $platforms
                )
            );
            exit();
        }
        else
        {
            $this->content = $this->Template(
                "view/v_user_auth.php", 
                array());
        }
    }
    
    
    /**
     * Удаляем игру из списка отслеживаемых
     */
    function action_delete()
    {
        if(isset($_REQUEST['gameId']) && isset($_REQUEST['platformId']))
        {
            echo $this->tracker->switchTracker();
            exit();
        }
    }
    
    
    /**
     * Обработчик запроса поиска игр пользователя
     */
    public function action_findGameAjax()
    {
        if($this->isPost())
        {
            $arGamesId = $this->search->searchGame();

            if(!$arGamesId)
            {
                $arGamesId = array();
            }
            
            $arUserGames = $this->room->getGamesById($arGamesId);
            $arResult = array();
            
            foreach($arUserGames as $arItem)
            {
                $arId = array($arItem['game_id']);
                $where = 'Game.game_id';
                $and = array(
                    'Platform.platform_id' => array($arItem['platform_id'])
                );

                $row = $this->catalog->getGames($arId, $where, $and);

                if(!empty($row))
                {
                    $arResult[] = $row[0];
                }
            }

            echo $this->Template(
                    'view/v_room_search_result.php', 
                    array(
                        'gameList' => $arResult
                    )
            );
            exit();
        }
    }
}

