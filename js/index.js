$(document).ready(function()
{
   bindTrackerHandler();
   var filterPlatformArray = [];
   var filterGenreArray = [];
   togglePaginationUpdates();
   
   // Массив фильтра платформ
   $('.filter button.platform').each(function()
   {
      filterPlatformArray[ $(this).attr('value') ] = false;
   });
   
    // Фильтр PC по умолчанию
   setDefaultFilter();
   
   
   // Массив фильтра жанров
   $('.filter button.genre').each(function()
   {
      filterGenreArray[ $(this).attr('value') ] = false;
   });
   
   /**
    * Обработка выбора фильтра
    */
   $('.filter button.platform').on('click', handleFilterBtnClick);
   $('.filter button.genre').on('click', handleFilterBtnClick);
   
   /**
    * Обработчик нажатия кнопки фильтра
    */
   function handleFilterBtnClick()
   {
        var filter_id = $(this).attr('value');
        var activePlatform = [];
        var activeGenre = [];
        var filterName = 'genre';
        var state = false;
        
        if($(this).hasClass('platform'))
        {
            filterName = 'platform';
        }
        
        if($(this).hasClass('active'))
        {
            $(this).removeClass('active');
            state = false;
        }
        else
        {
            $(this).addClass('active');
            state = true;
        }
        
        switch(filterName)
        {
            case 'platform':
                filterPlatformArray[ filter_id ] = state;
                break;
                
           case 'genre':
                filterGenreArray[ filter_id ] = state;
                break;
        }
        
        checkFilterSet();

        activePlatform = getActiveFilters(filterPlatformArray);
        activeGenre = getActiveFilters(filterGenreArray);
        
        filter(activePlatform, activeGenre);
   }
   
   /**
    * Возвращает активные поля фильтра
    * @param {array} arrayFilter массив состояний фильтров
    * @returns {Array} массив активных фильтров
    */
   function getActiveFilters(arrayFilter)
   {
        var active = [];

        for(var i = 0; i < arrayFilter.length; i++)
        {
            if(arrayFilter[i])
            {
                active.push(i);
            }
        }

        return active;
   }
   
   /**
    * Запрос фильтруемых данных
    * @param {int} arPlatformFilter Массив ID установленных фильтра платформ
    * @param {int} arGenreFilter Массив ID установленных фильтра жанров
    */
   function filter(arPlatformFilter, arGenreFilter)
   {
       if(arGenreFilter.length > 0)
       {
            var dataArray = {
                platformId: arPlatformFilter,
                genreId: arGenreFilter
            };  
       }
       else
       {
            var dataArray = {
                platformId: arPlatformFilter
            };
       }
       
       
       $.ajax({
            type: 'POST',
            url: 'index.php?c=index&act=filter',
            data: dataArray,
            cache: false,
            success: function(res)
            {
                unbindTrackerHandler();
                $('.products').remove();
                $('.content .lastUpdates').append(res);
                bindTrackerHandler();
                togglePaginationUpdates();
            }
        });
   }
   
   
   /**
    * Включение и отключение пагинации
    * @returns {undefined}
    */
   function togglePaginationUpdates()
   {
       var products = $('.productItem').length;
       $('div.pagination').fadeOut(100);
       last_updates_page = 0;
       
       if(products === 9)
       {
           $('div.pagination').fadeIn(100);
       }
   }
   
   
   /**
    * Установка фильтра по умолчанию
    * @returns {undefined}
    */
   function setDefaultFilter()
   {
       filterPlatformArray[1] = true;
       $('.filter button.platform[value="1"]').addClass('active');
   }
   
   
   /**
    * Проверяет на отсутствие установленных фильтров по платформе
    * @returns {undefined}
    */
   function checkFilterSet()
   {
        var countActive = 0;
        for(var i = 0; i < filterPlatformArray.length; i++)
        {
            if(filterPlatformArray[i])
            {
                countActive += 1;
            }
        }

        if(countActive === 0)
        {
            setDefaultFilter();
        }
   }
   
   
   $('.search_reset').click(function()
   {
       $('.search input').val('');
   });
   
   /**
    * Запуск поиска при нажатии Enter
    */
   $('div.search input[type="text"]').keypress(function(event)
   {
      if(event.which === 13)
      {
          var value = $(this).val();
          if(value !== '')
          {
              $('#search').click();
          }
      }
   });
   
   /**
     * Обработчик нажатия на кнопку поиска
     */
    $('#search').click(function()
    {
       var searchStr = $('div.search input[type="text"]').val();
       
       var platforms = [];
       $('button.option.platform').each(function()
       {
           var value = $(this).attr('value');
           platforms.push(value);
       });
       
       var dataArray = {
           name: searchStr,
           platformId: platforms
       };
       
       $.ajax({
            type: 'POST',
            url: 'index.php?c=index&act=findGameAjax',
            data: dataArray,
            cache: false,
            success: function(res)
            {
                unbindTrackerHandler();
                $('div.content .products').remove();
                $('div.content .lastUpdates').append(res);
                bindTrackerHandler();
                
                $('.search input').val('');
                
                $('.filter button.platform').each(function()
                {
                   filterPlatformArray[ $(this).attr('value') ] = false;
                   $(this).removeClass('active');
                });
                
                $('.filter button.genre').each(function()
                {
                   filterGenreArray[ $(this).attr('value') ] = false;
                   $(this).removeClass('active');
                });
                
            }
        });
    });
    
    
    function bindTrackerHandler()
    {
        $('div.tracker').each(function()
        {
           $(this).bind('click', trackerClickHandler);
        });
    }
    
    
    function unbindTrackerHandler()
    {
        $('div.tracker').each(function()
        {
           $(this).unbind();
        });
    }
    
    var trackerClicked = 0;
    
    
    /**
     * Обработчик нажатия на трекер
     * @returns {Boolean}
     */
    function trackerClickHandler()
    {
        var gameId = $(this).parents('div.item').attr('id');
        var platformId = $(this).parents('div.item')
                .children('div[name="platform_id"]').text();
        trackerClicked = gameId;
        
        var dataArray = {
                gameId: gameId,
                platformId: platformId
        };
      
        $.ajax({
            type: 'POST',
            url: 'index.php?c=index&act=tracker',
            data: dataArray,
            cache: false,
            success: function(res)
            {
                $tracker = $('div[id="' + trackerClicked + '"] div.tracker');
                
                if(res === '3')
                {
                    playInfoWindow('Для отслеживания игры необходима авторизация');
                }
                else if(res === '2')
                {
                    playInfoWindow('Игра добавлена в игровую комнату');
                    $tracker.addClass('active');
                }
                else if(res === '1')
                {
                    playInfoWindow('Игра удалена из игровой комнаты');
                    $tracker.removeClass('active');
                }
                else
                {
                    playInfoWindow('Не удалось отследить игру');
                }
            }
        });
        
        return false;
    }
    
    
    /* обработка пагинации раздела ПОСЛЕДНИЕ ОБНОВЛЕНИЯ */
    
    /* текущий номер страницы */
    var last_updates_page = 0;
    /* следующая, либо предыдущая страница */
    var page_direction = '';
    
    $('#prev_update').click(function()
    {
        if(last_updates_page === 0)
        {
            return;
        }
        
        page_direction = 'prev';
        updatesPageAjax(last_updates_page - 1);
    });
    
     /* обработка пагинации раздела ПОСЛЕДНИЕ ОБНОВЛЕНИЯ */
    var last_updates_page = 0;
    $('#next_update').click(function()
    {
        page_direction = 'next';
        updatesPageAjax(last_updates_page + 1);
    });
    
    
    /**
     * Получение данных после применения пагинации
     * @param {int} page номер страницы
     * @returns {undefined}
     */
    function updatesPageAjax(page)
    {
        var activePlatform = getActiveFilters(filterPlatformArray);
        var activeGenre = getActiveFilters(filterGenreArray);
        
        var data = { 
            offset: page,
            platformId: activePlatform,
            genreId: activeGenre
        };
        
        $.ajax({
            type: 'POST',
            url: 'index.php?c=index&act=pageUpdatesAjax',
            data: data,
            cache: false,
            success: function(res)
            {
                if(res === '')
                {
                    return;
                }
                
                unbindTrackerHandler();
                $('.products').remove();
                $('.content .lastUpdates').append(res);
                bindTrackerHandler();
                
                if(page_direction === 'prev')
                {
                    last_updates_page -= 1;
                }
                else
                {
                    last_updates_page += 1;
                }
            }
        });
    }
});