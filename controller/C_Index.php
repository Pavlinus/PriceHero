<?php

class C_Index extends C_Base
{
    private $mCatalog;
    private $fields;
    private $mTracker;
    private $search;
    
    public function __construct()
    {
        if($this->mCatalog == null)
        {
            $this->mCatalog = M_Catalog::Instance();
        }
        
        if($this->fields == null)
        {
            $this->fields = M_Fields::Instance();
        }
        
        if($this->mTracker == null)
        {
            $this->mTracker = M_Tracker::Instance();
        }
        
        if($this->search == null)
        {
            $this->search = M_Search::Instance();
        }
    }
    
    /**
     * Вывод главной страницы
     */
    function action_index()
    {
        $err_msg = '';
        $error = false;
        $login = '';
        
        $gamesList = $this->mCatalog->getLastUpdates();
        $platforms = $this->fields->getFields('t_platform');
        $genres = $this->fields->getFields('t_genre');

        $this->content = $this->Template('view/v_index.php', 
                array(
                    'error' => $error,
                    'login' => $login,
                    'err_msg' => $err_msg,
                    'gamesList' => $gamesList,
                    'platforms' => $platforms,
                    'genres' => $genres
                )
        );
    }
    
    
    /**
     * Фильтрация контента
     */
    function action_filter()
    {
        $platforms = $this->fields->getFields('t_platform');
        $arPlatform = array(1);    // выбираем PC по умолчанию
        $and = array();
        
        if(isset($_POST['platformId']) && !empty($_POST['platformId']))
        {
            $arPlatform = $_POST['platformId'];
        }
        
        if(isset($_POST['genreId']) && !empty($_POST['genreId']))
        {
            $and['Genre.genre_id'] = $_POST['genreId'];
        }
        
        $priceList = $this->mCatalog->getPriceUpdates();
        $and['Price.price_id'] = $priceList;
        
        $gamesList = $this->mCatalog->getGames(
                $arPlatform, 
                'Platform.platform_id',
                $and
        );
        
        echo $this->Template('view/v_index_filter_result.php', 
                array(
                    'gamesList' => $gamesList,
                    'platforms' => $platforms
                )
        );
        exit();
    }
    
    
    /**
     * Обработка трекинга
     */
    public function action_tracker()
    {
        if(isset($_COOKIE['user_id']))
        {
            $res = $this->mTracker->switchTracker();
            
            if($res == M_Tracker::TRACKER_ADD)
            {
                echo M_Tracker::TRACKER_ADD;
                exit();
            }
            elseif($res == M_Tracker::TRACKER_DELETE)
            {
                echo M_Tracker::TRACKER_DELETE;
                exit();
            }
            else
            {
                echo 0;
                exit();
            }
        }
        else
        {
            echo M_Tracker::TRACKER_AUTH;
            exit();
        }
    }
    
    
    /**
     * Обработка поискового запроса
     */
    public function action_findGameAjax()
    {
        if($this->isPost())
        {
            $gamesId = $this->search->searchGame();
            
            if(!$gamesId || empty($gamesId))
            {
                $gamesId = array();
            }
            
            $arResult = $this->mCatalog->getGames(
                    $gamesId, 
                    'Game.game_id'
            );

            echo $this->Template(
                    'view/v_index_search_result.php', 
                    array(
                        'gamesList' => $arResult
                    )
            );
            exit();
        }
    }
}
