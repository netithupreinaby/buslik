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

	"WORLDREGION" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("WORLDREGION"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
	"REGION" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("REGION"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
	"DAYS" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("DAYS"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
	"DATATOUR" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("DATATOUR"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
		"HOTEL_STARS" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("HOTEL_STARS"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
	"COUNTRY" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("COUNTRY"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
	"ROOM" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("ROOM"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
		"MEAL" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("MEAL"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
		"INCLUDED" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("INCLUDED"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
		"TRANSPORT" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("TRANSPORT"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
);
$arTemplateParameters['PARAMS']['HIDDEN'] = 'Y';
$arTemplateParameters['COND_PARAMS']['HIDDEN'] = 'Y';