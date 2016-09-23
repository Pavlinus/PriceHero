<div class="result_wrapper">
    
    <? if(empty($gameList)) : ?>
        <p class="info_msg">Вы пока не добавили ни одной игры</p>
    <? endif; ?>

    <? foreach($gameList as $game) : ?>
    
    <div class="row" id="row_<?=$game['game_id']?>">
        <span class="name col_3">
            <a target="_blank" href="<?=$game['link'] ?>">
                <img src="<?= $game['image'] ?>"/>
            </a>
        </span>
        <span class="name col_3 game_name">
            <a target="_blank" href="<?=$game['link'] ?>">
                <p class="name"><?= $game['game'] ?></p>
            </a>
            <p class="platform"><?= $game['platform'] ?></p>
        </span>
        <span class="platform col_3 product_price">
            <span>
                <?=$game['price'] ?> руб.
            </span>
        </span>
        <span class="col_3">
            <a class="action delete">
                Удалить
            </a>
        </span>
        <span class="hidden" id="gameId"><?=$game['game_id']?></span>
        <span class="hidden" id="platformId"><?=$game['platform_id']?></span>
    </div>

    <? endforeach; ?>

</div>