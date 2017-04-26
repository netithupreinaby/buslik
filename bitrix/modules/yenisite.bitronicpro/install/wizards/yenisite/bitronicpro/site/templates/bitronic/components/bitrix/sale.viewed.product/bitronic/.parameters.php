<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$list = array () ;
if(CModule::IncludeModule("yenisite.resizer2")){
	$arSets = CResizer2Set::GetList();
	while($arr = $arSets->Fetch())
	{
		$list[$arr["id"]] = "[".$arr["id"]."] ".$arr["NAME"];
	}
	
	$arTemplateParameters = array(
	"IMAGE_SET" => array(
		"PARENT" => "BASE",
		"NAME" => GetMessage("IMAGE_SET"),
		"TYPE" => "LIST",
		"VALUES" => $list,
		"DEFAULT" => "3",
	));
}
else {

	$arTemplateParameters = array(
		"VIEWED_IMG_HEIGHT" => array(
			"NAME" => GetMessage("VIEWED_IMG_HEIGHT"),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "150",
			"COLS" => 5,
			"PARENT" => "BASE",
		),
		"VIEWED_IMG_WIDTH" => array(
			"NAME" => GetMessage("VIEWED_IMG_WIDTH"),
			"TYPE" => "STRING",
			"MULTIPLE" => "N",
			"DEFAULT" => "150",
			"COLS" => 5,
			"PARENT" => "BASE",
		)
	);
}
?>