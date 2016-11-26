<?php


class C_Index extends C_Base
{
    private $mCatalog;
    private $fields;
    private $mTracker;
    private $search;
    private $mCheapHolidays;
    private $mLeaders;
    
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

        if($this->mCheapHolidays == null)
        {
            $this->mCheapHolidays = M_CheapHolidays::Instance();
        }

        if($this->mLeaders == null)
        {
            $this->mLeaders = M_Leaders::Instance();
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
        $search = '';
        
        $gamesList = $this->mCatalog->getLastUpdates();
        $platforms = $this->fields->getFields('t_platform');
        $genres = $this->fields->getFields('t_genre');

        $holidaysId = $this->mCheapHolidays->getGameIdList();
        $holidaysGameList = $this->mCatalog->getGames(
            $holidaysId, 
            'Game.game_id', 
            'AND Price.new_price <> 0', 
            'ORDER BY Price.new_price ASC');

        $leadersId = $this->mLeaders->getGameIdList();
        $leadersGameList = $this->mCatalog->getGames(
            $leadersId, 
            'Game.game_id', 
            'AND Price.new_price <> 0', 
            'ORDER BY Price.new_price ASC');

        /* был запрос поиска с другой страницы */
        if(isset($_POST['search']))
        {
            $search = $_POST['search'];
        }

        $this->content = $this->Template('view/v_index.php', 
                array(
                    'error' => $error,
                    'login' => $login,
                    'err_msg' => $err_msg,
                    'gamesList' => $gamesList,
                    'platforms' => $platforms,
                    'genres' => $genres,
                    'search' => $search,
                    'holidays' => $holidaysGameList,
                    'leaders' => $leadersGameList
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
        $priceFrom = 1;
        $priceTo = 10000;

        if( isset($_POST['price_from']) && 
            is_numeric($_POST['price_from']) && $_POST['price_from'] > 0)
        {
            $priceFrom = htmlspecialchars($_POST['price_from']);
        }

        if( isset($_POST['price_to']) && 
            is_numeric($_POST['price_to']) && $_POST['price_to'] > 0)
        {
            $priceTo = htmlspecialchars($_POST['price_to']);
        }
        
        if(isset($_POST['platformId']) && !empty($_POST['platformId']))
        {
            $arPlatform = $_POST['platformId'];
        }
        
        if(isset($_POST['genreId']) && !empty($_POST['genreId']))
        {
            $and['Genre.genre_id'] = $_POST['genreId'];
        }

        if(isset($_POST['steamId']) && $_POST['steamId'] != '')
        {
            if($_POST['steamId'] == 'keys')
            {
                $and['Link.site_id'] = $this->mCatalog->getSteamSiteIdArray();
            }
        }
        
        $offset = 0;
        $priceList = $this->mCatalog->getPriceUpdates($offset, $priceFrom, $priceTo);

        if(empty($priceList))
        {
            $and['Price.price_id'] = array(0);
        }
        else
        {
            $and['Price.price_id'] = $priceList;
        }
        
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
            
            $arPriceId = $this->mCatalog->getLowestPriceId($gamesId);
            $and = '';
            if(!empty($arPriceId))
            {
                $and['total.price_id'] = $arPriceId;
            }
            
            $arResult = $this->mCatalog->getGames(
                    $gamesId, 
                    'Game.game_id',
                    $and,
                    'ORDER BY game'
            );

            $orderedResult = array();
            $gameName = '';
            $row = 0;
            foreach($arResult as $result)
            {
                if($gameName != $result['game'])
                {
                    $row += 1;
                    $gameName = $result['game'];
                }

                $orderedResult[$row][] = $result;
            }

            echo $this->Template(
                'view/v_index_search_result.php', 
                array(
                    'gamesList' => $orderedResult
                )
            );
            exit();
        }
    }
    
    
    /**
     * Обработка пагинации
     */
    public function action_pageUpdatesAjax()
    {
        $priceFrom = 1;
        $priceTo = 10000;

        if( isset($_POST['price_from']) && 
            is_numeric($_POST['price_from']) && $_POST['price_from'] > 0)
        {
            $priceFrom = htmlspecialchars($_POST['price_from']);
        }

        if( isset($_POST['price_to']) && 
            is_numeric($_POST['price_to']) && $_POST['price_to'] > 0)
        {
            $priceTo = htmlspecialchars($_POST['price_to']);
        }

        $offset = 0;
        if(isset($_POST['offset']))
        {
            $offset = $_POST['offset'];
        }
        
        $arGames = $this->mCatalog->getLastUpdates($offset, $priceFrom, $priceTo);
        
        if(empty($arGames))
        {
            echo '';
            exit();
        }
        
        echo $this->Template(
                'view/v_index_filter_result.php', 
                array(
                    'gamesList' => $arGames
                )
        );
        exit();
    }
    
    
    /**
     * Обработка запроса похожих предложений
     */
    public function action_getSimilarOfferAjax()
    {
        $arResult = $this->mCatalog->getSimilarOffer();
        echo $this->Template(
                'view/v_index_offer.php', 
                array(
                    'offers' => $arResult
                )
        );
        exit();
    }


    /**
     * Загрузка статичного контента об игре
     */
    public function action_game()
    {
        if(isset($_GET['name']))
        {
            $filename = strtolower($_GET['name']);
            $rootPath = '/pages/'.$filename.'/';
            $filepath = $_SERVER['DOCUMENT_ROOT'].$rootPath;

            if(file_exists($filepath.$filename.'.php'))
            {
                ob_start();
                include_once($filepath.$filename.'.php');
                $content = ob_get_clean();

                $this->content = $this->Template(
                    'view/v_game_page.php', 
                    array(
                        'content' => $content,
                        'image' => $rootPath.$filename.'.jpg')
                );
                return;
            }
        }

        //header("Location: index.php");
        //exit();
    }
}
