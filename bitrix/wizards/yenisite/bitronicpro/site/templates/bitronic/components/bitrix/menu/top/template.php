<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?global $menus;?>
<?if (!empty($arResult)):?>
<?//include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
<ul class="menu">

<?
foreach($arResult as $arItem):
$menus[] = $arItem[LINK];
	if($arParams["MAX_LEVEL"] == 1 && $arItem["DEPTH_LEVEL"] > 1) 
		continue;
?>
	<?if($arItem["SELECTED"]):?>
		<?if("{$arItem["LINK"]}index.php" == $APPLICATION->GetCurPage() || $arItem["LINK"] == $APPLICATION->GetCurPage()):?>
			<li><?=$arItem["TEXT"]?></li>
		<?else:?>
			<li><a href="<?=$arItem["LINK"]?>" class="selected"><?=$arItem["TEXT"]?></a></li>
		<?endif;?>
	<?else:?>
		<li><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
	<?endif?>
	
<?endforeach?>

</ul>
<?endif?>