<section class="main">
    <div class="container">
        <div class="main_container_wrapper">

            <div class="side_left">
                <? require_once "v_admin_nav_menu.php"; ?>
            </div>

            <div class="content">
                <div class="wrapper">

                    <form action="" method="post" class="form add_cheapHolidaysGame">

                        <select multiple class="cheapGamesSelect">
                            <? foreach($gamesList as $game) : ?>
                                <option value="<?=$game['game_id']?>">
                                    <?=$game['name']?>
                                </option>
                            <? endforeach; ?>
                        </select>

                        <input type="submit" class="form_btn add_cheapHolidaysGame" 
                        value="Добавить игру">

                        <div class="cheapGamesList">
                            <? foreach($leaders as $game) : ?>
                                <div class='cheapItem' id='<?=$game['game_id']?>'>
                                    <span class='cheapName'><?=$game['name']?></span>
                                    <span class='cheapDel'>Удалить</span>
                                </div>
                            <? endforeach; ?>
                        </div>

                        <input type="submit" class="form_btn save_cheapHolidaysGame leaders" 
                        value="Сохранить">

                    </form>

                </div>
            </div>
        </div>
    </div>
</section>

<script src="js/panel.js"></script>