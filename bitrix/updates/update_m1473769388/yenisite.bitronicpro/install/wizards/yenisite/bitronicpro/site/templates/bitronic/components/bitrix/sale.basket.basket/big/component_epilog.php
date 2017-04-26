<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$id_list = array () ;
$arParams['ACCESSORIES_ON'] = 'Y' ;
foreach($arResult['ITEMS']['AnDelCanBuy'] as $item)
	$id_list[] = $item['PRODUCT_ID'] ;
if($arParams['ACCESSORIES_ON'] == 'Y' && count($id_list)):?>
	<?$APPLICATION->IncludeComponent("yenisite:catalog.accessories.list", "", array(
		"ID_LIST" => $id_list,
		"FILTER_NAME" => "accessories_filter",
		"PAGE_ELEMENT_COUNT" => "10",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "86400"
		),
		false
	);?>
<?
	global $accessories_filter ;
	if(count($accessories_filter['ID'])):?>
					<?$APPLICATION->IncludeComponent("yenisite:catalog.section.all", "detail_spec", array(
						"IBLOCK_TYPE" => "catalog_%",
						"IMAGE_SET" => "3",
						"SECTION_ID" => "",
						"SECTION_CODE" => "",
						"SLIDER_TITLE" => GetMessage('ACCESSORIES'),
						"SECTION_USER_FIELDS" => array(
							0 => "",
							1 => "",
						),
						"ELEMENT_SORT_FIELD" => "rand",
						"ELEMENT_SORT_ORDER" => "asc",
						"FILTER_NAME" => "accessories_filter",
						"INCLUDE_SUBSECTIONS" => "Y",
						"SHOW_ALL_WO_SECTION" => "Y",
						"PAGE_ELEMENT_COUNT" => "30",
						"LINE_ELEMENT_COUNT" => "3",
						"PROPERTY_CODE" => array(
							0 => "",
							1 => "MORE_PHOTO",
							2 => "",
						),
						"OFFERS_FIELD_CODE" => array(
							0 => "NAME",
							1 => "PREVIEW_PICTURE",
							2 => "DETAIL_PICTURE",
							3 => "",
						),
						"OFFERS_PROPERTY_CODE" => array(
							0 => "",
							1 => "test",
							2 => "",
						),
						"OFFERS_SORT_FIELD" => "sort",
						"OFFERS_SORT_ORDER" => "asc",
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
						"CACHE_TYPE" => "A",
						"CACHE_TIME" => "36000000",
						"CACHE_GROUPS" => "Y",
						"META_KEYWORDS" => "-",
						"META_DESCRIPTION" => "-",
						"BROWSER_TITLE" => "-",
						"ADD_SECTIONS_CHAIN" => "N",
						"DISPLAY_COMPARE" => "N",
						"SET_TITLE" => "Y",
						"SET_STATUS_404" => "N",
						"CACHE_FILTER" => "N",
						"PRICE_CODE" => array(
							0 => "WHOLESALE",
							1 => "Соглашение с интернет-клиентом",
							2 => "Цена в интернет магазине",
							3 => "BASE",
							4 => "RETAIL",
							5 => "Безналичный расчет",
						),
						"USE_PRICE_COUNT" => "N",
						"SHOW_PRICE_COUNT" => "1",
						"PRICE_VAT_INCLUDE" => "Y",
						"USE_PRODUCT_QUANTITY" => "N",

						"DISPLAY_TOP_PAGER" => "N",
						"DISPLAY_BOTTOM_PAGER" => "Y",
						"PAGER_TITLE" => "",
						"PAGER_SHOW_ALWAYS" => "Y",
						"PAGER_TEMPLATE" => "",
						"PAGER_DESC_NUMBERING" => "N",
						"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
						"PAGER_SHOW_ALL" => "Y",
						"AJAX_OPTION_ADDITIONAL" => ""
				),
				false
			);?>
	<?endif;
endif;?>