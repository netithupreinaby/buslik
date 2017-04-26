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

	"DIRECTOR" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("DIRECTOR"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
	"YEAR" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("YEAR"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
	"MEDIA" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("MEDIA"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
	"STARRING" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("STARRING"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
		"ORIGINALNAME" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("ORIGINALNAME"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
	"COUNTRY" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("COUNTRY"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
	"BARCODE" => Array(
		"PARENT" => "YENISITE_YM_VENDOR",
		"NAME" => GetMessage("BARCODE"),
		"TYPE" => "LIST",
		"VALUES" => $arProp,
	),
);
$arTemplateParameters['PARAMS']['HIDDEN'] = 'Y';
$arTemplateParameters['COND_PARAMS']['HIDDEN'] = 'Y';