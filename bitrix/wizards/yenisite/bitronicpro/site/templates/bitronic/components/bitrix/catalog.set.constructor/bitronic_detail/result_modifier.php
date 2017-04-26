<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/*
$arDefaultParams = array(
	'TEMPLATE_THEME' => 'blue',
);
$arParams = array_merge($arDefaultParams, $arParams);

$arParams['TEMPLATE_THEME'] = (string)($arParams['TEMPLATE_THEME']);
if ('' != $arParams['TEMPLATE_THEME'])
{
	$arParams['TEMPLATE_THEME'] = preg_replace('/[^a-zA-Z0-9_\-\(\)\!]/', '', $arParams['TEMPLATE_THEME']);
	if ('site' == $arParams['TEMPLATE_THEME'])
	{
		$arParams['TEMPLATE_THEME'] = COption::GetOptionString('main', 'wizard_eshop_adapt_theme_id', 'blue', SITE_ID);
	}
	if ('' != $arParams['TEMPLATE_THEME'])
	{
		if (!is_file($_SERVER['DOCUMENT_ROOT'].$this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css'))
			$arParams['TEMPLATE_THEME'] = '';
	}
}
if ('' == $arParams['TEMPLATE_THEME'])
	$arParams['TEMPLATE_THEME'] = 'blue';
*/
global $ys_options;
switch($ys_options['color_scheme']) {
	case 'red':    $arParams['TEMPLATE_THEME'] = 'red';    break;
	case 'ice':    $arParams['TEMPLATE_THEME'] = 'blue';   break;
	case 'green':  $arParams['TEMPLATE_THEME'] = 'green';  break;
	case 'yellow': $arParams['TEMPLATE_THEME'] = 'yellow'; break;
	case 'pink':   $arParams['TEMPLATE_THEME'] = 'wood';   break;
	case 'metal':  $arParams['TEMPLATE_THEME'] = 'black';  break;
	default:       break;
}
/*
if ($arResult["ELEMENT"]['DETAIL_PICTURE'] || $arResult["ELEMENT"]['PREVIEW_PICTURE'])
{
	$arFileTmp = CFile::ResizeImageGet(
		$arResult["ELEMENT"]['DETAIL_PICTURE'] ? $arResult["ELEMENT"]['DETAIL_PICTURE'] : $arResult["ELEMENT"]['PREVIEW_PICTURE'],
		array("width" => "150", "height" => "180"),
		BX_RESIZE_IMAGE_PROPORTIONAL,
		true
	);*/
	$arResult["ELEMENT"]['DETAIL_PICTURE'] = array('src' => CResizer2Resize::ResizeGD2(CFile::GetPath(yenisite_GetPicSrc($arResult["ELEMENT"]))), '3');//$arFileTmp;
//}

$arResult['ELEMENT']['PRICE_DISCOUNT_DIFFERENCE']  = yenisite_rubleSign($arResult['ELEMENT']['PRICE_DISCOUNT_DIFFERENCE']);
$arResult['ELEMENT']['PRICE_PRINT_DISCOUNT_VALUE'] = yenisite_rubleSign($arResult['ELEMENT']['PRICE_PRINT_DISCOUNT_VALUE']);
$arResult['ELEMENT']['PRICE_PRINT_VALUE']          = yenisite_rubleSign($arResult['ELEMENT']['PRICE_PRINT_VALUE']);

