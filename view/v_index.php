<section class="main">
    <div class="container">
        
        <? include "h_menu.php"; ?>
        
        <div class="main_container_wrapper">
            <div class="side_left">

                <div class="filter">
                    <h2>Платформа</h2>
                    <? foreach($platforms as $pl) : ?>
                        <button class="option platform" 
                                value="<?=$pl['platform_id']?>">
                            <?=$pl['name']?>
                        </button>
                    <? endforeach; ?>
                    <br><br>
                    <h2>Жанр</h2>
                    <? foreach($genres as $gen) : ?>
                        <button class="option genre" 
                                value="<?=$gen['genre_id']?>">
                            <?=$gen['name']?>
                        </button>
                    <? endforeach; ?>
                </div>
            </div>

            
            <div class="content">
                
                <div class="lastUpdates">
                    <h2>Последние обновления</h2>

                    <div class="products">

                        <? foreach($gamesList as $game) : ?>
                            <a target="_blank" href="<?=$game['link']?>" class="productItem">
                                <div class="item" id="<?=$game['game_id']?>">
                                    <div class="wrapper">
                                        <img src="<?=$game['image']?>" alt="">
                                        <div class="product_details">
                                            <!--span class="product_name"><?=$game['game']?></span-->
                                            <span class="product_price">
                                                <span class="platform">
                                                    <?=$game['platform']?>
                                                </span>
                                                <span>
                                                    <?=$game['price']?> руб.
                                                </span>
                                            </span>
                                        </div>
                                        
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
                                        
                                    </div>
                                    <div class="hidden" name="platform_id"><?=$game['platform_id']?></div>
                                    <div class="hidden" name="site_id"><?=$game['site_id']?></div>
                                </div>
                            </a>
                        <? endforeach; ?>

                    </div>

                    <div class="pagination">
                        <div id="prev_update">&lt;</div>
                        <div id="next_update">&gt;</div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</section>

<? if($search != '') : ?>
    
<script>
    $('.search input').val('<?=$search?>');
</script>

<? endif; ?>

<script src="/js/index.js"></script>