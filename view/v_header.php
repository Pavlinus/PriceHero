<!DOCTYPE html>
<html lang="ru-ru" dir="ltr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href="favicon.ico" rel="shortcut icon" type="image/vnd.microsoft.icon" />
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <link rel="stylesheet/less" href="css/style.less" type="text/css" />
        <title>Game2Buy (local)</title>
        <script src="js/jquery.min.js" type="text/javascript"></script>
        <script src="js/jquery-ui.min.js" type="text/javascript"></script>
        <script src="js/less.js" type="text/javascript"></script>
    </head>
    <body>

    <header>
        <div class="container">
            <div class="header_wrapper">
                <div class="logo">
                    <span>Game2Buy</span>
                    <img src="../images/robber_d.png">
                </div>
                <div class="search">
                    <input type="text" placeholder="Найти">
                    <!--div class="search_reset"></div-->
                    <div class="search_button" id="search"></div>
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