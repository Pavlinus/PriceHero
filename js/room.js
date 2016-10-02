$(document).ready(function()
{
   bindDelGameHandler();
   bindMoreHandler();
    
   var filterPlatformArray = [];
   
   // Массив фильтра платформ
   $('.filter button.platform').each(function()
   {
      filterPlatformArray[ $(this).attr('value') ] = false;
   });
   
   /**
    * Обработка выбора фильтра
    */
   $('.filter button.platform').on('click', handleFilterBtnClick);
   
   /**
    * Обработчик нажатия кнопки фильтра
    */
   function handleFilterBtnClick()
   {
        var filter_id = $(this).attr('value');
        var activePlatform = [];
        var activeGenre = [];
        var state = false;
        
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
        
        filterPlatformArray[ filter_id ] = state;
        activePlatform = getActiveFilters(filterPlatformArray);

        if(activePlatform.length === 0)
        {
            setDefaultFilterState();
            setFiltersNotActive();
        }
        console.log(activePlatform);
        
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
    */
   function filter(arPlatformFilter)
   {
       var dataArray = {
            platformId: arPlatformFilter
        };
       
       
       $.ajax({
            type: 'POST',
            url: 'index.php?c=room&act=filter',
            data: dataArray,
            cache: false,
            success: function(res)
            {
                unbindDelGameHandler();
                unbindMoreHandler();
                $('.result_wrapper').remove();
                $('section .wrapper').append(res);
                bindMoreHandler();
                bindDelGameHandler();
            }
        });
   }
   
   /**
    * Установка фильтров по умолчанию
    */
   function setDefaultFilterState()
   {
       setFiltersActive();
       var active = getActiveFilters(filterPlatformArray);
       filter(active);
   }
   
   /**
    * Установка фильтров в активный режим
    * @returns {undefined}
    */
   function setFiltersActive()
   {
        $('.filter button.platform').each(function()
        {
           filterPlatformArray[ $(this).attr('value') ] = true;
        });
   }
   
   /**
    * Установка фильтров в активный режим
    * @returns {undefined}
    */
   function setFiltersNotActive()
   {
        $('.filter button.platform').each(function()
        {
           filterPlatformArray[ $(this).attr('value') ] = false;
        });
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
       
       if(searchStr.length === 0)
       {
           return false;
       }
       
       var dataArray = {
           name: searchStr
       };
       
       $.ajax({
            type: 'POST',
            url: 'index.php?c=room&act=findGameAjax',
            data: dataArray,
            cache: false,
            success: function(res)
            {
                unbindDelGameHandler();
                unbindMoreHandler();
                $('.search input').val('');
                $('.result_wrapper').remove();
                $('.wrapper').append(res);
                bindMoreHandler();
                bindDelGameHandler();
                
                $('.filter button.platform').each(function()
                {
                   filterPlatformArray[ $(this).attr('value') ] = false;
                   $(this).removeClass('active');
                });
            }
        });
    });
    
    var deleteObjectId = 0;
    function bindDelGameHandler()
    {
        $('a.delete').each(function()
        {
           $(this).bind('click', delTrackerHandler);
        });
    }
    
    function unbindDelGameHandler()
    {
        $('a.delete').each(function()
        {
           $(this).unbind();
        });
    }
    
    function delTrackerHandler()
    {
        var gameId = $(this).parents('div.row').children('div[name="game_id"]').text();
        var platformId = $(this).parents('div.row').children('div[name="platform_id"]').text();
        var dataArray = {
            gameId: gameId,
            platformId: platformId
        };

        deleteObjectId = $(this).parents('div.row');

        $.ajax({
             type: 'POST',
             url: 'index.php?c=room&act=delete',
             data: dataArray,
             cache: false,
             success: function(res)
             {
                 if(res === '1')
                 {
                     deleteObjectId.remove();
                     playInfoWindow('Игра удалена из игровой комнаты');
                 }
                 else
                 {
                     playInfoWindow('Не удалось удалить игру');
                 }
             }
         });

         return false;
    }
    
    
});