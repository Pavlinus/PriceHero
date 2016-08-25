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
							<input type="text" name="name">
						</div>
						
						<div class="item">
							<span class="input_label">
								Жанр
							</span>
							<select name="genre">
								<option value="1">Экшн</option>
								<option value="2">Симулятор</option>
								<option value="3">Стратегия</option>
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
										<option value="1">SteamBuy</option>
										<option value="2">Steam</option>
										<option value="3">ZakaZaka</option>
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