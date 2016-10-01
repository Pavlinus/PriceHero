<section class="main room">
    <div class="container">
        
        <? include "h_menu.php"; ?>
        
        <div class="main_container_wrapper">
            <div class="content">
                <div class="wrapper">
                    <div class="form">
                        <? if($error != '') : ?>
                            <span class="error"><?= $error ?></span>
                        <? endif; ?>
                        <form method="post" 
                              action="index.php?c=room&act=registration">
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
                            
                            <div class="item">
                                <span class="input_label">
                                    Email
                                </span>
                                <input type="email" name="au_email" class="require">
                            </div>

                            <span class="required">* Обязательные поля</span>

                            <div class="spacer_30"></div>

                            <input type="submit" class="form_btn" value="Сохранить">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>