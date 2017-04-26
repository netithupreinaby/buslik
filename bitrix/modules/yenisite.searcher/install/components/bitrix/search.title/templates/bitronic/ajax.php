<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>		

<?foreach($arResult["CATEGORIES"] as $category_id => $arCategory):?>
	<?if($category_id !== "all" && $categoryfilter == "all"):?>
		<li class="noitem title">
			<h3><?echo $arCategory["TITLE"];?></h3>
		</li>
	<?endif;?>
	<?foreach($arCategory["ITEMS"] as $i => $arItem):?>
		<?	
			
			/*PROPERTIES
			$propTxt = "";
			foreach($arItem["PROPERTIES"] as $prop){	
			
				if($prop["DISPLAY_VALUE"]){
					
					if(is_array($prop["DISPLAY_VALUE"])){
						$propTxt .= $prop["NAME"].": ";
						$propTxt .= strip_tags(implode(", ", $prop["DISPLAY_VALUE"])."; ");
					}
					else{							
						$propTxt .= $prop["NAME"].": ".strip_tags($prop["DISPLAY_VALUE"])."; ";
					}
				}
				
				
			}
			if($propTxt) $propTxt = "<br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$propTxt;
			*/
			/*
			if ($USER->IsAdmin()){
				echo "<pre>";print_r($arItem);echo "</pre>";
			}*/
			
			
			$itemid = "ys-st-".$arItem['PARAM2']."-".$arItem['ITEM_ID'];
		?>
		<?if(is_array($arItem['SECTION'])):?>
			<li>
				<div class="sim-img" id="<?=$itemid?>-photo">
					<img src="<?=$arItem['PRODUCT_PICTURE_SRC']?>" alt="" />
				</div>
				<div class="name">
					<h3><a href="<?echo $arItem["URL"]?>"><?=$arItem['SECTION']["CATALOG"]?GetMessage('SECTION_CATALOG'):GetMessage('SECTION');?> <?echo $arItem["NAME"];?></a></h3>
				</div>
				<div style="clear:both;"></div>
			</li>
		<?elseif($category_id === "all"):?>
			<li class="noitem all">
				<div class="name">
					<h3><a href="<?echo $arItem["URL"]?>"><?echo $arItem["NAME"];?></a></h3>
				</div>
				<div style="clear:both;"></div>
			</li>
		<?elseif($category_id === "other"):?>
			<li class="noitem other">
				<div class="name">
					<h3><a href="<?echo $arItem["URL"]?>"><?echo $arItem["NAME"];?></a></h3>
				</div>
				<div style="clear:both;"></div>
			</li>
		<?elseif($arItem["MORE"] == "Y"):?>
			<li class="noitem i_all">
				<h3><a href="<?echo $arItem["URL"]?>"> <?echo $arItem["NAME"];?></a></h3><div style="clear:both;"></div>
			</li>
		<?else:?>
			<li>

				<div class="li_line sim-img" id="<?=$itemid?>-photo">
					<?if(isset($arItem["PRODUCT_PICTURE_SRC"])):?>
						<img src="<?=$arItem['PRODUCT_PICTURE_SRC']?>" alt="" />
					<?endif;?>
				</div>
				<div class="li_line name">
					<h3><a href="<?echo $arItem["URL"]?>"> <?echo $arItem["NAME"];?></a></h3>
				</div>
				<?if(isset($arItem["PRICES"])):
					unset($price_disc);
					$price = str_replace(GetMessage('RUB_REPLACE'), '<span class="rubl">'.GetMessage('RUB').'</span>', $arItem["PRICES"]["PRICE"]);
					if($arItem["PRICES"]["DISCOUNT_PRICE"] !== $arItem["PRICES"]["PRICE"])
					{
						if(isset($arItem["PRICES"]["DISCOUNT_PRICE"]))
							$price_disc = str_replace(GetMessage('RUB_REPLACE'), '<span class="rubl">'.GetMessage('RUB').'</span>',  $arItem["PRICES"]["DISCOUNT_PRICE"]);
					}		
				?>
					<div class="li_line ys-st-buy">		
						<?if($arItem["FOR_ORDER"]["VALUE"] == "Y"):?>
							<div class="have for_order"><?=GetMessage("HAVE_ORDER")?></div>
						<?elseif($arItem["QUANTITY_TRACE"] == "Y" && $arItem["QUANTITY"] == 0 && $arItem["CAN_BUY_ZERO"] != "Y"):?>
							<div class="have not_available"><?=GetMessage("HAVE_NOTAVAIABLE")?></div>
						<?else:?>
							<a class="add2basket" id="<?=$itemid?>" <?if(CModule::IncludeModule('yenisite.bitroniclite')):?>href="<?=$_REQUEST["red_url"];?>?action=ADD2BASKET&id=<?=$arItem['ITEM_ID'];?>"<?endif;?>>
								<?=GetMessage("ADD2BASKET")?>
							</a>
						<?endif;?>
					</div>									
					<div class="li_line price">
						<?if(isset($price_disc)):?>
							<div class="discount">
								<?=$price_disc?>
							</div>
							<div class="not_discount">
								<?=$price?>
							</div>
						<?else:?>
							<?=$price;?>
						<?endif;?>
					</div>
				<?endif;?>
				<div style="clear:both;"></div>
			</li>
		<?endif;?>
	<?endforeach;?>
<?endforeach;?>
