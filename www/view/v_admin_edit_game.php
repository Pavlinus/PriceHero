<section class="main">
    <div class="container">
        <div class="main_container_wrapper">

            <div class="side_left">
                <? require_once "v_admin_nav_menu.php"; ?>
            </div>

            <div class="content">
                <div class="wrapper">

                    <form action="" method="post" class="form add_game"
                          enctype="multipart/form-data">

                        <div class="item">
                            <span class="input_label">
                                Название
                            </span>
                            <input type="text" name="name" 
                                   value="<?=$gameData['gameName']?>">
                        </div>

                        <div class="item">
                            <span class="input_label">
                                Жанр
                            </span>
                            <select name="genre">
                                <? foreach($genres as $genre) : ?>
                                    <? if($genre['genre_id'] == $gameData['genreData']['genre_id']) : ?>
                                        <option value="<?=$genre['genre_id']?>" selected="selected">
                                            <?=$genre['name']?>
                                        </option>
                                    <? else : ?>
                                        <option value="<?=$genre['genre_id']?>">
                                            <?=$genre['name']?>
                                        </option>
                                    <? endif; ?>
                                <? endforeach; ?>
                            </select>
                        </div>

                        <div class="item">
                            <span class="input_label">
                                Ссылка
                            </span>

                            <div id="link_list">
                                <div class="link_item">
                                    <span class='rm_link'>X</span>
                                    <input type="text" name="link">
                                    <select name="service">
                                        <? foreach($sites as $site) : ?>
                                            <option value="<?=$site['site_id']?>">
                                                <?=$site['name']?>
                                            </option>
                                         <? endforeach; ?>
                                    </select>
                                    <select name="platform">
                                        <option value="1">PC</option>
                                        <option value="2">PS3</option>
                                        <option value="3">PS4</option>
                                        <option value="4">XBox One</option>
                                        <option value="5">XBox 360</option>
                                    </select>
                                </div>
                            </div>

                            <button id="add_link_btn" class="form_btn add">Добавить ссылку</button>
                        </div>

                        <div class="item">
                            <span class="input_label optional">
                                Картинка
                            </span>
                            <input type="file" name="image">
                        </div>

                        <input type="submit" class="form_btn" value="Добавить игру">

                    </form>

                </div>
            </div>
        </div>
    </div>
</section>

<script src="js/add_game.js"></script>