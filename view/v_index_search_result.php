<? if(empty($gamesList)) : ?>
<div class="products">
    <p>По Вашему запросу ничего не найдено</p>
</div>
<? else : ?>

    <div class="products">

        <? foreach($gamesList as $game) : ?>
        <a target="_blank" href="<?= $game['link'] ?>">
            <div class="item" id="<?=$game['game_id']?>">
                <div class="wrapper">
                    <img src="<?= $game['image'] ?>" alt="">
                    <div class="product_details">
                        <!--span class="product_name"><?= $game['game'] ?></span-->
                        <span class="product_price">
                            <span><?= $game['price'] ?> руб.</span>
                        </span>
                    </div>
                    <? if($game['tracker_id'] != '') : ?>
                        <div class="tracker active" title="Не отслеживать игру"></div>
                    <? else : ?>
                        <div class="tracker" title="Отслеживать игру"></div>
                    <? endif; ?>
                </div>
            </div>
        </a>
        <? endforeach; ?>

    </div>

<? endif; ?>