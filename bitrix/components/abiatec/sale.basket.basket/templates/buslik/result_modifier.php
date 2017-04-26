<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
use Bitrix\Main;

$defaultParams = array(
	'TEMPLATE_THEME' => 'blue'
);
$arParams = array_merge($defaultParams, $arParams);
unset($defaultParams);

$arParams['TEMPLATE_THEME'] = (string)($arParams['TEMPLATE_THEME']);
if ('' != $arParams['TEMPLATE_THEME'])
{
	$arParams['TEMPLATE_THEME'] = preg_replace('/[^a-zA-Z0-9_\-\(\)\!]/', '', $arParams['TEMPLATE_THEME']);
	if ('site' == $arParams['TEMPLATE_THEME'])
	{
		$templateId = (string)Main\Config\Option::get('main', 'wizard_template_id', 'eshop_bootstrap', SITE_ID);
		$templateId = (preg_match("/^eshop_adapt/", $templateId)) ? 'eshop_adapt' : $templateId;
		$arParams['TEMPLATE_THEME'] = (string)Main\Config\Option::get('main', 'wizard_'.$templateId.'_theme_id', 'blue', SITE_ID);
	}
	if ('' != $arParams['TEMPLATE_THEME'])
	{
		if (!is_file($_SERVER['DOCUMENT_ROOT'].$this->GetFolder().'/themes/'.$arParams['TEMPLATE_THEME'].'/style.css'))
			$arParams['TEMPLATE_THEME'] = '';
	}
}
if ('' == $arParams['TEMPLATE_THEME'])
	$arParams['TEMPLATE_THEME'] = 'blue';
	
	
	
	$resDelivery = CSaleDelivery::GetList(Array(),Array("LID" => SITE_ID, "ACTIVE" => "Y"),false,false,Array());

	if($arDelivery = $resDelivery->Fetch()) {
	   do {
		  $arResult["DELIVERY_TYPE"][] = $arDelivery;
	   } while ($arDelivery = $resDelivery->Fetch());
	}
	
	
$Myarray = array(
				"NAME",
				"DELAY",
				"DISCOUNT",
				"QUANTITY",
				"PRICE",
				"DELETE",
				"SUM",
				);

foreach ($arResult["GRID"]["HEADERS"] as $id => $arHeader){
	if(in_array($arHeader["id"],$Myarray)){
		$arResult["GRID"]["HEADERS_MY"][$arHeader["id"]] = $arHeader;
	}else{
		$arResult["GRID"]["HEADERS_hide"][$arHeader["id"]] = $arHeader;
	}

}
//$arResult["GRID"]["HEADERS_MY"] = array_merge($arResult["GRID"]["HEADERS_MY"],$arResult["GRID"]["HEADERS_hide"]);

// Basket in normal state
$basketIsRecalculated = false;

// Get certificates ID
$certificates = getCertificates(70);

