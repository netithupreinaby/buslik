<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Прайс-лист");?>
<?$APPLICATION->IncludeComponent("yenisite:catalog.price_generator", ".default", array(
	"IBLOCK_TYPE" => array(
		0 => "catalog",
	),
	"IBLOCK_ID" => array(
		
	),
	"PAGE_ELEMENT_COUNT" => "1",
	"FILE_NAME" => "price",
	"FILTER_NAME" => "arrFilter",
	"PROPERTY_CODE" => array(
		0 => "",
		1 => "",
	),
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "36000000",
	"CACHE_GROUPS" => "Y",
	"CACHE_FILTER" => "N",
	"PRICE_CODE" => array(
	)
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>