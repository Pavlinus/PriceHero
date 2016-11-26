<nav class="menu">
    <ul>
        <? if(isset($_GET['c'])) : ?>
        
            <? if($_GET['c'] == 'index') : ?>
                <li><a href="index.php?c=index" class="active">Главная</a></li>
            <? else : ?>
                <li><a href="index.php?c=index">Главная</a></li>
            <? endif; ?>
        
            <? if($_GET['c'] == 'about') : ?>
                <li><a href="index.php?c=about" class="active">О сервисе</a></li>
            <? else : ?>
                <li><a href="index.php?c=about">О сервисе</a></li>
            <? endif; ?>

            <? if($_GET['c'] == 'room' && $_GET['act'] == 'response') : ?>
                <li><a href="index.php?c=room&act=response" class="active">Отзывы</a></li>
            <? else : ?>
                <li><a href="index.php?c=room&act=response">Отзывы</a></li>
            <? endif; ?>
        
        <? else : ?>
            <li><a href="index.php?c=index" class="active">Главная</a></li>
            <li><a href="index.php?c=about">О сервисе</a></li>
            <li><a href="index.php?c=room&act=response">Отзывы</a></li>
        <? endif; ?>
    </ul>
</nav>

