$(document).ready(function()
{
   bindDelGameHandler();
    
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
                $('.result_wrapper').remove();
                $('.wrapper.white_back').append(res);
                bindDelGameHandler();
            }
        });
   }
   
   $('.search_reset').click(function()
   {
       $('.search input').val('');
   });
   
   /**
     * Обработчик нажатия на кнопку поиска
     */
    $('#search').click(function()
    {
       var searchStr = $('div.search input[type="text"]').val();
       
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
                $('.search input').val('');
                $('.result_wrapper').remove();
                $('.wrapper.white_back').append(res);
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
        var gameId = $(this).parents('div.row').children('#gameId').text();
        var platformId = $(this).parents('div.row').children('#platformId').text();
        var dataArray = {
            gameId: gameId,
            platformId: platformId
        };

        deleteObjectId = $(this).parents('div.row').attr('id');

        $.ajax({
             type: 'POST',
             url: 'index.php?c=room&act=delete',
             data: dataArray,
             cache: false,
             success: function(res)
             {
                 if(res === '1')
                 {
                     $('div#'+deleteObjectId).remove();
                 }
                 else
                 {
                     alert('Не удалось удалить игру');
                 }
             }
         });

         return false;
    }
});