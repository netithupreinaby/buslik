<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Регистрация");
?><?$APPLICATION->IncludeComponent(
	"abiatec:main.register", 
	"buslik", 
	array(
		"AUTH" => "Y",
		"REQUIRED_FIELDS" => array(
			0 => "EMAIL",
			1 => "NAME",
			2 => "LAST_NAME",
			3 => "PERSONAL_PHONE",
		),
		"SET_TITLE" => "Y",
		"SHOW_FIELDS" => array(
			0 => "EMAIL",
			1 => "NAME",
			2 => "LAST_NAME",
			3 => "WORK_PHONE",
		),
		"SUCCESS_PAGE" => "",
		"USER_PROPERTY" => array(
			0 => "UF_IM_SEARCH",
		),
		"DISCOUNT_TEXT" => "Хочешь получить скидку 10% на первую покупку?",
		"USE_BACKURL" => "Y",
		"COMPONENT_TEMPLATE" => "buslik",
		"FIELDS_ORDER" => "NAME|LAST_NAME|EMAIL|WORK_PHONE|PASSWORD|CONFIRM_PASSWORD",
		"SPECIAL_OFFERS" => "Оставьте инфо о рождении племянника и других родственников и мы сделаем Вам специальное предложение!!!"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>