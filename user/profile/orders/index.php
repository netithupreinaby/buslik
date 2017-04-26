<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>

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

		<?$APPLICATION->IncludeComponent("bitrix:sale.personal.order", "orders", array(
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
		);?>


	</section>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>