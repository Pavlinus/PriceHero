<div class="products">

    <? foreach($gamesList as $game) : ?>
    <a href="<?= $game['link'] ?>">
        <div class="item">
            <div class="wrapper">
                <img src="<?= $game['image'] ?>" alt="">
                <div class="product_details">
                    <span class="product_name"><?= $game['game'] ?></span>
                    <span class="product_price"><?= $game['price'] ?> руб.</span>
                </div>
            </div>
        </div>
    </a>
    <? endforeach; ?>

</div>