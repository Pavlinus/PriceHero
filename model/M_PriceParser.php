<?php
include_once('simple_html_dom.php');
include_once('M_CronUpdateLogger.php');

class M_PriceParser
{
    private static $instance;
    private $msql;
    private $logger;
    
    const STEAMBUY_ID = 1;
    const STEAM_ID = 2;
    const GAMERAY_ID = 3;
    const LOZMAN_ID = 4;
    const ROXEN_ID = 5;

    public function __construct()
    {
        $this->msql = M_MSQL::Instance();
        $this->logger = M_CronUpdateLogger::Instance();
    }
    
    
    /**
     * <p>Работает с экземпляром класса</p>
     * @return Экземпляр класса
     */
    public static function Instance() 
    {
        if (self::$instance == null) 
        {
            self::$instance = new M_PriceParser();
        }

        return self::$instance;
    }
    
    
    /**
     * <p>Извлекает цены с удаленных сайтов</p>
     * @param $links Массив идентификаторов ссылок
     * @return Массив цен
     */
    public function parse($links)
    {
        if(empty($links))
        {
            return array();
        }
        
        $linksData = $this->getLinksData($links);
        $priceList = array();
        
        foreach($linksData as $item)
        {
            switch ($item['site_id']) 
            {
                case self::STEAMBUY_ID:
                    $price = $this->parseSteamBuy($item);
                    if(count($price) > 0)
                    {
                        $priceList[] = $price;
                    }
                    else
                    {
                        $this->logger->addLog($item['link_id']);
                    }
                    break;

                case self::STEAM_ID:
                    $price = $this->parseSteam($item);
                    
                    if(count($price) > 0)
                    {
                        $priceList[] = $price;
                    }
                    else
                    {
                        $this->logger->addLog($item['link_id']);
                    }
                    break;
                    
                case self::GAMERAY_ID:
                    $price = $this->parseGameRay($item);
                    
                    if(count($price) > 0)
                    {
                        $priceList[] = $price;
                    }
                    else
                    {
                        $this->logger->addLog($item['link_id']);
                    }
                    break;
                    
                case self::LOZMAN_ID:
                    $price = $this->parseLozman($item);
                    
                    if(count($price) > 0)
                    {
                        $priceList[] = $price;
                    }
                    else
                    {
                        $this->logger->addLog($item['link_id']);
                    }
                    break;
                    
                case self::ROXEN_ID:
                    $price = $this->parseRoxen($item);
                    
                    if(count($price) > 0)
                    {
                        $priceList[] = $price;
                    }
                    else
                    {
                        $this->logger->addLog($item['link_id']);
                    }
                    break;
            }
        }
        
        return $priceList;
    }
    
    
    /**
     * Извлекает цены с Roxen
     * @param $linkItem Элемент ссылки
     * @return 
     */
    private function parseRoxen($linkItem)
    {
        $link = $linkItem['link'];
        $html = file_get_html($link);
        $matches = null;
        
        foreach($html->find('span.r-curr-price') as $span)
        {
            preg_match('/(\d)+/', $span->outertext, $matches);
        }

        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return array();
        }

        return array(
            'linkId' => $linkItem['link_id'],
            'price' => $matches[0]);
    }
    
    
    /**
     * Извлекает цены с Lozman
     * @param $linkItem Элемент ссылки
     * @return 
     */
    private function parseLozman($linkItem)
    {
        $link = $linkItem['link'];
        $html = file_get_html($link);
        $price = '';
        
        foreach($html->find('div.price') as $span)
        {
            $price = str_replace(" ", "", $span->outertext);
            $price = str_replace("руб.", "", $price);
            break;
        }

        // цена не найдена
        if($price == '')
        {
            return array();
        }

        return array(
            'linkId' => $linkItem['link_id'],
            'price' => $price);
    }
    
    
    /**
     * Извлекает цены с GameRay
     * @param $linkItem Элемент ссылки
     * @return 
     */
    private function parseGameRay($linkItem)
    {
        $link = $linkItem['link'];
        $html = file_get_html($link);
        $matches = null;
        
        foreach($html->find('span[itemprop="price"]') as $span)
        {
            preg_match('/(\d)+/', $span->outertext, $matches);
        }

        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return array();
        }

        return array(
            'linkId' => $linkItem['link_id'],
            'price' => $matches[0]);
    }
    
    
    /**
     * <p>Извлекает цены с SteamBuy</p>
     * @param $linkItem Элемент ссылки
     * @return 
     */
    private function parseSteamBuy($linkItem)
    {
        $link = $linkItem['link'];
        $html = file_get_html($link);
        $matches = null;
        
        foreach($html->find('span.tovar-price') as $span)
        {
            preg_match('/(\d)+/', $span->outertext, $matches);
        }

        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return array();
        }

        return array(
            'linkId' => $linkItem['link_id'],
            'price' => $matches[0]);
    }
    
    
    /**
     * <p>Извлекает цены с Steam</p>
     * @param $linkItem Элемент ссылки
     * @return 
     */
    private function parseSteam($linkItem)
    {
        $link = $linkItem['link'];
        $html = file_get_html($link);
        $matches = null;
        $arResult = $html->find('div.discount_final_price');
        
        if(empty($arResult) || $arResult == null)
        {
            $arResult = $html->find('div.price');
        }
        
        foreach($arResult as $span)
        {
            preg_match('/(\d)+/', $span->outertext, $matches);
        }
        
        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return array();
        }
        
        return array(
            'linkId' => $linkItem['link_id'],
            'price' => $matches[0]);
    }
    
    
    /**
     * <p>Извлекает значения ссылок из БД</p>
     * @param $links Массив идентификаторов ссылок
     * @return Массив значений ссылок
     */
    private function getLinksData($links)
    {
        if(empty($links))
        {
            return false;
        }
        
        $select = 'SELECT * FROM t_link ';
        $where = 'WHERE link_id IN ';
        $idArray = array();
        
        foreach ($links as $value) 
        {
            $idArray[] = $value['linkId'];
        }
        
        $inArray = '(' . implode(',', $idArray) . ')';
        $query = $select . $where . $inArray;
        
        return $this->msql->Select($query);
    }
}	

