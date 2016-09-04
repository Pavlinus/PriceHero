<section class="main">
    <div class="container">
        <div class="main_container_wrapper">
            <div class="side_left">
                <nav class="menu">
                    <ul>
                        <li class="head">Меню</li>
                        <li><a href="">Главная</a></li>
                        <li><a href="">Игровая комната</a></li>
                        <li><a href="">О сервисе</a></li>
                    </ul>
                </nav>

                <div class="filter">
                    <h2>Фильтр</h2>
                    <button class="option active">PC</button>
                    <button class="option">PS4</button>
                    <button class="option">XBox One</button>
                    <button class="option">PS3</button>
                    <button class="option">XBox 360</button>
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
                                </div>
                            </div>
                        </a>
                    <? endforeach; ?>
                    
                </div>
            </div>
        </div>
    </div>
</section>