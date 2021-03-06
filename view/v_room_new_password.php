<section class="main room">
    <div class="container">
        
        <? include "h_menu.php"; ?>
        
        <div class="main_container_wrapper">
            <div class="content">
                <div class="wrapper">
                    <div class="form">
                        <? if($error != '') : ?>
                            <span class="error"><?= $error ?></span>
                        <? else : ?>
                            <form method="post" 
                                  action="index.php?c=room&act=saveNewPassword">
                                <div class="item">
                                    <span class="input_label">
                                        Новый пароль
                                    </span>
                                    <input type="password" name="au_password" class="require">
                                    <span class="input_label">
                                        Подтверждение пароля
                                    </span>
                                    <input type="password" name="au_confirm" class="require">
                                </div>

                                <div class="spacer_30"></div>

                                <input type="hidden" name="token" value="<?=$_GET['token']?>">
                                <input type="hidden" name="email" value="<?=$_GET['email']?>">
                                <input type="submit" class="form_btn" value="Сохранить">
                            </form>
                        <? endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>