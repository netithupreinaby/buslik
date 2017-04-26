<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?><?
$cnt = count($arParams["FIELD_CODE"]);
$cnt += count($arParams["PROPERTY_CODE"]);
?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>

  <table class="bills">  
	<tbody>
		<tr>							
		<?foreach($arParams["FIELD_CODE"] as $code): if($code!="DATE_CREATE"):?>
			<th width="<?=100/$cnt;?>%"><?=GetMessage($code)?></th>						
		<?endif;endforeach;?>
						
		<?foreach($arParams["PROPERTY_CODE"] as $code): if($arResult["ITEMS"][0]["PROPERTIES"][$code]["NAME"]):?>
			<th width="<?=100/$cnt;?>%"><?=$arResult["ITEMS"][0]["PROPERTIES"][$code]["NAME"]?></th>						
		<?endif;endforeach;?>						
		</tr>
		<?foreach($arResult["ITEMS"] as $arItem):?>
		<tr>
			<?foreach($arParams["FIELD_CODE"] as $code): if($code!="DATE_CREATE"):?>
				<td width="<?=100/$cnt;?>%">
					<?=$arItem[$code]?>
					<?if($code == "ID"):?>
					<span class="date"><?=GetMessage("PO")?> <?=$arItem["DATE_CREATE"]?></span>
					<?endif?>
				</td>						
			<?endif; endforeach;?>
			<?foreach($arParams["PROPERTY_CODE"] as $code): ?>
			
				<td width="<?=100/$cnt;?>%">
			
			
				<?if(is_array($arItem["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])):?>
					<?foreach($arItem["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"] as $k=>$el):?>
						<span class='ys-elems'><?=$k+1;?>.&nbsp;<?=$el?><br/></span>
					<?endforeach?>
				<?else:?>
					<?=$arItem["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"];?>
				<?endif?>
				</td>						
			<?endforeach;?>						
		</tr>
		<?endforeach?>
	</tbody>
</table>	
