<? if(empty($gamesList)) : ?>
<div class="products transp">
    <p>По Вашему запросу ничего не найдено</p>
</div>
<? else : ?>


<? foreach($gamesList as $listItem) : ?>
    <div class="products transp">
        <h3><?=$listItem[0]['game']?></h3>
        <? foreach($listItem as $game) : ?>
            <a target="_blank" href="<?= $game['link'] ?>" class="productItem">
                <div class="item" id="<?=$game['game_id']?>">
                    <div class="wrapper">
                        <img src="<?= $game['image'] ?>" alt="">
                        <div class="product_details">
                            <!--span class="product_name"><?= $game['game'] ?></span-->
                            <span class="product_price">
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
                                <span class="platform">
                                    <?=$game['platform']?>
                                </span>
                                <span><?= $game['price'] ?> руб.</span>
                            </span>
                        </div>
                    </div>
                    <div class="hidden" name="platform_id"><?=$game['platform_id']?></div>
                    <div class="hidden" name="site_id"><?=$game['site_id']?></div>
                </div>
            </a>
        <? endforeach; ?>
        <div class="search_sep"></div>
    </div>
<? endforeach; ?>


<? endif; ?>