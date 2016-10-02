<? if(empty($gameList)) : ?>
<div class="result_wrapper">
    <p>По Вашему запросу ничего не найдено</p>
</div>
<? else : ?>

<div class="result_wrapper">

    <? foreach($gameList as $game) : ?>
    <div class="item" id="<?=$game['game_id']?>">
        <div class="row" id="row_<?= $game['game_id'] ?>">
            <span class="name col_3">
                <a target="_blank" href="<?= $game['link'] ?>">
                    <img src="<?= $game['image'] ?>"/>
                </a>
            </span>
            <span class="name col_3 game_name">
                <a target="_blank" href="<?= $game['link'] ?>">
                    <p class="name"><?= $game['game'] ?></p>
                </a>
                <p class="platform"><?= $game['platform'] ?></p>
            </span>
            <span class="name col_3 sim_offer">
                <div class="more">
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </span>
            <span class="platform col_3 product_price">
                <span>
                    <?= $game['price'] ?> руб.
                </span>
            </span>
            <span class="col_3">
                <a class="action delete">
                    Удалить
                </a>
            </span>
            <div class="hidden" name="game_id"><?=$game['game_id']?></div>
            <div class="hidden" name="platform_id"><?=$game['platform_id']?></div>
            <div class="hidden" name="site_id"><?=$game['site_id']?></div>
        </div>
    </div>
    <? endforeach; ?>

</div>

<? endif; ?>