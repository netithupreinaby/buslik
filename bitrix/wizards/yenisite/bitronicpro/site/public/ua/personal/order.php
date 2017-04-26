<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оформлення замовлення");

$pto = SITE_DIR."personal/basket.php";  
$pta = SITE_DIR."auth.php";
?>
<?
global $ys_options;
if($ys_options['order'] == 'one_step'):?>
	<?$APPLICATION->IncludeComponent("bitrix:sale.order.ajax", "bitronic_visual", array(
	"PAY_FROM_ACCOUNT" => "Y",
	"COUNT_DELIVERY_TAX" => "N",
	"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
	"ONLY_FULL_PAY_FROM_ACCOUNT" => "N",
	"ALLOW_AUTO_REGISTER" => "N",
	"SEND_NEW_USER_NOTIFY" => "Y",
	"DELIVERY_NO_AJAX" => "N",
	"DELIVERY_NO_SESSION" => "N",
	"TEMPLATE_LOCATION" => ".default",
	"DELIVERY_TO_PAYSYSTEM" => "d2p",
	"USE_PREPAYMENT" => "N",
	"PROP_1" => array(
	),
	"PROP_2" => array(
	),
	"PATH_TO_BASKET" => "basket.php",
	"PATH_TO_PERSONAL" => $pto,
	"PATH_TO_PAYMENT" => "payment.php",
	"PATH_TO_AUTH" => $pta,
	"SET_TITLE" => "Y",
	"DISPLAY_IMG_WIDTH" => "90",
	"DISPLAY_IMG_HEIGHT" => "90"
	),
	false
);?>
<?else:?>
	<?$APPLICATION->IncludeComponent("bitrix:sale.order.full", "order", array(
		"ALLOW_PAY_FROM_ACCOUNT" => "N",
		"SHOW_MENU" => "Y",
		"CITY_OUT_LOCATION" => "N",
		"COUNT_DELIVERY_TAX" => "N",
		"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
		"ONLY_FULL_PAY_FROM_ACCOUNT" => "N",
		"SEND_NEW_USER_NOTIFY" => "Y",
		"DELIVERY_NO_SESSION" => "N",
		"PROP_1" => array(
		),
		"PROP_2" => array(
		),
		"PATH_TO_BASKET" => "basket.php",
		"PATH_TO_PERSONAL" => $pto,
		"PATH_TO_AUTH" => $pta,
		"PATH_TO_PAYMENT" => "payment.php",
		"USE_AJAX_LOCATIONS" => "Y",
		"SHOW_AJAX_DELIVERY_LINK" => "Y",
		"SET_TITLE" => "Y",
		"PRICE_VAT_INCLUDE" => "Y",
		"PRICE_VAT_SHOW_VALUE" => "Y"
		),
		false
	);?>
<?endif;?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>