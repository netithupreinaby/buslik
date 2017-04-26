<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
// functions
if(!function_exists('yenisite_declOfNum'))
{
	function yenisite_declOfNum($number, $titles)
	{
		$cases = array (2, 0, 1, 1, 1, 2);
		return $titles[ ($number%100>4 && $number%100<20)? 2 : $cases[min($number%10, 5)] ];
	}
}
include_once('mod_for_market.php');
global $APPLICATION;
$arResult["READY"] = "Y";
if($this->__folder)
	$pathToTemplateFolder = $this->__folder ;
else
	$pathToTemplateFolder = str_replace(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '', dirname(__FILE__));

$arColorSchemes = array ('red', 'green', 'ice', 'metal', 'pink', 'yellow') ;

$bitronic_color_scheme = COption::GetOptionString('yenisite.market', 'color_scheme') ;

if($arParams['COLOR_SCHEME'] && in_array($arParams['COLOR_SCHEME'], $arColorSchemes, true))
	$color_scheme = $arParams['COLOR_SCHEME'] ;
elseif($arParams['COLOR_SCHEME'] === "blue")
	$color_scheme = 'ice' ;
elseif(in_array($bitronic_color_scheme, $arColorSchemes))
	$color_scheme = $bitronic_color_scheme ;
else
	$color_scheme = 'red' ;

$APPLICATION->SetAdditionalCSS("{$pathToTemplateFolder}/{$color_scheme}.css");

if(!$arParams['BASKET_ICON'])
	$arParams['BASKET_ICON'] = "{$pathToTemplateFolder}/images/icon_{$color_scheme}.png" ;
	
if(!$arParams['PATH_TO_NO_PHOTO'])
	$arParams['PATH_TO_NO_PHOTO'] = "{$pathToTemplateFolder}/images/no_photo.png" ;
	
if(!$arParams['IMAGE'])
	$arParams['IMAGE'] =  'MORE_PHOTO' ;
if(!$arParams['IMAGE_WIDTH'])
	$arParams['IMAGE_WIDTH'] = 50;
if(!$arParams['IMAGE_HEIGHT'])
	$arParams['IMAGE_HEIGHT'] = 50;
	
if(!$arParams['RESIZER2_SET'] && CModule::IncludeModule('yenisite.resizer2'))
{
	$dbSets = CResizer2Set::GetList();
	while($arSet = $dbSets->Fetch())
	{
		if($arSet['h'] == 50 && $arSet['w'] == 50)
			$defualt_set_id = $arSet['id'] ;
	}
	$arParams['RESIZER2_SET'] = $defualt_set_id ? $defualt_set_id : 5;
}
if(!$arParams['START_FLY_PX'])
	$arParams['START_FLY_PX'] = 100; 
if($arParams['INCLUDE_JQUERY'] == 'Y')
	CJSCore::Init(array("jquery"));

if($arParams['INCLUDE_JGROWL'] == 'Y')
	$APPLICATION->AddHeadScript("{$pathToTemplateFolder}/jquery.jgrowl_minimized.js");

if($arParams['VIEW_PROPERTIES'] != 'Y')
	$arParams['VIEW_PROPERTIES'] = 'N' ;
if(!$arParams['MARGIN_TOP'])
	$arParams['MARGIN_TOP'] = 10;
if(!$arParams['MARGIN_TOP_FLY_PX'])
	$arParams['MARGIN_TOP_FLY_PX'] = $arParams['MARGIN_TOP'];

//$APPLICATION->SetAdditionalCSS("{$pathToTemplateFolder}/style.php?pos={$arParams['BASKET_POSITION']}&amp;top={$arParams['MARGIN_TOP']}&amp;side={$arParams['MARGIN_SIDE']}&amp;fly_top={$arParams['MARGIN_TOP_FLY_PX']}&amp;v=110&amp;cs={$bitronic_color_scheme}&amp;ok");
$APPLICATION->AddHeadString('<link href="'."{$pathToTemplateFolder}/style.php?pos={$arParams['BASKET_POSITION']}&amp;top={$arParams['MARGIN_TOP']}&amp;side={$arParams['MARGIN_SIDE']}&amp;fly_top={$arParams['MARGIN_TOP_FLY_PX']}&amp;v=110&amp;cs={$bitronic_color_scheme}&amp;ok".'";  type="text/css" rel="stylesheet" />',true);

if(!$arParams['CURRENCY'] || $arParams['CURRENCY'] == 'ROUBLE_SYMBOL')
	$arResult['CURRENCY'] = GetMessage('YS_BS_CURRENCY');
else
	$arResult['CURRENCY'] = $arParams['CURRENCY']; 
	
$arResult['NUM_PRODUCTS'] = 0;
$arResult['allSum'] = 0;
$arResult['allSum_FORMAT'] = 0 ;

