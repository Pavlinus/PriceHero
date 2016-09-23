<section class="main room">
    <div class="container">
        <div class="main_container_wrapper">
            <div class="side_left background">
                <nav class="menu">
                    <ul>
                        <li><a href="index.php?c=index">Главная</a></li>
                        <li><a href="index.php?c=room&act=settings">Настройки</a></li>
                        <li><a href="index.php?c=room&act=logout">Выход</a></li>
                    </ul>
                </nav>
            </div>

            <div class="content white">
                <div class="wrapper pad_top_no">
                    <div class="filter">
                        <? foreach($platforms as $pl) : ?>
                            <button class="option platform" 
                                    value="<?=$pl['platform_id']?>">
                                <?=$pl['name']?>
                            </button>
                        <? endforeach; ?>
                    </div>

                    <div class="result_wrapper">
                        
                        <? if(empty($gameList)) : ?>
                            <p class="info_msg">Вы пока не добавили ни одной игры</p>
                        <? endif; ?>
                        
                        <? foreach($gameList as $game) : ?>
                        
                            <div class="row" id="row_<?=$game['game_id']?>">
                                <span class="name col_3">
                                    <a target="_blank" href="<?=$game['link']?>">
                                        <img src="<?=$game['image']?>"/>
                                    </a>
                                </span>
                                <span class="name col_3 game_name">
                                    <a target="_blank" href="<?=$game['link']?>">
                                        <p class="name"><?=$game['game']?></p>
                                    </a>
                                    <p class="platform"><?=$game['platform']?></p>
                                </span>
                                <span class="platform col_3 product_price">
                                    <span>
                                        <?=$game['price']?> руб.
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
                    
                </div>
            </div>
        </div>
    </div>
</section>

<script src="js/room.js"></script>