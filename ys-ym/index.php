<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Yandex market");
//global $arrFilter; $arrFilter = array("!PROPERTY_SALE" => false);
?> 
<?$APPLICATION->IncludeComponent("yenisite:yandex.market_new", ".default", array(
	"IBLOCK_TYPE" => "1c_catalog",
	"IBLOCK_ID_IN" => array(
		0 => "0",
	),
	"IBLOCK_ID_EX" => array(
		0 => "0",
	),
	"IBLOCK_SECTION" => array(
		0 => "0",
	),
	"SITE" => "mysite.com",
	"COMPANY" => "My company",
	"FILTER_NAME" => "arrFilter",
	"MORE_PHOTO" => "MORE_PHOTO",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "86400",
	"CACHE_FILTER" => "Y",
	"PRICE_CODE" => "BASE",
	"IBLOCK_ORDER" => "N",
	"CURRENCY" => "RUR",
	"LOCAL_DELIVERY_COST" => "150"
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
