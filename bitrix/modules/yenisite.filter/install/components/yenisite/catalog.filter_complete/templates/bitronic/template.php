<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div id="ys_filter_bitronic" class="item_filters">
<?if ($arResult['COUNT'] > 0):?>
	<h2><?=GetMessage('TITLE_FILTER')?></h2>
	<form action="">
		<?foreach($arResult["ITEMS"] as $arItem):
				if(array_key_exists("HIDDEN", $arItem)):
					echo $arItem["INPUT"];
				endif;
		endforeach;?>
		
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<?if(!array_key_exists("HIDDEN", $arItem)):?>
				
				<?if($arItem['PROPERTY_TYPE'] == "N"):?>
					
					<? if($arItem['VALUES']['MIN'] == $arItem['VALUES']['MAX']) continue; ?>
					<h3><?=$arItem["NAME"]?>:</h3>
					<script type="text/javascript">
					$(function() {
						$("#limit-<?=$arItem['CODE']?>").slider({
							range: true,
							min: <?=$arItem['VALUES']['MIN']?$arItem['VALUES']['MIN']:0;?>,
							max:  <?=$arItem['VALUES']['MAX']?$arItem['VALUES']['MAX']:1000;?>,
							
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
					
					<div class="price_slider">
						<div class='limit' id="limit-<?=$arItem['CODE']?>"></div>
						<?$arItem["INPUT"] = str_replace('<input', ' <input class="txt" ', $arItem["INPUT"]);?>
						<div class='inputs-filter'><?=$arItem["INPUT"]?></div>
					</div><!--.price_slider-->
					
				<?else:?>
				
				<?
				if(substr_count($arItem["INPUT"], "select") > 0)
					if(substr_count($arItem["INPUT"], "<option") == 1 || substr_count($arItem["INPUT"], "<option") == 0 || strpos($arItem["INPUT"], "arrFilter_op[CML2_LINK]") > 0) continue;
				?>
				
					<h3><?=$arItem["NAME"]?>:</h3>	
						<?
							if(substr_count($arItem["INPUT"], 'type="checkbox"') > 0)
								$arItem["INPUT"] = str_replace('type="checkbox"', ' class="checkbox" type="checkbox" ', $arItem["INPUT"]);
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
				<?endif?>
				
			<?endif?>
		<?endforeach?>
		
		<?if($arParams['FILTER_BY_QUANTITY'] == 'Y'):?>
			<label class="ys_quantity_chbx"><input class="checkbox1" type="checkbox" name="f_Quantity" value="Y"<?if($arResult['CHECKED_QUANTITY'] == 'Y'):?> checked="checked"<?endif;?>><?=GetMessage('YS_FILTER_QCHBX');?></label>
		<?endif;?>
		
		<input id='sf' type="hidden" name="sf" value="" />
		<input id='df' type="hidden" name="df" value="" />
		<div class='inputs-filter inputs-filter-button'>
			<button class="button" onclick="  $('#sf').attr('value', 'Y'); $('#sf').attr('name', 'set_filter');"><span><?=GetMessage("IBLOCK_SET_FILTER")?></span></button> <button class="button" onclick=" $('#df').attr('value', 'Y'); $('#df').attr('name', 'del_filter'); "><span><?=GetMessage("IBLOCK_DEL_FILTER")?></span></button>
		</div>
	</form>
<?endif;?>
</div><!--.item_filters-->

<script>
$(document).ready(function() {
	$(this).find('#ys_filter_bitronic a.selectBox').click(function() {
		$('.selectBox-dropdown-menu').css('z-index', 1);
	});
	$(this).find('#ys-bitronic-settings a.selectBox').click(function() {
		$('.selectBox-dropdown-menu').css('z-index', 9999);
	});
});
</script>