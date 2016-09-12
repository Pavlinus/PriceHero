$(document).ready(function()
{
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
                $('.result_wrapper').remove();
                $('.wrapper.white_back').append(res);
            }
        });
   }
   
   
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
            url: 'index.php?c=index&act=findGameAjax',
            data: dataArray,
            cache: false,
            success: function(res)
            {
                $('div.content .products').remove();
                $('div.content').append(res);
            }
        });
    });
    
    
});