<section class="main room">
    <div class="container">
        
        <? include "h_menu.php"; ?>
        
        <div class="main_container_wrapper">
            
            <div class="content room">
                <div class="wrapper">
                    <div class="form">
                        <? if($error != '') : ?>
                            <span class="error"><?= $error ?></span>
                        <? endif; ?>
                        <form method="post" 
                              action="index.php?c=room&act=auth">
                            <div class="item">
                                <span class="input_label">
                                    Логин
                                </span>
                                <input type="text" name="au_login" class="require">
                            </div>

                            <div class="item">
                                <span class="input_label">
                                    Пароль
                                </span>
                                <input type="password" name="au_password" class="require">
                            </div>

                            <span class="required">* Обязательные поля</span>

                            <div class="spacer_30"></div>

                            <input type="submit" class="form_btn enter" value="Войти">
                            <a href="index.php?c=room&act=registration" class="form_btn register">Регистрация</a>
                            <a href="index.php?c=room&act=restorePassword" class="form_btn register">Восстановить пароль</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="/js/search_inner.js.js"></script>