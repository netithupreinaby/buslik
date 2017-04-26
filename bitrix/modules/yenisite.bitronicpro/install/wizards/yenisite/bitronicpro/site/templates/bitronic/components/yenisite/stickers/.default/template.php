<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?//include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
<?if(is_array($arResult["HOLIDAY"])) :?>
	<img class="mark_holiday" alt="<?=$arResult["HOLIDAY"]["NAME"];?>" src="<?=$arResult["HOLIDAY"]["PIC"];?>" width="<?=$arResult["HOLIDAY"]["WIDTH"];?>">
<?endif?>
<?if($arResult["NEW"]):?>
	<div class="mark new-label" style="top: <?if($arResult["STICKER_PERCENT"]):?>50%<?else:?><?=($arResult["STICKER_TOP"]+0);?>px<?endif;?> !important;"><?=GetMessage('STICKER_NEW');?></div>
<?endif?>
<?if($arResult["HIT"]):?>
	<div class="mark star2-label" style="top: <?if($arResult["STICKER_PERCENT"]):?>60%<?else:?><?=($arResult["STICKER_TOP"]+30);?>px<?endif;?> !important;"><?=GetMessage('STICKER_HIT');?></div>
<?endif?>
<?if($arResult["SALE"]):?>
	<div class="mark per2-label" style="top: <?if($arResult["STICKER_PERCENT"]):?>70%<?else:?><?=($arResult["STICKER_TOP"]+60);?>px<?endif;?> !important;">
		<?if($arResult["SALE_DISC"]>0):?>
			-<?=Round($arResult["SALE_DISC"])?>
		<?endif?>
	%</div>
<?endif;?>
<?if($arResult["BESTSELLER"]):?>
	<div class="mark leader-label" style="top: <?if($arResult["STICKER_PERCENT"]):?>80%<?else:?><?=($arResult["STICKER_TOP"]+90);?>px<?endif;?> !important;"><?=GetMessage('STICKER_BESTSELLER');?></div>
<?endif?>