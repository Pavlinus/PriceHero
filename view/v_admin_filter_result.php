<div class="result_wrapper">
    <div class="row">
        <span class="caption col_3">Название</span>
        <span class="caption col_3">Действия</span>
    </div>

    <? foreach($gameList as $game) : ?>

    <div class="row">
        <span class="name col_3">
            <?= $game['game'] ?>
        </span>

        </span>
        <span class="col_3">
            <a href="index.php?c=suckmyadmincock&act=editGame&id=<?=$game['game_id']?>" 
                class="action edit">
             Изменить
            </a>
            <a href="index.php?c=suckmyadmincock&act=removeGame&id=<?=$game['game_id']?>" 
            class="action delGame">
             Удалить
            </a>
        </span>
    </div>

    <? endforeach; ?>

</div>