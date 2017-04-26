<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?if(count($arResult["ITEMS"])==0) return?>
<?global $ys_n_for_ajax;?>
<script type="text/javascript">
function aprof_counterBack(id,stmp) {
	var h = Math.floor(stmp/3600);
	var d = 0;
	if(h>24) {
		d = Math.floor(h/24);
		h = h-d*24;
	}
	var m = Math.floor((stmp-((h*3600)+(d*24*3600)))/60);
	var s = Math.floor(stmp-((m*60)+(h*3600)+(d*24*3600)));
	h = h+"";
	m = m+"";
	s = s+"";
	if(h.length==1)
		h = "0"+h;
	if(m.length==1)
		m = "0"+m;
	if(s.length==1)
		s = "0"+s;
	if(d>0) {
		d = d+"";
		if(d.length==1)
			d = "0"+d;
		var str = '<div>'+d+'<small><?=GetMessage("APROF_TIME2BUY_D")?></small></div>'+
					'<div class="slash">:</div>'+
					'<div>'+h+'<small><?=GetMessage("APROF_TIME2BUY_H")?></small></div>'+
					'<div class="slash">:</div>'+
					'<div>'+m+'<small><?=GetMessage("APROF_TIME2BUY_M")?></small></div>';
	}
	else {
		var str = '<div>'+h+'<small><?=GetMessage("APROF_TIME2BUY_H")?></small></div>'+
					'<div class="slash">:</div>'+
					'<div>'+m+'<small><?=GetMessage("APROF_TIME2BUY_M")?></small></div>'+
					'<div class="slash">:</div>'+
					'<div>'+s+'<small><?=GetMessage("APROF_TIME2BUY_S")?></small></div>';
	}
	document.getElementById(id).innerHTML = str;
	stmp--;
	if(stmp>0)
		setTimeout("aprof_counterBack('"+id+"',"+stmp+")",1000);
	else {
		$("#"+id).parents("li").hide(500,function(){
			var el = $(this).parents(".aprof-time2buy");
			$(this).remove();
			var slide_width = $(el).find(".aprof-time2buy-carousel li").width();
			var slide_cnt = $(el).find(".aprof-time2buy-carousel li").size();
			var slide = $(el).find(".aprof-time2buy-carousel li");
			var slider_width = $(el).find(".aprof-time2buy-carousel").width();
			var m = parseInt($(slide).css("margin-right"))+parseInt($(slide).css("margin-left"))+parseInt($(slide).css("padding-right"))+parseInt($(slide).css("padding-left"));
			var cnt = parseInt((slider_width+m)/(slide_width+m));
			if(cnt==slide_cnt)
				$(el).find(".aprof-time2buy-larr, .aprof-time2buy-rarr").hide();
		});
	}
}
</script>
<?/*if(strlen($arParams["TITLE"])>0) {?>
<h2><?=$arParams["TITLE"]?></h2>
<?}*/?>
<div class="aprof-time2buy">
	<div class="aprof-time2buy-carousel">
		<ul>
		<?foreach($arResult["ITEMS"] as $arItem):
			$ys_n_for_ajax++;
		?>
			<li>
				<hidden name="ajax_iblock_id_<?=$ys_n_for_ajax;?>" id="ajax_iblock_id_<?=$ys_n_for_ajax;?>" value="<?=$arItem['IBLOCK_ID'];?>"/>
				<?if($arParams["DISPLAY_NAME"]=="Y"){?>
					<div class="aprof-time2buy-name"><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?=$arItem["NAME"]?></a></div>
				<?}?>

				<?if($arParams["DISPLAY_PICTURE"]=="Y"&&is_array($arItem["PICTURE"])):?>
					<div class="aprof-time2buy-image">
						<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
							<img src="<?=$arItem["PICTURE"]["SRC"]?>" />
						</a>
					</div>

				<?elseif($arParams["DISPLAY_MORE_PHOTO"]=="Y"):?>
				<?$path = CFile::GetPath($arItem["MORE_PHOTO"]["VALUE"][0]);?>
					<div class="aprof-time2buy-image">
						<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
							<?if(CModule::IncludeModule('yenisite.resizer2')):?>
								<img id="product_photo_<?=$ys_n_for_ajax;?>" src='<?=CResizer2Resize::ResizeGD2($path,"4"/*$arParams['IMAGE_SET']*/);?>' alt='<?=$arItem['NAME']?>' />
							<?endif;?>
						</a>
					</div>
				<?endif;?>
			 

				<?if($arParams["DISPLAY_TIMER"]=="Y"){?>
					<div class="aprof-time2buy-timer">
						<div class="aprof-time2buy-time">
							<?=GetMessage("APROF_TIME2BUY_ENDSALE");?>
							<div id="aprof_time2buy_cnt_<?=$arItem["ID"]?>" class="aprof-time2buy-cnt">
								<?if($arItem["COUNTER"]["D"]>0){?>
								<div><?=$arItem["COUNTER"]["D"]?><small><?=GetMessage("APROF_TIME2BUY_D")?></small></div>
								<div class="slash">:</div>
								<div><?=$arItem["COUNTER"]["H"]?><small><?=GetMessage("APROF_TIME2BUY_H")?></small></div>
								<div class="slash">:</div>
								<div><?=$arItem["COUNTER"]["M"]?><small><?=GetMessage("APROF_TIME2BUY_M")?></small></div>
								<?}else{?>
								<div><?=$arItem["COUNTER"]["H"]?><small><?=GetMessage("APROF_TIME2BUY_H")?></small></div>
								<div class="slash">:</div>
								<div><?=$arItem["COUNTER"]["M"]?><small><?=GetMessage("APROF_TIME2BUY_M")?></small></div>
								<div class="slash">:</div>
								<div><?=$arItem["COUNTER"]["S"]?><small><?=GetMessage("APROF_TIME2BUY_S")?></small></div>
								<?}?>
							</div>
							<script type="text/javascript">
								aprof_counterBack("aprof_time2buy_cnt_<?=$arItem["ID"]?>",<?=$arItem["FULL_SECONDS"]?>);
							</script>
						</div>
						<div class="aprof-time2buy-quant">
							<?=GetMessage("APROF_TIME2BUY_MORE");?>
							<div><?=$arItem["QUANTITY"]?></div>
							<small><?=GetMessage("APROF_TIME2BUY_QUANTITY");?></small>
						</div>
					</div>
				<?}?>
				<?
				$ak = array_keys($arItem["PRICES"]);
				$arItem["PRICE"] = $arItem["PRICES"][$ak[0]];
				?>
				<?if($arParams["DISPLAY_SALE"]=="Y"){?>
					<div class="aprof-time2buy-sale">
						<div class="aprof-time2buy-sale-label"><?=GetMessage("APROF_TIME2BUY_SALE_TITLE");?></div>
						<div class="aprof-time2buy-sale-scale"><div style="width:<?=$arItem["SALE"]?>%;"></div><span><?=$arItem["SALE"]?>%</span></div>
					</div>
				<?}?>
				<?if($arParams["DISPLAY_PRICES"]=="Y"){?>
					<div class="aprof-time2buy-price">
						<div class="aprof-time2buy-oldprice">
							<div class="aprof-time2buy-price-label"><?=GetMessage("APROF_TIME2BUY_PRICE");?></div>
							<div class="aprof-time2buy-price-num"><?=str_replace(GetMessage('RUB_REPLACE'), '<span class="rubl">'.GetMessage('RUB').'</span>', $arItem["PRICE"]["PRINT_VALUE"]);?></div>
						</div>
						<div class="aprof-time2buy-newprice">
							<div class="aprof-time2buy-price-label"><?=GetMessage("APROF_TIME2BUY_NEW_PRICE");?></div>
							<div class="aprof-time2buy-price-num"><?=str_replace(GetMessage('RUB_REPLACE'), '<span class="rubl">'.GetMessage('RUB').'</span>', $arItem["PRICE"]["PRINT_DISCOUNT_VALUE"]);?></div>
						</div>
					</div>
				<?}?>
				<?if($arParams["DISPLAY_SAVE"]=="Y"){?>
					<div class="aprof-time2buy-save">
						<div class="aprof-time2buy-save-label"><?=GetMessage("APROF_TIME2BUY_SAVE_TITLE");?></div>
						<div class="aprof-time2buy-save-val"><?=str_replace(GetMessage('RUB_REPLACE'), '<span class="rubl">'.GetMessage('RUB').'</span>', $arItem["FORMAT_DISCOUNT_VALUE"]);?></div>
					</div>
				<?}?>
				<?if($arParams["DISPLAY_BUY_BTN"]=="Y"){?>
					<?
						$arItem['BUY_URL'] = str_replace('BUY', 'ADD2BASKET', $arItem['BUY_URL']);
						$arItem['BUY_URL'] = str_replace('ELEMENT_ID', 'id', $arItem['BUY_URL']);
					?>
					<a id="mb-<?=$ys_n_for_ajax;?>" href="<?=$arItem["BUY_URL"]?>" class="button2 aprof-time2buy-add2cart ajax_add2basket_main ajax_a2b_<?=$arItem['ID'];?>"><span><?=GetMessage("APROF_BUY");?></span></a> <?/* aprof-time2buy-add2cart */?>
				<?}?>
			</li>
		<?endforeach;?>
	</div>
</div>
<?COption::SetOptionString("yenisite.bitronicpro", "ys_n_for_ajax", $ys_n_for_ajax );?>