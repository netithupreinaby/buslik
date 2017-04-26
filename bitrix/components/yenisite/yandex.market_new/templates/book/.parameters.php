<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule("iblock"))
	return;

global $arComponentParameters;

$arComponentParameters["GROUPS"]["YENISITE_YM_VENDOR"]= array(
	"NAME" => GetMessage("YS_YM_VENDOR_NAME"),
	"SORT" => 2000,
);



foreach($arCurrentValues["IBLOCK_ID_IN"] as $id)
if($id > 0)
    {
        $rsProp = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $id,  array()   ));
        while($arr=$rsProp->Fetch())
		{
            if(!in_array($arr["NAME"], $arProp)){
                $arProp[$arr["CODE"]] = $arr["NAME"];
			}
		}
    }

		$arProp["EMPTY"] = "				";
	natsort($arProp);

$arTemplateParameters = array(
	"AUTHOR" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("AUTHOR"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,		
	),
	"PUBLISHER" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("PUBLISHER"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
	"SERIES" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("SERIES"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
		"YEAR" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("YEAR"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
	"ISBN" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("ISBN"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
	"VOLUME" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("VOLUME"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
	"PART" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("PART"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
	"BINDING" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("BINDING"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
	"PAGE_EXTENT" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("PAGE_EXTENT"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
);
$arTemplateParameters['PARAMS']['HIDDEN'] = 'Y';
$arTemplateParameters['COND_PARAMS']['HIDDEN'] = 'Y';