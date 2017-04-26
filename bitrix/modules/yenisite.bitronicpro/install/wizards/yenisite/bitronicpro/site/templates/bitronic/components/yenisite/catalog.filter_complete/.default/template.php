<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

	<div class="item_filters">
			<h2><?=GetMessage('TITLE_FILTER')?></h2>

			<form action="#">
			<?foreach($arResult["ITEMS"] as $arItem):
					if(array_key_exists("HIDDEN", $arItem)):
						echo $arItem["INPUT"];
					endif;
			endforeach;?>
			
			
		<?foreach($arResult["ITEMS"] as $arItem):?>
		<?if(substr_count($arItem["INPUT"], "CML2_") > 0) continue;?>
					<?if(!array_key_exists("HIDDEN", $arItem)):?>												
						<?if($arItem['PROPERTY_TYPE'] == "N"):?>	
						<? if($arItem[VALUES][MIN] == $arItem[VALUES][MAX]) continue; ?>
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
						<?else:?>							
							<h3><?=$arItem["NAME"]?>:</h3>	
							<?
								if(substr_count($arItem["INPUT"], 'type="checkbox"') > 0){
									$arItem["INPUT"] = str_replace('type="checkbox"', ' class="checkbox" type="checkbox" ', $arItem["INPUT"]);
									$arItem["INPUT"] = str_replace('</label>', '</label></br>', $arItem["INPUT"]);									
								}

								if(substr_count($arItem["INPUT"], '<select') > 0){
									$arItem["INPUT"] = str_replace('<select', '<select class="toggle-list" ', $arItem["INPUT"]);									
									$arItem["INPUT"] = str_replace('multiple', '', $arItem["INPUT"]);
									$arItem["INPUT"] = str_replace('size', 'rel', $arItem["INPUT"]);
								}
							?>
							<?=$arItem["INPUT"]?>
						<?endif?>
						
					<?endif?>
		<?endforeach?>	

<?/*?>			
				<div class="col">
				
					<input type="checkbox"  class="checkbox"/><label>Lenovo</label><br />
					<input type="checkbox"  class="checkbox"/><label>MSI</label><br />
					<input type="checkbox"  class="checkbox"/><label>Packard Bell</label><br />
					<input type="checkbox"  class="checkbox"/><label>Samsung</label><br />
					<input type="checkbox"  class="checkbox"/><label>Sony</label><br />
					<input type="checkbox"  class="checkbox"/><label>Toshiba</label>
				</div><!--.col-->
				<div style="clear:both;"></div>
				<h3>:</h3>
				<div class="price_slider">
					<div id="limit"></div>
					<input type="text" id="amount" />
				</div><!--.price_slider-->
				<div class="additionally">
					<input type="checkbox"  class="checkbox"/><label class="mrw"></label>
					<input type="checkbox"  class="checkbox"/><label>3G</label>
				</div><!--.additionally-->
				<h3> :</h3>
				<div class="additionally">
					<input type="checkbox"  class="checkbox"/><label>FreeDOS</label><br />
					<input type="checkbox"  class="checkbox"/><label>Linux</label><br />
					<input type="checkbox"  class="checkbox"/><label>MacOS</label><br />
					<input type="checkbox"  class="checkbox"/><label>Windows Vista</label><br />
					<input type="checkbox"  class="checkbox"/><label>Windows 7</label>
				</div><!--.additionally-->
				<h3>  <span>():</span></h3>
				<div class="additionally">
					<input type="checkbox"  class="checkbox"/><label class="mr">1</label>
					<input type="checkbox"  class="checkbox"/><label class="mr">2</label>
					<input type="checkbox"  class="checkbox"/><label class="mr">3</label>
					<input type="checkbox"  class="checkbox"/><label>4</label>
				</div><!--.additionally-->
				<h3> :</h3>
				<select class="toggle-list">
					<option> </option>
					<option> </option>
					<option> </option>
					<option> </option>
					<option selected="selected"> </option>
				</select>
<?*/?>			

<input id='sf' type="hidden" name="set_filter" value="" />
<input id='df' type="hidden" name="del_filter" value="" />
<div class='inputs-filter'>
<button class="button" onclick=" $('#sf').attr('value', 'Y'); "><span><?=GetMessage("IBLOCK_SET_FILTER")?></span></button> <button class="button" onclick=" $('#df').attr('value', 'Y'); "><span><?=GetMessage("IBLOCK_DEL_FILTER")?></span></button>

</div>
	
			</form>
			</div><!--.item_filters-->

