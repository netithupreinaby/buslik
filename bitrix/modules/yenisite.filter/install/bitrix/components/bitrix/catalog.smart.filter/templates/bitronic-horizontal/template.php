<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" class="smartfilter">
	<?foreach($arResult["HIDDEN"] as $arItem):?>
		<input
			type="hidden"
			name="<?echo $arItem["CONTROL_NAME"]?>"
			id="<?echo $arItem["CONTROL_ID"]?>"
			value="<?echo $arItem["HTML_VALUE"]?>"
			/>
	<?endforeach;?>
	<div class="ye_filter" id="ys_filter_bitronic">

		<?foreach($arResult["ITEMS"] as $arItem):?>
			<?if($arItem["PROPERTY_TYPE"] == "N" || isset($arItem["PRICE"])):?>
				<?if(isset($arItem["VALUES"]["MIN"]["VALUE"]) && isset($arItem["VALUES"]["MAX"]["VALUE"]) &&
					($arItem["VALUES"]["MAX"]["VALUE"] > $arItem["VALUES"]["MIN"]["VALUE"])):?>

					<div class="ye_price">
						<div class="ye_price_left">
							<span class="ye_tit"><?=$arItem["NAME"]?>:</span>
							<span><?=GetMessage('CT_BCSF_FILTER_FROM');?></span>

							<?
								$min = $arItem["VALUES"]["MIN"]["VALUE"];
								$max = $arItem["VALUES"]["MAX"]["VALUE"];

								$tmp = explode('.', $min);
								if (count($tmp)) {
									$min = $tmp[0];
								}
								$tmp = explode('.', $max);
								if (count($tmp)) {
									$max = $tmp[0];
								}
							?>

							<input class="min-price txt"
								   type="text"
								   name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>"
								   id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"
								   value="<?echo $min?>"
								   size="5"
								   placeholder="<? $tmp=explode('.', $arItem["VALUES"]["MIN"]["VALUE"]); echo $tmp[0]; ?>"
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
								   placeholder="<? $tmp=explode('.', $arItem["VALUES"]["MAX"]["VALUE"]); echo $tmp[0]; ?>"
								   onkeyup="smartFilter.keyup(this)" />
						</div>

						<div class="ye_price_content">
							<span class="ye_from" id="min-price-<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>"></span>
							<span class="ye_to" id="max-price-<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>"></span>
							<div class="ye_limit" id="limit-<?=substr(md5($arItem['CODE']), 0, 4)?>"></div>
						</div>
					</div>

					<script>
						var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($APPLICATION->GetCurPageParam())?>');
						//$(function() {
						var minprice = <?=$arItem["VALUES"]["MIN"]["VALUE"]?>;
						var maxprice = <?=$arItem["VALUES"]["MAX"]["VALUE"];?>;
						$( "#limit-<?=substr(md5($arItem['CODE']), 0, 4)?>").slider({
							range: true,
							min: minprice,
							max: maxprice,
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

		<table width="100%">
			<tr>
				<?$num = 0;?>
				<?foreach($arResult["ITEMS"] as $arItem):?>
				<?if(!empty($arItem["VALUES"]) && $arItem["PROPERTY_TYPE"] != "N" && !isset($arItem["PRICE"])):?>

				<?if($num == 3):?>
				<?$num = 0;?>
			</tr>
			<tr>
				<?endif;?>

				<td width="33%">
					<div class="ye_option">
						<div class="ya_pole">
							<div class="ye_head">
								<span class="ye_tit ys-opt ys-hide"><?=$arItem["NAME"]?>:</span>
								<span class="ye_q"><!--<a href="#">?</a>--></span>
							</div>

							<div class="ys-opt-labels" style="display: none;">
								<?$countProps = 0; $flag = 0;?>
								<?foreach($arItem["VALUES"] as $val => $ar):?>
							<?if($countProps == 5 && !$flag):?>
							<?$flag = 1;?>
								<div style="display: none;">
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
									<br />

									<?$countProps++;?>
									<?endforeach;?>

									<?if($flag):?>
								</div><!-- .ys-props-show / .ys-props-hide -->
								<a class="ys-props-toggler ys-props-hide ys-props-more" href="#"><?=GetMessage('MORE')?></a>
								<a class="ys-props-toggler ys-props-show ys-props-less" href="#" style="display: none;"><?=GetMessage('LESS')?></a>
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

		<?if($arParams['SECTION_ID'] == 0 || empty($arParams['SECTION_ID'])):?>
			<input id="ys_sections" type="hidden" value="sections"/>
		<?endif;?>

		<div class="ye_result" id="ye_result" <?if(!isset($arResult["ELEMENT_COUNT"])) echo 'style="display:none"';?>>
			<?echo GetMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">'.intval($arResult["ELEMENT_COUNT"]).'</span>'));?>
			<a href="<?echo $arResult["FILTER_URL"]?>" class="showchild"><?echo GetMessage("CT_BCSF_FILTER_SHOW")?></a>
			<!--<span class="ecke"></span>-->
		</div>

		<div id="ye_idea" style="display:none;">
			<a href="http://idea.1c-bitrix.ru/smart-filter-support-for-work-in-the-root-directory-without-specifying/">
				<?=GetMessage('VOTE_BY_IDEA')?>
			</a>
		</div>

		<div class="ye_clear"></div>

		<div class='inputs-filter inputs-filter-button' style="text-align: right;">
			<?global $FILTER_BY_QUANTITY ;
			if($FILTER_BY_QUANTITY == 'Y'):?>
				<label class="ys_quantity_chbx"><input class="checkbox" type="checkbox" name="f_Quantity"  id="cf_Quantity" value="Y"<?if($arResult['q_checked'] == 'Y'):?> checked="checked"<?endif;?>><?=GetMessage('YS_FILTER_QCHBX');?></label>
			<?endif;?>
			<input class="button" type="submit" id="set_filter" name="set_filter" value="<?=GetMessage("CT_BCSF_SET_FILTER")?>" />
			<input class="button" type="submit" id="del_filter" name="del_filter" value="<?=GetMessage("CT_BCSF_DEL_FILTER")?>" />
		</div>
	</div> <!-- div.ye_filter -->

</form>

<?if($arResult["IS_BITRONIC"] == "N"):?>
	<div class='loader'></div>
<?endif;?>

<script>
	var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>');
</script>