<?php

/**
* <p>Класс выполнения авторизации пользователя</p>
* @author Pavel Kovyrshin
* @date 03.09.2016
*/

include_once "M_MSQL.php";

class M_Link
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
            self::$instance = new M_Link();
        }

        return self::$instance;
    }
	
    
    /**
     * <p>Добавляет новую ссылку в БД</p>
     * @return Id новой(ых) ссылки(ок), иначе false
     */
    public function addLink() 
    {
        $linksData = array();

        if (isset($_POST['links'])) 
        {
            foreach ($_POST['links'] as $item) 
            {
                if($item['link'] == '')
                {
                    return false;
                }
                
                $object = array(
                    'site_id' => htmlspecialchars($item['service']),
                    'link' => htmlspecialchars($item['link']),
                );

                $id = $this->msql->Insert('t_link', $object);

                if ($id) 
                {
                    $linksData[] = array(
                        'linkId' => $id,
                        'site_id' => htmlspecialchars($item['service']),
                        'platform' => htmlspecialchars($item['platform'])
                    );
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }

        return $linksData;
    }


    /**
     * <p>Добавляет новую ссылку в БД</p>
     * @return Id новой(ых) ссылки(ок), иначе false
     */
    public function addLinkExt($gameData) 
    {
        $linksData = array();

        foreach ($gameData as $item) 
        {   
            $object = array(
                'site_id' => htmlspecialchars($item['site_id']),
                'link' => htmlspecialchars($item['link']),
            );

            $id = $this->msql->Insert('t_link', $object);

            if ($id) 
            {
                $linksData[] = array(
                    'linkId' => $id,
                    'site_id' => htmlspecialchars($item['site_id']),
                    'platform' => htmlspecialchars($item['platform_id'])
                );
            } else {
                return false;
            }
        }

        return $linksData;
    }
    
    
    /**
    * Получение объединенных данных о ссылке из `t_link` и `t_site`
    * @param array $links Строка ID извлекаемых ссылок
    * @return array Массив значений ссылок и сервисов, иначе false
    */
    public function getTblLinkMerged($links)
    {
        if(empty($links))
        {
            return false;
        }
        
        $linksStr = '(' . implode(',', $links) . ')';

        $query = " SELECT * FROM t_link "
                . "LEFT JOIN t_site ON t_site.site_id=t_link.site_id "
                . "WHERE link_id IN $linksStr";

        return $this->msql->Select($query);
    }
    
    
    /**
    * Обновление данных в таблицах `t_link`, `t_total`
    * @return boolean True если сохранение прошло, иначе False
    */
    public function updateLink()
    {
        foreach($_REQUEST['linksUpdate'] as $link)
        {
            $objectLink = array(
                'site_id' => $link['service'],
                'link' => $link['link'],
            );
            
            $whereLink = "link_id = {$link['linkId']}";
            
            $this->msql->Update('t_link', $objectLink, $whereLink);
            
            $objectTotal = array(
                'platform_id' => $link['platform']
            );
            
            $whereTotal = "link_id = {$link['linkId']}";
            
            $this->msql->Update('t_total', $objectTotal, $whereTotal);
        }
    }
    
    
    /**
     * Удаляет данные ссылки
     */
    public function deleteTblLink($toDelete = array())
    {
        if(isset($_REQUEST['linksDelete']) && !empty($_REQUEST['linksDelete']))
        {
            $toDelete = $_REQUEST['linksDelete'];
        }

        $priceIdArray = array();
        
        foreach($toDelete as $link)
        {
            $this->msql->Delete('t_link', "link_id=$link");
            
            $query = "SELECT price_id FROM t_total WHERE link_id=$link";
            $row = $this->msql->Select($query);

            if($row)
            {
                $priceIdArray[] = $row[0]['price_id'];
            }
            
            $this->msql->Delete('t_total', "link_id=$link");
        }
        
        return $priceIdArray;
    }
}