<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div style='color: red;'>
<? if(is_array($arResult['ERROR']))
foreach($arResult['ERROR'] as $key => $err) {
	if ($key !== 'BASKET') echo GetMessage("ERROR");
	echo $err, '<br/>';
}?>
</div>


<?if(isset($arResult["ITEMS"])):?>
<form method="POST">
    <table class="big-basket" width="100%">
        <tr><td width="20%" class="head"><?=GetMessage("PHOTO");?></td><td width="50%" class="head"><?=GetMessage("NAME");?></td><td width="10%" class="head l"><?=GetMessage("COUNT");?></td><td width="10%" class="head c"><?=GetMessage("PRICE");?></td><td width="10%" class="head"><?=GetMessage("DELETE");?></td></tr>
        <?foreach($arResult["ITEMS"] as $arItem):?>
        <tr class="item">
		<td>
			<?	if($arItem["FIELDS"]['PREVIEW_PICTURE']): ?>
				   <a href="<?=$arItem["FIELDS"]["DETAIL_PAGE_URL"]?>" title="<?=$arItem["FIELDS"]["NAME"]?>"><img src='<?=CFile::GetPath($arItem["FIELDS"]['PREVIEW_PICTURE']);?>' alt='<?=$arItem["FIELDS"]["NAME"]?>' /></a>
			<? endif; ?>
		</td>
		
		<td>
                <a href="<?=$arItem["FIELDS"]["DETAIL_PAGE_URL"]?>" title="<?=$arItem["FIELDS"]["NAME"]?>"><?=$arItem["FIELDS"]["NAME"]?></a><br/>
				<?if(count($arItem["PROPERTIES"]) > 0):?>
                <span>
                <? $i=0; foreach($arItem["PROPERTIES"] as $arProp): $i++;?>
                    <b><?=$arProp["NAME"]?>:</b> <?=$arProp["VALUE"]?><?=($i<count($arItem["PROPERTIES"]))?",&nbsp;":"";?>
                <?endforeach?>    
                </span>
				<?endif?>
            </td>
            <td>
                <input style="width: 40px;" type="text" name="count[]" id="QUANTITY_<?=$arItem['ID']?>" value="<?=$arItem["COUNT"]?>" />
                <input type="hidden" name="Key[]" value="<?=$arItem["KEY"]?>" />
            </td>
			<td><?=$arItem["MIN_PRICE"]?> <?=$arParams['UE'];?></td>
            <td><input type="checkbox" name="del[]" value="<?=$arItem["KEY"]?>"/></td></tr>
        <?endforeach?>
			<tr><td></td><td></td> <td></td> <td></td> <td><br/><b><?=GetMessage("ITOG");?>:</b> <?=$arResult["COMMON_PRICE"]?> <?=$arParams['UE'];?></td></tr>
        </table>
    
        <br/>
        
        <input style="float: right;" name="calculate" type="submit" value="<?=GetMessage("CALCULATE");?>"/>

<br/>
<br/>

    <table class="big-basket-fields" width="100%">
        <?foreach($arResult["DISPLAY_PROPERTIES"] as $arProp):?>
        <tr class="field">
            <td class="left">
                <?=$arProp["NAME"]?><?if($arProp['IS_REQUIRED'] == "Y"):?>*<?endif?>:
            </td>
            <td class="right">
                <?=$arProp["INPUT"]?>
            </td>
        </tr>
        <?endforeach?>
   </table>
   
   <br/>
   <input style="float: right;" type="submit" name="order" value="<?=GetMessage("ORDER");?>"/>
</form>
<?else:?>
<?=GetMessage("YENISITE_BASKET_EMPTY")?>
<?endif;?>
