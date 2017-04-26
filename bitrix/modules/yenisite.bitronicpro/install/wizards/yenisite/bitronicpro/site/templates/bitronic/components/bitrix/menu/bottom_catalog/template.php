<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
<div class="block_container">

<?
$cnt = 0;
foreach($arResult as $arItem):
?>
		<?if($arItem["DEPTH_LEVEL"]==1): ?>
				<li class="iblocktype"><span rel='span' class="item_menu"><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></span></li>
		<?endif?>
		
		<?if($arItem["DEPTH_LEVEL"]==2):?>					
				<li class="iblock"><a class="item_menu" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
		<?endif?>	
<?
endforeach;?>
<div style="clear: both;"></div>
</div>
