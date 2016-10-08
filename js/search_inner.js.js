$(document).ready(function()
{   
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
              $('input[name="search"]').val(value);
              $('form[id="h_form"]').submit();
          }
      }
   });
});