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
	"PLACE" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("PLACE"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,		
	),
	"DATE" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("DATE"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
	"IS_PREMIERE" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("IS_PREMIERE"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
	"IS_KIDS" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("IS_KIDS"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
);
$arTemplateParameters['PARAMS']['HIDDEN'] = 'Y';
$arTemplateParameters['COND_PARAMS']['HIDDEN'] = 'Y';