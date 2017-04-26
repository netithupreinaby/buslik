<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$cnt = count($arParams["FIELD_CODE"]);
$cnt += count($arParams["PROPERTY_CODE"]);
?>

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
					<?if($code == "ID"):?>
					<a href="<?=$arItem['DETAIL_PAGE_URL']?>">
					<?endif?>
					<?=$arItem[$code]?>
					<?if($code == "ID"):?>
					</a>
					<span class="date"><?=GetMessage("PO")?> <?=$arItem["DATE_CREATE"]?></span>
					<?endif?>
				</td>
			<?endif; endforeach;?>
			<?foreach($arParams["PROPERTY_CODE"] as $code): ?>
			
				<td width="<?=100/$cnt;?>%">
			
			
				<?if(is_array($arItem["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])):?>
					<?foreach($arItem["DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"] as $k=>$el):?>
						<?=$k+1;?>.&nbsp;<?=$el?><br/>
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
