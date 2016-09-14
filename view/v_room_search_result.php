<? if(empty($gameList)) : ?>
<div class="result_wrapper">
    <p>По Вашему запросу ничего не найдено</p>
</div>
<? else : ?>

<div class="result_wrapper">

    <? foreach($gameList as $game) : ?>

    <div class="row" id="row_<?= $game['game_id'] ?>">
        <span class="name col_3">
            <a href="<?= $game['link'] ?>">
                <img src="<?= $game['image'] ?>"/>
            </a>
        </span>
        <span class="name col_3 game_name">
            <a href="<?= $game['link'] ?>">
                <p class="name"><?= $game['game'] ?></p>
            </a>
            <p class="platform"><?= $game['platform'] ?></p>
        </span>
        <span class="platform col_3 product_price">
            <span>
                <?= $game['price'] ?> руб.
            </span>
        </span>
        <span class="col_3">
            <a href="index.php?c=room&act=delete&gameId=<?= $game['game_id'] ?>&platformId=<?= $game['platform_id'] ?>" 
               class="action" id="delete">
                Удалить
            </a>
        </span>
        <span class="hidden" id="gameId"><?= $game['game_id'] ?></span>
        <span class="hidden" id="platformId"><?= $game['platform_id'] ?></span>
    </div>

    <? endforeach; ?>

</div>

<? endif; ?>