<?php
include_once('simple_html_dom.php');

class LinkSearchParser
{
    private static $instance;
    private $dom;
    
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
        $this->dom = new simple_html_dom(
          null, true, true, DEFAULT_TARGET_CHARSET, true, DEFAULT_BR_TEXT, DEFAULT_SPAN_TEXT);
    }
    
    
    /**
     * <p>Работает с экземпляром класса</p>
     * @return Экземпляр класса
     */
    public static function Instance() 
    {
        if (self::$instance == null) 
        {
            self::$instance = new LinkSearchParser();
        }

        return self::$instance;
    }
    
    
    public function parse($content, $siteId)
    {
        switch ($siteId) 
        {
            case self::STEAMBUY_ID:
                return $this->parseSteamBuy($content);
                break;

            case self::STEAM_ID:
                $price = $this->parseSteam($content);
                break;
                
            case self::GAMERAY_ID:
                return $this->parseGameRay($content);
                break;
                
            case self::LOZMAN_ID:
                return $this->parseLozman($content);
                
            case self::ROXEN_ID:
                $price = $this->parseRoxen($content);
                break;
                
            case self::GAMEPARK_ID:
                return $this->parseGamePark($content);
                break;
                
            case self::GZONLINE_ID:
                return $this->parseGZOnline($content);
                break;
                
            case self::STEAMPAY_ID:
                return $this->parseSteampay($content);
                break;

            case self::GAME_REPUBLIC_ID:
                $price = $this->parseGameRepublic($content);
                break;

            case self::DANDYLAND_ID:
                $price = $this->parseDandyland($content);
                break;

            case self::XPRESSGAMES_ID:
                $price = $this->parseXpressGames($content);
                break;

            case self::PLAYGAMES_ID:
                return $this->parsePlayGames($content);
                break;

            case self::NEXTGAME_ID:
                return $this->parseNextGame($content);
                break;

            case self::GAMEBUY_ID:
                return $this->parseGameBuy($content);
                break;

            case self::PLAYO_ID:
                return $this->parsePlayo($content);
                break;

            case self::GAME93W_ID:
                $price = $this->parseGame93W($content);
                break;
        }
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
    private function parsePlayo($content)
    {
        $matches = array();
        $this->dom->load($content, true, true);
        
        foreach($this->dom->find('span.price') as $span)
        {
            $val = str_replace(' ', '', $span->outertext);
            preg_match('/\d+/', $val, $matches);
        }

        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return false;
        }

        return $matches[0];
    }


    /**
     * Извлекает цены с GameBuy
     * @param $linkItem Элемент ссылки
     * @return 
     */
    private function parseGameBuy($content)
    {
        $matches = array();
        $this->dom->load($content, true, true);
        
        foreach($this->dom->find('div.panel-col-last span.uc-price') as $span)
        {
            $val = str_replace(' ', '', $span->outertext);
            preg_match('/\d+/', $val, $matches);
            break;
        }

        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return false;
        }
        
        return $matches[0];
    }


    /**
     * Извлекает цены с NextGame
     * @param $linkItem Элемент ссылки
     * @return 
     */
    private function parseNextGame($content)
    {
        $matches = array();
        $this->dom->load($content, true, true);
        
        foreach($this->dom->find('span[id="price"]') as $span)
        {
            $val = str_replace(' ', '', $span->innertext);
            preg_match('/\d+/', $val, $matches);
        }

        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return false;
        }
        
        return $matches[0];
    }


    /**
     * Извлекает цены с PlayGames
     * @param $linkItem Элемент ссылки
     * @return 
     */
    private function parsePlayGames($content)
    {
        $matches = array();
        $this->dom->load($content, true, true);
        
        foreach($this->dom->find('div.add2cart span.price') as $span)
        {
            $val = str_replace(' ', '', $span->innertext);
            preg_match('/\d+/', $val, $matches);
        }

        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return false;
        }
        
        return $matches[0];
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
     * Извлекает цены с GZOnline
     * @param $linkItem Элемент ссылки
     * @return 
     */
    private function parseGZOnline($content)
    {
        $matches = array();
        $this->dom->load($content, true, true);
        
        foreach($this->dom->find('div.list_price') as $span)
        {
            preg_match('/\d+[\s]{0,1}\d*/', $span->outertext, $matches);
        }

        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return false;
        }
        
        return str_replace(" ", "", $matches[0]);
    }
    
    
    /**
     * Извлекает цены с GamePark
     * @param $linkItem Элемент ссылки
     * @return 
     */
    private function parseGamePark($content)
    {
        $matches = array();
        $this->dom->load($content, true, true);
        
        foreach($this->dom->find('div#eprice') as $span)
        {
            preg_match('/(\d)+/', $span->outertext, $matches);
            break;
        }

        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return false;
        }

        return $matches[0];
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
    private function parseLozman($content)
    {
        $matches = array();
        $this->dom->load($content, true, true);
        
        foreach($this->dom->find('div.not_order_price') as $span)
        {
            $outerText = str_replace(" ", "", $span->outertext);
            preg_match('/(\d)+/', $outerText, $matches);
            break;
        }

        foreach($this->dom->find('div.price') as $span)
        {
            $outerText = str_replace(" ", "", $span->outertext);
            preg_match('/(\d)+/', $outerText, $matches);
            break;
        }

        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return false;
        }

        return $matches[0];
    }


    /**
     * Извлекает цены с GameRay
     * @param $linkItem Элемент ссылки
     * @return 
     */
    private function parseGameRay($content)
    {
        $matches = array();
        $this->dom->load($content, true, true);
        
        foreach($this->dom->find('span[itemprop="price"]') as $span)
        {
            preg_match('/(\d)+/', $span->outertext, $matches);
        }

        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return false;
        }

        return $matches[0];
    }


    /**
     * Извлекает цены с Steampay
     * @param $linkItem Элемент ссылки
     * @return 
     */
    private function parseSteampay($content)
    {
        $matches = array();
        $this->dom->load($content, true, true);
        
        foreach($this->dom->find('span.price') as $span)
        {
            preg_match('/\d+[\s]{0,1}\d*/', $span->outertext, $matches);
        }

        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return false;
        }

        return $matches[0];
    }
    
    
    /**
     * <p>Извлекает цены с SteamBuy</p>
     * @param $linkItem Элемент ссылки
     * @return 
     */
    private function parseSteamBuy($content)
    {
        $matches = array();
        $this->dom->load($content, true, true);

        foreach($this->dom->find('span.tovar-price') as $span)
        {
            preg_match('/(\d)+/', $span->outertext, $matches);
        }

        // цена не найдена
        if(empty($matches) || $matches == null)
        {
            return false;
        }

        return $matches[0];
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
}	

