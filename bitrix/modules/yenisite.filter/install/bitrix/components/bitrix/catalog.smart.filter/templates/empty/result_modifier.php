<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $APPLICATION ;
global $FILTER_BY_QUANTITY ;
global ${$arParams['FILTER_NAME']} ;
if($FILTER_BY_QUANTITY == 'Y')
{	
	if (($_REQUEST["set_filter"] && $APPLICATION->get_cookie("f_Q_{$arParams['IBLOCK_ID']}", "bitronic") == 'Y') || empty($_REQUEST["set_filter"]) || isset($_REQUEST["del_filter"]))
	{
		$arResult['q_checked'] = 'Y';
		${$arParams['FILTER_NAME']}['>CATALOG_QUANTITY'] = '0';
	}
	else
	{
		$arResult['q_checked'] = 'N';
		${$arParams['FILTER_NAME']}[] = Array("LOGIC" => "OR",
			array('CATALOG_QUANTITY' => false),
			array('<=CATALOG_QUANTITY' => 0)) ;
	}
	$APPLICATION->set_cookie("f_Q_{$arParams['IBLOCK_ID']}", $arResult['q_checked'], time()+60 , "/" , false, false, true,  "bitronic");
}
?>