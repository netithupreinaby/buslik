<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(CModule::IncludeModule("yenisite.resizer2")){
	$arSets = CResizer2Set::GetList();
	while($arr = $arSets->Fetch())
	{
		$list[$arr["id"]] = "[".$arr["id"]."] ".$arr["NAME"];
	}

$arTemplateParameters = array(
		
		"BASKET_PHOTO" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("BASKET_PHOTO"),
			"TYPE" => "LIST",
			"VALUES" => $list,
		),
		
);
}
?>
