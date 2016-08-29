<?php
include_once('simple_html_dom.php');

class M_PriceParser
{
    private static $instance;
    private $msql;
    
    const STEAMBUY_ID = 1;
    const STEAM_ID = 2;

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
                    $priceList[] = $this->parseSteamBuy($item);
                    break;

                case self::STEAM_ID:
                    $priceList[] = $this->parseSteam($item);
                    break;
            }
        }
        
        return $priceList;
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
        
        foreach($html->find('span.tovar-price') as $span)
        {
            preg_match('/(\d)+/', $span->outertext, $matches);
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
        
        foreach($html->find('div.price') as $span)
        {
            preg_match('/(\d)+/', $span->outertext, $matches);
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

