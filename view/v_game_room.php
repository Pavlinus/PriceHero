<section class="main">
    <div class="container">
        <div class="main_container_wrapper">
            <div class="side_left">
                <nav class="menu">
                    <ul>
                        <li class="head">Меню</li>
                        <li><a href="index.php?c=index">Главная</a></li>
                        <li><a href="index.php?c=room&act=settings">Настройки</a></li>
                        <li><a href="index.php?c=room&act=logout">Выход</a></li>
                    </ul>
                </nav>
            </div>

            <div class="content white">
                <div class="wrapper white_back">
                    <div class="filter">
                        <button class="option active">PC</button>
                        <button class="option">PS4</button>
                        <button class="option">XBox One</button>
                        <button class="option">PS3</button>
                        <button class="option">XBox 360</button>
                    </div>

                    <div class="letters_wrapper">
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
                    </div>

                    <div class="search">
                        <input type="text" placeholder="Найти">
                        <div id="reset_search" class="search_reset"></div>
                        <div id="search" class="search_button"></div>
                    </div>

                    <div class="result_wrapper">
                        <div class="row">
                            <span class="caption col_3">Название</span>
                            <span class="caption col_3">Платформа</span>
                            <span class="caption col_3">Действие</span>
                        </div>
                        
                        <? foreach($gameList as $game) : ?>
                        
                            <div class="row">
                                <span class="name col_3">
                                    <?=$game['name']?>
                                </span>
                                <span class="platform col_3">
                                    
                                </span>
                                <span class="col_3">
                                    <a href="index.php?c=admin&act=editGame&id=<?=$game['game_id']?>" 
                                       class="action">
                                        Изменить
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