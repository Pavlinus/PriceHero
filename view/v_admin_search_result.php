<div class="result_wrapper">
    <div class="row">
        <span class="caption col_3">Название</span>
        <span class="caption col_3">Платформа</span>
        <span class="caption col_3">Действие</span>
    </div>

    <? foreach($gameList as $game) : ?>

        <div class="row">
            <span class="name col_3">
                <?=$game['name']?>
            </span>
            <span class="platform col_3">

            </span>
            <span class="col_3">
                <a href="index.php?c=suckmyadmincock&act=editGame&id=<?=$game['game_id']?>" 
                   class="action">
                    Изменить
                </a>
            </span>
        </div>

    <? endforeach; ?>

</div>