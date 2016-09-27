<?php

/**
* Класс записи логов
* @author Pavel Kovyrshin
* @date 17.09.2016
*/

include_once "M_MSQL.php";

class M_CronUpdateLogger
{
    private $msql;
    private $file;
    private static $instance;
    
    public function __construct()
    {
        $this->msql = M_MSQL::Instance();
        $this->file = "cron_log.txt";
    }
    
    /**
    * Работает с экземпляром класса
    * @return Экземпляр класса
    */
    public static function Instance()
    {
        if(self::$instance == null)
        {
            self::$instance = new M_CronUpdateLogger();
        }

        return self::$instance;
    }
    
    
    /**
     * Добавление записи в таблицу `t_cronLogger`
     * @param int $linkId ID ссылки
     */
    public function addLog($linkId)
    {
        $nextId = $this->getLoggerNextId();
        $object = array(
            'log_id' => $nextId,
            'link_id' => $linkId
        );
        $this->msql->Insert('t_cronLogger', $object);
    }
    
    
    /**
     * Получаем свободный ID для вставки в таблицу t_cronLogger
     * @return int свободный ID, иначе следующий индекс = 1
     */
    private function getLoggerNextId()
    {
        $query = "SELECT MAX(log_id) as c_id FROM t_cronLogger";
        $rows = $this->msql->Select($query);
        
        if(!empty($rows))
        {
            return $rows[0]['c_id'] + 1;
        }
        else
        {
            return 1;
        }
    }
    
    
    /**
     * Удаление записей
     * @param array $arLogId массив ID ссылок
     */
    public function deleteLog($arLogId)
    {
        foreach($arLogId as $log)
        {
            $where = "link_id = " . $log;
            $this->msql->Delete('t_cronLogger', $where);
        }
    }
    
    
    /**
     * Отсылает уведомление об оставшихся ошибках парсинга
     */
    public function sendNotification()
    {
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=utf-8\r\n";
        $headers .= "From: info@games2buy.ru\r\n";
        
        $subject = "Games2Buy уведомление";
        $to = "pavlin.kov@mail.ru";
        
        $msg  = "Походу тебе придется запустить парсинг вручную.\n";
        $msg .= "Я сделал все что смог.\n";
        
        mail($to, $subject, $msg, $headers);
    }
    
    
    /**
     * Подсчет общего числа логов
     * @return type
     */
    public function countLogs()
    {
        $query = "SELECT COUNT(log_id) as logs FROM t_cronLogger";
        $res = $this->msql->Select($query);
        
        return $res[0]['logs'];
    }
}