/**
 * 
 * Обработка запроса похожих предложений
 * 
 */

function bindMoreHandler()
{
    $('div.more').each(function()
    {
       $(this).bind('click', moreClickHandler);
    });
}


function unbindMoreHandler()
{
    $('div.more').each(function()
    {
       $(this).unbind();
    });
}

var moreObject = null;

/**
 * Обработчик нажатия кнопки `схожие предложения`
 * @returns {Boolean}
 */
function moreClickHandler()
{
    var child = $(this).children('div.similar_offer');

    if($(this).hasClass('active'))
    {
        $(this).removeClass('active');
    }
    else
    {
        $(this).addClass('active');
    }

    if(child.length > 0)
    {
        if(child.css('display') === 'none')
        {
            child.fadeIn(200);
        }
        else
        {
            child.fadeOut(200);
        }
        return false;
    }

    var gameId = $(this).parents('div.item').attr('id');
    var platformId = $(this).parents('div.item')
            .find('div[name="platform_id"]').text();
    var siteId = $(this).parents('div.item')
            .find('div[name="site_id"]').text();
    var price_from = $('input[name="price_from"]').val();
    var steamFilter = '';

    if($('.filter button.steam').hasClass('active'))
    {
        steamFilter = $('.filter button.steam').attr('value');
    }

    moreObject = $(this);

    var data = { 
        game_id: gameId,
        platform_id: platformId,
        site_id: siteId,
        price_from: price_from,
        steam: steamFilter
    };

    $.ajax({
        type: 'POST',
        url: 'index.php?c=index&act=getSimilarOfferAjax',
        data: data,
        cache: false,
        success: function(res)
        {
            moreObject.append(res);
            var sim_offer = $(moreObject).children('div.similar_offer');
            bindSimilarHandler(sim_offer);
        }
    });

    return false;
}

function bindSimilarHandler(obj)
{
    $(obj).bind('click', similarClickHandler);
}

function similarClickHandler(event)
{
    event.stopPropagation();
    return true;
}