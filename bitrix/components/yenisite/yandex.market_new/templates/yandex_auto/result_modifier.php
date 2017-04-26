<?
//settings auto params
$arIdOfer = array();
$arProperties_names = array("MARK","MODEL","YEAR","STATE","COLOR","BODY_TYPE","ENGINE_TYPE","GEAR_TYPE","TRANSMITION","HAGGLE","SELLER","SELLER_CITY","SELLER_PHONE",
			"EQUIPMENT","VIN","ADDITIONAL_INFO","DOORS_COUNT","GENERATION","MODIFICATION","HORSE_POWER","DISPLACEMENT","RUN","RUN_METRIC","STEERING_WHEL",
			"STOCK","COUSTOM_HOUSE_STATE");

foreach($arResult["OFFER"] as $arOffer){
	if (!empty($arOffer["GROUP_ID"])){
		$arIdOfer[] = $arOffer["GROUP_ID"];
	}
		$arIdOfer[] = $arOffer["ID"];
		if (!in_array ($arOffer["IBLOCK_ID"],$arIblocks_ID))
		{
			$arIblocks_ID[] = $arOffer["IBLOCK_ID"];
		}
}

$arFillter = array(
	"ID" => $arIdOfer,
	"ACTIVE" => "Y",
	);

$arSelect_params = array();
foreach ($arProperties_names as $code) {
	foreach ($arParams as $key => $param) {
		if ($code == $key){
			$arSelect_params[$code] = $param;
		}
	}
};

$arSelect= array(
	"ID" ,
	"IBLOCK_ID",
	"NAME",
	);

foreach ($arSelect_params as $key => $value) {
	$arSelect[] = "PROPERTY_".$value;
}


$rsElement = CIBlockElement::GetList(array("ID" => "DESC"),$arFillter,false,false,$arSelect);

foreach ($arIblocks_ID as $codePar => $codeIb) {

	$Ibloc_prop = CIBlock::GetProperties($codeIb, Array(), Array());

	$arReverce_select_params = array();
	while ($arIbloc_prop = $Ibloc_prop->Fetch()) {
		foreach ($arSelect_params as $key => $param) {
			$arReverce_select_params[] = $key;
			if ($param ==  $arIbloc_prop["CODE"]){
				$arProps[$codeIb][$key] = $arIbloc_prop;
			}
		}
	}
	unset($arIbloc_prop);
}

$arElements = array();
while ($arElement = $rsElement->Fetch()) {
	$arElements[] = $arElement;
}
unset($rsElement,$arElement);

foreach ($arProps as $propKey => $propValue)
{
	foreach ($arProps[$propKey] as $code => $value) {
		foreach ($arResult["OFFER"] as $key=>$arOffer) {
			foreach ($arElements as $element) 
			{
				if ($arOffer["ID"] == $element["ID"] && $arOffer['IBLOCK_ID'] == $element["IBLOCK_ID"])
				{
					if ($value['MULTIPLE'] == "Y")
					{
						if (!empty($element["PROPERTY_".$arParams[$code]."_VALUE"]))
						{
							$arProps[$code]["VALUE"][] = $element["PROPERTY_".$arParams[$code]."_VALUE"];
							$arProps[$code]["VALUE_ENUM_ID"][]= $element["PROPERTY_".$arParams[$code]."_ENUM_ID"];
							$arOffer['SKU_PROP_EMPTY'] = 'N';
						} 
						else
						{
							$arProps[$code]["VALUE"][] = array();
							$arOffer['SKU_PROP_EMPTY'] = 'Y';
						}
					}
					else
					{
						if (!empty($element["PROPERTY_".$arParams[$code]."_VALUE"]))
						{
							$arProps[$code]["VALUE"] = $element["PROPERTY_".$arParams[$code]."_VALUE"];
							$arProps[$code]["VALUE_ENUM_ID"] = $element["PROPERTY_".$arParams[$code]."_ENUM_ID"];
							$arOffer['SKU_PROP_EMPTY'] = 'N';
						} else
						{
							$arProps[$code]["VALUE"] = "";
							$arProps[$code]["VALUE_ENUM_ID"] = "";
							$arOffer['SKU_PROP_EMPTY'] = 'Y';
						}
					}
				}
				elseif ($arOffer['SKU_PROP_EMPTY'] == 'Y' && $arOffer["GROUP_ID"] == $element["ID"] && $arOffer['IBLOCK_ID_CATALOG'] == $element["IBLOCK_ID"])
				{
					if ($value['MULTIPLE'] == "Y")
					{
						if (!empty($element["PROPERTY_".$arParams[$code]."_VALUE"]))
						{
							$arProps[$code]["VALUE"][] = $element["PROPERTY_".$arParams[$code]."_VALUE"];
							$arProps[$code]["VALUE_ENUM_ID"][]= $element["PROPERTY_".$arParams[$code]."_ENUM_ID"];
						} 
						else
						{
							$arProps[$code]["VALUE"][] = array();
						}
					}
					else
					{
						if (!empty($element["PROPERTY_".$arParams[$code]."_VALUE"]))
						{
							$arProps[$code]["VALUE"] = $element["PROPERTY_".$arParams[$code]."_VALUE"];
							$arProps[$code]["VALUE_ENUM_ID"] = $element["PROPERTY_".$arParams[$code]."_ENUM_ID"];
						} else
						{
							$arProps[$code]["VALUE"] = "";
							$arProps[$code]["VALUE_ENUM_ID"] = "";
						}
					}

				} 
			}

			$arResult["OFFER"][$key]["PROPERTIES"][$code] = $arProps[$code];
			if (!empty($arParams['TYPE_OFFER']))
			{
				if ($arParams['TYPE_OFFER'] == "PRIVATE"){
					$arResult["OFFER"][$key]["TYPE_OFFER"] = "private";
				}
				elseif ($arParams['TYPE_OFFER'] == "COMMERCIAL") {
					$arResult["OFFER"][$key]["TYPE_OFFER"] = "commercial";
				}
			}

			unset($arProps[$code]["VALUE"], $arProps[$code]["VALUE_ENUM_ID"]);
		}
	}
}

foreach ($arResult["OFFER"] as &$arItem)
{

	//Function fot get Multiplay values of prop, type E,L.
	$arItem["PRODUCT_PROPERTIES"] = CIBlockPriceTools::GetProductProperties(
	$arItem["IBLOCK_ID"],
	$arItem["ID"], 
	$arReverce_select_params, //params wich mean $key in ar
	$arItem["PROPERTIES"] //props with type of prop, value, value_enum, and other
	);
	
	foreach ($arSelect_params as $code =>$value)
	{
		$arVal_prod_prop = $arItem["PRODUCT_PROPERTIES"][$code]["VALUES"];
		if (!empty($arVal_prod_prop)){
			foreach ($arVal_prod_prop as $key => $param) 
			{
					$arItem["PROPERTIES"][$code]["PRODUCT_PROPERTIES_VALUE"][] = $param;
			}
		}
		elseif (is_array($arItem["PROPERTIES"][$code]["VALUE"]) && !empty($arItem["PROPERTIES"][$code]["VALUE"]))
		{
			$arItem["PROPERTIES"][$code]["PRODUCT_PROPERTIES_VALUE"] = $arItem["PROPERTIES"][$code]["VALUE"];
		} 
		elseif (!empty($arItem["PROPERTIES"][$code]["VALUE"]))
		{
			$arItem["PROPERTIES"][$code]["PRODUCT_PROPERTIES_VALUE"][] = $arItem["PROPERTIES"][$code]["VALUE"];
		}
	}
}






