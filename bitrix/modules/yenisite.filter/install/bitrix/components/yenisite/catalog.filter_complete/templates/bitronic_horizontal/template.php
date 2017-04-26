<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<? if($arParams['INCLUDE_JQUERY'] == 'Y') CJSCore::Init(array("jquery")); ?>




<?$APPLICATION->SetAdditionalCSS("/bitrix/components/yenisite/catalog.filter_complete/templates/bitronic_horizontal/js/jquery-ui-1.7.3.custom.css");?>
<?$APPLICATION->SetAdditionalCSS("/bitrix/components/yenisite/catalog.filter_complete/templates/bitronic_horizontal/js/uniform.default.css");?>
<?$APPLICATION->SetAdditionalCSS("/bitrix/components/yenisite/catalog.filter_complete/templates/bitronic_horizontal/".$arParams["THEME"].".css");?>


<?$GLOBALS["APPLICATION"]->AddHeadScript("/bitrix/components/yenisite/catalog.filter_complete/templates/bitronic_horizontal/js/selectbox.js")?>
<?$GLOBALS["APPLICATION"]->AddHeadScript("/bitrix/components/yenisite/catalog.filter_complete/templates/bitronic_horizontal/js/jquery.uniform.min.js")?>
<?$GLOBALS["APPLICATION"]->AddHeadScript("/bitrix/components/yenisite/catalog.filter_complete/templates/bitronic_horizontal/js/jquery-ui-1.7.3.custom.min.js")?>
<?$GLOBALS["APPLICATION"]->AddHeadScript("/bitrix/components/yenisite/catalog.filter_complete/templates/bitronic_horizontal/js/script.js")?>


	<div  id="ys_filter_bitronic" class="item_filters">
	<div class="item_filters_inner">
			<h2><?=GetMessage('TITLE_FILTER')?></h2>

	<form action="#">
<div class="item_filters_in_form" >	
			<?foreach($arResult["ITEMS"] as $arItem):
					if(array_key_exists("HIDDEN", $arItem)):
						echo $arItem["INPUT"];
					endif;
			endforeach;?>
			
		<?foreach($arResult["ITEMS"] as $arItem):?>
		
					<?if(!array_key_exists("HIDDEN", $arItem)):?>													
						<?if($arItem['PROPERTY_TYPE'] == "N"):?>	
						<? if($arItem[VALUES][MIN] == $arItem[VALUES][MAX]) continue; ?>
						<div class="item_filters_items" >
						<h3><?=$arItem["NAME"]?>:</h3>
						<script type="text/javascript">
						$(function(){
							$("#limit-<?=$arItem[CODE]?>").slider({
								range: true,
								min: <?=$arItem[VALUES][MIN]?$arItem[VALUES][MIN]:0;?>,
								max:  <?=$arItem[VALUES][MAX]?$arItem[VALUES][MAX]:1000;?>,
								values: [ <?=$arItem[VALUES][MIN_VALUE]?$arItem[VALUES][MIN_VALUE]:$arItem[VALUES][MIN];?>, <?=$arItem[VALUES][MAX_VALUE]?$arItem[VALUES][MAX_VALUE]:$arItem[VALUES][MAX];?> ],
								slide: function(event, ui) {
									$("#<?=$arItem[CODE]?>-min").attr('value', ui.values[0]);
									$("#<?=$arItem[CODE]?>-max").attr('value', ui.values[1]);
									$("#left-<?=$arItem[CODE]?>").html(ui.values[0]);
									$("#right-<?=$arItem[CODE]?>").html(ui.values[1]);
								}
							});
							});
						</script>
						
							<div class="price_slider">
								<div class='limit' id="limit-<?=$arItem[CODE]?>"></div>
								<?$arItem["INPUT"] = str_replace('<input', ' <input class="txt" ', $arItem["INPUT"]);?>
								<div class='inputs-filter'><?=$arItem["INPUT"]?></div>
							</div><!--.price_slider-->
							</div>
						<?else:?>
						  <?
								if(substr_count($arItem["INPUT"], "select") > 0)
									if(substr_count($arItem["INPUT"], "<option") == 1 || substr_count($arItem["INPUT"], "<option") == 0) continue;
							?>
							<div class="item_filters_items" >
							<h3><?=$arItem["NAME"]?>:</h3>
							<?
								if(substr_count($arItem["INPUT"], 'type="checkbox"') > 0)
									$arItem["INPUT"] = str_replace('type="checkbox"', ' class="checkbox" type="checkbox" ', $arItem["INPUT"]);
									$arItem["INPUT"] = str_replace('</label>', '</label></br>', $arItem["INPUT"]);

								if(substr_count($arItem["INPUT"], '<select') > 0)
									$arItem["INPUT"] = str_replace('<select', '<select class="toggle-list" ', $arItem["INPUT"]);									
									$arItem["INPUT"] = str_replace('multiple', '', $arItem["INPUT"]);
									$arItem["INPUT"] = str_replace('size', 'rel', $arItem["INPUT"]);
							?>
							<?=$arItem["INPUT"]?>
							</div>
						<?endif?>
						
					<?endif?>
		
		<?endforeach?>
</div>		

<input id='sf' type="hidden" name="sf" value="" />
<input id='df' type="hidden" name="df" value="" />
<div class='inputs-filter' >
<button class="button" onclick="  $('#sf').attr('value', 'Y'); $('#sf').attr('name', 'set_filter');"><span><?=GetMessage("IBLOCK_SET_FILTER")?></span></button> <button class="button" onclick=" $('#df').attr('value', 'Y'); $('#df').attr('name', 'del_filter'); "><span><?=GetMessage("IBLOCK_DEL_FILTER")?></span></button>
</div>
	
			</form>
				
			</div>			
			<br style="clear: both;" />
</div><!--.item_filters-->

