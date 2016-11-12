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
    const GAMEPARK_ID = 6;
    const GZONLINE_ID = 7;
    const STEAMPAY_ID = 8;
    const GAME_REPUBLIC_ID = 9;
    const DANDYLAND_ID = 10;
    const XPRESSGAMES_ID = 11;
    const PLAYGAMES_ID = 12;
    const NEXTGAME_ID = 13;
    const GAMEBUY_ID = 14;
    const PLAYO_ID = 15;
    const GAME93W_ID = 16;


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
            if($item['link'] == '')
            {
                continue;
            }
            
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
                    
                case self::GAMEPARK_ID:
                    $price = $this->parseGamePark($item);
                    
                    if(count($price) > 0)
                    {
                        $priceList[] = $price;
                    }
                    else
                    {
                        $this->logger->addLog($item['link_id']);
                    }
                    break;
                    
                case self::GZONLINE_ID:
                    $price = $this->parseGZOnline($item);
                    
                    if(count($price) > 0)
                    {
                        $priceList[] = $price;
                    }
                    else
                    {
                        $this->logger->addLog($item['link_id']);
                    }
                    break;
                    
                case self::STEAMPAY_ID:
                    $price = $this->parseSteampay($item);
                    
                    if(count($price) > 0)
                    {
                        $priceList[] = $price;
                    }
                    else
                    {
                        $this->logger->addLog($item['link_id']);
                    }
                    break;

                case self::GAME_REPUBLIC_ID:
                    $price = $this->parseGameRepublic($item);
                    
                    if(count($price) > 0)
                    {
                        $priceList[] = $price;
                    }
                    else
                    {
                        $this->logger->addLog($item['link_id']);
                    }
                    break;

                case self::DANDYLAND_ID:
                    $price = $this->parseDandyland($item);
                    
                    if(count($price) > 0)
                    {
                        $priceList[] = $price;
                    }
                    else
                    {
                        $this->logger->addLog($item['link_id']);
                    }
                    break;

                case self::XPRESSGAMES_ID:
                    $price = $this->parseXpressGames($item);
                    
                    if(count($price) > 0)
                    {
                        $priceList[] = $price;
                    }
                    else
                    {
                        $this->logger->addLog($item['link_id']);
                    }
                    break;

                case self::PLAYGAMES_ID:
                    $price = $this->parsePlayGames($item);
                    
                    if(count($price) > 0)
                    {
                        $priceList[] = $price;
                    }
                    else
                    {
                        $this->logger->addLog($item['link_id']);
                    }
                    break;

                case self::NEXTGAME_ID:
                    $price = $this->parseNextGame($item);
                    
                    if(count($price) > 0)
                    {
                        $priceList[] = $price;
                    }
                    else
                    {
                        $this->logger->addLog($item['link_id']);
                    }
                    break;

                case self::GAMEBUY_ID:
                    $price = $this->parseGameBuy($item);
                    
                    if(count($price) > 0)
                    {
                        $priceList[] = $price;
                    }
                    else
                    {
                        $this->logger->addLog($item['link_id']);
                    }
                    break;

                case self::PLAYO_ID:
                    $price = $this->parsePlayo($item);
                    
                    if(count($price) > 0)
                    {
                        $priceList[] = $price;
                    }
                    else
                    {
                        $this->logger->addLog($item['link_id']);
                    }
                    break;

                case self::GAME93W_ID:
                    $price = $this->parseGame93W($item);
                    
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
     * Извлекает цены с Game93W
     * @param $linkItem Элемент ссылки
     * @return 
     */
    private function parseGame93W($linkItem)
    {
        $link = $linkItem['link'];
        $html = file_get_html($link);
        $matches = null;
        
        if($html == null || !$html)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
        }
        
        foreach($html->find('div.ribbon2 b') as $span)
        {
            $val = str_replace(' ', '', $span->outertext);
            preg_match('/\d+/', $val, $matches);
        }

        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
        }
        
        $price = str_replace(" ", "", $matches[0]);
        
        return array(
            'linkId' => $linkItem['link_id'],
            'price' => $price);
    }


    /**
     * Извлекает цены с Playo
     * @param $linkItem Элемент ссылки
     * @return 
     */
    private function parsePlayo($linkItem)
    {
        $link = $linkItem['link'];
        $html = file_get_html($link);
        $matches = null;
        
        if($html == null || !$html)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
        }
        
        foreach($html->find('span.price') as $span)
        {
            $val = str_replace(' ', '', $span->outertext);
            preg_match('/\d+/', $val, $matches);
        }

        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
        }
        
        $price = str_replace(" ", "", $matches[0]);
        
        return array(
            'linkId' => $linkItem['link_id'],
            'price' => $price);
    }


    /**
     * Извлекает цены с GameBuy
     * @param $linkItem Элемент ссылки
     * @return 
     */
    private function parseGameBuy($linkItem)
    {
        $link = $linkItem['link'];
        $html = file_get_html($link);
        $matches = null;
        
        if($html == null || !$html)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
        }
        
        foreach($html->find('div.panel-col-last span.uc-price') as $span)
        {
            $val = str_replace(' ', '', $span->outertext);
            preg_match('/\d+/', $val, $matches);
            break;
        }

        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
        }
        
        $price = str_replace(" ", "", $matches[0]);
        
        return array(
            'linkId' => $linkItem['link_id'],
            'price' => $price);
    }


    /**
     * Извлекает цены с NextGame
     * @param $linkItem Элемент ссылки
     * @return 
     */
    private function parseNextGame($linkItem)
    {
        $link = $linkItem['link'];
        $html = file_get_html($link);
        $matches = null;
        
        if($html == null || !$html)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
        }
        
        foreach($html->find('span[id="price"]') as $span)
        {
            $val = str_replace(' ', '', $span->innertext);
            preg_match('/\d+/', $val, $matches);
        }

        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
        }
        
        $price = str_replace(" ", "", $matches[0]);
        
        return array(
            'linkId' => $linkItem['link_id'],
            'price' => $price);
    }


    /**
     * Извлекает цены с PlayGames
     * @param $linkItem Элемент ссылки
     * @return 
     */
    private function parsePlayGames($linkItem)
    {
        $link = $linkItem['link'];
        $html = file_get_html($link);
        $matches = null;
        
        if($html == null || !$html)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
        }
        
        foreach($html->find('div.add2cart span.price') as $span)
        {
            $val = str_replace(' ', '', $span->innertext);
            preg_match('/\d+/', $val, $matches);
        }

        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
        }
        
        $price = str_replace(" ", "", $matches[0]);
        
        return array(
            'linkId' => $linkItem['link_id'],
            'price' => $price);
    }


    /**
     * Извлекает цены с XpressGames
     * @param $linkItem Элемент ссылки
     * @return 
     */
    private function parseXpressGames($linkItem)
    {
        $link = $linkItem['link'];
        $html = file_get_html($link);
        $matches = null;
        
        if($html == null || !$html)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
        }
        
        foreach($html->find('span.price-new') as $span)
        {
            $val = str_replace(' ', '', $span->innertext);
            preg_match('/\d+/', $val, $matches);
        }

        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
        }
        
        $price = str_replace(" ", "", $matches[0]);
        
        return array(
            'linkId' => $linkItem['link_id'],
            'price' => $price);
    }


    /**
     * Извлекает цены с Dandyland
     * @param $linkItem Элемент ссылки
     * @return 
     */
    private function parseDandyland($linkItem)
    {
        $link = $linkItem['link'];
        $html = file_get_html($link);
        $matches = null;
        
        if($html == null || !$html)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
        }
        
        foreach($html->find('span.inner_price_el') as $span)
        {
            $val = str_replace(' ', '', $span->innertext);
            preg_match('/\d+/', $val, $matches);
        }

        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
        }
        
        $price = str_replace(" ", "", $matches[0]);
        
        return array(
            'linkId' => $linkItem['link_id'],
            'price' => $price);
    }


    /**
     * Извлекает цены с GameRepublic
     * @param $linkItem Элемент ссылки
     * @return 
     */
    private function parseGameRepublic($linkItem)
    {
        $link = $linkItem['link'];
        $html = file_get_html($link);
        $matches = null;
        
        if($html == null || !$html)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
        }
        
        foreach($html->find('span.catalog_price_big') as $span)
        {
            preg_match('/\d+/', $span->innertext, $matches);
            break;
        }

        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
        }
        
        $price = str_replace(" ", "", $matches[0]);
        
        return array(
            'linkId' => $linkItem['link_id'],
            'price' => $price);
    }
    
    
    /**
     * Извлекает цены с Steampay
     * @param $linkItem Элемент ссылки
     * @return 
     */
    private function parseSteampay($linkItem)
    {
        $link = $linkItem['link'];
        $html = file_get_html($link);
        $matches = null;
        
        if($html == null || !$html)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
        }
        
        foreach($html->find('span.price') as $span)
        {
            preg_match('/\d+[\s]{0,1}\d*/', $span->outertext, $matches);
        }

        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
        }
        
        $price = str_replace(" ", "", $matches[0]);
        
        return array(
            'linkId' => $linkItem['link_id'],
            'price' => $price);
    }
    
    
    /**
     * Извлекает цены с GZOnline
     * @param $linkItem Элемент ссылки
     * @return 
     */
    private function parseGZOnline($linkItem)
    {
        $link = $linkItem['link'];
        $html = file_get_html($link);
        $matches = null;
        
        if($html == null || !$html)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
        }
        
        foreach($html->find('div.list_price') as $span)
        {
            preg_match('/\d+[\s]{0,1}\d*/', $span->outertext, $matches);
        }

        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
        }
        
        $price = str_replace(" ", "", $matches[0]);
        
        return array(
            'linkId' => $linkItem['link_id'],
            'price' => $price);
    }
    
    
    /**
     * Извлекает цены с GamePark
     * @param $linkItem Элемент ссылки
     * @return 
     */
    private function parseGamePark($linkItem)
    {
        $link = $linkItem['link'];

        // Делаем из партнерки обычную ссылку
        if(preg_match('/.*ad\.admitad\.com.*/i', $link))
        {
            $link = preg_replace('/.*ulp=/i', '', $link);
            $link = str_replace('%3A', ':', $link);
            $link = str_replace('%2F', '/', $link);
            $link = str_replace('%3F', '?', $link);
            $link = str_replace('%3D', '=', $link);
        }

        $html = file_get_html($link);
        $matches = null;
        
        if($html == null || !$html)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
        }
        
        foreach($html->find('div#eprice') as $span)
        {
            preg_match('/(\d)+/', $span->outertext, $matches);
            break;
        }

        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
        }

        return array(
            'linkId' => $linkItem['link_id'],
            'price' => $matches[0]);
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
        
        if($html == null || !$html)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
        }
        
        foreach($html->find('span.r-curr-price') as $span)
        {
            preg_match('/(\d)+/', $span->outertext, $matches);
            break;
        }

        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
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
        $matches = array();
        
        if($html == null || !$html)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
        }
        
        foreach($html->find('div.not_order_price') as $span)
        {
            $outerText = str_replace(" ", "", $span->outertext);
            preg_match('/(\d)+/', $outerText, $matches);
            break;
        }

        foreach($html->find('div.price') as $span)
        {
            $outerText = str_replace(" ", "", $span->outertext);
            preg_match('/(\d)+/', $outerText, $matches);
            break;
        }


        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
        }

        return array(
            'linkId' => $linkItem['link_id'],
            'price' => $matches[0]);
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
        
        if($html == null || !$html)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
        }
        
        foreach($html->find('span[itemprop="price"]') as $span)
        {
            preg_match('/(\d)+/', $span->outertext, $matches);
        }

        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
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
        
        if($html == null || !$html)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
        }
        
        foreach($html->find('span.tovar-price') as $span)
        {
            preg_match('/(\d)+/', $span->outertext, $matches);
        }

        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
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
        
        if($html == null || !$html)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
        }
        
        $arResult = $html->find('div.discount_final_price');
        
        if(empty($arResult) || $arResult == null)
        {
            $arResult = $html->find('div.price');
        }
        
        if(empty($arResult) || $arResult == null)
        {
            $arResult = $html->find('div.game_purchase_price');
        }
        
        
        foreach($arResult as $span)
        {
            preg_match('/(\d)+/', $span->outertext, $matches);
            break;
        }
        
        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return array(
                'linkId' => $linkItem['link_id'],
                'price' => 0);
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
    public function getLinksData($links)
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

