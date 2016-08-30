$(document).ready(function() 
{

    /**
    * <p>Обработчик кнопки сохранить игру.</p>
    * <p>Формируем данные и выполняем AJAX запрос на </p>
    * <p>сохранение игры.</p>
    */
    $('input[type="submit"].save').click(function()
    {
        save();
        
        return false;
    });
    
    
    /**
     * Сохраняем отредактированные поля (уже существующие в БД)
     * @returns 
     */
    function save()
    {
        var name = $('input[name="name"]').val();
        var genre = $('select[name="genre"]').val();
        var linksArr = {};
        var newLinksArr = {};
        var gameId = $('input[name="gameId"]').val();
        
        $('.link_item').each(function(index)
        {
            var link = $(this).find('input').val();
            var service = $(this).find('select[name="service"]').val();
            var platform = $(this).find('select[name="platform"]').val();
            
            // если не новое поле
            if($(this).attr('id') !== undefined)
            {
                var linkId = $(this).attr('id');

                linksArr[index] = {
                        linkId: linkId,
                        link: link, 
                        service: service,
                        platform: platform
                };
            }
            else
            {
                newLinksArr[index] = {
                        linkId: linkId,
                        link: link, 
                        service: service,
                        platform: platform
                };
            }
        });

        var dataArray = {
            gameId: gameId,
            name: name, 
            genre: genre, 
            linksUpdate: linksArr,
            links: newLinksArr
        };

        $.ajax({
            type: 'POST',
            url: 'index.php?c=admin&act=editGameAjax',
            data: dataArray,
            cache: false,
            success: function(res)
            {
                alert(res);
            }
        });
    }	
});