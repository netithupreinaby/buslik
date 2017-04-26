<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<pre><?// print_r($arResult);?></pre>

<div id="ys_filter_bitronic" class="item_filters">
	<h2><?=GetMessage('TITLE_FILTER')?></h2>
	<form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" class="smartfilter">
			<?foreach($arResult["HIDDEN"] as $arItem):
				if($arItem["CONTROL_NAME"] == "f_Quantity" || strpos($arItem["HTML_VALUE"], 'bxrand')!== FALSE) 
					continue;
			?>
			<input
				type="hidden"
				name="<?echo $arItem["CONTROL_NAME"]?>"
				id="<?echo $arItem["CONTROL_ID"]?>"
				value="<?echo $arItem["HTML_VALUE"]?>"
			/>
		<?endforeach;?>

		<?
		$bActiveFilters = false;
		$bExpansion = false;
		$visibleCount = 0;

		foreach($arResult["ITEMS"] as $arItem):?>
			<?if(!empty($arItem["VALUES"]) && $arItem["PROPERTY_TYPE"] != "N" && !isset($arItem["PRICE"])):?>

				<?if ($arParams['ENABLE_EXPANSION'] != 'N') {
					$visibleCount++;
					if ($visibleCount > $arParams['VISIBLE_PROPS_COUNT'] && !$bExpansion) {
						$bExpansion = true;
						echo '<div class="ys-expand_opt"',
							($arParams['START_EXPANDED'] != 'Y' ? ' style="display:none"' : ''),
							'>';
					}
				}?>

				<h3 class="ys-opt <?if($arParams['EXPAND_PROPS']!="Y"):?>ys-hide<?else:?>ys-show<?endif?>"><a><?=$arItem["NAME"]?>:</a>
					<?if (!empty($arItem['HINT'])):?><span class="ye_q" title="<?=$arItem['HINT']?>"></span><?endif?>
				</h3>
				<div class="ys-opt-labels" style="<?if($arParams['EXPAND_PROPS']!="Y"):?>display: none;<?endif?>">

				<?$countProps = 0; $flag = 0;?>
				<?foreach($arItem["VALUES"] as $val => $ar):?>
					<?if($countProps == 5 && !$flag):?>
						<?$flag = 1;?>
						<div<?if(!$arItem["EXPAND"]):?> style="display: none;"<?endif?>>
					<?endif;?>

					<label for="<?echo $ar["CONTROL_ID"]?>" class="checkbox lvl2<?echo $ar["DISABLED"]? ' lvl2_disabled': ''?>">
						<input
						type="checkbox"
						class="checkbox"
						value="<?echo $ar["HTML_VALUE"]?>"
						name="<?echo $ar["CONTROL_NAME"]?>"
						id="<?echo $ar["CONTROL_ID"]?>"
						<?echo $ar["CHECKED"] ? 'checked="checked"' : ''?>
						onclick="smartFilter.click(this)"
						/><?echo $ar["VALUE"];?>
					</label>
				
					<?
					$countProps++;
					if($ar["CHECKED"]) $bActiveFilters = true;
					?>
				<?endforeach;?>

					<?if($flag):?>
						</div><!-- .ys-props-show / .ys-props-hide -->
						<a class="ys-props-toggler ys-props-hide ys-props-more" href="#"<?if( $arItem["EXPAND"]):?> style="display: none;"<?endif?>><?=GetMessage('MORE')?></a>
						<a class="ys-props-toggler ys-props-show ys-props-less" href="#"<?if(!$arItem["EXPAND"]):?> style="display: none;"<?endif?>><?=GetMessage('LESS')?></a>
					<?endif;?>

				</div><!-- .ys-opt .ys-show / .ys-hide -->

			<?endif;?>
		<?endforeach;?>
		<?if ($bExpansion):?>
		</div>
		<?endif?>



		<?foreach($arResult["ITEMS"] as $arItem):?>
			<?if($arItem["PROPERTY_TYPE"] == "N" && !empty($arItem["VALUES"]["MIN"]["VALUE"]) &&
				!empty($arItem["VALUES"]["MAX"]["VALUE"]) && !isset($arItem["PRICE"])):?>

				<h3 class="ys-opt <?if($arParams['EXPAND_PROPS']!="Y"):?>ys-hide<?else:?>ys-show<?endif?>"><a><?=$arItem["NAME"]?>:</a>
					<?if (!empty($arItem['HINT'])):?><span class="ye_q" title="<?=$arItem['HINT']?>"></span><?endif?>
				</h3>
				<div class="price_slider" style="<?if($arParams['EXPAND_PROPS']!="Y"):?>display: none;<?endif?>">

					<?
					$min = (empty($arItem["VALUES"]["MIN"]["HTML_VALUE"])) ? "" : $arItem["VALUES"]["MIN"]["HTML_VALUE"];
					$max = (empty($arItem["VALUES"]["MAX"]["HTML_VALUE"])) ? "" : $arItem["VALUES"]["MAX"]["HTML_VALUE"];

					if ($min != "" || $max != "") $bActiveFilters = true;

					$minValue = $arItem["VALUES"]["MIN"]["VALUE"];
					$maxValue = $arItem["VALUES"]["MAX"]["VALUE"];
					$halfValue = ($minValue + $maxValue) / 2;

					$bFloat = false;
					if (strpos($minValue, '.') !== false) {
						$bFloat = true;
						$minValue = round($minValue, 2);
					}
					if (strpos($maxValue, '.') !== false) {
						$bFloat = true;
						$maxValue = round($maxValue, 2);
					}
					$precision = $bFloat ? 2 : ($maxValue - $minValue > 4 ? 0 : 2);
					?>

					<div class='limit' id="limit-<?=substr(md5($arItem['CODE']), 0, 4)?>">
						<div class="ye_limit_label">
							<div class="ye_limit_label_ticks" style="left: 0%"><span><?=$minValue?></span></div>
							<div class="ye_limit_label_ticks_small" style="left: 25%"><span><?=round(($minValue+$halfValue)/2, $precision, PHP_ROUND_HALF_DOWN)?></span></div>
							<div class="ye_limit_label_ticks" style="left: 50%"><span><?=round($halfValue, $precision, PHP_ROUND_HALF_DOWN)?></span></div>
							<div class="ye_limit_label_ticks_small" style="left: 75%"><span><?=round(($halfValue+$maxValue)/2, $precision, PHP_ROUND_HALF_DOWN)?></span></div>
							<div class="ye_limit_label_ticks" style="left: 100%"><span><?=$maxValue?></span></div>
						</div>
					</div>
					<?=GetMessage('CT_BCSF_FILTER_FROM')?>

					<input
						class="min-price txt"
						type="text"
						name="<?echo $arItem['VALUES']['MIN']['CONTROL_NAME']?>"
						id="<?echo $arItem['VALUES']['MIN']['CONTROL_ID']?>"
						value="<?=$min?>"
						size="3"
						placeholder="<?=$minValue?>"
						onkeyup="smartFilter.keyup(this)"
					/>
					<?=GetMessage('CT_BCSF_FILTER_TO')?>
					<input
						class="max-price txt"
						type="text"
						name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
						id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
						value="<?=$max?>"
						size="3"
						placeholder="<?=$maxValue?>"
						onkeyup="smartFilter.keyup(this)"
					/>
					<div class="slider-range" id="slider-<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"></div>
				</div><!--.price_slider-->
				
				<?if ($arItem["VALUES"]["MIN"]["VALUE"] >= 0 && $arItem["VALUES"]["MAX"]["VALUE"] > 0 && $arItem["VALUES"]["MIN"]["VALUE"] < $arItem["VALUES"]["MAX"]["VALUE"]):?>	
					<script>
						var minprice2 = <?=$arItem["VALUES"]["MIN"]["VALUE"]?>;
						var maxprice2 = <?=$arItem["VALUES"]["MAX"]["VALUE"]?>;
						$( "#limit-<?=substr(md5($arItem['CODE']), 0, 4)?>" ).slider({
							range: true,
							min: minprice2,
							max: maxprice2,<?

							if ($bFloat):?>

							step: 0.01,<?

							endif?>

							values: [ <?=(empty($arItem["VALUES"]["MIN"]["HTML_VALUE"])) ? $arItem["VALUES"]["MIN"]["VALUE"] : $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>, <?=(empty($arItem["VALUES"]["MAX"]["HTML_VALUE"])) ? $arItem["VALUES"]["MAX"]["VALUE"] : $arItem["VALUES"]["MAX"]["HTML_VALUE"]?> ],
							slide: function( event, ui ) {
								$("#<?echo $arItem['VALUES']['MIN']['CONTROL_ID']?>").val(ui.values[0]);
								$("#<?echo $arItem['VALUES']['MAX']['CONTROL_ID']?>").val(ui.values[1]);
								smartFilter.keyup(BX("<?echo $arItem['VALUES']['MIN']['CONTROL_ID']?>"));
							}
						});
						
					</script>
				<?endif?>
			<?endif; // if($arItem["PROPERTY_TYPE"] == "N" || isset($arItem["PRICE"]))?>
		<?endforeach;?>

		<?foreach($arResult["ITEMS"] as $arItem):?>
			<?if(isset($arItem["PRICE"])
				&& !empty($arItem["VALUES"]["MIN"]["VALUE"])
				&& !empty($arItem["VALUES"]["MAX"]["VALUE"])
				&& $arItem["VALUES"]["MIN"]["VALUE"] != $arItem["VALUES"]["MAX"]["VALUE"]):?>
				
				<h3 class="ys-opt ys-show"><a><?=$arItem["NAME"]?>:</a></h3>
				<div class="price_slider">

					<?
					$min = (empty($arItem["VALUES"]["MIN"]["HTML_VALUE"])) ? "" : round($arItem["VALUES"]["MIN"]["HTML_VALUE"]);
					$max = (empty($arItem["VALUES"]["MAX"]["HTML_VALUE"])) ? "" : round($arItem["VALUES"]["MAX"]["HTML_VALUE"]);

					if ($min != "" || $max != "") $bActiveFilters = true;

					$minValue = round($arItem["VALUES"]["MIN"]["VALUE"]);
					$maxValue = round($arItem["VALUES"]["MAX"]["VALUE"]);

					$halfValue = ($minValue + $maxValue) / 2;
					?>

					<div class='limit' id="limit-<?=substr(md5($arItem['CODE']), 0, 4)?>">
						<div class="ye_limit_label">
							<div class="ye_limit_label_ticks" style="left: 0%"><span><?=$minValue?></span></div>
							<div class="ye_limit_label_ticks_small" style="left: 25%"><span><?=round(($minValue+$halfValue)/2)?></span></div>
							<div class="ye_limit_label_ticks" style="left: 50%"><span><?=round($halfValue)?></span></div>
							<div class="ye_limit_label_ticks_small" style="left: 75%"><span><?=round(($halfValue+$maxValue)/2)?></span></div>
							<div class="ye_limit_label_ticks" style="left: 100%"><span><?=$maxValue?></span></div>
						</div>
					</div>

					<?=GetMessage('CT_BCSF_FILTER_FROM')?>
					<input
						class="min-price txt"
						type="text"
						name="<?echo $arItem['VALUES']['MIN']['CONTROL_NAME']?>"
						id="<?echo $arItem['VALUES']['MIN']['CONTROL_ID']?>"
						value="<?=$min?>"
						size="3"
						placeholder="<?=$minValue?>"
						onkeyup="smartFilter.keyup(this)"
					/>
					<?=GetMessage('CT_BCSF_FILTER_TO')?>
					<input
						class="max-price txt"
						type="text"
						name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
						id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
						value="<?=$max?>"
						size="3"
						placeholder="<?=$maxValue?>"
						onkeyup="smartFilter.keyup(this)"
					/>
					<div class="slider-range" id="slider-<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"></div>				
				</div><!--.price_slider-->
				
				<?if ($arItem["VALUES"]["MIN"]["VALUE"] >= 0 && $arItem["VALUES"]["MAX"]["VALUE"] > 0 && $arItem["VALUES"]["MIN"]["VALUE"] < $arItem["VALUES"]["MAX"]["VALUE"]):?>	
					<script>
						var minprice2 = <?=$arItem["VALUES"]["MIN"]["VALUE"]?>;
						var maxprice2 = <?=$arItem["VALUES"]["MAX"]["VALUE"]?>;
						$( "#limit-<?=substr(md5($arItem['CODE']), 0, 4)?>" ).slider({
							range: true,
							min: minprice2,
							max: maxprice2,
							values: [ <?=(empty($arItem["VALUES"]["MIN"]["HTML_VALUE"])) ? $arItem["VALUES"]["MIN"]["VALUE"] : $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>, <?=(empty($arItem["VALUES"]["MAX"]["HTML_VALUE"])) ? $arItem["VALUES"]["MAX"]["VALUE"] : $arItem["VALUES"]["MAX"]["HTML_VALUE"]?> ],
							slide: function( event, ui ) {
								$("#<?echo $arItem['VALUES']['MIN']['CONTROL_ID']?>").val(ui.values[0]);
								$("#<?echo $arItem['VALUES']['MAX']['CONTROL_ID']?>").val(ui.values[1]);
								smartFilter.keyup(BX("<?echo $arItem['VALUES']['MIN']['CONTROL_ID']?>"));
							}
						});

						// $("#<?echo $arItem['VALUES']['MAX']['CONTROL_ID']?>").val(maxprice2);
						// $("#<?echo $arItem['VALUES']['MIN']['CONTROL_ID']?>").val(minprice2);
						
					</script>
				<?endif?>
			<?endif; // if($arItem["PROPERTY_TYPE"] == "N" || isset($arItem["PRICE"]))?>
		<?endforeach;?>
		
		<?if($arParams['SECTION_ID'] == 0 || empty($arParams['SECTION_ID'])):?>
			<input id="ys_sections" type="hidden" value="sections"/>
		<?endif;?>

		<?
		global $FILTER_BY_QUANTITY;
		if ($FILTER_BY_QUANTITY == 'Y'):
		?>
				<h3 class="ys-opt ys-show"><a><?=GetMessage('YS_FILTER_QUANTG');?>:</a></h3>
				<div class="ys-opt-labels">
					<label class="ys_quantity_chbx"><input class="checkbox" type="checkbox" name="f_Quantity"  id="cf_Quantity" value="Y"<?if($arResult['q_checked'] == 'Y' && !isset($_GET["del_filter"])):?> checked="checked"<?endif;?> onclick="smartFilter.click(this)"><?=GetMessage('YS_FILTER_QCHBX');?></label>
				</div>
		<?endif;?>
		
		<div class='inputs-filter inputs-filter-button'>
			<?if($arParams['AJAX_FILTER'] != "Y"):?>
			<a href="<?=$APPLICATION->GetCurDir()?>" class="del_filter<?if(!$bActiveFilters):?> disabled<?endif?>" id="del_filter">
				<?=GetMessage("CT_BCSF_DEL_FILTER")?>
			</a>
			<button class="button disabled" type="submit" id="set_filter" name="set_filter" value="Y" disabled="disabled">
				<span class="text show"><?=GetMessage("CT_BCSF_SET_FILTER")?></span>
				<span class="notloader"></span>
			</button>
			<?else:?>
			<button class="button<?if(!$bActiveFilters):?> disabled" disabled="disabled<?endif?>" type="submit" id="del_filter" name="del_filter" value="Y">
				<span class="text show"><?=GetMessage("CT_BCSF_DEL_FILTER")?></span>
				<span class="notloader"></span>
			</button>
			<?endif;?>
		</div>

		<div class="ye_result" id="ye_result" <?if(!isset($arResult["ELEMENT_COUNT"])) echo 'style="display:none"';?>>
			<?echo GetMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">'.intval($arResult["ELEMENT_COUNT"]).'</span>'));?>
			<a href="<?echo $arResult["FILTER_URL"]?>" class="showchild"><?echo GetMessage("CT_BCSF_FILTER_SHOW")?></a>
			<!--<span class="ecke"></span>-->
		</div>
		

		

	<?if($USER->IsAdmin()):?>
		<div id="ye_idea" style="display:none;">
			<a href="http://idea.1c-bitrix.ru/smart-filter-support-for-work-in-the-root-directory-without-specifying/">
				<?=GetMessage('VOTE_BY_IDEA')?>
			</a><?=GetMessage('FOR_ADMIN_ONLY')?>
		</div>
	<?endif?>
	<?if ($arParams['ENABLE_EXPANSION'] != 'N' && $bExpansion):?>
			<div class="rectangl-top<?if ($arParams['START_EXPANDED'] == 'Y'):?> ys-hide_filtr<?endif?>"></div>
			<a href="#" class="show_filtr<?if ($arParams['START_EXPANDED'] == 'Y'):?> ys-hide_filtr<?endif?>"><span class="more_txt"><?=GetMessage('YS_FILTER_EXPAND')?></span><span class="show_filtr_multyangle"></span></a>
			
		
			<a href="#" class="hide_filtr<?if ($arParams['START_EXPANDED'] != 'Y'):?> ys-hide_filtr<?endif?>"><span class="more_txt"><?=GetMessage('YS_FILTER_REDUCE')?></span><span class="hide_filtr_multyangle"></span></a>
			<div class="rectangl-bottom<?if ($arParams['START_EXPANDED'] != 'Y'):?> ys-hide_filtr<?endif?>"></div>
	<?endif?>
		<div class="ye_clear"></div>

		
	</form>
</div> <!-- id="ys_filter_bitronic" class="item_filters" -->

	<div class='f_loader'></div>
<script>
	var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>', '<?=($arParams['AJAX_FILTER'] == "Y")? "Y":"N";?>', '<?=SITE_TEMPLATE_PATH."/ajax/catalog_filter.php"?>', '<?=SITE_ID;?>', '<?=$arParams["IBLOCK_ID"];?>');
</script>