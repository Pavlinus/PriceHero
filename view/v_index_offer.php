<div class="similar_offer">
    
    <? if(empty($offers)) : ?>
    
        <p>Нет других предложений</p>
        
    <? else : ?>
    
        <? foreach($offers as $offer) : ?>
            <div>
                <a target="_blank" href="<?=$offer['link']?>">
                    <span class="site_offer"><?=$offer['site']?></span>
                    <span class="offer_price"><?=$offer['price']?>&nbsp;руб.</span>
                </a>
            </div>
        <? endforeach; ?>
        
    <? endif; ?>
    
</div>