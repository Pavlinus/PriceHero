<?php

/**
* <p>Класс выполнения авторизации пользователя</p>
* @author Pavel Kovyrshin
* @date 03.09.2016
*/

include_once "M_MSQL.php";
include_once "M_Token.php";

class M_Room
{
    private $msql;
    private static $instance;
    private $catalog;
    private $token;


    public function __construct()
    {
        $this->msql = M_MSQL::Instance();
        
        if($this->catalog == null)
        {
            $this->catalog = M_Catalog::Instance();
        }
        
        if($this->token == null)
        {
            $this->token = M_Token::Instance();
        }
    }


    /**
    * <p>Работает с экземпляром класса</p>
    * @return Экземпляр класса
    */
    public static function Instance()
    {
        if(self::$instance == null)
        {
            self::$instance = new M_Room();
        }

        return self::$instance;
    }
	
    
    /**
     * Извлекает игры, привязанные к пользователю
     * @return array Массив ID игр и ID платформ, иначе false
     */
    public function getUserGames()
    {
        if(!isset($_COOKIE['user_id']))
        {
            return false;
        }
        
        if(isset($_POST['platformId']) && !empty($_POST['platformId']))
        {
            $and  = " AND platform_id IN ";
            $and .= "(" . implode(",", $_POST['platformId']) . ")";
        }
        else
        {
            $and = '';
        }
        
        $userId = htmlspecialchars($_COOKIE['user_id']);
        $query = "SELECT game_id, platform_id FROM t_tracker ";
        $query .= "WHERE user_id=$userId $and";
        $rows = $this->msql->Select($query);
        $arGames = array();

        if($rows)
        {
            foreach ($rows as $row)
            {
                $arGames[] = array(
                    'game_id' => $row['game_id'],
                    'platform_id' => $row['platform_id']
                );
            }
        }
        
        return $arGames;
    }
    
    
    /**
     * Извлекает массив данных игр пользователя
     * @return array массив данных игр
     */
    public function getGamesList()
    {
        $arGames = $this->getUserGames();
        $arResult = array();
        
        foreach($arGames as $arItem)
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

        return $arResult;
    }
    
    
    /**
     * Извлекает игры, привязанные к пользователю, по ID
     * @return array Массив ID игр и ID платформ
     */
    public function getGamesById($arGamesId)
    {
        if(!isset($_COOKIE['user_id']))
        {
            return false;
        }
        
        if(!empty($arGamesId))
        {
            $and  = " AND game_id IN ";
            $and .= "(" . implode(",", $arGamesId) . ")";
        }
        else
        {
            return array();
        }
        
        $userId = htmlspecialchars($_COOKIE['user_id']);
        $query = "SELECT game_id, platform_id FROM t_tracker ";
        $query .= "WHERE user_id=$userId $and";
        $rows = $this->msql->Select($query);
        $arGames = array();

        if($rows)
        {
            foreach ($rows as $row)
            {
                $arGames[] = array(
                    'game_id' => $row['game_id'],
                    'platform_id' => $row['platform_id']
                );
            }
        }
        
        return $arGames;
    }
    
    
    /**
     * Регистрация нового пользователя
     * @return int -1 - логин существует, 0 - ошибка записи, > 0 - успех
     */
    public function saveNewUser()
    {
        if(isset($_POST['au_login']) &&
           isset($_POST['au_password']) &&
           isset($_POST['au_email']))
        {
            $login = htmlspecialchars($_POST['au_login']);
            $password = htmlspecialchars($_POST['au_password']);
            $email = htmlspecialchars($_POST['au_email']);
            
            if(!$this->checkLogin($login))
            {
                return -1;
            }
            
            $object = array(
                'login' => $login,
                'password' => md5($password),
                'email' => $email
            );
            
            return $this->msql->Insert('t_user', $object);
        }
    }
    
    
    /**
     * Проверка существующего логина
     * @param string $userLogin логин пользователя
     * @return boolean true если логина не существует, иначе false
     */
    private function checkLogin($userLogin)
    {
        $login = htmlspecialchars($userLogin);
        $query = "SELECT * FROM t_user WHERE login='$login'";
        $rows = $this->msql->Select($query);
        
        if(count($rows) > 0)
        {
            return false;
        }
        
        return true;
    }
    
    
    /**
     * Обработка запроса на восстановление пароля
     * @return boolean
     */
    public function restorePassword()
    {
        if(isset($_POST['au_email']))
        {
            return false;
        }
        
        $email = htmlspecialchars($_POST['au_email']);
        $token = $this->token->getToken($email);
        $this->sendRestoreEmail($email, $token);
    }
    
    
    /**
     * Отправка письма для восстановления пароля пользователю
     * @param string $email email
     * @param string $token токен
     */
    private function sendRestoreEmail($email, $subject, $token)
    {
        $to  = "<$email>"; 
        
        $subject = "Запрос на смену пароля | sitename";
        
        $link =   "http://sitename.ru/index.php?"
                . "c=room&act=restorePassword&token=$token&email=$email";
        
        $message = " <p>Привет, пользователь!</p> </br>";
        $message = " <p>Для смены пароля своей учетной записи перейди по ссылке ниже:</p> </br>";
        $message = " <a href='$link'>$link</a> </br>";

        $headers  = "Content-type: text/html; charset=utf-8 \r\n"; 
        $headers .= "From: От кого письмо <sitename@somemail.com>\r\n"; 

        mail($to, $subject, $message, $headers); 
    }
    

    
    public function saveSettings()
    {
        
    }
}