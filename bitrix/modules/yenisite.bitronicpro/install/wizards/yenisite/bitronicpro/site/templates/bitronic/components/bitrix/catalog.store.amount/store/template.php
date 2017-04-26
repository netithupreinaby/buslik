<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(strlen($arResult["ERROR_MESSAGE"])>0)
	ShowError($arResult["ERROR_MESSAGE"]);?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?if(count($arResult["STORES"]) > 0):?>
<div class="ys-stores">
	
	<?foreach($arResult["STORES"] as $pid=>$arProperty):?>
	<div class="ys-store-detail">
		<span class="ys-store-title"><?=$arProperty["TITLE"]?></span>
		<? if(isset($arProperty["PHONE"])):?>
		<span class="support"	><?=GetMessage('S_PHONE')?></span>
		<span class="info"		><?=$arProperty["PHONE"]?></span>
		<?endif;?>
		<? if(isset($arProperty["SCHEDULE"])):?>
		<span class="support"><?=GetMessage('S_SCHEDULE')?></span>
		<span class="info"		><?=$arProperty["SCHEDULE"]?></span>
		<?endif;?>
		<?if($arProperty["NUM_AMOUNT"]==0):?>
			<?if ($arParams["SHOW_SKLAD"] == "Y"):?>
			<p> </p>
				<div class="product_amount">
				</div>
			<?else:?>
			<p class="none"><?=$arProperty["AMOUNT"]?></p>
			<?endif;?>
		<?elseif($arProperty["NUM_AMOUNT"]<$arParams["MIN_AMOUNT"]):?>
			<?if ($arParams["SHOW_SKLAD"] == "Y"):?>
			<p> </p>
			
				<div class="product_amount">
				<div class="unvis"></div>
				<div class="unvis"></div>
				<div ></div>
				</div>
			<?else:?>
			<p class="little"><?=$arProperty["AMOUNT"]?></p>
			<?endif?>
		<?else:?>
			
			<?if ($arParams["SHOW_SKLAD"] == "Y"):?>
			<p> </p>
				<div class="product_amount">
				<div ></div>
				<div ></div>
				<div ></div>
				</div>
			<?else:?>
			<p class="much"><?=$arProperty["AMOUNT"]?></p>
			<?endif?>
		<?endif;?>
	</div>
	<?endforeach;?>
</div>
<?endif;?>