<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$pathToDefaultImg = str_replace($_SERVER['DOCUMENT_ROOT'], '', __FILE__);
$pathToDefaultImg = str_replace('.parameters.php', 'images/icon.png', $pathToDefaultImg);

global $arComponentParameters;

$arComponentParameters["GROUPS"]["YENISITE_BS_FLY"]= array(
	"NAME" => GetMessage("YS_BS_GROUP_NAME"),
	"SORT" => 2000,
);
$arProperty = array ('DETAIL_PICTURE' => GetMessage('YS_BS_AR_PROPS_DP'), 'PREVIEW_PICTURE' => GetMessage('YS_BS_AR_PROPS_PP'));
$arPosition = array ('LEFT' => GetMessage('YS_BS_AR_POSITION_LEFT'),'RIGHT' => GetMessage('YS_BS_AR_POSITION_RIGHT'));
if (CModule::IncludeModule("currency") && CModule::IncludeModule("sale")) {
$currency = CSaleLang::GetLangCurrency(SITE_ID);
$arCurrency = CCurrencyLang::GetCurrencyFormat($currency);
$strCurrency = str_replace('#', '', $arCurrency['FORMAT_STRING']);
$strCurrency = trim($strCurrency);
}
$arParamsCurrency = array ('ROUBLE_SYMBOL' => GetMessage('YS_BS_ROUBLE_SYMBOL'));
if($strCurrency && $arCurrency['CURRENCY'])
	$arParamsCurrency[$strCurrency] = "[{$arCurrency['CURRENCY']}] {$strCurrency}";

$arTemplateParameters = array(
	"COLOR_SCHEME" => Array(
		"PARENT" => "YENISITE_BS_FLY",
		"NAME" => GetMessage("YS_BS_COLOR"),
		"TYPE" => "LIST",
		"VALUES" => array("red" => GetMessage("YS_BS_COLOR_RED"), "green" => GetMessage("YS_BS_COLOR_GREEN"), "ice" => GetMessage("YS_BS_COLOR_BLUE"), "metal" => GetMessage("YS_BS_COLOR_METAL"), "pink" => GetMessage("YS_BS_COLOR_PINK"), "yellow" => GetMessage("YS_BS_COLOR_YELLOW")),
		"ADDITIONAL_VALUES" => "Y",
	),
	"INCLUDE_JQUERY" => Array(
		"PARENT" => "YENISITE_BS_FLY",
		"NAME" => GetMessage("YS_BS_INCLUDE_JQUERY"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => 'Y'
	),
	"CHANGE_QUANTITY" => Array(
		"PARENT" => "YENISITE_BS_FLY",
		"NAME" => GetMessage("YS_BS_CHANGE_QUANTITY"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => 'N'
	),
	"IMAGE" => Array(
		"PARENT" => "YENISITE_BS_FLY",
		"NAME" => GetMessage("YS_BS_PROPERTY_IMAGE"),
		"TYPE" => "LIST",
		"VALUES" => $arProperty,
		"ADDITIONAL_VALUES" => "Y",
	),
	"CURRENCY" => Array(
		"PARENT" => "YENISITE_BS_FLY",
		"NAME" => GetMessage("YS_BS_CURRENCY"),
		"TYPE" => "LIST",
		"VALUES" => $arParamsCurrency,
		"ADDITIONAL_VALUES" => "Y",
	),
	"BASKET_ICON" => Array(
		"PARENT" => "YENISITE_BS_FLY",
		"NAME" => GetMessage("YS_BS_BASKET_ICON"),
		"TYPE" => "STRING",
		"DEFAULT" => "",
	),	
	"MARGIN_TOP" => Array(
		"PARENT" => "YENISITE_BS_FLY",
		"NAME" => GetMessage("YS_BS_MARGIN_TOP"),
		"TYPE" => "STRING",
		"DEFAULT" => "10"
	),
	"MARGIN_SIDE" => Array(
		"PARENT" => "YENISITE_BS_FLY",
		"NAME" => GetMessage("YS_BS_MARGIN_SIDE"),
		"TYPE" => "STRING",
		"DEFAULT" => "10"
	),
	"START_FLY_PX" => Array(
		"PARENT" => "YENISITE_BS_FLY",
		"NAME" => GetMessage("YS_BS_START_FLY"),
		"TYPE" => "STRING",
		"DEFAULT" => "100"
	),
	"BASKET_POSITION" => Array(
		"PARENT" => "YENISITE_BS_FLY",
		"NAME" => GetMessage("YS_BS_BASKET_POSITION"),
		"TYPE" => "LIST",
		"VALUES" => $arPosition,
		"DEFAULT" => "LEFT"
	),
);
if(CModule::IncludeModule('yenisite.resizer2'))
{
	$dbSets = CResizer2Set::GetList();
	while($arSet = $dbSets->Fetch())
	{
		$arSets[$arSet["id"]] = "[{$arSet["id"]}] {$arSet["NAME"]} ({$arSet['w']}x{$arSet['h']})";
		if($arSet['h'] == 50 && $arSet['w'] == 50)
			$defualt_set_id = $arSet['id'] ;
	}
	
	$arTemplateParameters['RESIZER2_SET'] = Array(
		"PARENT" => "YENISITE_BS_FLY",
		"NAME" => GetMessage("YS_BS_RESIZER2_SETS"),
		"TYPE" => "LIST",	
		"VALUES" => $arSets,
		"DEFAULT" => $defualt_set_id ? $defualt_set_id : '5',
	);
}
?> 