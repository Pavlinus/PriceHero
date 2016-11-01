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
                                                <span class="platform">
                                                    <?=$game['platform']?>
                                                </span>
                                                <span>
                                                    <?=$game['price']?> руб.
                                                </span>
                                            </span>
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

        <div class="main_container_wrapper shadow">
        	<div class="content stock">
        		<h2>Акции</h2>
	        	<div class="stock_list">
	        		<div class="item">
	        			<a href="http://steambuy.com/click.php?t=bn&i=14" target="_blank">
	        				<img src="../images/stock/batman_stock.jpg">
	        			</a>
	        		</div>
	        		<div class="item">
	        			<a href="http://steambuy.com/click.php?t=bn&i=7" target="_blank">
	        				<img src="../images/stock/siege_stock.jpg">
	        			</a>
	        		</div>
	        		<div class="item">
	        			<a href="http://steambuy.com/click.php?t=bn&i=12" target="_blank">
	        				<img src="../images/stock/division_stock.jpg">
	        			</a>
	        		</div>
	        		<div class="item">
	        			<a href="http://steambuy.com/click.php?t=bn&i=9" target="_blank">
	        				<img src="../images/stock/farcry_stock.jpg">
	        			</a>
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

<script src="/js/similar_offer.js"></script>
<script src="/js/index.js"></script>