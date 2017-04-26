<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Корзина");
?>
<?$APPLICATION->IncludeComponent("yenisite:catalog.basket", "bitronic", array(
	"PROPERTY_CODE" => array(
		0 => "FIO",
		1 => "EMAIL",
		2 => "PHONE",
		3 => "ABOUT",
		4 => "DELIVERY_E",
		5 => "PAYMENT_E",
	),
	"EVENT" => "SALE_ORDER",
	"EVENT_ADMIN" => "SALE_ORDER_ADMIN",
	"YENISITE_BS_FLY" => "",
	"THANK_URL" => "/account/cart/thank_you.php",
	"UE" => "Р",
	"ADMIN_MAIL" => "admin@email.ru",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "3600",
	"INCLUDE_JQUERY" => "Y",
	"COLOR_SCHEME" => ($ys_options["color_scheme"]=="ice"?"blue":$ys_options["color_scheme"]),
	),
	false
);?> <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
