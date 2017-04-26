<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule("iblock"))die();

foreach($arCurrentValues["IBLOCK_ID_IN"] as $id)
if ($id > 0)
{
	$arProp = array();
	
	if ( CModule::IncludeModule('catalog') )
	{
		$andreytroll = CCatalog::GetList(array(),array("PRODUCT_IBLOCK_ID"=> $id), false, false, array());
		$check = $andreytroll->Fetch();
			
		$rsProp = CIBlockProperty::GetList(array("sort" => "desc"), array("IBLOCK_ID" => $check["IBLOCK_ID"],
			array("LOGIC" => "OR", array("PROPERTY_TYPE" => "L"),
			array("PROPERTY_TYPE" => "E"), array("PROPERTY_TYPE" => "N"),
			array("PROPERTY_TYPE" => "S")) ) );
			
		while( $arr = $rsProp->Fetch() )
		{
			if ( !in_array($arr["NAME"], $arProp) && ($arr["PROPERTY_TYPE"] == "E" || $arr["PROPERTY_TYPE"] == "L" ||
				$arr["PROPERTY_TYPE"] == "S" || $arr["PROPERTY_TYPE"] == "N") )
			{
				$arProp[$arr["CODE"]] = "SKU_".$arr["NAME"];
			}
		}
	}
		
	$rsProp = CIBlockProperty::GetList(array("sort" => "desc"), array("IBLOCK_ID" => $id,  array("LOGIC" => "OR",
		array("PROPERTY_TYPE" => "L"), array("PROPERTY_TYPE" => "E"), array("PROPERTY_TYPE" => "E"), array("PROPERTY_TYPE" => "N") ) ) );
		
	while ( $arr = $rsProp->Fetch() )
	{
		if ( !in_array($arr["NAME"], $arProp) && ($arr["PROPERTY_TYPE"] == "E" || $arr["PROPERTY_TYPE"] == "L" ||
			$arr["PROPERTY_TYPE"] == "S" || $arr["PROPERTY_TYPE"] == "N") )
		{
			$arProp[$arr["CODE"]] = $arr["NAME"];
		}
	}
}

	$arProp["EMPTY"] = "				"; 
	natsort($arProp);

