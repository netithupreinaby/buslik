<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
	die();
}
// functions
if (!function_exists('yenisite_declOfNum')) {
	function yenisite_declOfNum($number, $titles)
	{
		$cases = array(2, 0, 1, 1, 1, 2);
		return $titles[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
	}
}
if (!function_exists('yenisite_GetFieldPicSrc')) {
	function yenisite_GetFieldPicSrc($arElementPicField)
	{
		
		if(is_array($arElementPicField))
		{
			return $arElementPicField['ID'] ;
		}
		elseif(intval($arElementPicField) > 0)
		{
			return intval($arElementPicField); //CFile::GetPath(intval($arElementPicField)) ;
		}
		
		return false;
	}
}
if (!function_exists('yenisite_GetPropPicSrc')) {
	function yenisite_GetPropPicSrc($arElement, $prop_code)
	{
		if(!is_array($arElement) || !$prop_code)
			return false;
		
		$arPropFile = false ;
			
		if(is_array($arElement['PROPERTIES'][$prop_code]))
		{
			$arPropFile = $arElement['PROPERTIES'][$prop_code]['VALUE'] ;
		}
		else
		{
			$dbProp = CIBlockElement::GetProperty($arElement['IBLOCK_ID'], $arElement['ID'], array("ID" => "ASC"), Array("CODE" => $prop_code));
			if($arProp = $dbProp->Fetch())
			{	
				$arPropFile = $arProp['VALUE'] ;
			}
		}
		if($arPropFile)
		{
			if(is_array($arPropFile))
			{
				return $arPropFile[0] ;
			}
			elseif(intval($arPropFile) > 0)
			{
				return $arPropFile ;
			}
			
			
			/* if(intval($pic_id) > 0)
			{
				return CFile::GetPath(intval($pic_id)) ;
			} */
		}
		return false;
	}
}
if (!function_exists('yenisite_GetPicSrc')) {
	function yenisite_GetPicSrc ($arElement, $arParamsImage, $default_image_code = 'MORE_PHOTO')
	{
		if(!is_array($arElement))
			return false;
			
		$picsrc = false; $find_in_prop = false;
		if($arParamsImage != 'PREVIEW_PICTURE' && $arParamsImage != 'DETAIL_PICTURE')
		{
			$find_in_prop = true ;
			if(!$picsrc = yenisite_GetPropPicSrc($arElement, $arParamsImage))
			{
				$picsrc = yenisite_GetPropPicSrc($arElement, $default_image_code) ;
			}
		}
		
		if($arParamsImage == 'DETAIL_PICTURE' || $arParamsImage == 'PREVIEW_PICTURE')
		{
			$picsrc = yenisite_GetFieldPicSrc ($arElement[$arParamsImage]) ;
		}	
			
		if(!$picsrc)
		{
			if(!$find_in_prop)
				$picsrc = yenisite_GetPropPicSrc($arElement, $default_image_code) ;
				
			if(!$picsrc && $arParamsImage != 'DETAIL_PICTURE')
				$picsrc = yenisite_GetFieldPicSrc($arElement['DETAIL_PICTURE']) ;
			
			if(!$picsrc && $arParamsImage != 'PREVIEW_PICTURE')
				$picsrc = yenisite_GetFieldPicSrc($arElement['PREVIEW_PICTURE']) ;
		}
		return $picsrc ;
	}
}

if ($arParams['IT_IS_AJAX_CALL'] != 'Y') {
	$curParamsSerialize = serialize($arParams);
// start modify by Ivan, 09.10.2013 ---->
	if(class_exists('CYSBitronicSettings'))
	{
		$module_id = CYSBitronicSettings::getModuleId() ;
	}
	else
	{
		$module_id = "yenisite.bitronic" ;
	}
	// $BasketSmallParams = COption::GetOptionString("yenisite.bitronic", "BasketSmallParams", '');
	$BasketSmallParams = COption::GetOptionString($module_id, "BasketSmallParams", '');
	if ($curParamsSerialize != $BasketSmallParams) {
		// COption::SetOptionString("yenisite.bitronic", "BasketSmallParams", $curParamsSerialize);
		COption::SetOptionString($module_id, "BasketSmallParams", $curParamsSerialize);
	}
	// <---- end modify by Ivan, 09.10.2013
}
global $APPLICATION;

if ($this->__folder) {
	$pathToTemplateFolder = $this->__folder;
} else {
	$pathToTemplateFolder = str_replace(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '', dirname(__FILE__));
}

$arColorSchemes = array('red', 'green', 'ice', 'metal', 'pink', 'yellow');

$bitronic_color_scheme = COption::GetOptionString('yenisite.market', 'color_scheme');

if ($arParams['COLOR_SCHEME'] && in_array($arParams['COLOR_SCHEME'], $arColorSchemes, true)) {
	$color_scheme = $arParams['COLOR_SCHEME'];
} elseif ($arParams['COLOR_SCHEME'] === "blue") {
	$color_scheme = 'ice';
} elseif (in_array($bitronic_color_scheme, $arColorScheme)) {
	$color_scheme = $bitronic_color_scheme;
} else {
	$color_scheme = 'red';
}

/* old or new fonts */
if ($arParams['NEW_FONTS'] == 'Y') {
	$APPLICATION->SetAdditionalCSS("{$pathToTemplateFolder}/new_fonts.css");
	$arResult['FONTS'] = "_NEW";
} else {
	$APPLICATION->SetAdditionalCSS("{$pathToTemplateFolder}/old_fonts.css");
	$arResult['FONTS'] = "";
}


$APPLICATION->SetAdditionalCSS("{$pathToTemplateFolder}/{$color_scheme}.css");

if (!$arParams['BASKET_ICON']) {
	$arParams['BASKET_ICON'] = "{$pathToTemplateFolder}/images/icon_{$color_scheme}.png";
}

if (!$arParams['PATH_TO_NO_PHOTO']) {
	$arParams['PATH_TO_NO_PHOTO'] = "{$pathToTemplateFolder}/images/no_photo.png";
}

if (!$arParams['IMAGE']) {
	$arParams['IMAGE'] = 'MORE_PHOTO';
}
if (!$arParams['IMAGE_WIDTH']) {
	$arParams['IMAGE_WIDTH'] = 50;
}
if (!$arParams['IMAGE_HEIGHT']) {
	$arParams['IMAGE_HEIGHT'] = 50;
}
if (!$arParams['RESIZER2_SET'] && CModule::IncludeModule('yenisite.resizer2')) {
	$dbSets = CResizer2Set::GetList();
	while ($arSet = $dbSets->Fetch()) {
		if ($arSet['h'] == 50 && $arSet['w'] == 50) {
			$defualt_set_id = $arSet['id'];
		}
	}
	$arParams['RESIZER2_SET'] = $defualt_set_id ? $defualt_set_id : 5;
}
if (!$arParams['START_FLY_PX']) {
	$arParams['START_FLY_PX'] = 100;
}
if ($arParams['INCLUDE_JQUERY'] == 'Y') {
	CJSCore::Init(array("jquery"));
}

if ($arParams['INCLUDE_JGROWL'] == 'Y') {
	$APPLICATION->AddHeadScript("{$pathToTemplateFolder}/jquery.jgrowl_minimized.js");
}

if ($arParams['VIEW_PROPERTIES'] != 'Y') {
	$arParams['VIEW_PROPERTIES'] = 'N';
}
if (!$arParams['MARGIN_TOP']) {
	$arParams['MARGIN_TOP'] = 10;
}
if (!$arParams['MARGIN_TOP_FLY_PX']) {
	$arParams['MARGIN_TOP_FLY_PX'] = $arParams['MARGIN_TOP'];
}

//$APPLICATION->SetAdditionalCSS("{$pathToTemplateFolder}/style.php?pos={$arParams['BASKET_POSITION']}&amp;top={$arParams['MARGIN_TOP']}&amp;side={$arParams['MARGIN_SIDE']}&amp;fly_top={$arParams['MARGIN_TOP_FLY_PX']}&amp;v=110&amp;cs={$bitronic_color_scheme}&amp;ok");
$APPLICATION->AddHeadString('<link href="' . "{$pathToTemplateFolder}/style.php?pos={$arParams['BASKET_POSITION']}&amp;top={$arParams['MARGIN_TOP']}&amp;side={$arParams['MARGIN_SIDE']}&amp;fly_top={$arParams['MARGIN_TOP_FLY_PX']}&amp;v=110&amp;cs={$bitronic_color_scheme}&amp;ok" . '"  type="text/css" rel="stylesheet" />', true);

if (!$arParams['CURRENCY'] || $arParams['CURRENCY'] == 'ROUBLE_SYMBOL') {
	$arResult['CURRENCY'] = GetMessage('YS_BS_CURRENCY');
} else {
	$arResult['CURRENCY'] = $arParams['CURRENCY'];
}

$arResult['NUM_PRODUCTS'] = 0;
$arResult['allSum'] = 0;
$arResult['allSum_FORMAT'] = 0;

if ($arResult["READY"] == "Y") {
	$obCache = new CPHPCache;

	$life_time = $arParams['CACHE_PIC_TIME'] ? IntVal($arParams['CACHE_PIC_TIME']) : 2419200;

	if (is_array($arResult['ITEMS'][0])) {
		$cache_id = 'yen-bs-fly' . $arResult['ITEMS'][0]['CURRENCY'];
		if ($obCache->InitCache($life_time, $cache_id, "/")) {
			$vars = $obCache->GetVars();
			$currency = $vars["CURRENCY"];
		} else {
			$arCurFormat = CCurrencyLang::GetCurrencyFormat($arResult['ITEMS'][0]['CURRENCY']);
			$currency = str_replace('#', '', $arCurFormat['FORMAT_STRING']);
			unset($arCurFormat);
		}
		if ($obCache->StartDataCache()) {
			$obCache->EndDataCache(
				array(
					"CURRENCY" => $currency
				)
			);
		}
	}

	unset($obCache);

	$obCache = new CPHPCache;

	foreach ($arResult['ITEMS'] as &$arItem) {
		if ($arItem['CAN_BUY'] == 'Y') {
			if ($arParams['QUANTITY_LOGIC'] == 'q_products') {
				$arResult['NUM_PRODUCTS'] += $arItem['QUANTITY'];
			} else {
				$arResult['NUM_PRODUCTS']++;
			}
			
			if ($arParams['ALLOW_FLOAT_Q'] == 'Y'){
				$arItem['QUANTITY'] = number_format($arItem["QUANTITY"], 2, '.', '');
			}
			else{
				$arItem['QUANTITY'] = number_format($arItem["QUANTITY"], 0, ',', '');
			}
			
			$arResult['allSum'] += $arItem['PRICE'] * $arItem['QUANTITY'];
			
			$arItem['YS_PRICE_FORMATED'] = ($currency) ? str_replace($currency, '', $arItem["PRICE_FORMATED"]) : $arItem["PRICE_FORMATED"];

			if (CModule::IncludeModule("iblock") /*  && $arParams['IMAGE'] */) {
				$cache_id = 'yen-bs-fly1026' . $arParams['VIEW_PROPERTIES'] . $arItem['PRODUCT_ID'] . $arItem['ID'];
				if ($obCache->InitCache(86400, $cache_id, "/")) {
					$vars = $obCache->GetVars();
					$arItem['PROPS'] = $vars["AR_PROPERTIES"];
				}
				else {
					if ($arParams['VIEW_PROPERTIES'] == 'Y' && CModule::IncludeModule("sale")) {
						$dbProps = CSaleBasket::GetPropsList(array(), array("BASKET_ID" => $arItem['ID']));
						while ($arProp = $dbProps->Fetch()) {
							if ($arProp['CODE'] != 'CATALOG.XML_ID' && $arProp['CODE'] != 'PRODUCT.XML_ID') {
								$arItem['PROPS'][] = $arProp;
							}
						}
					}
				}
				if ($obCache->StartDataCache()):
					$obCache->EndDataCache(
						array(
							"AR_PROPERTIES"       => $arItem['PROPS'],
						)
					);
				endif;
				
				$cache_id = 'yen-bs-fly1040' . $arParams['VIEW_PROPERTIES'] . $arItem['PRODUCT_ID'];
				
				if ($obCache->InitCache($life_time, $cache_id, "/")) {
					$vars = $obCache->GetVars();
					$pathResizeImage = $vars["PRODUCT_PICTURE_SRC"];
					$detail_page_url = $vars["DETAIL_PAGE_URL"];
					$arItem['CODE'] = $vars["CODE"];
				} 
				else {
					if (CModule::IncludeModule("catalog")) {
						$res = CIBlockElement::GetByID(IntVal($arItem['PRODUCT_ID']));
						if($ar_res = $res->GetNext())
							$arItem['CODE'] = $ar_res['CODE'];
					}
					
					$dbElement = CIBlockElement::GetByID($arItem['PRODUCT_ID']);
					if ($arElement = $dbElement->GetNext()) 
					{
						// get parrent iblock for SKU
						$obCacheIBlock = new CPHPCache;
						if ($obCacheIBlock->InitCache($life_time, 'yen-bs-fly-ib1025' . $arElement['IBLOCK_ID'], "/")) {
							$vars = $obCacheIBlock->GetVars();
							$arInfo = $vars['arInfo'];
						} else {
							if (CModule::IncludeModule("catalog")) {
								$arInfo = CCatalogSKU::GetInfoByOfferIBlock($arElement['IBLOCK_ID']);
							}
						}
						if ($obCacheIBlock->StartDataCache()) {
							$obCacheIBlock->EndDataCache(
								array(
									"arInfo" => $arInfo,
								)
							);
						}
						unset($obCacheIBlock);
						// link on parent product for SKU
						if ($arInfo['SKU_PROPERTY_ID']) {
							$dbProp = CIBlockElement::GetProperty($arElement['IBLOCK_ID'], $arElement['ID'], array("ID" => "ASC"), Array("ID" => $arInfo['SKU_PROPERTY_ID']));
							if ($arProp = $dbProp->Fetch()) {
								if ($arProp['VALUE']) {
									$dbProduct = CIBlockElement::GetByID($arProp['VALUE']);
									$arProduct = $dbProduct->GetNext();
									if ($arProduct) $arItem['CODE'] = $arProduct['CODE'];
								}
							}
						}
						if ($arInfo['SKU_PROPERTY_ID'] && $arProduct['DETAIL_PAGE_URL']) {
							$detail_page_url = $arProduct['DETAIL_PAGE_URL'];
						} 
						else {
							$detail_page_url = $arItem['DETAIL_PAGE_URL'];
						}
						
						// get image :
						if(!$image = yenisite_GetPicSrc ($arElement, $arParams['IMAGE']))
						{
							$image = yenisite_GetPicSrc ($arProduct, $arParams['IMAGE']) ;
						}
					}

					if (CModule::IncludeModule('yenisite.resizer2')) {
						$image_src = CFile::GetPath($image);
						$pathResizeImage = CResizer2Resize::ResizeGD2($image_src, $arParams['RESIZER2_SET']);
					}
					elseif($image)
					{
						$ResizeParams = array('width' => $arParams['IMAGE_WIDTH'], 'height' => $arParams['IMAGE_HEIGHT']);
						$ResizeImage = CFile::ResizeImageGet($image, $ResizeParams, BX_RESIZE_IMAGE_PROPORTIONAL, true);
						$pathResizeImage = $ResizeImage['src'];
					}
				}
				if ($obCache->StartDataCache()):
					$obCache->EndDataCache(
						array(
							"PRODUCT_PICTURE_SRC" => $pathResizeImage,
							"DETAIL_PAGE_URL"     => $detail_page_url,
							"CODE"       => $arItem['CODE'],
						)
					);
				endif;

				$arItem['DETAIL_PAGE_URL'] = $detail_page_url;
				$arItem['PRODUCT_PICTURE_SRC'] = $pathResizeImage;
				
				
				
				////SEF 
			
			
				if(class_exists('CYSBitronicSettings'))
					$module_id = CYSBitronicSettings::getModuleId() ;
				else
					$module_id = "yenisite.bitronic" ;
				
				if (CModule::IncludeModule($module_id)) 
				{
					$sef = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "sef_mode", false, SITE_ID);
				
					if($sef == "Y") {
					
						if(empty($arItem['CODE']))
						{
							if (CModule::IncludeModule("catalog")) {
								$res = CIBlockElement::GetByID(IntVal($arItem['PRODUCT_ID']));
								if($ar_res = $res->GetNext())
									$arItem['CODE'] = $ar_res['CODE'];
							}
						}
					
						preg_match('/SECTION_ID=(\d+)&/', $arItem["DETAIL_PAGE_URL"], $match);

						if (is_set($match[1])) {
							$obCache = new CPHPCache();
							$life_time =  2592000;
							$cache_id = "ys-section-url";

							if ($obCache->InitCache($life_time, $cache_id, 'ys-cache')) {
								$vars = $obCache->GetVars();
								$arSecs = $vars['SECS'];
							}

							if (empty($arSecs[$match[1]])) {
								$arSecPage = CIBlockSection::GetByID($match[1])->GetNext();

								$arSecs[$match[1]] = $arSecPage['SECTION_PAGE_URL'];

								if ($obCache->StartDataCache($life_time, $cache_id, "/")) {
									$obCache->EndDataCache(array("SECS" => $arSecs));
								}
							}
							$arItem["DETAIL_PAGE_URL"] = $arSecs[$match[1]] . $arItem["CODE"] . '.html';
							unset($obCache);
						} else {
							$arTmp = explode('/', $arItem["DETAIL_PAGE_URL"]);
							$arTmp = array_slice($arTmp, 0, 3);
							$str = implode('/', $arTmp);
							$str .= '/';
							$arItem["DETAIL_PAGE_URL"] = $str . $arItem["CODE"] . '.html';
						}
					}
				}				
				
				
				
			}
		}
	}

	unset($obCache);
	$arResult['NUM_PRODUCTS'] = number_format($arResult['NUM_PRODUCTS'], 0, ',', '');
	$arResult['allSum_FORMAT'] = CurrencyFormat($arResult['allSum'], $arResult['ITEMS'][0]['CURRENCY']);
	if ($currency) {
		$arResult['allSum_FORMAT'] = str_replace($currency, '', $arResult['allSum_FORMAT']);
	}
}

