<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;

$rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("CODE" => "YENISITE_MARKET_ORDER"));
$arr = $rsIBlock->Fetch();
$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arr["ID"]));
while($arr=$rsProp->Fetch())
{
	$arProperty[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];	
}





$arComponentParameters = array(
	"PARAMETERS" => array(
		"PROPERTY_CODE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("IBLOCK_PROPERTY"),
			"MULTIPLE" => "Y",
			"TYPE" => "LIST",
			"VALUES" => $arProperty,
			"DEFAULT" => array('FIO','EMAIL','ABOUT','PHONE','DELIVERY_E','PAYMENT')
		),
		"THANK_URL" => array(
			"PARENT" => "URL_TEMPLATES",
			"NAME" => GetMessage("IBLOCK_THANK_URL"),
			"TYPE" => "STRING",
			"DEFAULT" => "/basket/thank_you.php",
		),
		"ORDER_URL" => array(
			"PARENT" => "URL_TEMPLATES",
			"NAME" => GetMessage("IBLOCK_ORDER_URL"),
			"TYPE" => "STRING",
			"DEFAULT" => "/account/orders/detail.php?ID=#ID#",
		),
		"UE" => array(
			"PARENT" => "URL_TEMPLATES",
			"NAME" => GetMessage("UE"),
			"TYPE" => "STRING",
			"DEFAULT" => GetMessage("RUB"),
		),
		"EVENT" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("EVENT"),
			"TYPE" => "STRING",
			"DEFAULT" => "SALE_ORDER"
		),

		"ADMIN_MAIL" => array(
			"PARENT" => "URL_TEMPLATES",
			"NAME" => GetMessage("ADMIN_MAIL"),
			"TYPE" => "STRING",
			"DEFAULT" => ".",
		),
	
		"EVENT_ADMIN" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("EVENT_ADMIN"),
			"TYPE" => "STRING",
			"DEFAULT" => "SALE_ORDER_ADMIN"
		),
		
		"CACHE_TIME"  =>  Array("DEFAULT"=>3600),
	),
);
?>