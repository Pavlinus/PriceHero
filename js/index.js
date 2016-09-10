$(document).ready(function()
{
   var filterPlatformArray = [];
   var filterGenreArray = [];
   
   // Массив фильтра платформ
   $('.filter button.platform').each(function()
   {
      filterPlatformArray[ $(this).attr('value') ] = false;
   });
   
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
                $('.products').remove();
                $('.content').append(res);
            }
        });
   }
   
   var trackerClicked = 0;
   
   $('div.tracker').click(function()
   {
        var gameId = $(this).parents('div.item').attr('id');
        var platformId = $(this).parents('div.item')
                .children('div[name="platform_id"]').text();
        trackerClicked = gameId;
        
        var dataArray = {
                gameId: gameId,
                platformId: platformId
        };
        
        $(this).animate({scale: '-=0.1'},{duration: 300});
      
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
                    alert('Для отслеживания игры необходима авторизация');
                }
                else if(res === '2')
                {
                    alert('Игра добавлена в игровую комнату');
                    $tracker.addClass('active');
                    
                }
                else if(res === '1')
                {
                    alert('Игра удалена из игровой комнаты');
                    $tracker.removeClass('active');
                }
                else
                {
                    alert('Не удалось отследить игру');
                }
                
                $tracker.animate({scale: '+=0.1'},{duration: 300});
            }
        });
        
        return false;
   });
});