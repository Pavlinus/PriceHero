<section class="main room">
    <div class="container">
        
        <? include "h_menu.php"; ?>
        
        <div class="main_container_wrapper">
            <div class="content response">
                <div class="wrapper">
                    <div class="form">
                        <? if($error != '') : ?>
                            <span class="error"><?= $error ?></span>
                        <? endif; ?>
                        <form method="post" 
                              action="index.php?c=room&act=response">
                              
                            <div class="item">
                                <span class="input_label">
                                    Ваш отзыв
                                </span>
                                <textarea name="response" class="require"></textarea>
                            </div>

                            <div class="item">
                                <span class="input_label optional">
                                    Email
                                </span>
                                <input type="email" name="au_email">
                            </div>

                            <div class="item">
                                <span class="input_label">
                                    Введите код с картинки
                                </span>
                                <div class="captcha">
                                    <img id="norobot" src="../captcha.php" 
                                    onclick="document.getElementById('norobot').src='../captcha.php';">
                                    <input class="input" type="text" name="norobot" class="require"/>
                                </div>
                            </div>

                            <input type="submit" class="form_btn" value="Отправить">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="/js/search_inner.js.js"></script>