if($arResult["READY"] == 'Y')
{
	$obCache = new CPHPCache; 
	
	$life_time = $arParams['CACHE_PIC_TIME'] ? IntVal($arParams['CACHE_PIC_TIME']) : 2419200;

	if(CModule::IncludeModule("currency") && is_array($arResult['ITEMS'][0]))
	{	
		$cache_id = 'yen-bs-fly'.$arResult['ITEMS'][0]['CURRENCY']; 
		if($obCache->InitCache($life_time, $cache_id, "/")) 
		{
			$vars = $obCache->GetVars();
			$currency = $vars["CURRENCY"];
		}
		else
		{
			$arCurFormat = CCurrencyLang::GetCurrencyFormat($arResult['ITEMS'][0]['CURRENCY']);
			$currency = str_replace('#', '', $arCurFormat['FORMAT_STRING']); 
			unset($arCurFormat);
		}
		if($obCache->StartDataCache())
		{
			$obCache->EndDataCache(array(
				"CURRENCY"    => $currency
				)); 
		}
	}
	
	unset($obCache);
	
	$obCache = new CPHPCache;
if(is_array($arResult['ITEMS']))
{
foreach($arResult['ITEMS'] as &$arItem)
	{
		if($arItem['CAN_BUY'] == 'Y')
		{
			if($arParams['QUANTITY_LOGIC'] == 'q_products')
				$arResult['NUM_PRODUCTS']  += $arItem['QUANTITY'] ;
			else
				$arResult['NUM_PRODUCTS']  ++ ;
				
			$arItem['QUANTITY'] = number_format($arItem["QUANTITY"], 0, ',', '');
			$arResult['allSum'] += $arItem['PRICE'] * $arItem['QUANTITY'] ;
			if($currency)
				$arItem['YS_PRICE_FORMATED'] = str_replace($currency, '',  $arItem["PRICE_FORMATED"]);

			if(CModule::IncludeModule("iblock")/*  && $arParams['IMAGE'] */)
			{
				$cache_id = 'yen-bs-fly1017'.$arParams['VIEW_PROPERTIES'].$arItem['PRODUCT_ID']; 
				if($obCache->InitCache($life_time, $cache_id, "/")) 
				{
					$vars = $obCache->GetVars();
					$pathResizeImage = $vars["PRODUCT_PICTURE_SRC"];
					$detail_page_url = $vars["DETAIL_PAGE_URL"];
					$arItem['PROPS'] = $vars["AR_PROPERTIES"];
					$name = $vars['NAME'];
				}
				else
				{
					if ($arParams['VIEW_PROPERTIES'] == 'Y' && CModule::IncludeModule("sale"))
					{
						$dbProps = CSaleBasket::GetPropsList(array(), array("BASKET_ID" => $arItem['ID']));
						while ($arProp = $dbProps->Fetch())
							if ($arProp['CODE'] != 'CATALOG.XML_ID' && $arProp['CODE'] != 'PRODUCT.XML_ID')
								$arItem['PROPS'][] = $arProp;
					}
					
					$dbElement = CIBlockElement::GetByID($arItem['PRODUCT_ID']);
					
					if($arElement = $dbElement->GetNext())
					{
						$name = $arElement['NAME'] ;
						$arItem['DETAIL_PAGE_URL'] = $arElement['DETAIL_PAGE_URL'] ;
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
							$detail_page_url = $arElement['DETAIL_PAGE_URL'] ;
													
						if($arParams['IMAGE'] == 'DETAIL_PICTURE' || $arParams['IMAGE'] == 'PREVIEW_PICTURE')
							$image = $arElement[$arParams['IMAGE']];
						else
						{							
							$dbProp = CIBlockElement::GetProperty($arElement['IBLOCK_ID'], $arItem['PRODUCT_ID'], array("ID" => "ASC"), Array("CODE"=>$arParams['IMAGE']));
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
							$dbProp = CIBlockElement::GetProperty($arElement['IBLOCK_ID'], $arItem['PRODUCT_ID'], array("ID" => "ASC"), Array("CODE"=>'MORE_PHOTO'));
							
							if($arProp = $dbProp->GetNext())
								$image = $arProp['VALUE']; 
							else
								$image = $arElement['PREVIEW_PICTURE'] ? $arElement['PREVIEW_PICTURE'] : $arElement['DETAIL_PICTURE'] ;
						}
					}
					
					if(!$image)
					{
						$image = $arItem['FIELDS']['PREVIEW_PICTURE'];
					}

					if(CModule::IncludeModule('yenisite.resizer2'))
					{
						$pathImage = CFile::GetPath($image);
						$pathResizeImage = CResizer2Resize::ResizeGD2($pathImage, $arParams['RESIZER2_SET']);
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
						"DETAIL_PAGE_URL" => $detail_page_url,
						"AR_PROPERTIES" => $arItem['PROPS'],
						"NAME" => $name,
						)); 
				endif;
				$arItem['NAME'] = $name ;
				$arItem['DETAIL_PAGE_URL'] = $detail_page_url ;
				$arItem['PRODUCT_PICTURE_SRC'] = $pathResizeImage ;
			}
		}
	}
}
	unset($obCache);
	$arResult['NUM_PRODUCTS'] = number_format($arResult['NUM_PRODUCTS'], 0, ',', '');
	if(CModule::IncludeModule("currency"))
		$arResult['allSum_FORMAT'] = CurrencyFormat($arResult['allSum'], $arResult['ITEMS'][0]['CURRENCY']);
	else
		$arResult['allSum_FORMAT'] = number_format($arResult['allSum'], 2, '.', ' ');
		
	if($currency)
		$arResult['allSum_FORMAT'] = str_replace($currency, '',  $arResult['allSum_FORMAT']);
}	
// js:
$url = "{$pathToTemplateFolder}/ys_basket_tools.php";?>
<script type="text/javascript">
<?if($bitronic_color_scheme):?>
var yen_bs_position;
var yen_bs_offset;
var yen_bs_to_basket = '<?=GetMessage('YS_BS_TO_BASKET_BUTTON');?>';

