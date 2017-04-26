<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
global $APPLICATION ;
global $FILTER_BY_QUANTITY ;
global ${$arParams['FILTER_NAME']} ;
if($FILTER_BY_QUANTITY == 'Y')
{
	if (($_REQUEST["f_Quantity"] == "Y" || ($APPLICATION->get_cookie("f_Q_{$arParams['IBLOCK_ID']}", "bitronic") == 'Y' && empty($_REQUEST["set_filter"]) && $_REQUEST["ajax"] !== "y")) && !isset($_GET["del_filter"]))
	{
		$arResult['q_checked'] = 'Y';
		// show all
		
		
		//${$arParams['FILTER_NAME']}['CATALOG_AVAILABLE'] = 'N'; // show only not available elements
	}
	else
	{
		$arResult['q_checked'] = 'N';
		${$arParams['FILTER_NAME']}['CATALOG_AVAILABLE'] = 'Y';
	}
	$APPLICATION->set_cookie("f_Q_{$arParams['IBLOCK_ID']}", $arResult['q_checked'], time()+60 , "/" , false, false, true,  "bitronic");

	if(isset($_REQUEST["ajax"]) && $_REQUEST["ajax"] === "y")
	{	
		$FILTER_NAME = (string)$arParams["FILTER_NAME"];
		$arFilter = $this->__component->makeFilter($FILTER_NAME);
		// redefine ELEMENT_COUNT because we added param in ${$arParams['FILTER_NAME']}
		$arResult["ELEMENT_COUNT"] = CIBlockElement::GetList(array(), $arFilter, array(), false);
	}
}
?>