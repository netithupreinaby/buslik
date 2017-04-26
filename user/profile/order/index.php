<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$sSectionName = "Оформление заказа";
$APPLICATION->SetPageProperty("description", "Заказ");
$APPLICATION->SetTitle("Title");
?>
<section class="order-main-content"> 
	<section class="order">

	<?$APPLICATION->IncludeComponent(
		"bitrix:breadcrumb",
		"",
		Array(
			"PATH" => "",
			"SITE_ID" => "s1",
			"START_FROM" => "0"
		)
	);?>
	
	<?$APPLICATION->IncludeComponent(
		"abiatec:sale.order.ajax",
		"buslik",
		Array(
			"ADDITIONAL_PICT_PROP_1" => "-",
			"ADDITIONAL_PICT_PROP_57" => "-",
			"ADDITIONAL_PICT_PROP_58" => "-",
			"ALLOW_AUTO_REGISTER" => "Y",
			"ALLOW_NEW_PROFILE" => "Y",
			"ALLOW_USER_PROFILES" => "Y",
			"BASKET_IMAGES_SCALING" => "standard",
			"BASKET_POSITION" => "before",
			"COMPATIBLE_MODE" => "Y",
			"DELIVERIES_PER_PAGE" => "8",
			"DELIVERY_FADE_EXTRA_SERVICES" => "N",
			"DELIVERY_NO_AJAX" => "N",
			"DELIVERY_NO_SESSION" => "Y",
			"DELIVERY_TO_PAYSYSTEM" => "d2p",
			"DISABLE_BASKET_REDIRECT" => "N",
			"ONLY_FULL_PAY_FROM_ACCOUNT" => "N",
			"PATH_TO_AUTH" => "/auth/",
			"PATH_TO_BASKET" => "/user/profile/cart/",
			"PATH_TO_PAYMENT" => "payment.php",
			"PATH_TO_PERSONAL" => "index.php",
			"PAY_FROM_ACCOUNT" => "N",
			"PAY_SYSTEMS_PER_PAGE" => "8",
			"PICKUPS_PER_PAGE" => "5",
			"PRODUCT_COLUMNS_HIDDEN" => array(),
			"PRODUCT_COLUMNS_VISIBLE" => array("PREVIEW_PICTURE","PROPS"),
			"PROPS_FADE_LIST_1" => array("1","2","3","4","7"),
			"PROPS_FADE_LIST_2" => array(),
			"SEND_NEW_USER_NOTIFY" => "Y",
			"SERVICES_IMAGES_SCALING" => "standard",
			"SET_TITLE" => "Y",
			"SHOW_BASKET_HEADERS" => "N",
			"SHOW_COUPONS_BASKET" => "N",
			"SHOW_COUPONS_DELIVERY" => "N",
			"SHOW_COUPONS_PAY_SYSTEM" => "N",
			"SHOW_DELIVERY_INFO_NAME" => "Y",
			"SHOW_DELIVERY_LIST_NAMES" => "Y",
			"SHOW_DELIVERY_PARENT_NAMES" => "Y",
			"SHOW_MAP_IN_PROPS" => "N",
			"SHOW_NEAREST_PICKUP" => "N",
			"SHOW_ORDER_BUTTON" => "final_step",
			"SHOW_PAY_SYSTEM_INFO_NAME" => "Y",
			"SHOW_PAY_SYSTEM_LIST_NAMES" => "Y",
			"SHOW_STORES_IMAGES" => "Y",
			"SHOW_TOTAL_ORDER_BUTTON" => "N",
			"SKIP_USELESS_BLOCK" => "Y",
			"TEMPLATE_LOCATION" => "popup",
			"TEMPLATE_THEME" => "site",
			"USE_CUSTOM_ADDITIONAL_MESSAGES" => "N",
			"USE_CUSTOM_ERROR_MESSAGES" => "N",
			"USE_CUSTOM_MAIN_MESSAGES" => "N",
			"USE_PRELOAD" => "Y",
			"USE_PREPAYMENT" => "N",
			"USE_YM_GOALS" => "N"
		)
	);?>

	</section>
</section>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>