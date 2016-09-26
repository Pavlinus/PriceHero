<? if(empty($gamesList)) : ?>
<div class="products">
    <p>По Вашему запросу ничего не найдено</p>
</div>
<? else : ?>

    <div class="products">

        <? foreach($gamesList as $game) : ?>
        <a target="_blank" href="<?= $game['link'] ?>" class="productItem">
            <div class="item" id="<?=$game['game_id']?>">
                <div class="wrapper">
                    <img src="<?= $game['image'] ?>" alt="">
                    <div class="product_details">
                        <!--span class="product_name"><?= $game['game'] ?></span-->
                        <span class="product_price">
                            <span class="platform">
                                <?=$game['platform']?>
                            </span>
                            <span><?= $game['price'] ?> руб.</span>
                        </span>
                    </div>
                    <? if($game['tracker_id'] != '') : ?>
                        <div class="tracker active" title="Не отслеживать игру"></div>
                    <? else : ?>
                        <div class="tracker" title="Отслеживать игру"></div>
                    <? endif; ?>
                    
                    <div class="more">
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>
                <div class="hidden" name="platform_id"><?=$game['platform_id']?></div>
                <div class="hidden" name="site_id"><?=$game['site_id']?></div>
            </div>
        </a>
        <? endforeach; ?>

    </div>

<? endif; ?>