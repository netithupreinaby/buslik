<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
if($arParams["COLOR_SCHEME"] == "green")
	$GLOBALS["APPLICATION"]->SetAdditionalCSS(str_replace($_SERVER["DOCUMENT_ROOT"], "", dirname(__FILE__)."/green.css"));
elseif($arParams["COLOR_SCHEME"] == "blue")
	$GLOBALS["APPLICATION"]->SetAdditionalCSS(str_replace($_SERVER["DOCUMENT_ROOT"], "", dirname(__FILE__)."/blue.css"));
elseif($arParams["COLOR_SCHEME"] == "yellow")
	$GLOBALS["APPLICATION"]->SetAdditionalCSS(str_replace($_SERVER["DOCUMENT_ROOT"], "", dirname(__FILE__)."/yellow.css"));
elseif($arParams["COLOR_SCHEME"] == "metal")
	$GLOBALS["APPLICATION"]->SetAdditionalCSS(str_replace($_SERVER["DOCUMENT_ROOT"], "", dirname(__FILE__)."/metal.css"));
elseif($arParams["COLOR_SCHEME"] == "pink")
	$GLOBALS["APPLICATION"]->SetAdditionalCSS(str_replace($_SERVER["DOCUMENT_ROOT"], "", dirname(__FILE__)."/pink.css"));
else{	
	$cs = COption::GetOptionString("yenisite.market", "color_scheme")=="ice"?"blue":COption::GetOptionString("yenisite.market", "color_scheme");
	if($cs && !$arParams["COLOR_SCHEME"] )
		$GLOBALS["APPLICATION"]->SetAdditionalCSS(str_replace($_SERVER["DOCUMENT_ROOT"], "", dirname(__FILE__)."/".$cs.".css"));
	else
		$GLOBALS["APPLICATION"]->SetAdditionalCSS(str_replace($_SERVER["DOCUMENT_ROOT"], "", dirname(__FILE__)."/red.css"));
}

?>

<div style='color: red;'>
<? if(is_array($arResult['ERROR']))
foreach($arResult['ERROR'] as $key => $err) {
	if ($key !== 'BASKET') echo GetMessage("ERROR");
	echo $err, '<br/>';
}?>
<br/>
</div>

	<div class="ys-user-basket">

<?if(isset($arResult["ITEMS"])):?>
<?$resizer = CModule::IncludeModule('yenisite.resizer2');?>	 
<form method="POST" id="basket_form">
<input type="hidden" name="calculate_no" id="calculate"  value="Y" />
<input type="hidden" name="order_no" id="order" value="<?=GetMessage("ORDER");?>" />
			<table>
       <?foreach($arResult["ITEMS"] as $k=>$arItem):?>
						<tr>
			  <td class="ibimg">
			  
			 <input type="hidden" class="no_del" name="no_del[]" id="del_<?=$k?>" value="<?=$arItem["KEY"]?>" />
			 

			 
			 <? 
				$photo = CIBlockElement::GetProperty($arItem["FIELDS"]["IBLOCK_ID"], $arItem["FIELDS"]["ID"], array("sort" => "asc"), Array("CODE"=>"MORE_PHOTO"))->Fetch(); 
				$path = CFile::GetPath($photo["VALUE"]);
				$pathid = $photo["VALUE"];

				if(!$path){
					$path = CFile::GetPath($arItem["FIELDS"]["PREVIEW_PICTURE"]);
					$pathid = $arItem["FIELDS"]["PREVIEW_PICTURE"];
					
				}
				if($resizer)
					$path = CResizer2Resize::ResizeGD2($path, $arParams["BASKET_PHOTO"]?$arParams["BASKET_PHOTO"]:5);
				else{
					$ResizeParams = array('width' => 50, 'height' => 50);
					$path = CFile::ResizeImageGet($pathid, $ResizeParams,  BX_RESIZE_IMAGE_PROPORTIONAL, true);
					$path = $path['src'];
				}
				
			 ?>
			  
			  <img src="<?=$path;?>" alt="" /></td>
			  <td class="ys-ibname">
				<h3><a href="<?=$arItem["FIELDS"]["DETAIL_PAGE_URL"]?>"><?=$arItem["FIELDS"]["NAME"]?></a></h3><?

				foreach ($arItem['PROPERTIES'] as $arProp):?>

				<div><strong><?=$arProp['NAME']?>: <?=$arProp['VALUE']?></strong></div><?

				endforeach?>

			  </td>
			  <td class="ys-ibprice"><span class="price"><?=$arItem["MIN_PRICE"]?> <span class="rubl"><?=GetMessage('RUB');?></span></span></td>
			  <td class="ys-ibcount"><input type="text" name="count[<?=$arItem["KEY"]?>]" id="QUANTITY_<?=$k?>" value="<?=$arItem["COUNT"]?>" class="txt w32" />
				<button onclick="$('#order').attr('name', 'no_order'); $('#calculate').attr('name', 'calculate'); setQuantity('#QUANTITY_<?=$k?>', '+'); return false;" class="button4">+</button> <button onclick="$('#order').attr('name', 'no_order'); $('#calculate').attr('name', 'calculate'); setQuantity('#QUANTITY_<?=$k?>', '-'); return false;" class="button5">-</button></td>
			  <td class="ys-ibdel"><button onclick="$('#order').attr('name', 'no_order'); $('#calculate').attr('name', 'calculate'); $('#del_<?=$k?>').attr('name', 'del[]');" class="button6 sym">&#206;</button></td>
			</tr>
			<?endforeach;?>
		</table>

						

					<div class="make_order"> 
