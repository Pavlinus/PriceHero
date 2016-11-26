$(document).ready(function()
{
   bindTrackerHandler();
   bindMoreHandler();
   
   var filterPlatformArray = [];
   var filterGenreArray = [];
   var filterSteam = '';
   var price_from = 1;
   var price_to = 10000;
   
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
   $('.filter button.steam').on('click', handleFilterBtnClick);
   $('input[name="price_from"]').on('change', handlePriceFilterChange);
   $('input[name="price_to"]').on('change', handlePriceFilterChange);

   /**
    * Обработчик изменения поля цены
    */
   function handlePriceFilterChange()
   {
        price_from = parseInt($('input[name="price_from"]').val());
        price_to = parseInt($('input[name="price_to"]').val());

        if(price_from === NaN || price_from <= 0 ||
           price_to === NaN || price_to <= 0)
        {
          console.log('invalid value');
          return false;
        }
        var activePlatform = getActiveFilters(filterPlatformArray);
        var activeGenre = getActiveFilters(filterGenreArray);
        
        filter(activePlatform, activeGenre);
   }

   /**
    * Обработчик нажатия кнопки фильтра
    */
   function handleFilterBtnClick()
   {
        var filter_id = $(this).attr('value');
        var activePlatform = [];
        var activeGenre = [];
        var filterName = 'steam';
        var state = false;
        
        if($(this).hasClass('platform'))
        {
            filterName = 'platform';
        }
        else if($(this).hasClass('genre'))
        {
            filterName = 'genre';
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
              resetFilter();
              $(this).addClass('active');
              filterPlatformArray[ filter_id ] = state;
              break;
                
           case 'genre':
              filterGenreArray[ filter_id ] = state;
              break;

           case 'steam':
              filterSteam = '';
              if(state)
              {
                resetFilter();
                setDefaultFilter();
                $(this).addClass('active');
                filterSteam = filter_id;
              }
              break;
        }
        
        checkFilterSet();

        activePlatform = getActiveFilters(filterPlatformArray);
        activeGenre = getActiveFilters(filterGenreArray);
        
        filter(activePlatform, activeGenre, filterSteam);
   }

   /**
    * Сброс фильтра по платформам и Steam
    */
   function resetFilter()
   {
        $('.filter button.platform').each(function()
        {
          filterPlatformArray[ $(this).attr('value') ] = false;
          $(this).removeClass('active');
        });

        $('.filter button.steam').each(function()
        {
          filterSteam = '';
          $(this).removeClass('active');
        });
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
    * @param {int} steamFilter значение фильтра Steam
    */
   function filter(arPlatformFilter, arGenreFilter, steamFilter)
   {
       var dataArray = {platformId: arPlatformFilter};
       if(arGenreFilter.length > 0)
       {
            dataArray['genreId'] = arGenreFilter;
       }

       if(steamFilter !== '')
       {
            dataArray['steamId'] = steamFilter;
       }

       dataArray['price_from'] = price_from;
       dataArray['price_to'] = price_to;
       
       console.log(dataArray);

       $.ajax({
            type: 'POST',
            url: 'index.php?c=index&act=filter',
            data: dataArray,
            cache: false,
            success: function(res)
            {
                unbindTrackerHandler();
                unbindMoreHandler();
                $('.products').fadeOut(0, function(){$(this).remove();});
                $('.content .lastUpdates').append(res);
                $('.products').fadeIn(200);
                bindTrackerHandler();
                bindMoreHandler();
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
       $('div.pagination').fadeOut(0);
       last_updates_page = 0;
       
       if(products === 12)
       {
           $('div.pagination').fadeIn(0);
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
    $('#search').click(function(event)
    {
       event.preventDefault();
       
       var searchStr = $('div.search input[type="text"]').val();
       
       if(searchStr.length === 0)
       {
           return false;
       }
       
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
                unbindMoreHandler();
                $('.products').fadeOut(0, function(){$(this).remove();});
                $('div.content .lastUpdates').append(res);
                $('.products').fadeIn(300);
                bindTrackerHandler();
                bindMoreHandler();
                
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
                
                //togglePaginationUpdates();
                $('div.pagination').fadeOut(0);
            }
        });
        
        return false;
    });
    
    if($('.search input').val() !== '')
    {
        $('#search').click();
        
    }
    
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
    
    
    /* объект трекера */
    var trackerClicked = null;
    
    /**
     * Обработчик нажатия на трекер
     * @returns {Boolean}
     */
    function trackerClickHandler()
    {
        var gameId = $(this).parents('div.item').attr('id');
        var platformId = $(this).parents('div.item')
                .children('div[name="platform_id"]').text();
        trackerClicked = $(this);
        
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
                if(res === '3')
                {
                    playInfoWindow('Для отслеживания игры необходима авторизация');
                }
                else if(res === '2')
                {
                    playInfoWindow('Игра добавлена в игровую комнату');
                    trackerClicked.addClass('active');
                }
                else if(res === '1')
                {
                    playInfoWindow('Игра удалена из игровой комнаты');
                    trackerClicked.removeClass('active');
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
            genreId: activeGenre,
            price_from: price_from,
            price_to: price_to
        };

        if(filterSteam !== '')
        {
            data['steamId'] = filterSteam;
        }
        
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
                unbindMoreHandler();
                $('.products').fadeOut(0, function(){$(this).remove();});
                $('.content .lastUpdates').append(res);
                $('.products').fadeIn(300);
                bindTrackerHandler();
                bindMoreHandler();
                
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

    var mySwiper = new Swiper ('.swiper-container', 
    {
      pagination: '.swiper-pagination',
      nextButton: '.swiper-button-next',
      prevButton: '.swiper-button-prev',
      paginationClickable: true,
      spaceBetween: 0,
      centeredSlides: true,
      autoplay: 4000,
      autoplayDisableOnInteraction: true
      //mousewheelControl: true
    });

    $('.swiper-container').hover(
      function()
      {
        mySwiper.stopAutoplay();
      },
      function()
      {
        mySwiper.startAutoplay();
      }
    );

    var initialSlidePic = $('.swiper-slide-active img:first-of-type').attr('src');

    mySwiper.on('slideChangeEnd', function()
    {
      initialSlidePic = $('.swiper-slide-active img:first-of-type').attr('src');
    });


    $('div.thumb img').hover(
      function()
      {
        var src = $(this).attr('src');
        $('.swiper-slide-active .slider_left img.visible').fadeOut(0).removeClass('visible');
        $('.swiper-slide-active .slider_left img[src="'+src+'"]').fadeIn(0).addClass('visible');
      },
      function(){}
    );

    $('div.thumb').hover(
      function(){},
      function()
      {
        if(!$('.swiper-slide-active .slider_left img[src="'+initialSlidePic+'"]').hasClass('visible'))
        {
          $('.swiper-slide-active .slider_left img.visible').fadeOut(150).removeClass('visible');
          $('.swiper-slide-active .slider_left img[src="'+initialSlidePic+'"]').fadeIn(150).addClass('visible');
        }
      }
    );
});