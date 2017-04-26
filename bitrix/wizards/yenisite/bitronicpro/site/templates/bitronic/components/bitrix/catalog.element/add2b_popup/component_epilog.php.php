<?if($arParams['ACCESSORIES_ON'] == 'Y' && count($id_list)):?>
<?
global $SLIDER_FILTER ;
if($arParams['ACCESSORIES_ON'] == 'Y')
{
	$APPLICATION->IncludeComponent("yenisite:catalog.accessories", "", array(
		"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"ELEMENT_ID" => $arParams['ID'],
		"ACCESSORIES_LINK" => $arParams["ACCESSORIES_LINK"],
		"ACCESSORIES_PROPS" => $arParams["ACCESSORIES_PROPS"],
		"FILTER_NAME" => "SLIDER_FILTER",
		"PAGE_ELEMENT_COUNT" => $arParams["ACCESSORIES_PAGE_ELEMENT_COUNT"],
		"CACHE_TYPE" => $arParams["CACHE_TYPE"],
 		"CACHE_TIME" => $arParams["CACHE_TIME"],
		),
		$component
	);
	
	$slider_show  		= count($SLIDER_FILTER['ID']) ; 
	if($arParams['FILTER_BY_QUANTITY'] == 'Y')
		$SLIDER_FILTER['>CATALOG_QUANTITY'] = 0;
	$slider_title 		= GetMessage('ACCESSORIES');
	$slider_iblock_type	= '' ; 
	$slider_iblock_id	= '' ;
	$slider_section_id  = '' ;
	if($slider_show):?>
		<?$APPLICATION->IncludeComponent("yenisite:catalog.section.all", "add2b_spec", array(
			"SLIDER_TITLE" => $slider_title,
			"IBLOCK_TYPE" => $slider_iblock_type,
			"IBLOCK_ID" => $slider_iblock_id,
			"IMAGE_SET" => "3",	//
			"SECTION_ID" => $slider_section_id,
			"SECTION_CODE" => "",
			"SECTION_USER_FIELDS" => array(
				0 => "",
				1 => "",
			),
			"ELEMENT_SORT_FIELD" => "rand",
			"ELEMENT_SORT_ORDER" => "asc",
			"FILTER_NAME" => "SLIDER_FILTER",
			"INCLUDE_SUBSECTIONS" => "Y",
			"SHOW_ALL_WO_SECTION" => "Y",
			"PAGE_ELEMENT_COUNT" => "30",
			"LINE_ELEMENT_COUNT" => "3",
			"PROPERTY_CODE" => array(
				0 => "",
				1 => "MORE_PHOTO",
				2 => "",
			),
			"SECTION_URL" => "",
			"DETAIL_URL" => "",
			"BASKET_URL" => "/personal/basket.php",
			"ACTION_VARIABLE" => "action",
			"PRODUCT_ID_VARIABLE" => "id",
			"PRODUCT_QUANTITY_VARIABLE" => "quantity",
			"PRODUCT_PROPS_VARIABLE" => "prop",
			"SECTION_ID_VARIABLE" => "SECTION_ID",
			"AJAX_MODE" => "N",
			"AJAX_OPTION_JUMP" => "N",
			"AJAX_OPTION_STYLE" => "Y",
			"AJAX_OPTION_HISTORY" => "N",
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => $arParams["CACHE_TIME"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
			"ADD_SECTIONS_CHAIN" => "N",
			"DISPLAY_COMPARE" => "N",
			"SET_TITLE" => "N",
			"SET_STATUS_404" => "N",
			"CACHE_FILTER" => "Y",
			"PRICE_CODE" => $arParams["PRICE_CODE"],
			"USE_PRICE_COUNT" => "N",
			"SHOW_PRICE_COUNT" => "1",
			"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
			"PRODUCT_PROPERTIES" => array(
			),
			"INCLUDE_SUBSECTION" => "Y",
			"USE_PRODUCT_QUANTITY" => "N",
			"DISPLAY_TOP_PAGER" => "N",
			"DISPLAY_BOTTOM_PAGER" => "Y",
			"PAGER_TITLE" => "",
			"PAGER_SHOW_ALWAYS" => "Y",
			"PAGER_TEMPLATE" => "",
			"PAGER_DESC_NUMBERING" => "N",
			"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
			"PAGER_SHOW_ALL" => "Y",
			"OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
			"OFFERS_FIELD_CODE" => $arParams["DETAIL_OFFERS_FIELD_CODE"],
			"OFFERS_PROPERTY_CODE" => $arParams["DETAIL_OFFERS_PROPERTY_CODE"],
			"OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
			"OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
			"AJAX_OPTION_ADDITIONAL" => "",

			"CONVERT_CURRENCY" => $arParams["CONVERT_CURRENCY"],
			"CURRENCY_ID" => $arParams["CURRENCY_ID"],
			"HIDE_ORDER_PRICE" => $arParams["HIDE_ORDER_PRICE"],
			"ELEMENT_ID" => $ElementID,

			'HIDE_BUY_IF_PROPS' => $arParams['HIDE_BUY_IF_PROPS'],
			"SHOW_ELEMENT" => $arParams['SHOW_ELEMENT']
			),
			$component
		);?>
	<?endif;
}?>