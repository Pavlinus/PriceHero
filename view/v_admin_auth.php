<section class="admin_auth">
    <div class="container">
        <div class="content">
            <div class="wrapper">
                <div class="form">
                    <? if($error != '') : ?>
                        <span class="error"><?= $error ?></span>
                    <? endif; ?>
                    <form method="post" 
                          action="index.php?c=admin&act=auth">
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

                        <input type="submit" class="form_btn" value="Войти">
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>