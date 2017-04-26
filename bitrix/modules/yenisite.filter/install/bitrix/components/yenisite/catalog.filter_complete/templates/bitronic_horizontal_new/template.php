<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
$arrN = array();
$arrNN = array();
foreach($arResult["ITEMS"] as $arItem)
{
	if($arItem["PROPERTY_TYPE"] == "N")
		$arrN[] = $arItem;
	else
		$arrNN[] = $arItem;
}
$arResult["ITEMS"] = array_merge($arrN, $arrNN);
?>

<form action="#">
	<div class="ye_filter" id="ys_filter_bitronic">
	<?if(count($arrN) > 0):?>
	<?foreach($arrN as $arItem):?>

	<? if($arItem['VALUES']['MIN'] == $arItem['VALUES']['MAX']) continue; ?>
	<?		
		preg_match_all("| name=\"(.*)\" |U", $arItem["INPUT"], $names);
		$min_name = $names[1][0];
		$max_name = $names[1][1];
	?>
							<script type="text/javascript">
							$(function(){
							
								$("#<?=$arItem['CODE']?>-min").attr('value', <?=$arItem['VALUES']['MIN_VALUE']?$arItem['VALUES']['MIN_VALUE']:$arItem['VALUES']['MIN'];?>);
								$("#<?=$arItem['CODE']?>-max").attr('value', <?=$arItem['VALUES']['MAX_VALUE']?$arItem['VALUES']['MAX_VALUE']:$arItem['VALUES']['MAX'];?>);
							
								$("#limit-<?=$arItem['CODE']?>").slider({
									range: true,
									min: <?=$arItem['VALUES']['MIN']?$arItem['VALUES']['MIN']:0;?>,
									max:  <?=$arItem['VALUES']['MAX']?$arItem['VALUES']['MAX']:10000;?>,
									<?if ($arItem["IS_FLOAT"] == 1):?>
										step: 0.1,
									<?endif;?>
									values: [ <?=$arItem['VALUES']['MIN_VALUE']?$arItem['VALUES']['MIN_VALUE']:$arItem['VALUES']['MIN'];?>, <?=$arItem['VALUES']['MAX_VALUE']?$arItem['VALUES']['MAX_VALUE']:$arItem['VALUES']['MAX'];?> ],
									slide: function(event, ui) {
										$("#<?=$arItem['CODE']?>-min").attr('value', ui.values[0]);
										$("#<?=$arItem['CODE']?>-max").attr('value', ui.values[1]);
										$("#left-<?=$arItem['CODE']?>").html(ui.values[0]);
										$("#right-<?=$arItem['CODE']?>").html(ui.values[1]);
									}
								});
							});
							</script>
		<div class="ye_price">
			<div class="ye_price_left">
				<span class="ye_tit"><?=$arItem["NAME"]?>:</span>
				<span><?=GetMessage('OT');?></span> <input value="<?=$arItem['VALUES']['MIN_VALUE']?$arItem['VALUES']['MIN_VALUE']:$arItem['VALUES']['MIN'];?>" name="<?=$min_name?>" type="text" class="txt" id="<?=$arItem[CODE]?>-min" />
			</div>
			<div class="ye_price_right"><span><?=GetMessage('DO');?></span> <input name="<?=$max_name?>" value="<?=$arItem['VALUES']['MAX_VALUE']?$arItem['VALUES']['MAX_VALUE']:$arItem['VALUES']['MAX'];?>" type="text" class="txt" id="<?=$arItem['CODE']?>-max" /></div>
			<div class="ye_price_content">
				<span class="ye_from"><?=$arItem['VALUES']['MIN']?$arItem['VALUES']['MIN']:0;?></span>
				<span class="ye_to"><?=$arItem['VALUES']['MAX']?$arItem['VALUES']['MAX']:10000;?></span>
				<div class="ye_limit" id="limit-<?=$arItem['CODE']?>"></div>
			</div>

		
		</div>
	<?endforeach?>		
	<?endif?>
	
	<?if(count($arrNN) > 0):?>
		<div class="ye_option">
		<?foreach($arrNN as $arItem):?>
			<?if(!array_key_exists("HIDDEN", $arItem)):?>
			
				<?
				if (strpos($arItem["INPUT"], "arrFilter_op[CML2_LINK]") > 0)
					continue;
				?>
				
					<div class="ya_pole">
						<div class="ye_head"><span class="ye_tit"><?=$arItem["NAME"]?>:</span>  <span class="ye_q"><!--<a href="#">?</a>--></span></div>
						<div class="ya_col">
							<?
							if(substr_count($arItem["INPUT"], 'type="checkbox"') > 0)
								$arItem["INPUT"] = str_replace('type="checkbox"', ' class="checkbox1" type="checkbox" ', $arItem["INPUT"]);
								
								$arItem["INPUT"] = str_replace('</label>', '</label><br />', $arItem["INPUT"]);

							if(substr_count($arItem["INPUT"], '<select') > 0)
								if(substr_count($arItem["INPUT"], '<select class="') > 0)
									$arItem["INPUT"] = preg_replace('/<select class=\"/', '<select class="toggle-list ', $arItem["INPUT"]);
								else
									$arItem["INPUT"] = str_replace('<select', '<select class="toggle-list" ', $arItem["INPUT"]);
								$arItem["INPUT"] = str_replace('multiple', '', $arItem["INPUT"]);
								$arItem["INPUT"] = preg_replace('/size=\"\d\"/', '', $arItem["INPUT"]);
							?>
							<?=$arItem["INPUT"]?>
						</div><!--.col-->
						
					</div>
			<?endif?>
		<?endforeach?>
			<div class="ye_clear"></div>
		</div>
	<?endif?>	
		<div class="ye_clear"></div>
		
		<input id='sf' type="hidden" name="sf" value="" />
	<input id='df' type="hidden" name="df" value="" />
	<div class='inputs-filter inputs-filter-button' style="text-align: right;">
	
	<?if($arParams['FILTER_BY_QUANTITY'] == 'Y'):?>
		<label class="ys_quantity_chbx"><input class="checkbox1" type="checkbox" name="f_Quantity" value="Y"<?if($arResult['CHECKED_QUANTITY'] == 'Y'):?> checked="checked"<?endif;?>><?=GetMessage('YS_FILTER_QCHBX');?></label>
	<?endif;?>
	
	<button class="button" onclick="  $('#sf').attr('value', 'Y'); $('#sf').attr('name', 'set_filter');"><span><?=GetMessage("IBLOCK_SET_FILTER")?></span></button> <button class="button" onclick=" $('#df').attr('value', 'Y'); $('#df').attr('name', 'del_filter'); "><span><?=GetMessage("IBLOCK_DEL_FILTER")?></span></button>

	</div>
	</div>
	<?foreach($arResult["ITEMS"] as $arItem):
			if(array_key_exists("HIDDEN", $arItem)):
				echo $arItem["INPUT"];
			endif;
	endforeach;?>
</form>