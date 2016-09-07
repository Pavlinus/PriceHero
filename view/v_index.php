<section class="main">
    <div class="container">
        <div class="main_container_wrapper">
            <div class="side_left">
                <nav class="menu">
                    <ul>
                        <li class="head">Меню</li>
                        <li><a href="index.php">Главная</a></li>
                        <li><a href="index.php?c=room">Игровая комната</a></li>
                        <li><a href="index.php?c=about">О сервисе</a></li>
                    </ul>
                </nav>

                <div class="filter">
                    <h2>Фильтр</h2>
                    <? foreach($platforms as $pl) : ?>
                        <button class="option platform" 
                                value="<?=$pl['platform_id']?>">
                            <?=$pl['name']?>
                        </button>
                    <? endforeach; ?>
                    <br><br>
                    <? foreach($genres as $gen) : ?>
                        <button class="option genre" 
                                value="<?=$gen['genre_id']?>">
                            <?=$gen['name']?>
                        </button>
                    <? endforeach; ?>
                </div>
            </div>

            <div class="content">
                <h2>Последние обновления</h2>

                <div class="products">
                    
                    <? foreach($gamesList as $game) : ?>
                        <a href="<?=$game['link']?>">
                            <div class="item">
                                <div class="wrapper">
                                    <img src="<?=$game['image']?>" alt="">
                                    <div class="product_details">
                                        <span class="product_name"><?=$game['game']?></span>
                                        <span class="product_price"><?=$game['price']?> руб.</span>
                                    </div>
                                    <div class="tracker" title="Отслеживать игру"></div>
                                </div>
                            </div>
                        </a>
                    <? endforeach; ?>
                    
                </div>
                
            </div>
        </div>
    </div>
</section>

<script src="/js/index.js"></script>