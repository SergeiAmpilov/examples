<div class="header-mobile__search">

	<form class="header-mobile__search-form">
		<div class="header-mobile__search-bar">
			<button class="header-mobile__search-submit" type="submit">
				<img src="<?= SITE_TEMPLATE_PATH ?>/src/dist/images/mobile-search.svg">
			</button>
			<input class="header-mobile__search-input" type="text" placeholder="Поиск">
			<button class="header-mobile__search-reset" type="reset">
				<img src="<?= SITE_TEMPLATE_PATH ?>/src/dist/images/mobile-close.svg">
			</button>
		</div>
		<a class="header-mobile__search-cancel d-none">Отмена</a>
	</form>

	<div class="header-mobile__search-content d-none">

		<div class="header-mobile__search-populars d-none">
			<h2 class="title">Популярное</h2>
			<ul class="populars__list">
				<?
				$i = 0;
				$ibreack = 2;
				foreach ($arResult['POPULAR'] as $item) {
				$i++;
				?>
				<li class="populars__item">
					<a href="javascript:void(0);"><?= $item["PHRASE"]; ?></a>
				</li>
				<?
				if ($i === $ibreack) {
				?>
			</ul>
			<ul class="populars__list">
				<?
				$i = 0;
				if ($ibreack === 2) {
					$ibreack = 3;
				} else {
					$ibreack = 2;
				}
				}
				}
				?>
			</ul>
		</div>

		<div class="header-mobile__search-result">
			<div class="error">Не удалось найти <span></span>.</div>
			<div class="done" id="header-search-result"></div>
		</div>

	</div>
</div>