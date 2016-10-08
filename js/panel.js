$(document).ready(function() {
    
    var deletedLinks = [];      // ID удаленных ссылок
    
    bindDelGameHandler();
    bindRemoveBtn();

    /**
    * <p>Обработчик кнопки добавить игру.</p>
    * <p>Формируем данные и выполняем AJAX запрос на </p>
    * <p>добавление игры в БД.</p>
    * @param
    * @return
    */
    $('input[type="submit"].add_game').click(function(event)
    {
        event.preventDefault();
        
        var name = $('input[name="name"]').val();
        var genre = $('select[name="genre"]').val();
        var fileElement = document.getElementById('game_pic');
        var gameImage = fileElement.files[0];
        var formData = new FormData();
        var uploadPath = '/upload/images/';
        var linksArr = {};
        
        if(gameImage !== undefined)
        {
            if(gameImage.type.match('image/*'))
            {
                formData.append('gameImage', gameImage);
            }
        }

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
            links: linksArr,
            image: uploadPath + gameImage.name
        };

        $.ajax({
            type: 'POST',
            url: 'index.php?c=suckmyadmincock&act=addGameAjax',
            data: dataArray,
            cache: false,
            success: function(res)
            {
                playInfoWindow(res);
            }
        });
        
        $.ajax({
            type: 'POST',
            url: 'index.php?c=suckmyadmincock&act=uploadImageAjax',
            data: formData,
            processData: false,
            contentType: false
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
            playInfoWindow("Должна быть хотя бы одна ссылка");
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
            if($(this).parent().attr('id') !== undefined)
            {
                deletedLinks.push($(this).parent().attr('id'));
            }
            
            $(this).unbind();
            $(this).parent().remove();
        }
    }
    
    /**
    * <p>Обработчик кнопки сохранить игру.</p>
    * <p>Формируем данные и выполняем AJAX запрос на </p>
    * <p>сохранение игры.</p>
    */
    $('input[type="submit"].save').click(function(event)
    {
        event.preventDefault();
        
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
        var fileElement = document.getElementById('game_pic');
        var gameImage = fileElement.files[0];
        var formData = new FormData();
        var uploadPath = '/upload/images/';
        var newImage = null;
        
        if(gameImage !== undefined)
        {
            if(gameImage.type.match('image/*'))
            {
                formData.append('gameImage', gameImage);
                newImage = uploadPath + gameImage.name;
            }
        }
        
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
            linksDelete: deletedLinks,
            links: newLinksArr,
            image: newImage
        };

        $.ajax({
            type: 'POST',
            url: 'index.php?c=suckmyadmincock&act=editGameAjax',
            data: dataArray,
            cache: false,
            success: function(res)
            {
                playInfoWindow(res);
            }
        });
        
        $.ajax({
            type: 'POST',
            url: 'index.php?c=suckmyadmincock&act=uploadImageAjax',
            data: formData,
            processData: false,
            contentType: false
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
            url: 'index.php?c=suckmyadmincock&act=findGameAjax',
            data: dataArray,
            cache: false,
            success: function(res)
            {
                $('div.wrapper .result_wrapper').remove();
                $('div.wrapper').append(res);
            }
        });
    });
    
    
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
            url: 'index.php?c=suckmyadmincock&act=filter',
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
   
    function bindDelGameHandler()
    {
        $('a.delGame').each(function()
        {
           $(this).bind('click', delGameHandler);
        });
    }
    
    function unbindDelGameHandler()
    {
        $('a.delGame').each(function()
        {
           $(this).unbind();
        });
    }
    
    var delGameId = 0;
    /**
     * Обработчик нажатия кнопки удаления игры
     * @returns {Boolean}
     */
    function delGameHandler()
    {
        if(!confirm("Вы действительно хотите удалить игру?"))
        {
            return false;
        }
        
        var gameId = $(this).attr('id');
        var dataArray = {
            gameId: gameId
        };
        delGameId = gameId;
        
        $.ajax({
            type: 'POST',
            url: 'index.php?c=suckmyadmincock&act=removeGame',
            data: dataArray,
            cache: false,
            success: function(res)
            {
                $('a[id="'+delGameId+'"]').parents('div.row').remove();
                
                if(res === '1')
                {
                    playInfoWindow('Игра успешно удалена');
                }
                else
                {
                    playInfoWindow('Не удалось удалить игру');
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
        var data = { offset: page };
        
        $.ajax({
            type: 'POST',
            url: 'index.php?c=suckmyadmincock&act=pageGamesListAjax',
            data: data,
            cache: false,
            success: function(res)
            {
                if(res === '')
                {
                    return;
                }
                
                unbindTrackerHandler();
                $('.products').remove();
                $('.content .lastUpdates').append(res);
                bindTrackerHandler();
                
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
});