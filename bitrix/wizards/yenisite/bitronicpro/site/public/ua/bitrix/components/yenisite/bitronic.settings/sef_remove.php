<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("yenisite.bitronic");
CModule::IncludeModule("yenisite.bitronicpro");
CModule::IncludeModule("yenisite.bitroniclite");

CModule::IncludeModule('iblock');

$sef 	= COption::GetOptionString(CYSBitronicSettings::getModuleId(), "sef_mode", false, SITE_ID);
$arch 	= COption::GetOptionString(CYSBitronicSettings::getModuleId(), "architect", false, SITE_ID);
if ($sef === 'N') die('end');

$typeC 		= $_REQUEST['type'];
$type 		= str_replace("catalog_", "", $typeC);
$type 		= str_replace(SITE_ID.'_', "", $type);
$typeDir    = str_replace("_", "-", $type);

$path = '/' . $typeDir . '/' . $_REQUEST['iblock'] . '/index.php';
$arUrls = CUrlRewriter::GetList(array('PATH' => $path , 'ID' => ''));

foreach ($arUrls as $arUrl) {
	if (!empty($arUrl["RULE"])) {
		CUrlRewriter::Delete($arUrl);
	}
}

?>