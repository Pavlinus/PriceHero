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

class C_Room extends C_Base
{
    private $auth;
    private $search;
    private $room;
    private $catalog;

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
    }


    /**
    * <p>Главное окно панели управления, либо форма авторизации.</p>
    */
    public function action_index()
    {
        if(isset($_COOKIE['user']) && isset($_COOKIE['user_id']))
        {
            $arGames = $this->room->getGamesList();
            
            $this->content = $this->Template(
                "view/v_game_room.php", 
                array(
                    'gameList' => $arGames
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
}

