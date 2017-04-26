<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Title");
?><?$APPLICATION->IncludeComponent(
	"sebekon:delivery.calc",
	"visual",
	Array(
		"ADD2BASKET" => "N",
		"CUSTOM_CALC" => "Y",
		"CUSTOM_PRICE" => "1",
		"CUSTOM_WEIGHT" => "",
		"MAP" => array("136407"),
		"MULTI_POINTS" => "Y",
		"SHOW_ROUTE" => "N"
	)
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>