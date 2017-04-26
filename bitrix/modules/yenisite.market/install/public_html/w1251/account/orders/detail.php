<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("��� ������");

global $USER;
if ($USER->IsAuthorized()):?>

<?$APPLICATION->IncludeComponent("bitrix:news.detail", "market", Array(
	"IBLOCK_TYPE" => "yenisite_market",	// ��� ��������������� ����� (������������ ������ ��� ��������)
		"IBLOCK_ID" => "#IBLOCK_ID#",	// ��� ��������������� �����
		"ELEMENT_ID" => intval($_REQUEST["ID"]),	// ID �������
		"ELEMENT_CODE" => "",	// ��� �������
		"CHECK_DATES" => "Y",	// ���������� ������ �������� �� ������ ������ ��������
		"FIELD_CODE" => array(	// ����
			0 => "CREATED_BY",
			1 => "DATE_CREATE",
		),
		"PROPERTY_CODE" => array(	// ��������
			0 => "PAYMENT_E",
			1 => "STATUS",
			2 => "FIO",
			4 => "PHONE",
			3 => "EMAIL",
			5 => "ABOUT",
			7 => "",
			8 => "",
			9 => "",
		),
		"IBLOCK_URL" => SITE_DIR."account/orders/",	// URL �������� ��������� ������ ��������� (�� ��������� - �� �������� ���������)
		"AJAX_MODE" => "N",	// �������� ����� AJAX
		"AJAX_OPTION_JUMP" => "N",	// �������� ��������� � ������ ����������
		"AJAX_OPTION_STYLE" => "Y",	// �������� ��������� ������
		"AJAX_OPTION_HISTORY" => "N",	// �������� �������� ��������� ��������
		"CACHE_TYPE" => "N",	// ��� �����������
		"CACHE_TIME" => "36000000",	// ����� ����������� (���.)
		"CACHE_GROUPS" => "Y",	// ��������� ����� �������
		"SET_TITLE" => "N",	// ������������� ��������� ��������
		"SET_BROWSER_TITLE" => "Y",	// ������������� ��������� ���� ��������
		"BROWSER_TITLE" => "-",	// ���������� ��������� ���� �������� �� ��������
		"SET_META_KEYWORDS" => "Y",	// ������������� �������� ����� ��������
		"META_KEYWORDS" => "-",	// ���������� �������� ����� �������� �� ��������
		"SET_META_DESCRIPTION" => "Y",	// ������������� �������� ��������
		"META_DESCRIPTION" => "-",	// ���������� �������� �������� �� ��������
		"SET_STATUS_404" => "N",	// ������������� ������ 404, ���� �� ������� ������� ��� ������
		"INCLUDE_IBLOCK_INTO_CHAIN" => "Y",	// �������� �������� � ������� ���������
		"ADD_SECTIONS_CHAIN" => "Y",	// �������� ������ � ������� ���������
		"ADD_ELEMENT_CHAIN" => "N",	// �������� �������� �������� � ������� ���������
		"ACTIVE_DATE_FORMAT" => "d.m.Y",	// ������ ������ ����
		"USE_PERMISSIONS" => "N",	// ������������ �������������� ����������� �������
		"PAGER_TEMPLATE" => ".default",	// ������ ������������ ���������
		"DISPLAY_TOP_PAGER" => "N",	// �������� ��� �������
		"DISPLAY_BOTTOM_PAGER" => "Y",	// �������� ��� �������
		"PAGER_TITLE" => "��������",	// �������� ���������
		"PAGER_SHOW_ALL" => "Y",	// ���������� ������ "���"
		"DISPLAY_DATE" => "Y",	// �������� ���� ��������
		"DISPLAY_NAME" => "Y",	// �������� �������� ��������
		"DISPLAY_PICTURE" => "Y",	// �������� ��������� �����������
		"DISPLAY_PREVIEW_TEXT" => "Y",	// �������� ����� ������
		"USE_SHARE" => "N",	// ���������� ������ ���. ��������
		"AJAX_OPTION_ADDITIONAL" => "",	// �������������� �������������
	),
	false
);?>

<?endif?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>