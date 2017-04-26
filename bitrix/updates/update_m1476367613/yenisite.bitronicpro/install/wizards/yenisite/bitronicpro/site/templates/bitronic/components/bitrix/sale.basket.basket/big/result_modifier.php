<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/functions/yenisitegetimage.php');
$arParams['IMAGE'] = 'MORE_PHOTO';
$arParams['HEIGHT'] = '50';
$arParams['WIDTH'] = '50';
$arParams['BASKET_PHOTO'] = $arParams['BASKET_PHOTO'] ? intval($arParams['BASKET_PHOTO']) : 5;
$obCache = new CPHPCache; 
	
	foreach($arResult['ITEMS']["AnDelCanBuy"] as &$arItem)
	{
		if($arItem['CAN_BUY'] == 'Y')
		{
			if(CModule::IncludeModule("iblock")/*  && $arParams['IMAGE'] */)
			{
				$cache_id = 'yen-bigbasket'.$arItem['PRODUCT_ID']; 
				if($obCache->InitCache($life_time, $cache_id, "/")) 
				{
					$vars = $obCache->GetVars();
					$pathResizeImage = $vars["PRODUCT_PICTURE_SRC"];
				}
				else
				{
					$dbElement = CIBlockElement::GetByID($arItem['PRODUCT_ID']);
					if($arElement = $dbElement->GetNext())
					{
						// get parrent iblock for SKU
						$obCacheIBlock = new CPHPCache ;
						if($obCacheIBlock->InitCache($life_time, 'yen-bs-fly-ib1017'.$arElement['IBLOCK_ID'], "/")) 
						{
							$vars = $obCacheIBlock->GetVars();
							$arInfo = $vars['arInfo'] ;
						}
						else
						{
							if( CModule::IncludeModule("catalog") )
							{
								$arInfo = CCatalogSKU::GetInfoByOfferIBlock($arElement['IBLOCK_ID']) ;
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
							$dbProp = CIBlockElement::GetProperty($arElement['IBLOCK_ID'], $arElement['ID'], array("ID" => "ASC"), Array("ID"=>$arInfo['SKU_PROPERTY_ID']));
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
							$detail_page_url = $arItem['DETAIL_PAGE_URL'] ;
													
						// get image :
						if(!$image = yenisite_GetPicSrc ($arElement, $arParams['IMAGE']))
						{
							$image = yenisite_GetPicSrc ($arProduct, $arParams['IMAGE']) ;
						}
					}

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