function ys_ajax_bs_buttons() {
	yen_bs_offset = $('.yen-bs-box').offset();
	yen_bs_position = $('.yen-bs-box').position();

	$('.yen-bs-eid').each(function () {
		var element_id = $(this).attr('value');
		var $b = $('#b-' + element_id);
		if ($b.length && !$b.hasClass('button_in_basket')) {
			if ($b.hasClass('ajax_add2basket_q')) {
				$b.addClass('button_in_basket');
			}
			else if(!$b.hasClass('ajax_add2basket_prop')) {
				$b.addClass('button_in_basket');
				var $bSpan = $('#b-' + element_id + ' span');
				$bSpan.addClass('ajax_h');
				if ($b.hasClass('button1')) {
					$bSpan.html('<?=GetMessage('YS_BS_IN_BASKET_BUTTON_SHORT');?>');
				}
				else {
					$bSpan.html('<?=GetMessage('YS_BS_IN_BASKET_BUTTON');?>');
				}
			}
		}
		else {
			var $a2b = $('.ajax_a2b_' + element_id);
			if ($a2b.length) {
				$a2b.addClass('button_in_basket');
				if ($a2b.hasClass('button1')) {
					$('.ajax_a2b_' + element_id + ' span').html('<?=GetMessage('YS_BS_IN_BASKET_BUTTON_SHORT');?>');
				}
				else {
					$('.ajax_a2b_' + element_id + ' span').html('<?=GetMessage('YS_BS_IN_BASKET_BUTTON');?>');
				}
			}
		}
	});
};
	
$(document).ready(ys_ajax_bs_buttons);
$(document).ajaxComplete(ys_ajax_bs_buttons);
BX.addCustomEvent("onFrameDataReceived", ys_ajax_bs_buttons);

$(document).ready(function(){
	yen_bs_offset = $('.yen-bs-box').offset();
	yen_bs_position = $('.yen-bs-box').position();
});
$(window).resize(function(){
	$('.yen-bs-box').removeClass('yen-bs-scrollBasket');
	$('.yen-bs-box').css('left','');
	yen_bs_offset = $('.yen-bs-box').offset();
	yen_bs_position = $('.yen-bs-box').position();
	if($(window).scrollTop() >= <?=$arParams['START_FLY_PX'];?>){
		$('.yen-bs-box').addClass('yen-bs-scrollBasket');
	}
});
<?endif;?>
$(window).scroll(function(){
	if($(window).scrollTop() >= <?=$arParams['START_FLY_PX'];?>){
		$('.yen-bs-box').addClass('yen-bs-scrollBasket');
		<?if($bitronic_color_scheme):?>
			$('.yen-bs-box').css('left',yen_bs_offset.left);
		<?endif;?>
	}
	else{
		$('.yen-bs-box').removeClass('yen-bs-scrollBasket');
		<?if($bitronic_color_scheme):?>
			$('.yen-bs-box').css({'left':'auto' , 'position':'absolute'});
		<?endif;?>
	}
});

function yen_setQuantity(id, sign)
{
	if(!isNaN(id) && sign){
		var url = '<?=$url;?>';
		var pr_id = parseInt($('#YS_BS_ROW_'+id).find('.yen-bs-eid').val());
		var titles = ['<?=GetMessage('YS_BS_PRODUCT1');?>', '<?=GetMessage('YS_BS_PRODUCT2');?>', '<?=GetMessage('YS_BS_PRODUCT0');?>'];
		var control_quantity = '<?=$arParams['CONTROL_QUANTITY'];?>';
		var quantity_logic = '<?=$arParams['QUANTITY_LOGIC'];?>';
		yenisite_set_quantity(id, sign, url, titles, control_quantity, pr_id, quantity_logic, '<?=GetMessage('YS_BS_NO_QUANTITY');?>');
	}
}
$(document).ready(function(){
	/* $('body').click(function(){
		yenisite_bs_close();
		console.log('1');
	}); */
});
</script>
