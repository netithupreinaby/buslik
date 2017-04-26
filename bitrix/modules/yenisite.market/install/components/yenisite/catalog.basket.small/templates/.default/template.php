<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>

<div id ="market-basket">
	<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING')); ?>
    <h3><a href="<?=$arParams["BASKET_URL"]?>"><?=GetMessage("YENISITE_BASKET_NAME")?></a></h3>
    <ul>
        <li><?=GetMessage("YENISITE_BASKET_BASKET")?>:  <strong><?=$arResult["COMMON_COUNT"]?></strong></li>
        <li><?=GetMessage("YENISITE_BASKET_SUMM")?>:  <strong><?=$arResult["COMMON_PRICE"]?> <?=$arParams['VALUTA']?></strong></li>
    </ul>
	<?if(method_exists($this, 'createFrame')) $frame->end();?>
</div>
