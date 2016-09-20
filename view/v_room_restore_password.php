<section class="main room">
    <div class="container">
        <div class="main_container_wrapper">
            <div class="side_left background">
                <nav class="menu">
                    <ul>
                        <li><a href="index.php?c=index">Главная</a></li>
                        <li><a href="index.php?c=room">Игровая комната</a></li>
                    </ul>
                </nav>
            </div>
            
            <div class="content">
                <div class="wrapper">
                    <p class="center_text">На Ваш email будет выслана ссылка для смены пароля</p>
                    <div class="spacer_30"></div>
                    <div class="form">
                        <? if($error != '') : ?>
                            <span class="error"><?= $error ?></span>
                        <? endif; ?>
                        <form method="post" 
                              action="index.php?c=room&act=restorePassword">
                            <div class="item">
                                <span class="input_label">
                                    Email
                                </span>
                                <input type="text" name="au_email" class="require">
                            </div>

                            <div class="spacer_30"></div>

                            <input type="submit" class="form_btn" value="Восстановить">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>