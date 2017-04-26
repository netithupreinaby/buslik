<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<link type="text/css" href="<?=str_replace("\\", "/", str_replace($_SERVER['DOCUMENT_ROOT'],'',dirname(__FILE__)))?>/scripts/css/ui-lightness/jquery-ui-1.8.14.custom.css" rel="stylesheet" />
<script src="<?=str_replace("\\", "/", str_replace($_SERVER['DOCUMENT_ROOT'],'',dirname(__FILE__)))?>/scripts/js/jquery-1.5.1.min.js" type="text/javascript"></script>
<script src="<?=str_replace("\\", "/", str_replace($_SERVER['DOCUMENT_ROOT'],'',dirname(__FILE__)))?>/scripts/js/jquery-ui-1.8.14.custom.min.js" type="text/javascript"></script>


<form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get">
	<?foreach($arResult["ITEMS"] as $arItem):
		if(array_key_exists("HIDDEN", $arItem)):
			echo $arItem["INPUT"];
		endif;
	endforeach;?>
	<table class="filter-table" width="300px" cellspacing="0" cellpadding="2">
	<thead>
		<tr>
			<td colspan="2" align="center"><?=GetMessage("IBLOCK_FILTER_TITLE")?></td>
		</tr>
	</thead>
	<tbody>
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<?if(!array_key_exists("HIDDEN", $arItem)):?>				   
				<?if($arItem['PROPERTY_TYPE'] == "N"):?>	
				
					<? if($arItem[VALUES][MIN] == $arItem[VALUES][MAX]) continue; ?>				
				    <script>
					$(function() {
						$( "#slider-<?=$arItem[CODE]?>").slider({
							range: true,
							min: <?=$arItem[VALUES][MIN]?$arItem[VALUES][MIN]:0;?>,
							max:  <?=$arItem[VALUES][MAX]?$arItem[VALUES][MAX]:1000;?>,
							values: [ <?=$arItem[VALUES][MIN_VALUE]?$arItem[VALUES][MIN_VALUE]:$arItem[VALUES][MIN];?>, <?=$arItem[VALUES][MAX_VALUE]?$arItem[VALUES][MAX_VALUE]:$arItem[VALUES][MAX];?> ],
							slide: function( event, ui ) {
								$("#<?=$arItem[CODE]?>-min").attr('value', ui.values[0]);
								$("#<?=$arItem[CODE]?>-max").attr('value', ui.values[1]);
								$("#left-<?=$arItem[CODE]?>").html(ui.values[0]);
								$("#right-<?=$arItem[CODE]?>").html(ui.values[1]);
							}
						});
					});
				      </script>
				    <tr>
					
					<td valign="top" colspan="2"><?//=$arItem["INPUT"]?>				
					    <div class='filter-field-name' ><?=$arItem["NAME"]?>: (<span id="left-<?=$arItem[CODE]?>"><?=$arItem[VALUES][MIN_VALUE]?$arItem[VALUES][MIN_VALUE]:$arItem[VALUES][MIN];?></span> - <span id="right-<?=$arItem[CODE]?>"><?=$arItem[VALUES][MAX_VALUE]?$arItem[VALUES][MAX_VALUE]:$arItem[VALUES][MAX];?></span>)</div> <div id="slider-<?=$arItem[CODE]?>"></div>
					    <div class='filter-field-input'><?=$arItem["INPUT"]?></div>
					</td>
				    </tr>

				<?else:?>
					<?
                        if(substr_count($arItem["INPUT"], "select") > 0)
                             if(substr_count($arItem["INPUT"], "<option") == 1 || substr_count($arItem["INPUT"], "<option") == 0) continue;
                    ?>

				    <tr>
					<td valign="top"><?=$arItem["NAME"]?>:</td>
					<td valign="top"><?=$arItem["INPUT"]?></td>
				    </tr>
				
				<?endif?>

				

			<?endif?>
		<?endforeach;?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="2">
				<input type="submit" name="set_filter" value="<?=GetMessage("IBLOCK_SET_FILTER")?>" /><input type="hidden" name="set_filter" value="Y" />&nbsp;&nbsp;<input type="submit" name="del_filter" value="<?=GetMessage("IBLOCK_DEL_FILTER")?>" /></td>
		</tr>
	</tfoot>
	</table>
</form>
