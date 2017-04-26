<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
	return;
if(!CModule::IncludeModule("sebekon.deliveryprice"))
	return;


$arMaps = array();
$iblock = CIBlock::GetList(
	array(),
	array(
		"TYPE" => "sebekon_DELIVERY_PRICE",
		"CODE" => "sebekon_MAPS"
	)
);
if($iblock = $iblock->GetNext()){
	$maps = CIBlockElement::GetList(
		array(
			"SORT" 	=> "ASC",
			"NAME"	=> "ASC"
		), 
		array(
			"IBLOCK_ID" => $iblock["ID"],
			"ACTIVE" => "Y"
		)
	);
	while($map = $maps->GetNext()){
		$arMaps[$map["ID"]] = $map["NAME"];
	}
}

$PARAMETERS = array(
	"MAP" => Array(
		"PARENT" => "BASE",
		"NAME" => $iblock["NAME"],
		"TYPE" => "LIST",
		"VALUES" => $arMaps,
		"MULTIPLE" => "Y"
	)
);

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => $PARAMETERS
);
?>
