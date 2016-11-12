<section class="slider">
    <div class="container">
        <article id="cc-slider">
          <input checked="checked" name="cc-slider" id="slide1" type="radio">
          <input name="cc-slider" id="slide2" type="radio">
          <input name="cc-slider" id="slide3" type="radio">
          <input name="cc-slider" id="slide4" type="radio">
          <input name="cc-slider" id="slide5" type="radio">
          <input name="cc-slider" id="slide6" type="radio">

          <div id="cc-slides">
            <div id="overflow">
              <div class="inner">

                <article>
                    <div class="cctooltip">
                        <h3>Quantum Break</h3>
                        <h3 class="price">595 руб</h3>
                    </div>
                    <a href="index.php?c=index&act=game&name=quantumbreak">
                        <img src="../pages/quantumbreak/quantumbreak.jpg"> 
                    </a>
                </article>

                <article>
                    <div class="cctooltip">
                        <h3>Stellaris</h3>
                        <h3 class="price">599 руб</h3>
                    </div>
                    <a href="index.php?c=index&act=game&name=stellaris">
                        <img src="../pages/stellaris/stellaris.jpg"> 
                    </a>
                </article>

                <article>
                    <div class="cctooltip">
                        <h3>Battlefield 1</h3>
                        <h3 class="price">1 999 руб</h3>
                    </div>
                    <a href="index.php?c=index&act=game&name=battlefield1">
                        <img src="../pages/battlefield1/battlefield1.jpg"> 
                    </a>
                </article>

                <article>
                    <div class="cctooltip">
                        <h3>Dark souls 3</h3>
                        <h3 class="price">1 295 руб</h3>
                    </div>
                    <a href="index.php?c=index&act=game&name=darksouls3">
                        <img src="../pages/darksouls3/darksouls3.jpg"> 
                    </a>
                </article>

                <article>
                    <div class="cctooltip">
                        <h3>DOOM</h3>
                        <h3 class="price">799 руб</h3>
                    </div>
                    <a href="index.php?c=index&act=game&name=doom">
                        <img src="../pages/doom/doom.jpg"> 
                    </a>
                </article>

              </div>
            </div>
          </div>

          <div id="controls">
            <label for="slide1"></label>
            <label for="slide2"></label>
            <label for="slide3"></label>
            <label for="slide4"></label>
            <label for="slide5"></label>
            <label for="slide6"></label>
          </div>

        </article>
    </div>
</section>

<section class="main">
    <div class="container">
        
        <? include "h_menu.php"; ?>
        
        <h1 class="hidden">Game2Buy | Дешевые игры для приставок и ПК</h1>

        <div class="block_body">
            <h2 class="title"><span>///</span> Последние обновления</h2>

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
        </div>

        <div class="block_sep"></div>

        <div class="block_body">
            <h2 class="title"><span>///</span> Акции</h2>

            <div class="main_container_wrapper">
                <div class="content stock">
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
                    <div class="single">
                        <a href="https://ad.admitad.com/g/af8ef42a17d9b214c029e8b31ead25/?ulp=http%3A%2F%2Fwww.gamepark.ru%2Fsearch%2F%3Fbynews%3D242166">
                            <img src="../images/stock/gamepark07112016.png">
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