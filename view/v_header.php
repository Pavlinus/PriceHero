<!DOCTYPE html>
<html lang="ru-ru" dir="ltr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href="../images/favicon.ico" rel="shortcut icon" 
            type="image/vnd.microsoft.icon" />
        <meta name="description" content="Выбираем лучшие цены среди множества интернет магазинов, таких как Steam, SteamBuy, SteamPay и других. Большой выбор дешевых игр на ПК и приставки PS (PlayStation) и XBox." />
        <meta name="keywords" content="дешевые игры, купить игры дешево, каталог дешевых игр, купить игры для ps дешево, купить игры для xbox дешево, дешевые игры для приставок" />

        <!-- Google+ meta -->
        <meta property = "og:image" content = "http://game2buy.ru/images/logo_small.png">
        <meta property = "og:title" content = "Game2Buy | Дешевые игры для приставок и ПК">
        <meta property = "og:description" content = "Выбираем лучшие цены среди множества интернет магазинов. Большой выбор дешевых игр на ПК и приставки PS (PlayStation) и XBox.">

        <!-- Twitter meta -->
        <meta name = "twitter:title" content = "Game2Buy | Дешевые игры для приставок и ПК">
        <meta name = "twitter:site" content = "Game2Buy.ru">
        <meta name = "twitter:description" content = "Выбираем лучшие цены среди множества интернет магазинов. Большой выбор дешевых игр на ПК и приставки PS (PlayStation) и XBox.">
        <meta name = "twitter:domain" content = "game2buy.ru">
        <meta name = "twitter:image:src" content = "http://game2buy.ru/images/logo_small.png">

        <!-- Facebook meta -->
        <meta property = "og:url" content = "http://game2buy.ru">
        <meta property = "og:see_also" content = "http://game2buy.ru">
        <meta property = "og:site_name" content = "Game2Buy.ru">

        <meta name = "robots" content = "all">

        <meta name="verify-admitad" content="d9b214c029" />

        <link rel="stylesheet/less" href="css/style.less" type="text/css" />
        <link rel="stylesheet" href="css/component.css" type="text/css" />
        <title>Game2Buy | Дешевые игры для приставок и ПК</title>
        <script src="js/jquery.min.js" type="text/javascript"></script>
        <script src="js/jquery-ui.min.js" type="text/javascript"></script>
        <script src="js/less.js" type="text/javascript"></script>
    </head>
    <body>

    <header>
        <div class="container">
            <div class="header_wrapper">
                <div class="logo">
                    <div>
                        <a href="/" title="Каталог дешевых игр">
                            <span>Game</span>
                            <span class="two_rot">2</span>
                            <span>Buy</span>
                        </a>
                    </div>
                </div>
                <div class="search">
                    <div class="inner_search">
                            <input type="text" placeholder="Найти">
                            <!--div class="search_reset"></div-->
                            <div class="search_button" id="search"></div>
                            
                            <form method="post" action="index.php" id="h_form">
                                <input type="hidden" name="search" value="">
                            </form>
                    </div>
                </div>
                <div class="room_btn">
                    <? if(!isset($_COOKIE['user_id'])) : ?>
                        <a href="index.php?c=room" class="form_btn enter">Войти</a>
                    <? else : ?>
                        <a href="index.php?c=room&act=logout" class="form_btn out">Выйти</a>
                    <? endif; ?>
                </div>
            </div>
        </div>
    </header>
        
    <div class="info_window">
        <span>Сообщение</span>
    </div>
        
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function (d, w, c) {
            (w[c] = w[c] || []).push(function() {
                try {
                    w.yaCounter39946855 = new Ya.Metrika({
                        id:39946855,
                        clickmap:true,
                        trackLinks:true,
                        accurateTrackBounce:true
                    });
                } catch(e) { }
            });

            var n = d.getElementsByTagName("script")[0],
                s = d.createElement("script"),
                f = function () { n.parentNode.insertBefore(s, n); };
            s.type = "text/javascript";
            s.async = true;
            s.src = "https://mc.yandex.ru/metrika/watch.js";

            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else { f(); }
        })(document, window, "yandex_metrika_callbacks");
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/39946855" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
