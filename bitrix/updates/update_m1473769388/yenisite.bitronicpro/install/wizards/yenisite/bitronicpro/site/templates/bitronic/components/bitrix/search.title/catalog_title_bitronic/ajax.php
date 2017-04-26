<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
$arParams['BASKET_PHOTO'] = $arParams['BASKET_PHOTO'] ? intval($arParams['BASKET_PHOTO']) : 5 ;

if(!empty($arResult["CATEGORIES"])):?>
<? // ----- find photo ---------?>
						<?
						$arParams['IMAGE'] = 'MORE_PHOTO';
						$arParams['HEIGHT'] = '50';
						$arParams['WIDTH'] = '50';
						$obCache = new CPHPCache; 
						foreach($arResult["CATEGORIES"] as $category_id => $arCategory){
							foreach($arCategory["ITEMS"] as &$arItem)
							{

									if(CModule::IncludeModule("iblock")/*  && $arParams['IMAGE'] */)
									{
										$cache_id = 'yen-ajax-search-1'.$arItem['ITEM_ID']; 
										if($obCache->InitCache($life_time, $cache_id, "/")) 
										{
											$vars = $obCache->GetVars();
											$pathResizeImage = $vars["PRODUCT_PICTURE_SRC"];
										}
										else
										{
											//$dbElement = CIBlockElement::GetByID($arItem['ITEM_ID']);
											//if($arElement = $dbElement->GetNext())
											//{
												// get parrent iblock for SKU
												$obCacheIBlock = new CPHPCache ;
												if($obCacheIBlock->InitCache($life_time, 'yen-ajax-search-sku-1'.$arItem['PARAM2'], "/")) 
												{
													$vars = $obCacheIBlock->GetVars();
													$arInfo = $vars['arInfo'] ;
												}
												else
												{
													if( CModule::IncludeModule("catalog") )
													{
														$arInfo = CCatalogSKU::GetInfoByOfferIBlock($arItem['PARAM2']) ;
													}
												}
												if($obCacheIBlock->StartDataCache())
												{
													$obCacheIBlock->EndDataCache(array(
														"arInfo"    => $arInfo,
														)); 
												}
												unset($obCacheIBlock) ;
												// link on parent product for SKU
												if($arInfo['SKU_PROPERTY_ID'])
												{
													$dbProp = CIBlockElement::GetProperty($arItem['PARAM2'], $arItem['ITEM_ID'], array("ID" => "ASC"), Array("ID"=>$arInfo['SKU_PROPERTY_ID']));
													if($arProp = $dbProp -> Fetch())
													{
														if($arProp['VALUE'])
														{
															$dbProduct = CIBlockElement::GetByID($arProp['VALUE']) ;
															$arProduct = $dbProduct->GetNext() ;
														}
													}
												}
												//echo '$arProduct[DETAIL_PAGE_URL] = '.$arProduct['DETAIL_PAGE_URL'] ;
												if($arInfo['SKU_PROPERTY_ID'] && $arProduct['DETAIL_PAGE_URL'])
													$detail_page_url = $arProduct['DETAIL_PAGE_URL'];
												else
													$detail_page_url = $arItem['URL'] ;
																			
												if($arParams['IMAGE'] == 'DETAIL_PICTURE' || $arParams['IMAGE'] == 'PREVIEW_PICTURE')
												{	
													$dbElement = CIBlockElement::GetByID($arItem['ITEM_ID']);
													if($arElement = $dbElement->GetNext())
														$image = $arElement[$arParams['IMAGE']];
												}
												else
												{							
													$dbProp = CIBlockElement::GetProperty($arItem['PARAM2'], $arItem['ITEM_ID'], array("ID" => "ASC"), Array("CODE"=>$arParams['IMAGE']));
													if($arProp = $dbProp->GetNext())
														$image = $arProp['VALUE']; 
												}
												
												if(!$image)
												{
													if($arInfo['SKU_PROPERTY_ID'] && $arInfo['PRODUCT_IBLOCK_ID'])
													{
														if($arParams['IMAGE'] == 'DETAIL_PICTURE' || $arParams['IMAGE'] == 'PREVIEW_PICTURE')
														{
															$image = $arProduct[$arParams['IMAGE']];
														}
														else
														{							
															$dbProp = CIBlockElement::GetProperty($arProduct['IBLOCK_ID'], $arProduct['ID'], array("ID" => "ASC"), Array("CODE"=>$arParams['IMAGE']));
															if($arProp = $dbProp->GetNext())
																$image = $arProp['VALUE']; 
														}
													}
												}
												if(!$image)
												{
													$dbProp = CIBlockElement::GetProperty($arItem['PARAM2'], $arItem['ITEM_ID'], array("ID" => "ASC"), Array("CODE"=>'MORE_PHOTO'));
													
													if($arProp = $dbProp->GetNext())
														$image = $arProp['VALUE']; 
													else
													{
														if(!is_array($arElement))
														{
															$dbElement = CIBlockElement::GetByID($arItem['ITEM_ID']);
															$arElement = $dbElement->GetNext();
														}
														$image = $arElement['PREVIEW_PICTURE'] ? $arElement['PREVIEW_PICTURE'] : $arElement['DETAIL_PICTURE'] ;
													}
												}
											//}

											if(CModule::IncludeModule('yenisite.resizer2'))
											{
												$pathImage = CFile::GetPath($image);
												$pathResizeImage = CResizer2Resize::ResizeGD2($pathImage, $arParams['BASKET_PHOTO']);
											}
											else
											{
												$ResizeParams = array('width' => $arParams['IMAGE_WIDTH'], 'height' => $arParams['IMAGE_HEIGHT']);
												$ResizeImage = CFile::ResizeImageGet($image, $ResizeParams,  BX_RESIZE_IMAGE_PROPORTIONAL, true);
												
												$pathResizeImage = $ResizeImage['src'] ;
											}
										}
										if($obCache->StartDataCache()):
											$obCache->EndDataCache(array(
												"PRODUCT_PICTURE_SRC"    => $pathResizeImage,
												)); 
										endif;
										
										$arItem['DETAIL_PAGE_URL'] = $detail_page_url ;
										$arItem['PRODUCT_PICTURE_SRC'] = $pathResizeImage ;
									}
							}
						}
							unset($obCache);
						?>
						<? // ----- end find photo -----?>
	<ul class="similar_items" style="display: block;">
		<?foreach($arResult["CATEGORIES"] as $category_id => $arCategory):?>
			
			<?foreach($arCategory["ITEMS"] as $i => $arItem):?>
			<?
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
				
				 if($arItem["PRICES"]){				    
					$price = implode(", ", $arItem["PRICES"]);
					if($price) $price = "&nbsp;".$price; 
				}
			?>
			
			<li>
				<div>
				<div class="price">
				
				<?if($category_id === "all"):?>
					<td class="title-search-all"><span class="price2"></span></td>
				<?elseif(isset($arItem["ICON"])):?>
				<?$price = str_replace(GetMessage('RUB_REPLACE'), '<span class="rubl">'.GetMessage('RUB').'</span>',  $price);?>
					<td class="title-search-item"><span class="price2"><?=$price;?></span></td>
				<?endif;?>
				</div>
				
				<div class="sim-img">
					<?if($category_id === "all"):?>
					<th align="center"></th>
				<?elseif(isset($arItem["ICON"])):?>
					<th align="center">
						<img src="<?=$arItem['PRODUCT_PICTURE_SRC']?>" alt="" /></th>
					<?endif;?>
				</div>
				
				<div>
				<h3><a href="<?echo $arItem["DETAIL_PAGE_URL"];?>"> <?echo $arItem["NAME"];?></a></h3>
			</li>
			
			<?endforeach;?>
		<?endforeach;?>
		</ul>
<?endif?>