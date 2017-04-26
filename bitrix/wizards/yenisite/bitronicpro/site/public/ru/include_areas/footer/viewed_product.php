<?if( CModule::IncludeModule('sale') &&
	  $APPLICATION->GetCurDir() != SITE_DIR && 
	  ($APPLICATION->GetCurPage() == "/personal/basket.php" || $APPLICATION->GetCurDir() != "/personal/")
)
{
	$APPLICATION->IncludeComponent("bitrix:sale.viewed.product", "bitronic", array(
		"VIEWED_COUNT" => "3",
		"VIEWED_NAME" => "Y",
		"VIEWED_IMAGE" => "Y",
		"VIEWED_PRICE" => "Y",
		"VIEWED_CURRENCY" => "default",
		"VIEWED_CANBUY" => "N",
		"VIEWED_CANBASKET" => "N",
		"VIEWED_IMG_HEIGHT" => "100",
		"VIEWED_IMG_WIDTH" => "100",
		"IMAGE_SET" => "3",
		"BASKET_URL" => "/personal/basket.php",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id_viewed",
		"SET_TITLE" => "N"
	),
	false,
	array("ACTIVE_COMPONENT" => "Y")
	);					
}?>