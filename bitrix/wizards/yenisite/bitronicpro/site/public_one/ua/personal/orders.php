<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

?><?$APPLICATION->IncludeComponent("bitrix:sale.personal.order", "orders", array(
	"PROP_1" => array(
	),
	"PROP_2" => array(
	),
	"SEF_MODE" => "N",
	"SEF_FOLDER" => "/personal/",
	"ORDERS_PER_PAGE" => "20",
	"PATH_TO_PAYMENT" => "payment.php",
	"PATH_TO_BASKET" => "basket.php",
	"SET_TITLE" => "Y",
	"SAVE_IN_SESSION" => "Y",
	"NAV_TEMPLATE" => ""
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>