<span class="ys-delivery"><?=GetMessage("DELIVERY");?>: <strong>0<span class="rubl"><?=GetMessage('RUB');?></span></strong></span> 
<br/>
<span class="ys-sum"><?=GetMessage("ITOG");?>: <strong><?=$arResult["COMMON_PRICE"]?></strong><b><span class="rubl"><?=GetMessage('RUB');?></span></b></span>
<br/>					
					
		<div style="text-align: left;">	
        <?
		$n_line = 0 ;
		foreach($arResult["DISPLAY_PROPERTIES"] as $arProp):
			if($n_line > 0) echo '<br/><br/>';
			$n_line ++ ;
			if(substr_count($arProp["INPUT"], "radio") > 0){
				$arr = explode("<br/>", $arProp["INPUT"]);
				foreach($arr as $k=>&$ar){
					if($ar){
						if(substr_count($ar, "checked") > 0)
							$ar = '<li><a class="ys_active" class="" href="#tab-'.$k.md5($arProp["NAME"]).'">&nbsp;&nbsp;&nbsp&nbsp;&nbsp'.$ar.'</a></li>';					
						else
							$ar = '<li><a class="" href="#tab-'.$k.md5($arProp["NAME"]).'">&nbsp;&nbsp;&nbsp&nbsp;&nbsp'.$ar.'</a></li>';					
					}
				}
				
				
				$arProp["INPUT"] = '<ul class="ys_tab_nav">'.implode(" ", $arr)."</ul>";
			}
		?>
				<?
					if(substr_count($arProp["INPUT"], 'text')) $arProp["INPUT"] = str_replace("<input ", "<input class='txt' ", $arProp["INPUT"]) ;
				?>
                <b><?=$arProp["NAME"]?> <?if($arProp['IS_REQUIRED'] == "Y"):?>*<?endif?></b>:<br/>            
                <?=$arProp["INPUT"]?>
        <?endforeach?>
		<br/><b>*</b><span class="req_message"><?=GetMessage('REQUIRED');?></span>
		</div>
   
<button onclick="$('#calculate').attr('name', 'no_calculate'); $('#order').attr('name', 'order');  $('#basket_form').attr('action', '<?=$arParams["BASKET_URL"]?>').submit();  return false;" class="button3"><?=GetMessage("ORDER");?></button></div>
	</form>					
								  <!--.make_order-->
<?else:?>
<?=GetMessage("YENISITE_BASKET_EMPTY")?>
<?endif;?>
                 
                </div><!--.user_basket-->
