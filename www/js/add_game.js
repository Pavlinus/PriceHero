$(document).ready(function() {
    
    var deletedLinks = [];      // ID удаленных ссылок
    
    bindRemoveBtn();

    /**
    * <p>Обработчик кнопки добавить игру.</p>
    * <p>Формируем данные и выполняем AJAX запрос на </p>
    * <p>добавление игры в БД.</p>
    * @param
    * @return
    */
    $('input[type="submit"].add').click(function()
    {

        var name = $('input[name="name"]').val();
        var genre = $('select[name="genre"]').val();
        var linksArr = {};

        $('.link_item').each(function(index)
        {
            var link = $(this).find('input').val();
            var service = $(this).find('select[name="service"]').val();
            var platform = $(this).find('select[name="platform"]').val();

            linksArr[index] = {
                    link: link, 
                    service: service,
                    platform: platform};
        });

        var dataArray = {
            name: name, 
            genre: genre, 
            links: linksArr
        };

        $.ajax({
            type: 'POST',
            url: 'index.php?c=admin&act=addGameAjax',
            data: dataArray,
            cache: false,
            success: function(res)
            {
                alert(res);
            }
        });

        return false;
    });


    /**
    * <p>Обработчик кнопки добавить ссылку.</p>
    * <p>Создает новое поле ввода и выпадающий список.</p>
    * @param
    * @return
    */
    $('#add_link_btn').click(function() 
    {
        var newElement = "<div class='link_item new_link'>";
        newElement += $('.link_item:first-of-type').html();
        newElement += "</div>";

        $('#link_list').append(newElement);

        var bindItem = $('.rm_link:last-of-type');
        bindRemoveBtn(bindItem);

        return false;
    });


    /**
    * <p>Проверяет количество полей ссылок</p>
    * <p>Должно быть не менее одного поля</p>
    * @param
    * @return TRUE если полей больше 1-го, иначе FALSE 
    */
    function isRemovable()
    {
        var count = $('.link_item').length;

        if(count > 1)
        {
            return true;
        }
        else
        {
            alert("Должна быть хотя бы одна ссылка");
            return false;
        }
    }


    /**
    * <p>Подключает обработчик для элемента удаления ссылки</p>
    * 
    * @param item элемент для подключения обработчика. Если null,
    * то добавляется обработчик ко всем элементам класса rm_link
    * 
    * @return
    */
    function bindRemoveBtn(item)
    {
        if(item !== undefined)
        {
            //$(item).bind('click', removeLink);
            $('.rm_link:last-of-type').bind('click', removeLink);
        }
        else
        {
            $('.rm_link').bind('click', removeLink);
        }
    }


    /**
    * <p>Удаляет элементы ссылки</p>
    * 
    * @return
    */
    function removeLink()
    {
        if(isRemovable())
        {
            if($(this).attr('id') !== undefined)
            {
                deletedLinks.push($(this).attr('id'));
            }
            
            $(this).unbind();
            $(this).parent().remove();
        }
    }
	
});