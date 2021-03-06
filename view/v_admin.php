﻿<section class="main">
    <div class="container">
        <div class="main_container_wrapper">
            <div class="side_left">
               <? require_once "v_admin_nav_menu.php"; ?>
            </div>

            <div class="content white">
                <div class="wrapper white_back">
                    <div class="filter">
                        <? foreach($platforms as $pl) : ?>
                            <button class="option platform" value="<?= $pl['platform_id'] ?>">
                                <?= $pl['name'] ?>
                            </button>
                        <? endforeach; ?>
                    </div>

                    <!--div class="letters_wrapper">
                        <span class="letter">A</span>
                        <span class="letter">B</span>
                        <span class="letter">C</span>
                        <span class="letter">D</span>
                        <span class="letter">E</span>
                        <span class="letter">F</span>
                        <span class="letter">G</span>
                        <span class="letter">H</span>
                        <span class="letter">I</span>
                        <span class="letter">J</span>
                        <span class="letter">K</span>
                        <span class="letter">L</span>
                        <span class="letter">M</span>
                        <span class="letter">N</span>
                        <span class="letter">O</span>
                        <span class="letter">P</span>
                        <span class="letter">Q</span>
                        <span class="letter">R</span>
                        <span class="letter">S</span>
                        <span class="letter">T</span>
                        <span class="letter">U</span>
                        <span class="letter">V</span>
                        <span class="letter">W</span>
                        <span class="letter">X</span>
                        <span class="letter">Y</span>
                        <span class="letter">Z</span>
                    </div>

                    <div class="letters_wrapper">
                        <span class="letter">А</span>
                        <span class="letter">Б</span>
                        <span class="letter">В</span>
                        <span class="letter">Г</span>
                        <span class="letter">Д</span>
                        <span class="letter">Е</span>
                        <span class="letter">Ж</span>
                        <span class="letter">З</span>
                        <span class="letter">И</span>
                        <span class="letter">К</span>
                        <span class="letter">Л</span>
                        <span class="letter">М</span>
                        <span class="letter">Н</span>
                        <span class="letter">О</span>
                        <span class="letter">П</span>
                        <span class="letter">Р</span>
                        <span class="letter">С</span>
                        <span class="letter">Т</span>
                        <span class="letter">У</span>
                        <span class="letter">Ф</span>
                        <span class="letter">Х</span>
                        <span class="letter">Ц</span>
                        <span class="letter">Ч</span>
                        <span class="letter">Ш</span>
                        <span class="letter">Щ</span>
                        <span class="letter">Э</span>
                        <span class="letter">Ю</span>
                        <span class="letter">Я</span>
                    </div-->

                    <div class="result_wrapper">
                        <div class="row">
                            <span class="caption col_3">Название</span>
                            <span class="caption col_3">Действия</span>
                        </div>
                        
                        <? foreach($gameList as $game) : ?>
                        
                            <div class="row">
                                <span class="name col_3">
                                    <?=$game['name']?>
                                </span>
                                    
                                </span>
                                <span class="col_3">
                                    <a href="index.php?c=suckmyadmincock&act=editGame&id=<?=$game['game_id']?>" 
                                       class="action edit">
                                        Изменить
                                    </a>
                                    <a href="index.php?c=suckmyadmincock&act=removeGame&id=<?=$game['game_id']?>" 
                                       class="action delGame" id="<?=$game['game_id']?>">
                                        Удалить
                                    </a>
                                </span>
                            </div>
                        
                        <? endforeach; ?>
                        
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</section>

<script src="js/panel.js">