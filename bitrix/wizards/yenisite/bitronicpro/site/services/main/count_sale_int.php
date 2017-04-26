<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();
$file_path = $_SERVER['DOCUMENT_ROOT'].'/bitrix/php_interface/init.php';
$init_text = file_get_contents($file_path) ;
if($init_text != false && strpos ($init_text , 'yenisite_SaveNumberSales'))
	return true;
	
if (!CModule::IncludeModule("sale") || !CModule::IncludeModule("iblock"))
	return false;
	
$arProductSales = array () ;
$arFilter = Array('STATUS_ID' => 'F');
$dbOrders = CSaleOrder::GetList(array("ID" => "ASC"), $arFilter);
while ($arOrder = $dbOrders->Fetch())
{
	$arBasketItems = array();
	$dbBasketItems = CSaleBasket::GetList(array("ID" => "ASC"),
		array("ORDER_ID" => $arOrder['ID']),
			false,
			false,
			array("ID", "PRODUCT_ID", "IBLOCK_ID", "QUANTITY")
		);
	while ($arItem = $dbBasketItems->Fetch())
	{
		$arElement = CIBlockElement::GetByID($arItem['PRODUCT_ID'])->Fetch() ;
		$arProductSales[$arItem['PRODUCT_ID']]['IBLOCK_ID'] = $arElement['IBLOCK_ID'] ;
		$arProductSales[$arItem['PRODUCT_ID']]['SALE_INT'] = $arProductSales[$arItem['PRODUCT_ID']]['SALE_INT'] ? $arProductSales[$arItem['PRODUCT_ID']]['SALE_INT'] + IntVal($arItem['QUANTITY']) : IntVal($arItem['QUANTITY']) ;
	}
}

foreach ($arProductSales as $element_id => $arProduct)
	CIBlockElement::SetPropertyValues($element_id, $arProduct['IBLOCK_ID'], $arProduct['SALE_INT'], 'SALE_INT');
?>