$arDefaultSetIDs = array($arResult["ELEMENT"]["ID"]);
$arMissingItems = array();
foreach (array("DEFAULT", "OTHER") as $type)
{
	foreach ($arResult["SET_ITEMS"][$type] as $key=>$arItem)
	{
		if (yenisite_CATALOG_AVAILABLE($arItem)) {
			if ($type == "DEFAULT") {
				$arDefaultSetIDs[] = $arItem["ID"];
			}
		} else {
			$arMissingItems[] = array('type' => $type, 'key' => $key);
			continue;
		}
		
		$arElement = array(
			"ID"=>$arItem["ID"],
			"NAME" =>$arItem["NAME"],
			"DETAIL_PAGE_URL"=>$arItem["DETAIL_PAGE_URL"],
			"DETAIL_PICTURE"=>$arItem["DETAIL_PICTURE"],
			"PREVIEW_PICTURE"=> $arItem["PREVIEW_PICTURE"],
			"PRICE_CURRENCY" => $arItem["PRICE_CURRENCY"],
			"PRICE_DISCOUNT_VALUE" => $arItem["PRICE_DISCOUNT_VALUE"],
			"PRICE_PRINT_DISCOUNT_VALUE" => yenisite_rubleSign($arItem["PRICE_PRINT_DISCOUNT_VALUE"]),
			"PRICE_VALUE" => $arItem["PRICE_VALUE"],
			"PRICE_PRINT_VALUE" => yenisite_rubleSign($arItem["PRICE_PRINT_VALUE"]),
			"PRICE_DISCOUNT_DIFFERENCE_VALUE" => $arItem["PRICE_DISCOUNT_DIFFERENCE_VALUE"],
			"PRICE_DISCOUNT_DIFFERENCE" => yenisite_rubleSign($arItem["PRICE_DISCOUNT_DIFFERENCE"]),
		);
		if ($arItem["PRICE_CONVERT_DISCOUNT_VALUE"])
			$arElement["PRICE_CONVERT_DISCOUNT_VALUE"] = $arItem["PRICE_CONVERT_DISCOUNT_VALUE"];
		if ($arItem["PRICE_CONVERT_VALUE"])
			$arElement["PRICE_CONVERT_VALUE"] = $arItem["PRICE_CONVERT_VALUE"];
		if ($arItem["PRICE_CONVERT_DISCOUNT_DIFFERENCE_VALUE"])
			$arElement["PRICE_CONVERT_DISCOUNT_DIFFERENCE_VALUE"] = $arItem["PRICE_CONVERT_DISCOUNT_DIFFERENCE_VALUE"];

		/*
		if ($arItem['DETAIL_PICTURE'] || $arItem['PREVIEW_PICTURE'])
		{
			$arFileTmp = CFile::ResizeImageGet(
				$arItem['DETAIL_PICTURE'] ? $arItem['DETAIL_PICTURE'] : $arItem['PREVIEW_PICTURE'],
				array("width" => "150", "height" => "180"),
				BX_RESIZE_IMAGE_PROPORTIONAL,
				true
			);*/
			$arElement['DETAIL_PICTURE'] = array('src' => CResizer2Resize::ResizeGD2(CFile::GetPath(yenisite_GetPicSrc($arItem)), '3'));// $arFileTmp;
		//}

		$arResult["SET_ITEMS"][$type][$key] = $arElement;
	}
}

foreach ($arMissingItems as $index) {
	unset($arResult["SET_ITEMS"][$index['type']][$index['key']]);
}
while (count($arResult['SET_ITEMS']['DEFAULT']) < 3
    && count($arResult["SET_ITEMS"]['OTHER'])   > 0) {
	
	$arItem = array_shift($arResult["SET_ITEMS"]['OTHER']);
	$arResult['SET_ITEMS']['DEFAULT'][] = $arItem;
	$arDefaultSetIDs[] = $arItem["ID"];
}
$arResult['SET_ITEMS']['DEFAULT'] = array_merge(array(), $arResult['SET_ITEMS']['DEFAULT']);
$arResult["DEFAULT_SET_IDS"] = $arDefaultSetIDs;

$arResult["SET_ITEMS"]["PRICE"]     = yenisite_rubleSign($arResult["SET_ITEMS"]["PRICE"]);
$arResult["SET_ITEMS"]["OLD_PRICE"] = yenisite_rubleSign($arResult["SET_ITEMS"]["OLD_PRICE"]);
$arResult["SET_ITEMS"]["PRICE_DISCOUNT_DIFFERENCE"] = yenisite_rubleSign($arResult["SET_ITEMS"]["PRICE_DISCOUNT_DIFFERENCE"]);