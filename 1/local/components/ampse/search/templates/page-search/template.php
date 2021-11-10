<div class="search-section" id="searchSection">

	<a class="continue-shopping" href="#">продолжить шоппинг</a>

	<div class="container-popup">
		<div class="basket__section-field form-field required-field">

			<div class="form-field__wrapper">
				<input class="basket__section-input" type="text" placeholder="Что ищем?" required>
			</div>
			<a class="form-field__cancel d-none" href="">Отмена</a>

			<div class="form-field__load d-none"><img src="<?=SITE_TEMPLATE_PATH?>/src/dist/images/load-circle.svg"></div>

			<div class="form-field__select d-none">
				<div class="form-field__select-wrapper">

					<div class="form-field__select-item select-item">
						<div class="select-item__title">Категории</div>
						<a class="select-item__content" href="javascript:void(0)">
							Ботинки (40)
						</a>
					</div>

				</div>
			</div>

			<div class="form-field__error d-none">Не удалось найти <span></span>. </div>

		</div>
		
		<div class="basket__section-field form-field required-field">
			<div id="retairocketSearch">
				<div data-retailrocket-markup-block="6114d30197a52823682a43c3" data-search-phrase="search_phrase"></div>
				<script>retailrocket.markup.render();</script>
			</div>
		</div>

		<div class="populars">
			<div class="populars__box-link">
				<h2 class="populars__title">Популярное</h2>

				<ul class="populars__list">
				<?
					$i = 0;
					$ibreack = 2;
					foreach($arResult['POPULAR'] as $item) {
						$i++;
						?>
							<li class="populars__item">
								<a href="javascript:void(0)"><?=$item["PHRASE"];?></a>
							</li>
						<?
						if($i === $ibreack) {
							?>
								</ul>
								<ul class="populars__list">
							<?
							$i = 0;
							if($ibreack === 2) {
								$ibreack = 3;
							} else {
								$ibreack = 2;
							}
						}
					}
				?>
				</ul>
			</div>
			<div class="populars__box-mailing">
				<h2 class="populars__title">Не пропустите <span>закрытую распродажу</span> и специальные предложения</h2>
				<form action="#">
					<input type="email" name="" placeholder="Введите ваш e-mail" required>
					<button>Подписаться</button>
				</form>
			</div>
		</div>
	</div>

</div>