// Sort product properties and get product id
$arResult['PRICE_FOR_BONUSES'] = 0;
$productIds = array();
$updatedProducts = array();
$currentStores = CatalogHelpers::getCurrentStores($_SESSION['regionId']);
foreach ($arResult['GRID']['ROWS'] as $itemIndex => $item) {

	// Item properties
	$itemProperties = array();

	foreach ($item as $itemKeys => $itemValues) {
		//if ((strpos($itemKeys, 'PROPERTY_') === 0) && (strpos($itemKeys, '_VALUE') === (strlen($itemKeys) - 6))){
		if (strpos($itemKeys, 'PROPERTY_') === 0){
			$propertyKey = substr($itemKeys, 9);
			$arResult['GRID']['ROWS'][$itemIndex]['COLUMN_PROPERTIES'][$propertyKey] = $itemValues;
			unset($arResult['GRID']['ROWS'][$itemIndex][$itemKeys]);
		}
		//if ((strpos($itemKeys, '~PROPERTY_') === 0) && (strpos($itemKeys, '_VALUE') === strlen($itemKeys) - 6)){
		if (strpos($itemKeys, '~PROPERTY_') === 0){
			$propertyKey = '~' . substr($itemKeys, 10);
			$arResult['GRID']['ROWS'][$itemIndex]['COLUMN_PROPERTIES'][$propertyKey] = $itemValues;
			unset($arResult['GRID']['ROWS'][$itemIndex][$itemKeys]);
		}
	}

	if (isset($item["NOT_AVAILABLE"]) && $item["NOT_AVAILABLE"] == true){
		$notAvailable = true;
	}

	if($item["DELAY"] == "Y"){
		$delay = true;
	}

	$useInCalculation = true;
	if($notAvailable==true || $delay == true){
		$useInCalculation = false;
	}

	$infinityQuantity = false;
	if (in_array($item['PRODUCT_ID'], $certificates)){
		$useInCalculation = false;
		$item['AVAILABLE_QUANTITY'] = 100000;
		$infinityQuantity = true;
	}

	if ($item['DISCOUNT_PRICE'] === 0.0 && $useInCalculation === true){

		$totalBonusProductPrice = $item['PRICE'] * $item['QUANTITY'];
		$arResult['PRICE_FOR_BONUSES'] += (float)$totalBonusProductPrice;

	}

	$productIds[$itemIndex] = $item['PRODUCT_ID'];

	if ($infinityQuantity){
		$arResult['GRID']['ROWS'][$itemIndex]['AVAILABLE_QUANTITY'] = $item['AVAILABLE_QUANTITY'];
	} else {
		$storesQuantity = CatalogHelpers::getStoresQuantity($item['PRODUCT_ID'],$currentStores);
		$arResult['GRID']['ROWS'][$itemIndex]['AVAILABLE_QUANTITY'] = $storesQuantity[$_SESSION['catalogStoreID']];
		$item['AVAILABLE_QUANTITY'] = $storesQuantity[$_SESSION['catalogStoreID']];
	}

	if (!$arResult['GRID']['ROWS'][$itemIndex]['AVAILABLE_QUANTITY']) {
		$arResult['GRID']['ROWS'][$itemIndex]['AVAILABLE_QUANTITY'] = 0;
	}

	// Recalculate basket if needed
	$updateFields = array();
	if ($item['QUANTITY'] > $item['AVAILABLE_QUANTITY']){

		$totalPrice = $item['PRICE'] * $item['AVAILABLE_QUANTITY'];
		$totalPriceFormatted = CCurrencyLang::CurrencyFormat($totalPrice, $item['CURRENCY'], true);
		$updateFields = array(
			'QUANTITY' => $item['AVAILABLE_QUANTITY'],
			'SUM' => $totalPriceFormatted,
		);
		CSaleBasket::Update($item['ID'], $updateFields);

		$arResult['GRID']['ROWS'][$itemIndex]['QUANTITY'] = $item['AVAILABLE_QUANTITY'];
		$arResult['GRID']['ROWS'][$itemIndex]['SUM'] = $totalPriceFormatted;

		$updatedProducts[] = array(
			'NAME' => $item['NAME'],
		);

		$basketIsRecalculated = true;
	}

}

$arResult['updatedProducts'] = $updatedProducts;
$arResult['basketIsRecalculated'] = $basketIsRecalculated;

// Get cart bonuses
if ($arResult['PRICE_FOR_BONUSES'] > 0) {

	$arResult['BONUS_QUANTITY'] = getBonuses($arResult['PRICE_FOR_BONUSES']);

}

$basketItemsIDs = array_flip($productIds);

$a = '';
// Get products quantity from stock
/*if (!empty($productIds)){
	$bFilter = array(
		'ID' => $productIds,
		'IBLOCK_ID',
		"IBLOCK_ACTIVE" => "Y",
	);

	$bSelect = array(
		"ID",
		"IBLOCK_ID",
		"CATALOG_QUANTITY",
	);

	$bSort = array();

	$bRSElement = CIBlockElement::GetList($bSort, $bFilter, false, false, $bSelect);

	$bArElements = array();
	while ($bObElement = $bRSElement->GetNextElement()){
		$catalogProperties = $bObElement->GetFields();

		if ($catalogProperties['ID']){
			$arResult['GRID']['ROWS'][$basketItemsIDs[$catalogProperties['ID']]]['CATALOG_PROPERTIES'] = $catalogProperties;
		}

	}

	$a = '';
}*/

