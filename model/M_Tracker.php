<?php

/**
* <p>Класс выполнения авторизации пользователя</p>
* @author Pavel Kovyrshin
* @date 03.09.2016
*/

include_once "M_MSQL.php";

class M_Tracker
{
    private $msql;
    private static $instance;
    
    const TRACKER_AUTH = 3;
    const TRACKER_ADD = 2;
    const TRACKER_DELETE = 1;

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
            self::$instance = new M_Tracker();
        }

        return self::$instance;
    }
	
    
    /**
     * Устанавливает трекинг игры пользователем
     * @return int 1 - трекер удален, 2 - трекер добавлен, 0 - ошибка
     */
    public function switchTracker()
    {
        if(isset($_REQUEST['gameId']) &&
           isset($_REQUEST['platformId']) &&
           isset($_COOKIE['user_id']))
        {
            $gameId = htmlspecialchars($_REQUEST['gameId']);
            $platformId = htmlspecialchars($_REQUEST['platformId']);
            
            $query  = "SELECT * FROM t_tracker ";
            $query .= "WHERE game_id=$gameId AND platform_id=$platformId";
            $rows = $this->msql->Select($query);
            
            if(!empty($rows))
            {
                $trackerId = $rows[0]['tracker_id'];
                return $this->deleteTracker($gameId, $platformId, $trackerId);
            }
            else
            {
                return $this->addTracker($gameId, $platformId);
            }
        }
        else
        {
            return 0;
        }
    }
    
    
    /**
     * Удаление трекера
     * @param int $gameId ID игры
     * @param int $platformId ID платформы
     * @param int $trackerId ID трекера
     * @return int 1 - успех, 0 - ошибка
     */
    private function deleteTracker($gameId, $platformId, $trackerId)
    {
        $res = $this->addIndexerValue($trackerId);
        
        if($res == 0)
        {
            return 0;
        }
        
        $userId = $_COOKIE['user_id'];
        $where = "user_id=$userId AND game_id=$gameId AND platform_id=$platformId";
        $rows = $this->msql->Delete('t_tracker', $where);
        
        if($rows > 0)
        {
            return 1;
        }
        else
        {
            return 0;
        }
    }
    
    
    /**
     * Добавление трекера
     * @param int $gameId ID игры
     * @param int $platformId ID платформы
     * @return int 2 - успех, 0 - ошибка
     */
    private function addTracker($gameId, $platformId)
    {
        $trackerId = $this->getIndexerValue();

        $userId = $_COOKIE['user_id'];
        $object = array(
            'tracker_id' => $trackerId,
            'user_id' => $userId,
            'game_id' => $gameId,
            'platform_id' => $platformId
        );
        
        $this->msql->Insert('t_tracker', $object);
        $rows = $this->msql->getAffectedRows();

        if($rows > 0)
        {
            return 2;
        }
        else
        {
            return 0;
        }
    }
    
    
    /**
     * Добавляем ID удаляемого трекера в t_trackerIndexer
     * @param int $trackerId ID трекера
     * @return int количество затронутых строк БД
     */
    private function addIndexerValue($trackerId)
    {
        $indexerId = $this->getIndexerNextId();
        
        $idTracker = htmlspecialchars($trackerId);
        $object = array(
            'indexer_id' => $indexerId,
            'tracker_id' => $idTracker
        );
        
        $this->msql->Insert('t_trackerIndexer', $object);
        return $this->msql->getAffectedRows();
    }
    
    
    /**
     * Извлекает свободный ID трекера для новой записи в t_tracker
     * @return int ID трекера, иначе false
     */
    private function getIndexerValue()
    {
        $query  = "SELECT tracker_id FROM t_trackerIndexer ";
        $query .= "ORDER BY indexer_id DESC";
        $rows = $this->msql->Select($query);
        
        if(!empty($rows))
        {
            $this->deleteIndexerValue($rows[0]['tracker_id']);
            return $rows[0]['tracker_id'];
        }
        else
        {
            return $this->getTrackerNextId();
        }
    }
    
    
    /**
     * Удаляем запись таблицы t_trackerIndexer
     * @param type $trackerId
     */
    private function deleteIndexerValue($trackerId)
    {
        $where = "tracker_id=$trackerId";
        $this->msql->Delete('t_trackerIndexer', $where);
    }
    
    
   /**
     * Получаем свободный ID для вставки в таблицу t_tracker
     * @return int свободный ID, иначе следующий индекс = 1
     */
    private function getTrackerNextId()
    {
        $query = "SELECT MAX(tracker_id) as t_id FROM t_tracker";
        $rows = $this->msql->Select($query);
        
        if(!empty($rows))
        {
            return $rows[0]['t_id'] + 1;
        }
        else
        {
            return 1;
        }
    }
    
    
    /**
     * Получаем ID для вставки в таблицу t_trackerIndexer
     * @return int свободный ID, иначе свободный индекс = 1
     */
    private function getIndexerNextId()
    {
        $query = "SELECT MAX(indexer_id) as i_id FROM t_trackerIndexer";
        $rows = $this->msql->Select($query);
        
        if($rows)
        {
            return $rows[0]['i_id'] + 1;
        }
        else
        {
            return 1;
        }
    }
}