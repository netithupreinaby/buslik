<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

$arReturn = array('status' => 'error');

do {
	if(!check_bitrix_sessid()) {
		$arReturn['error'] = 'Wrong sessid!';
		break;
	}
	if (!CModule::IncludeModule("yenisite.specifications")) {
		$arReturn['error'] = 'Module "yenisite.specifications" is not installed!';
		break;
	}
	if (!CModule::IncludeModule("iblock")) {
		$arReturn['error'] = 'Module "iblock" is not installed!';
		break;
	}
	$arReturn = CSpecifications::ProcessAjax(array_merge($_GET, $_POST));
} while (0);

echo json_encode($arReturn);
die();

