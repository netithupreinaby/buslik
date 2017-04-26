<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<?$APPLICATION->SetTitle("Корзина");?>
<?php //TODO remove this at production ?>
<?php $_SESSION['DELIVERY_TYPE'] = 'pick_up' ?>
<?php $_SESSION['DELIVERY_TYPE'] = 'delivery' ?>
<section class="main-content">
    <section class="cart">
        <?$APPLICATION->IncludeComponent(
            "bitrix:menu",
            "dropdownPersonal",
            array(
                "ALLOW_MULTI_SELECT" => "N",
                "CHILD_MENU_TYPE" => "left",
                "DELAY" => "N",
                "MAX_LEVEL" => "1",
                "MENU_CACHE_GET_VARS" => array(
                ),
                "MENU_CACHE_TIME" => "3600",
                "MENU_CACHE_TYPE" => "N",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "ROOT_MENU_TYPE" => "user",
                "USE_EXT" => "Y",
                "COMPONENT_TEMPLATE" => "topmenu2"
            ),
            false
        );?>

        <?$APPLICATION->IncludeComponent(
	"abiatec:sale.basket.basket", 
	"buslik", 
	array(
		"ACTION_VARIABLE" => "basketAction",
		"AUTO_CALCULATION" => "Y",
		"BASKET_PHOTO" => "1",
		"COLUMNS_LIST" => array(
			0 => "NAME",
			1 => "DISCOUNT",
			2 => "WEIGHT",
			3 => "PROPS",
			4 => "DELETE",
			5 => "DELAY",
			6 => "TYPE",
			7 => "PRICE",
			8 => "QUANTITY",
			9 => "SUM",
			10 => "PROPERTY_PICS_NEWS",
			11 => "PROPERTY_CML2_BAR_CODE",
			12 => "PROPERTY_CML2_ARTICLE",
			13 => "PROPERTY_CML2_ATTRIBUTES",
			14 => "PROPERTY_CML2_TRAITS",
			15 => "PROPERTY_CML2_BASE_UNIT",
			16 => "PROPERTY_CML2_TAXES",
			17 => "PROPERTY_MORE_PHOTO",
			18 => "PROPERTY_FILES",
			19 => "PROPERTY_CML2_MANUFACTURER",
			20 => "PROPERTY__1_ROST_ODEZHDA",
			21 => "PROPERTY__5_OBKHVAT_GOLOVY",
			22 => "PROPERTY__2_RAZMER_ODEZHDY",
			23 => "PROPERTY__8_TSVET_ODEZHDY",
			24 => "PROPERTY_IDTORGOVOYMARKIFILTR",
			25 => "PROPERTY_PREDPRIYATIE_IZGOTOVITEL",
			26 => "PROPERTY_TSVET",
		),
		"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",
		"GIFTS_BLOCK_TITLE" => "Выберите один из подарков",
		"GIFTS_CONVERT_CURRENCY" => "N",
		"GIFTS_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_HIDE_NOT_AVAILABLE" => "N",
		"GIFTS_MESS_BTN_BUY" => "Выбрать",
		"GIFTS_MESS_BTN_DETAIL" => "Подробнее",
		"GIFTS_PAGE_ELEMENT_COUNT" => "4",
		"GIFTS_PLACE" => "TOP",
		"GIFTS_PRODUCT_PROPS_VARIABLE" => "prop",
		"GIFTS_PRODUCT_QUANTITY_VARIABLE" => "",
		"GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
		"GIFTS_SHOW_IMAGE" => "Y",
		"GIFTS_SHOW_NAME" => "Y",
		"GIFTS_SHOW_OLD_PRICE" => "Y",
		"GIFTS_TEXT_LABEL_GIFT" => "Подарок",
		"HIDE_COUPON" => "N",
		"OFFERS_PROPS" => array(
			0 => "_5_OBKHVAT_GOLOVY",
			1 => "_1_ROST_ODEZHDA",
			2 => "_2_RAZMER_ODEZHDY",
			3 => "_8_TSVET_ODEZHDY",
		),
		"PATH_TO_ORDER" => "/user/profile/order/index.php",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"QUANTITY_FLOAT" => "Y",
		"SET_TITLE" => "Y",
		"TEMPLATE_THEME" => "site",
		"USE_GIFTS" => "Y",
		"USE_PREPAYMENT" => "N",
		"USE_SLIDER_MOUSEWHEEL" => "Y",
		"COMPONENT_TEMPLATE" => "buslik"
	),
	false
);?>
    </section>
</section>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>