// js:
$url = "{$pathToTemplateFolder}/ys_basket_tools.php";
$bitronic = $bitronic_color_scheme;
?>

<script type="text/javascript">
	<?if($bitronic):?>
	var yen_bs_position;
	var yen_bs_offset;
	var yen_bs_to_basket = '<?=GetMessage('YS_BS_TO_BASKET_BUTTON');?>';
	<?endif;?>
	
	
	
	 function ys_ajax_bs_buttons() {
		<?if($bitronic):?>
		yen_bs_offset = $('.yen-bs-box').offset();
		yen_bs_position = $('.yen-bs-box').position();

		$('.yen-bs-eid').each(function () {
			var element_id = $(this).attr('value');
			if ($('#b-' + element_id).length && !$('#b-' + element_id).hasClass('button_in_basket')) {
				if ($('#b-' + element_id).hasClass('ajax_add2basket_q')) {
					$('#b-' + element_id).addClass('button_in_basket');
				}
				else if(!$('#b-' + element_id).hasClass('ajax_add2basket_prop')) {
					$('#b-' + element_id).addClass('button_in_basket');
					$('#b-' + element_id + ' span').addClass('ajax_h');
					if ($('#b-' + element_id).hasClass('button1')) {
						$('#b-' + element_id + ' span').html('<?=GetMessage('YS_BS_IN_BASKET_BUTTON_SHORT');?>');
					}
					else {
						$('#b-' + element_id + ' span').html('<?=GetMessage('YS_BS_IN_BASKET_BUTTON');?>');
					}
				}
			}
			else {

				if ($('.ajax_a2b_' + element_id).length) {
					$('.ajax_a2b_' + element_id).addClass('button_in_basket');
					if ($('.ajax_a2b_' + element_id).hasClass('button1')) {
						$('.ajax_a2b_' + element_id + ' span').html('<?=GetMessage('YS_BS_IN_BASKET_BUTTON_SHORT');?>');
					}
					else {
						$('.ajax_a2b_' + element_id + ' span').html('<?=GetMessage('YS_BS_IN_BASKET_BUTTON');?>');
					}
				}
			}
		});
		<?endif;?>
	};
	
	$(document).ready(ys_ajax_bs_buttons);
	$(document).ajaxComplete(ys_ajax_bs_buttons);
	BX.addCustomEvent("onFrameDataReceived", ys_ajax_bs_buttons);
	
	
	$(function(){
		<?if($bitronic):?>
		yen_bs_offset = $('.yen-bs-box').offset();
		yen_bs_position = $('.yen-bs-box').position();
		$(window).resize(function () {
			var scrollBasket = false;
			if ($('.yen-bs-box').hasClass('yen-bs-scrollBasket')) {
				scrollBasket = true;
				$('.yen-bs-box').removeClass('yen-bs-scrollBasket').css({'left':'auto' , 'position':'absolute'}).offset().left
			}
			yen_bs_offset = $('.yen-bs-box').offset();
			yen_bs_position = $('.yen-bs-box').position();
			if (scrollBasket)
				$('.yen-bs-box').addClass('yen-bs-scrollBasket');
			$(window).trigger('scroll');
		});
		<?endif;?>

		

		$(window).scroll(function () {
			if ($(window).scrollTop() >= <?=$arParams['START_FLY_PX'];?>) {
				$('.yen-bs-box').addClass('yen-bs-scrollBasket');
				<?if($bitronic):?>
				$('.yen-bs-box').css('left', yen_bs_offset.left);
				<?endif;?>
			}
			else {
				$('.yen-bs-box').removeClass('yen-bs-scrollBasket');
				<?if($bitronic):?>
				$('.yen-bs-box').css({'left':'auto' , 'position':'absolute'});
				<?endif;?>
			}
		});	
	
	});
	
	
	function yen_setQuantity(id, sign, pr_id) {
		if (!isNaN(id) && sign) {
			var url = '<?=$url;?>';
			var titles = ['<?=GetMessage('YS_BS_PRODUCT1');?>', '<?=GetMessage('YS_BS_PRODUCT2');?>', '<?=GetMessage('YS_BS_PRODUCT0');?>'];
			var control_quantity = '<?=$arParams['CONTROL_QUANTITY'];?>';
			var quantity_logic = '<?=$arParams['QUANTITY_LOGIC'];?>';
			var allow_float_q = '<?=$arParams['ALLOW_FLOAT_Q'] == 'Y' ? 1 : 0;?>';
			yenisite_set_quantity(id, sign, url, titles, control_quantity, pr_id, quantity_logic, '<?=GetMessage('YS_BS_NO_QUANTITY');?>', allow_float_q);
		}
	}
</script>