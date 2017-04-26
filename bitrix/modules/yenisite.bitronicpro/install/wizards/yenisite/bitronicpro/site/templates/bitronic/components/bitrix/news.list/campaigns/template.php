<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>

<?if(count($arResult["ITEMS"])>0):?>
<h2><?=GetMessage('CAMPAINGS_TITLE')?></h2>
<?endif?>	

<?foreach($arResult["ITEMS"] as $arItem):?>
 <?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
		<div  id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="stock-item"> <a href="<?echo $arItem["DETAIL_PAGE_URL"]?>" class="image"><img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="" /></a> <span class="stock-date"><?=GetMessage('S')?> <?=$arItem[DATE_ACTIVE_FROM]?> <?=GetMessage('PO')?> <?=$arItem[DATE_ACTIVE_TO]?></span>
			<h3><a href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><?echo $arItem["NAME"]?></a></h3>
		  </div>
<?endforeach?>		  
