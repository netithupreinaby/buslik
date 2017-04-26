<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle(" ");
if(!isset($_REQUEST[ID])):
?>

<?$APPLICATION->IncludeComponent("bitrix:sale.personal.profile.list", "list", array(
	"PATH_TO_DETAIL" => "",
	"PER_PAGE" => "20",
	"SET_TITLE" => "Y"
	),
	false
);?> 

<?else:?>

<?$APPLICATION->IncludeComponent("bitrix:sale.personal.profile", "template1", Array(
	
	),
	false
);?>

<?endif?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>