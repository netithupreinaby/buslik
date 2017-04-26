<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?if(CModule::IncludeModule('statistic')):?>

<?if(function_exists('yenisite_GetCompositeLoader')){global $MESS;$MESS ['COMPOSITE_LOADING'] = yenisite_GetCompositeLoader();}?>
<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING')); ?>

<?if($arParams['ONLY_GEOIP'] != 'Y'):?>
<span><?=GetMessage("LOC_FROM")?></span>
<a href="#" class="ys-geoip-store-city">
	<?=$arResult['CITY']?>
</a>

<div class="ys-popup popup" id="ys-geoipstore" style="display:none;">

	<a class="close sym" href="javascript:viod(0);" title="<?=GetMessage('EXIT')?>"><?=GetMessage("CLOSE{$arResult['FONTS']}");?></a>
	<h2></h2>
	<?foreach($arResult['ITEMS'] as $item):?>
		<div class="ys-geoipstore-container <?if($arResult['ACTIVE_ITEM_ID'] == $item['ID']):?><?="ys-geoipstore-cont-active"?><?endif?>">
			<div class="ys-geoipstore-item">
				<a href="#" class="ys-geoipstore-itemlink" data-ys-item-id="<?=$item['ID']?>"><?=$item['CITY_DEL_NAME']?></a>
				<?if($arResult['ACTIVE_ITEM_ID'] == $item['ID']):?><span class="sym" style="color: red;"> <?=GetMessage("YOUR{$arResult['FONTS']}");?></span><?endif?>
				<?if(!empty($item['STORES'])):?>
					<ul class="ys-geoipstore-stores">
						<?foreach($item['STORES'] as $store):?>
							<li><span><?=$store['TITLE']?></span></li>
						<?endforeach?>
					</ul>
				<?endif?>
			</div>
		</div>
	<?endforeach?>
	
	<div class="clear"></div>

</div>
<?else: // object name : id , for geoip?>
	<script>
		var ysGeoStoreActiveId = <?=$arResult['ACTIVE_ITEM_ID']?>;
		var ysGeoStoreList = {
		<?foreach($arResult['ITEMS'] as $item){
			echo "'{$item['CITY_DEL_NAME']}':{$item['ID']},";
			if($item['DEFAULT'] == 'Y'){
				$default_store_id = $item['ID'] ;
			}
		}?>
		}
		var ysGeoStoreDefault = <?=$default_store_id?>;
	</script>
<?endif;?>
<?if(method_exists($this, 'createFrame')) $frame->end();?>
<?endif;?>