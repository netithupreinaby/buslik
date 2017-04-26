<?if(CModule::IncludeModule('iggle.luckydiscount')):?><br>
<?$APPLICATION->IncludeComponent(
	"iggle:lucky.discount",
	"",
	Array(
		"DISCOUNT_VALUE_TYPE" => "P",
		"MIN_DISCOUNT_VALUE" => "1",
		"MAX_DISCOUNT_VALUE" => "20",
		"DISCOUNT_STEP" => "1",
		"CURRENCY" => "RUB",
		"MAX_TRYES" => "5",
		"USER_GROUPS" => array("2")
	),
false
);?>
<?endif;?>