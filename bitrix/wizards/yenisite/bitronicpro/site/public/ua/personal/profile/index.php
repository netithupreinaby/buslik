<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("��������� �����������");
?><?$APPLICATION->IncludeComponent("bitrix:main.profile", "profile", Array(
	"SET_TITLE" => "Y",	// ������������� ��������� ��������
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>