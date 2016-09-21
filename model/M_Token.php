<?php

/**
* Класс генерации и обработки токенов
* @author Pavel Kovyrshin
* @date 20.09.2016
*/

include_once "M_MSQL.php";

class M_Token
{
    private $msql;
    private static $instance;


    public function __construct()
    {
        $this->msql = M_MSQL::Instance();
    }


    /**
    * <p>Работает с экземпляром класса</p>
    * @return Экземпляр класса
    */
    public static function Instance()
    {
        if(self::$instance == null)
        {
            self::$instance = new M_Token();
        }

        return self::$instance;
    }
    
    
    /**
     * генерирует и сохраняет временный токен для пользователя
     * @param type $email
     * @return type
     */
    public function getToken($email)
    {
        $token = $this->generateToken($email);
        $this->saveToken($token, $email);
        
        return $token;
    }
    
    
    /**
     * Проверяет наличие токена в БД
     * @param string $userToken токен
     * @return boolean true - существует, иначе false
     */
    public function checkToken($userToken)
    {
        $token = htmlspecialchars($userToken);
        $query = "SELECT * FROM t_token WHERE token='$token'";
        $rows = $this->msql->Select($query);

        if(count($rows) > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
	
    
    /**
     * Сохранение токена в БД
     * @param string $userToken токен
     * @param string $userMail email
     */
    private function saveToken($userToken, $userMail)
    {
        $token = htmlspecialchars($userToken);
        $email = htmlspecialchars($userMail);
        
        $query = "SELECT * FROM t_token WHERE email='$email'";
        $rows = $this->msql->Select($query);
        $object = array(
            'token' => $token,
            'email' => $email
        );
        
        if(count($rows) > 0)
        {
            $where = "email='$email'";
            $this->msql->Update('t_token', $object, $where);
        }
        else
        {
            $this->msql->Insert('t_token', $object);
        }
    }
    
    
    /**
     * Генерация токена для смены пароля пользователя
     * @param string $email
     * @return string токен
     */
    private function generateToken($email)
    {
        $secret_key = "{*Fu->cker|ha)cker}";
        $date = date("l dS of F Y h:i:s A");
        $resultString = md5($secret_key . $date . $email);
        
        return substr($resultString, 5, 9);
    }
    
    
    /**
     * Сброс токена из БД
     * @param string $token токен
     * @return boolean true - успех, иначе false
     */
    public function resetToken($token)
    {
        if(!$this->checkToken($token))
        {
            return false;
        }
        
        if(!$this->delToken($token))
        {
            return false;
        }
        
        return true;
    }
    
    
    /**
     * Удаление токена из БД
     * @param string $tokenVal токен
     * @return boolean true - удален, иначе false
     */
    private function delToken($tokenVal)
    {
        $token = htmlspecialchars($tokenVal);
        $where = "token='$token'";
        $res = $this->msql->Delete('t_token', $where);
        
        if($res > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}