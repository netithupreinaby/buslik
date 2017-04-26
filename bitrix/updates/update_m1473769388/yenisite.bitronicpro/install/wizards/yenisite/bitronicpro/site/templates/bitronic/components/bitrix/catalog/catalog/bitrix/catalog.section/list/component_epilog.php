<?
global $APPLICATION;

$dir = explode("/", $APPLICATION->GetCurDir());
unset($dir[count($dir)-1]);
unset($dir[count($dir)-1]);
$dir = implode("/", $dir)."/";


if($arResult["NAME"]){
	CModule::IncludeModule('iblock');
	$res2 = CIBlockSection::GetByID($arResult["IBLOCK_SECTION_ID"])->GetNext();
	$section = array();
		//$section[] = array("NAME" => $res2["NAME"], "CODE" => $res2["CODE"]);
	while($res2["IBLOCK_SECTION_ID"] > 0 || $res2["DEPTH_LEVEL"]==1){		
		$section[] = array("NAME" => $res2["NAME"], "CODE" => $res2["CODE"]);		
		$res2 = CIBlockSection::GetByID($res2["IBLOCK_SECTION_ID"])->GetNext();
	}
	for($i = count($section)-1; $i >= 0; $i--)
		$APPLICATION->AddChainItem($section[$i]["NAME"], $dir.$section[$i]["CODE"]."/");	
    $APPLICATION->AddChainItem($arResult["NAME"]);
    $APPLICATION->SetTitle($arResult["NAME"]);
    $APPLICATION->SetPageProperty("title", $arResult["NAME"]);
}
?>
