<section class="main room">
    <div class="container">
        <div class="main_container_wrapper">
            <div class="side_left">
                <nav class="menu">
                    <ul>
                        <li><a href="index.php?c=index">Главная</a></li>
                        <li><a href="index.php?c=room&act=settings">Настройки</a></li>
                        <li><a href="index.php?c=room&act=logout">Выход</a></li>
                    </ul>
                </nav>
            </div>

            <div class="content white">
                <div class="wrapper white_back">
                    <div class="filter">
                        <? foreach($platforms as $pl) : ?>
                            <button class="option platform" 
                                    value="<?=$pl['platform_id']?>">
                                <?=$pl['name']?>
                            </button>
                        <? endforeach; ?>
                    </div>

                    <div class="result_wrapper">
                        
                        <? foreach($gameList as $game) : ?>
                        
                            <div class="row">
                                <span class="name col_3">
                                    <a href="<?=$game['link']?>">
                                        <img src="<?=$game['image']?>"/>
                                    </a>
                                </span>
                                <span class="name col_3 game_name">
                                    <a href="<?=$game['link']?>">
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
                                    <a href="index.php?c=admin&act=editGame&id=<?=$game['game_id']?>" 
                                       class="action">
                                        Удалить
                                    </a>
                                </span>
                            </div>
                        
                        <? endforeach; ?>
                        
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</section>

<script src="js/room.js"></script>