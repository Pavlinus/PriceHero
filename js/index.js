$(document).ready(function()
{
   var filterArray = [];
   
   $('.filter button').each(function()
   {
      filterArray[ $(this).attr('value') ] = false;
   });
   
   /**
    * Обработка выбора фильтра
    */
   $('.filter button').click(function()
   {
      var platform_id = $(this).attr('value');
      var activeFilter = [];

      if($(this).hasClass('active'))
      {
          $(this).removeClass('active');
          filterArray[ platform_id ] = false;
      }
      else
      {
          $(this).addClass('active');
          filterArray[ platform_id ] = true;
      }
      
      activeFilter = getActiveFilters(filterArray);
      filter(activeFilter);
   });
   
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
    * @param {int} arFilter Массив ID установленных фильтров
    */
   function filter(arFilter)
   {
       var dataArray = {
         platformId: arFilter  
       };
       
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
});