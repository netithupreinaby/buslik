<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Редагування профілю");
?> <?$APPLICATION->IncludeComponent(
	"bitrix:sale.personal.profile.detail",
	"",
Array(),
false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>