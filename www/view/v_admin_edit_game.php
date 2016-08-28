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
                        <input type="hidden" name="gameId" value="<?=$gameData['gameId']?>">
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
                                <? foreach($gameData['links'] as $link) : ?>
                                    <div class="link_item" id="<?=$link['link_id']?>">
                                        <span class='rm_link'>X</span>
                                        <input type="text" name="link" value="<?=$link['link']?>">

                                        <select name="service">
                                            <? foreach($sites as $site) : ?>
                                                <? if($site['site_id'] == $link['site_id']) : ?>
                                                    <option value="<?=$site['site_id']?>" selected="selected">
                                                        <?=$site['name']?>
                                                    </option>
                                                <? else : ?>
                                                    <option value="<?=$site['site_id']?>">
                                                        <?=$site['name']?>
                                                    </option>
                                                <? endif; ?>
                                             <? endforeach; ?>
                                        </select>

                                        <select name="platform">
                                            <? foreach($platforms as $pl) : ?>
                                                <? if($pl['platform_id'] == $gameData['linkToPlatform'][ $link['link_id'] ]) : ?>
                                                    <option value="<?=$pl['platform_id']?>" selected="selected">
                                                        <?=$pl['name']?>
                                                    </option>
                                                <? else : ?>
                                                    <option value="<?=$pl['platform_id']?>">
                                                        <?=$pl['name']?>
                                                    </option>
                                                <? endif; ?>
                                             <? endforeach; ?>
                                        </select>

                                    </div>
                                <? endforeach; ?>
                            </div>

                            <button id="add_link_btn" class="form_btn add">Добавить ссылку</button>
                        </div>

                        <div class="item">
                            <span class="input_label optional">
                                Картинка
                            </span>
                            <input type="file" name="image">
                        </div>

                        <input type="submit" class="form_btn save" value="Сохранить">

                    </form>

                </div>
            </div>
        </div>
    </div>
</section>

<script src="js/add_game.js"></script>
<script src="js/edit_game.js"></script>