<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
} ?>
<form>
	<input type="hidden" id="use" value="<?= $arParams['AUTO_SLIDE'] ?>">
	<input type="hidden" id="delay" value="<?= $arParams['DELAY_SLIDE'] ?>">
</form>
<div id="tv">

	<div class="tv_container">

		<div class="tv_wrapper">

			<? foreach ($arResult["ITEMS"] as $cell => $arElement): ?>
				<? $no_hide_for_order = !($arParams['HIDE_ORDER_PRICE'] == 'Y' && $arElement['PROPERTIES']['FOR_ORDER']['VALUE'] == 'Y'); ?>
				<div class="tv_tab" id="tab-<?= $cell ?>">
					<div class="tv_img">
						<? $path = CFile::GetPath($arElement[PROPERTIES][MORE_PHOTO][VALUE][0]); ?>

						<img alt="<?= $arElement[PROPERTIES][MORE_PHOTO][DESCRIPTION][0] ? $arElement[PROPERTIES][MORE_PHOTO][DESCRIPTION][0] : $arElement[NAME] ?>" src="<?= CResizer2Resize::ResizeGD2($path, $arParams[IMAGE_SET]); ?>">
						<!--<img src="/bitrix/templates/bitronic/static/tmp/tv_tab.jpg" alt="" />-->
					</div>
					<!--.tv_img-->
					<div class="inner">

						<? if ($arParams["SHOW_ELEMENT"] == "Y"): ?>
							<? $res = CIBlock::GetMessages($arElement["IBLOCK_ID"]); ?>
						<? endif ?>
						<div class="title"><?= $res["ELEMENT_NAME"]; ?> <?= $arElement["NAME"] ?></div>
						<p><?= $arElement["PREVIEW_TEXT"] ?></p>

						<? /* OFFERS MIN PRICE START */ ?>
						<? $pr = 0;
						$kr = 0;
						$kk = 0; ?>
						<? if (is_array($arElement["OFFERS"]) && !empty($arElement["OFFERS"]) && count($arElement["OFFERS"]) > 0): ?>
							<? foreach ($arElement["OFFERS"] as $arOffer): ?>


								<?
								$arElement["CATALOG_QUANTITY_TRACE"] = "Y";
								if ($arOffer["CATALOG_QUANTITY_TRACE"] == "N" || ($arOffer["CATALOG_QUANTITY_TRACE"] == "Y" && $arElement["CATALOG_QUANTITY"] > 0)) {
									$arElement["CATALOG_QUANTITY_TRACE"] = "N";
								}
								?>


								<?

								foreach ($arOffer[PRICES] as $k => $price) {
									if ($price[VALUE] < $pr || $pr == 0) {
										$pr = $price[VALUE];
										$kr = $k;
										$arElement[PRICES][$kr] = $arOffer[PRICES][$kr];
									}
								}
								$price = $arOffer[PRICES][$kr][VALUE];
								$disc = 0;
								if ($arOffer[PRICES][$kr][DISCOUNT_VALUE]) {
									$disc = ($arOffer["PRICES"][$kr]["VALUE"] - $arOffer["PRICES"][$kr]["DISCOUNT_VALUE"]) * 100 / $arOffer["PRICES"][$kr]["VALUE"];
								}
								?>
							<? endforeach ?>
						<? endif ?>

						<? $pr = 0;
						$kr = 0;
						$kk = 0; ?>
						<? /* OFFERS MIN PRICE END */ ?>


						<?
						$pr = 0;
						$kr = 0;
						foreach ($arElement[PRICES] as $k => &$price) {
							if (CModule::IncludeModule("catalog")) {
								$price['PRINT_VALUE'] = str_replace(GetMessage('RUB_REPLACE'), '<span class="rubl">' . GetMessage('RUB') . '</span>', $price['PRINT_VALUE']);
								$price['PRINT_DISCOUNT_VALUE'] = str_replace(GetMessage('RUB_REPLACE'), '<span class="rubl">' . GetMessage('RUB') . '</span>', $price['PRINT_DISCOUNT_VALUE']);
							} else {
								$price['PRINT_VALUE'] .= '<span class="rubl">' . GetMessage('RUB') . '</span>';
								$price['PRINT_DISCOUNT_VALUE'] .= '<span class="rubl">' . GetMessage('RUB') . '</span>';
							}
							if ($price[VALUE] < $pr || $pr == 0) {
								$pr = $price[VALUE];
								$ppr = $price[PRINT_VALUE];
								$kr = $k;
							}
						}
						$disc = 0;
						if ($arElement[PRICES][$kr][DISCOUNT_VALUE]) {
							$disc = ($arElement["PRICES"][$kr]["VALUE"] - $arElement["PRICES"][$kr]["DISCOUNT_VALUE"]) * 100 / $arElement["PRICES"][$kr]["VALUE"];
						}
						?>



						<? if ($no_hide_for_order): ?>
							<span class="price"><?= GetMessage('VSEGO_ZA'); ?> <strong><?= $arElement[PRICES][$kr][PRINT_DISCOUNT_VALUE] ? $arElement[PRICES][$kr][PRINT_DISCOUNT_VALUE] : $ppr; ?></strong>
								<? if ($arElement[PRICES][$kr][DISCOUNT_VALUE] && $arElement[PRICES][$kr][DISCOUNT_VALUE] != $arElement[PRICES][$kr][VALUE]): ?>
									<span class="oldprice"><?= $ppr; ?></span>
								<? endif ?>
				</span>
						<? endif; ?>

						<div class="more"><a href="<?= $arElement["DETAIL_PAGE_URL"] ?>"><?= GetMessage('PODROBNEE') ?></a></div>

					</div>
					<!--.inner-->


					<div class="clear"></div>

				</div><!--.tv_tab-->
				<? $ppr = ""; ?>
			<? endforeach ?>

		</div>
		<!--.tv_wrapper-->

	</div>
	<!--.tv_container-->
	<? global $ys_options; ?>
	<? if ($ys_options["menu_filter"] == "top-left" || $arParams["MENU_FILTER"] == "top-left"): ?>
		<ul class="tv_menu tab_nav">
			<? foreach ($arResult["ITEMS"] as $cell => $arElement): ?><? if (strlen($arElement["NAME"]) > 40) {
				$arElement['NAME'] = substr($arElement['NAME'], 0, 40) . '...';
			} ?>
				<li><a href="#tab-<?= $cell ?>"><span><b><?= $arElement["NAME"]; ?></b></span></a></li><? endforeach ?>
		</ul>
	<? endif ?>

	<div class="tv_pager">
		<ul class="tab_nav">
			<?
			foreach ($arResult["ITEMS"] as $cell => $arElement) {
				echo '<li style="background:' . $ys_options["windowcolor"] . ' !important"><a href="#tab-' . $cell . '"></a></li>';
			}
			?>
		</ul>
	</div>
	<!--.tv_pager-->

</div><!--#tv-->
