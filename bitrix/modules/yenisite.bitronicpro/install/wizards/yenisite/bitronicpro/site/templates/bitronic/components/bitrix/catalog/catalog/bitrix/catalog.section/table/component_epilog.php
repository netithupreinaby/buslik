<?
global $APPLICATION;

$dir = explode("/", $APPLICATION->GetCurDir());
unset($dir[count($dir)-1]);
unset($dir[count($dir)-1]);

$sef = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "sef_mode", false, SITE_ID);
if ($sef == 'Y') {
	$arch = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "architect", false, SITE_ID);

	if ($arch == 'multi') {
		$dir = array_slice($dir, 0, 3);
	} else {
		$dir = array_slice($dir, 0, 2);
	}
}

$dir = implode("/", $dir)."/";

if($arResult["NAME"]) {
	CModule::IncludeModule('iblock');
	$res2 = CIBlockSection::GetByID($arResult["IBLOCK_SECTION_ID"])->GetNext();
	$section = array();
	//$section[] = array("NAME" => $res2["NAME"], "CODE" => $res2["CODE"]);
	while($res2["IBLOCK_SECTION_ID"] > 0 || $res2["DEPTH_LEVEL"] == 1) {
		$section[] = array("NAME" => $res2["NAME"], "CODE" => $res2["CODE"]);		
		$res2 = CIBlockSection::GetByID($res2["IBLOCK_SECTION_ID"])->GetNext();
	}

	for($i = count($section)-1; $i >= 0; $i--) {
		$APPLICATION->AddChainItem($section[$i]["NAME"], $dir.$section[$i]["CODE"]."/");
	}

    $APPLICATION->AddChainItem($arResult["NAME"]);
}
if(!CModule::IncludeModule('yenisite.meta'))
{
	$APPLICATION->SetPageProperty("title", empty($arResult['IPROPERTY_VALUES']['SECTION_META_TITLE'])? $arResult["NAME"]: $arResult['IPROPERTY_VALUES']['SECTION_META_TITLE']);
	$APPLICATION->SetTitle(empty($arResult['IPROPERTY_VALUES']['SECTION_PAGE_TITLE'])? $arResult['NAME']: $arResult['IPROPERTY_VALUES']['SECTION_PAGE_TITLE']);
	$APPLICATION->SetPageProperty("keywords", $arResult['IPROPERTY_VALUES']['SECTION_META_KEYWORDS']);
	$APPLICATION->SetPageProperty("description", $arResult['IPROPERTY_VALUES']['SECTION_META_DESCRIPTION']);
}
?>