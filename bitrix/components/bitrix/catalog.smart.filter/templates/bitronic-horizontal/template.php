<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div id="smartfilter">
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<script>
	if (typeof smartFilter != "object") {
		var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>', '<?=($arParams['AJAX_FILTER'] == "Y")? "Y":"N";?>', '<?=SITE_TEMPLATE_PATH."/ajax/catalog_filter.php"?>', '<?=SITE_ID;?>', '<?=$arParams["IBLOCK_ID"];?>');
	}
</script>
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
	<div class="ye_filter" id="ys_filter_bitronic">
<? $bActiveFilters = false;?>
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<?if($arItem["PROPERTY_TYPE"] == "N" || isset($arItem["PRICE"])):?>
				<?if(isset($arItem["VALUES"]["MIN"]["VALUE"]) && isset($arItem["VALUES"]["MAX"]["VALUE"]) &&
					($arItem["VALUES"]["MAX"]["VALUE"] > $arItem["VALUES"]["MIN"]["VALUE"])):?>

					<div class="ye_price">
						<div class="ye_price_left">
							<span class="ye_tit"><?=$arItem["NAME"]?>:<?
							if (!empty($arItem['HINT'])):?><span class="ye_q" title="<?=$arItem['HINT']?>"></span><?endif?></span>
							<span><?=GetMessage('CT_BCSF_FILTER_FROM');?></span>

							<?
							$min = (empty($arItem["VALUES"]["MIN"]["HTML_VALUE"])) ? "" : round($arItem["VALUES"]["MIN"]["HTML_VALUE"]);
							$max = (empty($arItem["VALUES"]["MAX"]["HTML_VALUE"])) ? "" : round($arItem["VALUES"]["MAX"]["HTML_VALUE"]);
							
							if ($min != '' || $max != '') $bActiveFilters = true;

							$minValue = $arItem["VALUES"]["MIN"]["VALUE"];
							$maxValue = $arItem["VALUES"]["MAX"]["VALUE"];
							$halfValue = ($minValue + $maxValue) / 2;
							$quartStep = ($minValue - $halfValue) / -2;
							$octoStep = $quartStep / 2;

							$bFloat = false;
							if (strpos($minValue, '.') !== false) {
								if (!isset($arItem['PRICE'])) $bFloat = true;
								$minValue = round($minValue, 2);
							}
							if (strpos($maxValue, '.') !== false) {
								if (!isset($arItem['PRICE'])) $bFloat = true;
								$maxValue = round($maxValue, 2);
							}
							$precision = $bFloat ? 2 : ($maxValue - $minValue > 8 ? 0 : 2);
							?>

							<input class="min-price txt"
								   type="text"
								   name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
								   id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
								   value="<?echo $min?>"
								   size="5"
								   placeholder="<?=$minValue?>"
								   onkeyup="smartFilter.keyup(this)" />
						</div>

						<div class="ye_price_right">
							<span><?=GetMessage('CT_BCSF_FILTER_TO');?></span>
							<input class="max-price txt"
								   type="text"
								   name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>"
								   id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"
								   value="<?echo $max?>"
								   size="5"
								   placeholder="<?=$maxValue?>"
								   onkeyup="smartFilter.keyup(this)" />
						</div>

						<div class="ye_price_content">
							<?/*<span class="ye_from" id="min-price-<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"></span>
							<span class="ye_to" id="max-price-<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"></span>*/?>
							<div class="ye_limit" id="limit-<?=substr(md5($arItem['CODE']), 0, 4)?>">
								<div class="ye_limit_label">
									<div class="ye_limit_label_ticks"       style="left: 0%">   <span><?=$minValue?></span></div>
									<div class="ye_limit_label_ticks_small" style="left: 12.5%"><span><?=round($minValue+$octoStep,  $precision, PHP_ROUND_HALF_DOWN)?></span></div>
									<div class="ye_limit_label_ticks"       style="left: 25%">  <span><?=round($minValue+$quartStep, $precision, PHP_ROUND_HALF_DOWN)?></span></div>
									<div class="ye_limit_label_ticks_small" style="left: 37.5%"><span><?=round($halfValue-$octoStep, $precision, PHP_ROUND_HALF_DOWN)?></span></div>
									<div class="ye_limit_label_ticks"       style="left: 50%">  <span><?=round($halfValue,           $precision, PHP_ROUND_HALF_DOWN)?></span></div>
									<div class="ye_limit_label_ticks_small" style="left: 62.5%"><span><?=round($halfValue+$octoStep, $precision, PHP_ROUND_HALF_DOWN)?></span></div>
									<div class="ye_limit_label_ticks"       style="left: 75%">  <span><?=round($maxValue-$quartStep, $precision, PHP_ROUND_HALF_DOWN)?></span></div>
									<div class="ye_limit_label_ticks_small" style="left: 87.5%"><span><?=round($maxValue-$octoStep,  $precision, PHP_ROUND_HALF_DOWN)?></span></div>
									<div class="ye_limit_label_ticks"       style="left: 100%"> <span><?=$maxValue?></span></div>
									
												
								</div>
							</div>
						</div>
					</div>

					<script>
						//var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($APPLICATION->GetCurPageParam())?>');
						//$(function() {
						var minprice = <?=$arItem["VALUES"]["MIN"]["VALUE"]?>;
						var maxprice = <?=$arItem["VALUES"]["MAX"]["VALUE"];?>;
						$( "#limit-<?=substr(md5($arItem['CODE']), 0, 4)?>").slider({
							range: true,
							min: minprice,
							max: maxprice,<?
							if ($bFloat):?>

							step: 0.01,<?
							endif?>

							values: [ <?=(empty($arItem["VALUES"]["MIN"]["HTML_VALUE"])) ? $arItem["VALUES"]["MIN"]["VALUE"] : $arItem["VALUES"]["MIN"]["HTML_VALUE"]?>, <?=(empty($arItem["VALUES"]["MAX"]["HTML_VALUE"])) ? $arItem["VALUES"]["MAX"]["VALUE"] : $arItem["VALUES"]["MAX"]["HTML_VALUE"];?> ],
							slide: function( event, ui ) {
								$("#<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>").val(ui.values[0]);
								$("#<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>").val(ui.values[1]);
								smartFilter.keyup(BX("<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"));
							}
						});

						$("#min-price-<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>").text(minprice);
						$("#max-price-<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>").text(maxprice);

						//});
					</script>
				<?endif;?>
			<?endif;?>
		<?endforeach;?>

		<?
		$rowsCount = 0;
		$bExpansion = false;
		?>

		<?if ($arParams['ENABLE_EXPANSION'] == 'N' || $arParams['VISIBLE_ROWS_COUNT'] > 0):?>
		<table width="100%">
			<tr>
				<?$num = 0;?>
				<?foreach($arResult["ITEMS"] as $arItem):?>
				<?if(!empty($arItem["VALUES"]) && $arItem["PROPERTY_TYPE"] != "N" && !isset($arItem["PRICE"])):?>

				<?if($num == 3):?>
				<?
				$rowsCount++;

				if($arParams['ENABLE_EXPANSION'] != 'N'
				&& $rowsCount >= $arParams['VISIBLE_ROWS_COUNT']) {
					break;
				}
				$num = 0;
				?>
			</tr>
			<tr>
				<?endif;?>

				<td width="33%">
					<div class="ye_option">
						<div class="ya_pole">
							<div class="ye_head">
								<span class="ye_tit ys-opt <?if($arParams['EXPAND_PROPS']!="Y"):?>ys-hide<?else:?>ys-show<?endif?>"><?=$arItem["NAME"]?>:</span>
								<?if (!empty($arItem['HINT'])):?><span class="ye_q" title="<?=$arItem['HINT']?>"></span><?endif?>
							</div>

							<div class="ys-opt-labels" style="<?if($arParams['EXPAND_PROPS']!="Y"):?>display: none;<?endif?>">
								<?$countProps = 0; $flag = 0;?>
								<?foreach($arItem["VALUES"] as $val => $ar):?>
							<?if($countProps == 5 && !$flag):?>
							<?$flag = 1;?>
								<div<?if(!$arItem["EXPAND"]):?> style="display: none;"<?endif?>>
									<?endif;?>
									<label for="<?echo $ar["CONTROL_ID"]?>" class="lvl2<?echo $ar["DISABLED"]? ' lvl2_disabled': ''?>"><input
											type="checkbox"
											class="checkbox"
											value="<?echo $ar["HTML_VALUE"]?>"
											name="<?echo $ar["CONTROL_NAME"]?>"
											id="<?echo $ar["CONTROL_ID"]?>"
											<?echo $ar["CHECKED"]? 'checked="checked"': ''?>
											onclick="smartFilter.click(this)"
											/><?echo $ar["VALUE"];?>
									</label>

									<?
									$countProps++;
									if ($ar["CHECKED"]) $bActiveFilters = true;
									?>
									<?endforeach;?>

									<?if($flag):?>
								</div><!-- .ys-props-show / .ys-props-hide -->
								<a class="ys-props-toggler ys-props-hide ys-props-more" href="#"<?if( $arItem["EXPAND"]):?> style="display: none;"<?endif?>><?=GetMessage('MORE')?></a>
								<a class="ys-props-toggler ys-props-show ys-props-less" href="#"<?if(!$arItem["EXPAND"]):?> style="display: none;"<?endif?>><?=GetMessage('LESS')?></a>
							<?endif;?>

								<div class="ya_col">
								</div>

							</div>
						</div>
					</div>
				</td>
				<?$num++;?>
				<?endif;?>
				<?endforeach;?>
			</tr>
		</table>
		<?endif?>

		<?if ($arParams['ENABLE_EXPANSION'] != 'N' && $rowsCount >= $arParams['VISIBLE_ROWS_COUNT']):
			$bExpansion = true;?>
		<div class="ys-expand_table"<?if($arParams['START_EXPANDED'] != 'Y'):?> style="display:none"<?endif?>>
		<table width="100%">
			<tr>
				<?$num = 0;
				$count = 0;
				?>
				<?foreach($arResult["ITEMS"] as $arItem):?>
				<?if(!empty($arItem["VALUES"]) && $arItem["PROPERTY_TYPE"] != "N" && !isset($arItem["PRICE"])):?>

				<?
				if ($count++ < $arParams['VISIBLE_ROWS_COUNT'] * 3) continue;

				if($num == 3):?>
				<?$num = 0;?>
			</tr>
			<tr>
				<?endif;?>

				<td width="33%">
					<div class="ye_option">
						<div class="ya_pole">
							<div class="ye_head">
								<span class="ye_tit ys-opt <?if($arParams['EXPAND_PROPS']!="Y"):?>ys-hide<?else:?>ys-show<?endif?>"><?=$arItem["NAME"]?>:</span>
								<?if (!empty($arItem['HINT'])):?><span class="ye_q" title="<?=$arItem['HINT']?>"></span><?endif?>
							</div>

							<div class="ys-opt-labels" style="<?if($arParams['EXPAND_PROPS']!="Y"):?>display: none;<?endif?>">
								<?$countProps = 0; $flag = 0;?>
								<?foreach($arItem["VALUES"] as $val => $ar):?>
							<?if($countProps == 5 && !$flag):?>
							<?$flag = 1;?>
								<div<?if(!$arItem["EXPAND"]):?> style="display: none;"<?endif?>>
									<?endif;?>
									<label for="<?echo $ar["CONTROL_ID"]?>" class="lvl2<?echo $ar["DISABLED"]? ' lvl2_disabled': ''?>"><input
											type="checkbox"
											class="checkbox"
											value="<?echo $ar["HTML_VALUE"]?>"
											name="<?echo $ar["CONTROL_NAME"]?>"
											id="<?echo $ar["CONTROL_ID"]?>"
											<?echo $ar["CHECKED"]? 'checked="checked"': ''?>
											onclick="smartFilter.click(this)"
											/><?echo $ar["VALUE"];?>
									</label>

									<?
									$countProps++;
									if ($ar["CHECKED"]) $bActiveFilters = true;
									?>
									<?endforeach;?>

									<?if($flag):?>
								</div><!-- .ys-props-show / .ys-props-hide -->
								<a class="ys-props-toggler ys-props-hide ys-props-more" href="#"<?if( $arItem["EXPAND"]):?> style="display: none;"<?endif?>><?=GetMessage('MORE')?></a>
								<a class="ys-props-toggler ys-props-show ys-props-less" href="#"<?if(!$arItem["EXPAND"]):?> style="display: none;"<?endif?>><?=GetMessage('LESS')?></a>
							<?endif;?>

								<div class="ya_col">
								</div>

							</div>
						</div>
					</div>
				</td>
				<?$num++;?>
				<?endif;?>
				<?endforeach;?>
				<?while($num++ < 3):?>
				<td width="33%"> </td>
				<?endwhile?>
			</tr>
		</table>
		</div>
		<?endif?>

		<?if($arParams['SECTION_ID'] == 0 || empty($arParams['SECTION_ID'])):?>
			<input id="ys_sections" type="hidden" value="sections"/>
		<?endif;?>

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
	<?endif;?>
		<?
		global $FILTER_BY_QUANTITY;
		if ($FILTER_BY_QUANTITY == 'Y'):
		?>
			<div class="ya_pole">
				<div class="ye_head">
					<span class="ye_tit ys-opt ys-show"><?=GetMessage('YS_FILTER_QUANTG');?>:</span>
					<?//<div class="ye_q"><!--<a href="#">?</a>--></div>?>
				</div>
				<div class="ys-opt-labels">
					<label class="ys_quantity_chbx"><input class="checkbox" type="checkbox" name="f_Quantity"  id="cf_Quantity" value="Y"<?if($arResult['q_checked'] == 'Y' && !isset($_GET['del_filter'])):?> checked="checked"<?endif;?> onclick="smartFilter.click(this)"><?=GetMessage('YS_FILTER_QCHBX');?></label>
					<div class="ya_col"></div>
				</div>
			</div>
		<?endif;?>
		<div class="ye_clear"></div>
		<?if ($arParams['ENABLE_EXPANSION'] != 'N' && $bExpansion):?>
			<a href="#" class="show_filtr"<?if ($arParams['START_EXPANDED'] == 'Y'):?> style="display:none"<?endif?>><span class="more_txt"><?=GetMessage('YS_FILTER_EXPAND')?></span><span class="show_filtr_multyangle"></span></a>
			<a href="#" class="hide_filtr"<?if ($arParams['START_EXPANDED'] != 'Y'):?> style="display:none"<?endif?>><span class="more_txt"><?=GetMessage('YS_FILTER_REDUCE')?></span><span class="hide_filtr_multyangle"></span></a>
		<?endif?>
		<div class='inputs-filter inputs-filter-button' style="text-align: right;">
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

	</div> <!-- div.ye_filter -->

</form>

	<div class='f_loader'></div>
</div>