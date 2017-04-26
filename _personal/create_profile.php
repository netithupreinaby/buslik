<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Создание профиля");
?> <?$APPLICATION->IncludeComponent("yenisite:sale.personal.profile.add", ".default", array(
	"PATH_TO_LIST" => "/personal/profiles.php",
	"PATH_TO_DETAIL" => "",
	"USE_AJAX_LOCATIONS" => "N",
	"SET_TITLE" => "N"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>