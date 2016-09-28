$(document).ready(function() {
  
  $("form input[type='submit']").click(function() 
  {
    if(hasEmptyFields())
    {
		return false;
    }
  });
  

  function hasEmptyFields()
  {
    var hasEmpty = false;

    $.each($("input.require"), function() 
	{  
	  if($(this).val() === '')
      {
		//alert("here");
        $(this).animate({backgroundColor:'#636363'}, {duration: 200})
          .delay(200).animate({backgroundColor:'#fff'}, {duration: 300});
		  
        hasEmpty = true;
      }
    });

    return hasEmpty;
  }
});