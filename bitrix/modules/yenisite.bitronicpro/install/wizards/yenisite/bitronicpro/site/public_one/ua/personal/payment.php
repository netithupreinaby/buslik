<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Оплата");
$APPLICATION->AddChainItem($APPLICATION->GetTitle());
?> <?$APPLICATION->IncludeComponent(
	"bitrix:sale.order.payment",
	"",
Array(),
false
);?>
<?require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_after.php");?>