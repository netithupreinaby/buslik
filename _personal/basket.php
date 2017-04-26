<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Моя корзина");
$APPLICATION->AddChainItem("Моя корзина");
$APPLICATION->SetTitle("Моя корзина");
?> <?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket", "big", array(
	"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
	"BASKET_PHOTO" => "5",
	"COLUMNS_LIST" => array(
		0 => "NAME",
		1 => "PRICE",
		2 => "TYPE",
		3 => "QUANTITY",
		4 => "DELETE",
		5 => "DELAY",
		6 => "WEIGHT",
		7 => "DISCOUNT",
	),
	"PATH_TO_ORDER" => "/personal/order.php",
	"HIDE_COUPON" => "N",
	"QUANTITY_FLOAT" => "N",
	"PRICE_VAT_SHOW_VALUE" => "N",
	"SET_TITLE" => "Y"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
