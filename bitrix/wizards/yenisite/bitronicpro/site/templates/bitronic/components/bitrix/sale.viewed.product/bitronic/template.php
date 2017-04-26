<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(function_exists('yenisite_GetCompositeLoader')){global $MESS;$MESS ['COMPOSITE_LOADING'] = yenisite_GetCompositeLoader();}?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING')); ?>
<?if (count($arResult) > 0):?>
<div class="view-list">
	<h2><?=GetMessage("VIEW_HEADER");?></h2>
	<?foreach($arResult as $arItem):?>
		<div class="view-item">
			<?if($arParams["VIEWED_IMAGE"]=="Y" && is_array($arItem["PICTURE"])):?>
				<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="no-border"><img src="<?=$arItem["PICTURE"]["src"]?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>"></a>
			<?endif?>
			<?if($arParams["VIEWED_NAME"]=="Y"):?>
				<div><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a></div>
			<?endif?>
			<?if($arParams["VIEWED_PRICE"]=="Y" && $arItem["CAN_BUY"]=="Y"):?>
				<div><?=str_replace(GetMessage('RUB_REPLACE'), '<span class="rubl">'.GetMessage('RUB').'</span>',  $arItem["PRICE_FORMATED"])?></div>
			<?endif?>
			<?if($arParams["VIEWED_CANBUY"]=="Y" && $arItem["CAN_BUY"]=="Y"):?>
				<noindex>
					<a href="<?=$arItem["BUY_URL"]?>" rel="nofollow"><?=GetMessage("PRODUCT_BUY")?></a>
				</noindex>
			<?endif?>
			<?if($arParams["VIEWED_CANBASKET"]=="Y" && $arItem["CAN_BUY"]=="Y"):?>
				<noindex>
					<a href="<?=$arItem["ADD_URL"]?>" rel="nofollow"><?=GetMessage("PRODUCT_BASKET")?></a>
				</noindex>
			<?endif?>
		</div>
	<?endforeach;?>
</div>
<?endif;?>
<?if(method_exists($this, 'createFrame')) $frame->end();?>