$arTemplateParameters = array(
		"TYPE_OFFER" => array(
				"NAME" => GetMessage("TYPE_OFFER"),
				"TYPE" => "LIST",
				"VALUES" => array (
					"PRIVATE" => GetMessage("PRIVATE"),
					"COMMERCIAL" => GetMessage("COMMERCIAL"),
					),
				"DEFAULT" => "PRIVATE",
		),
	"MARK" => array(
				"NAME" => GetMessage("MARK"),
				"TYPE" => "LIST",
				"VALUES" => $arProp,
		),
	"MODEL" => array(
				"NAME" => GetMessage("MODEL"),
				"TYPE" => "LIST",
				"VALUES" => $arProp,
		),
	 "YEAR"  => array(
				"NAME" => GetMessage("YEAR"),
				"TYPE" => "LIST",
				"VALUES" => $arProp,
		), 
	 "SELLER_CITY"  => array(
				"NAME" => GetMessage("SELLER_CITY"),
				"TYPE" => "LIST",
				"VALUES" => $arProp,
		),
	 "SELLER_PHONE"  => array(
				"NAME" => GetMessage("SELLER_PHONE"),
				"TYPE" => "LIST",
				"VALUES" => $arProp,
		), 
	 "DISPLACEMENT"  => array(
				"NAME" => GetMessage('DISPLACEMENT'),
				"TYPE" => "LIST",
				"VALUES" => $arProp,
		),
	 "RUN"  => array(
				"NAME" => GetMessage("RUN"),
				"TYPE" => "LIST",
				"VALUES" => $arProp,
		),
	"RUN_METRIC"  => array(
				"NAME" => GetMessage("RUN_METRIC"),
				"TYPE" => "LIST",
				"VALUES" => $arProp,
		),
	"STEERING_WHEL"  => array(
				"NAME" => GetMessage("STEERING_WHEL"),
				"TYPE" => "LIST",
				"VALUES" => $arProp,
		),
	"STOCK"  => array(
				"NAME" => GetMessage("STOCK"),
				"TYPE" => "LIST",
				"VALUES" => $arProp,
		),
	 "STATE"  => array(
				"NAME" => GetMessage("STATE"),
				"TYPE" => "LIST",
				"VALUES" => $arProp,
		),
	 "COLOR"  => array(
				"NAME" => GetMessage("COLOR"),
				"TYPE" => "LIST",
				"VALUES" => $arProp,
		),
	 "BODY_TYPE"  => array(
				"NAME" => GetMessage("BODY_TYPE"),
				"TYPE" => "LIST",
				"VALUES" => $arProp,
		),
	 "ENGINE_TYPE"  => array(
				"NAME" => GetMessage("ENGINE_TYPE"),
				"TYPE" => "LIST",
				"VALUES" => $arProp,
		),
	 "GEAR_TYPE"  => array(
				"NAME" => GetMessage("GEAR_TYPE"),
				"TYPE" => "LIST",
				"VALUES" => $arProp,
		),
	 "TRANSMITION"  => array(
				"NAME" => GetMessage("TRANSMITION"),
				"TYPE" => "LIST",
				"VALUES" => $arProp,
		),
	 "HAGGLE"  => array(
				"NAME" => GetMessage("HAGGLE"),
				"TYPE" => "LIST",
				"VALUES" => $arProp,
		),
	"SELLER"  => array(
				"NAME" => GetMessage("SELLER"),
				"TYPE" => "LIST",
				"VALUES" => $arProp,
		),
	"EQUIPMENT"  => array(
				"NAME" => GetMessage("EQUIPMENT"),
				"TYPE" => "LIST",
				"VALUES" => $arProp,
		),
	"VIN"  => array(
				"NAME" => GetMessage("VIN"),
				"TYPE" => "LIST",
				"VALUES" => $arProp,
		),
	"ADDITIONAL_INFO"  => array(
				"NAME" => GetMessage("ADDITIONAL_INFO"),
				"TYPE" => "LIST",
				"VALUES" => $arProp,
		),
	"DOORS_COUNT"  => array(
				"NAME" => GetMessage("DOORS_COUNT"),
				"TYPE" => "LIST",
				"VALUES" => $arProp,
		),
	"GENERATION"  => array(
				"NAME" => GetMessage("GENERATION"),
				"TYPE" => "LIST",
				"VALUES" => $arProp,
		),
	"MODIFICATION"  => array(
				"NAME" => GetMessage("MODIFICATION"),
				"TYPE" => "LIST",
				"VALUES" => $arProp,
		),
	"HORSE_POWER"  => array(
				"NAME" => GetMessage('HORSE_POWER'),
				"TYPE" => "LIST",
				"VALUES" => $arProp,
		),
	"COUSTOM_HOUSE_STATE"  => array(
				"NAME" => GetMessage("COUSTOM_HOUSE_STATE"),
				"TYPE" => "LIST",
				"VALUES" => $arProp,
		),
	"SELF_SALES_NOTES" => array(
			"HIDDEN" => 'Y',
		),
	"SALES_NOTES_NAMES" => array(
		"HIDDEN" => 'Y',
		),
	"SELF_SALES_NOTES_INPUT" => array(
		"HIDDEN" => 'Y',
		),
	"IBLOCK_ORDER" => array(
		"HIDDEN" => 'Y',
		),
	"DETAIL_TEXT_PRIORITET" => array(
		"HIDDEN" => 'Y',
		),
	"NAME_PROP" => array(
		"HIDDEN" => 'Y',
		),
	"OLD_PRICE_LIST" => array(
		"HIDDEN" => 'Y',
		),
	"OLD_PRICES" => array(
		"HIDDEN" => 'Y',
		),
	"OLD_PRICE_CODE" => array(
		"HIDDEN" => 'Y',
		),
	"SKU_NAME_PARAM" => array(
		"HIDDEN" => 'Y',
		),
	"SKU_PROPERTY" => array(
		"HIDDEN" => 'Y',
		),
	"LOCAL_DELIVERY_COST" => array(
		"HIDDEN" => 'Y',
		),
	"PARAMS" => array(
		"HIDDEN" => 'Y',
		),
	"COND_PARAMS" => array(
		"HIDDEN" => 'Y',
		),
);

?>