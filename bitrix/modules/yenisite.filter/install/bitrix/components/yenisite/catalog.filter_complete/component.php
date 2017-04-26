<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("iblock"))
{
	ShowError(GetMessage("CC_BCF_MODULE_NOT_INSTALLED"));
	return;
}

include 'common.php';

// ini_set('error_reporting', E_ALL);
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
	
/*************************************************************************
	Processing of received parameters
*************************************************************************/
if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

unset($arParams["IBLOCK_TYPE"]); //was used only for IBLOCK_ID setup with Editor
$arParams["IBLOCK_ID"] = intval($arParams["IBLOCK_ID"]);

if(!is_array($arParams["FIELD_CODE"]))
	$arParams["FIELD_CODE"] = array();
foreach($arParams["FIELD_CODE"] as $k=>$v)
	if($v==="")
		unset($arParams["FIELD_CODE"][$k]);

if(!is_array($arParams["PROPERTY_CODE"]))
	$arParams["PROPERTY_CODE"] = array();
foreach($arParams["PROPERTY_CODE"] as $k=>$v)
	if($v === "")
		unset($arParams["PROPERTY_CODE"][$k]);

if(!is_array($arParams["PRICE_CODE"]))
	$arParams["PRICE_CODE"] = array();

if(!is_array($arParams["OFFERS_FIELD_CODE"]))
	$arParams["OFFERS_FIELD_CODE"] = array();
foreach($arParams["OFFERS_FIELD_CODE"] as $k=>$v)
	if($v==="")
		unset($arParams["OFFERS_FIELD_CODE"][$k]);

if(!is_array($arParams["OFFERS_PROPERTY_CODE"]))
	$arParams["OFFERS_PROPERTY_CODE"] = array();
foreach($arParams["OFFERS_PROPERTY_CODE"] as $k=>$v)
	if($v==="")
		unset($arParams["OFFERS_PROPERTY_CODE"][$k]);

$arParams["SAVE_IN_SESSION"] = $arParams["SAVE_IN_SESSION"]=="Y";

if(strlen($arParams["FILTER_NAME"])<=0|| !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"]))
	$arParams["FILTER_NAME"] = "arrFilter";
$FILTER_NAME = $arParams["FILTER_NAME"];

global $$FILTER_NAME;
$$FILTER_NAME = array();

$arParams["NUMBER_WIDTH"] = intval($arParams["NUMBER_WIDTH"]);
if($arParams["NUMBER_WIDTH"] <= 0)
	$arParams["NUMBER_WIDTH"] = 3;
$arParams["TEXT_WIDTH"] = intval($arParams["TEXT_WIDTH"]);
if($arParams["TEXT_WIDTH"] <= 0)
	$arParams["TEXT_WIDTH"] = 20;
$arParams["LIST_HEIGHT"] = intval($arParams["LIST_HEIGHT"]);
if($arParams["LIST_HEIGHT"] <= 0)
	$arParams["LIST_HEIGHT"] = 5;

/*************************************************************************
		Processing the "Filter" and "Reset" button actions
*************************************************************************/

//if($arParams[IBLOCK_SECTION]!="-1") {

	$select = array('ID', 'IBLOCK_ID');
	$selectOf = array("IBLOCK_ID");
  
	foreach($arParams['FIELD_CODE'] as $s)
		$select[] = $s;

	foreach($arParams['PROPERTY_CODE'] as $s)
		$select[] = "PROPERTY_".$s;
	
	foreach($arParams['OFFERS_PROPERTY_CODE'] as $s)
		$selectOf[] = "PROPERTY_".$s;
		
	$arElems = array();
	
	//
	// Structure of cached data
	//
	// 'vars' => array(
	//			'sec' 				=> array(),	- sections
	//			'arElemsSecLess15' 	=> array(),	- elements with sections and less than 15 properties
	//			'arElemsSecMore15' 	=> array(), - elements with sections and more than 15 properties
	//			'arElemsLess15' 	=> array(), - elements with less than 15 properties
	//			'arElemsMore15' 	=> array(), - elements with more than 15 properties
	//			'OFFERS'			=> Y|N,		- If SKU in this Iblock
	//			'IBLOCK_ID'			=> ID,		- ID  this Iblock
	//			'allValuesOf' 		=> array(),	- Values of SKU properties
	//			'EList' 			=> array('0' => array(), '1' => array, ...), - Property type is linking to elements in list view
	//			'List' 				=> array('0' => array(), '1' => array, ...), - Property type is linking to elements
	//			'LIST_TYPE' 		=> array('IBLOCK_ID' => array('PROPERTY_CODE' => 'LIST_TYPE', ...)), - List type of 'PROPERTY_CODE'
	//			'min'				=> array(),	- min price of product
	//			'max'				=> array(),	- max price of product
	//			'min2'				=> array(),	- min price of SKU
	//			'max2'				=> array()) - max price of SKU
	
	$cache_id = getCacheIdFromParams($arParams);
	$cache_id .= ':'.$USER->GetUserGroupString();
	
	$cache_time = $arParams["CACHE_TIME"];
	
	$cache = new CPHPCache();
	$vars = array();
	
	if ($cache_time > 0 && $cache->InitCache($cache_time, $cache_id, '/filter'))
	{
		$variables = $cache->GetVars();
		$vars['vars'] = $variables['vars'];
		unset($variables);
	}
	
	if($arParams['IBLOCK_SECTION'])
	{	
		$sec = $vars['vars']['sec'];
		
		if (empty($sec))
		{
			$sec = CIBlockSection::GetList(array(), array('IBLOCK_ID' => $arParams['IBLOCK_ID'], "ACTIVE" => "Y", "INCLUDE_SUBSECTIONS" => "Y", 'CODE' => $arParams['IBLOCK_SECTION']))->Fetch();
			
			$varsTemp["sec"] = $sec;	
		}
		
		if(!$sec['ID']) $sec['ID'] = $arParams['IBLOCK_SECTION'];
		
		if($sec['ID'] == "")
			$res = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ACTIVE" => "Y", "INCLUDE_SUBSECTIONS" => "Y"), false, false, $select);
		else
		{
			if (count($select) <= 15)
			{
				$arElems = $vars['vars']["arElemsSecLess15"];
				
				if(empty($arElems))
				{
					$res = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ACTIVE" => "Y", "INCLUDE_SUBSECTIONS" => "Y", "SECTION_ID" => $sec['ID']), false, false, $select);
					while($arElem = $res->Fetch())
					{
						$arElems[] = $arElem;
					}
					
					$varsTemp["arElemsSecLess15"] = $arElems;
				}
			}
			else
			{
				$arElems = $vars['vars']["arElemsSecMore15"];
				
				if (empty($arElems))
				{
					$int = 15;
					$len = count($select);
					$kol = 0;
					for($i = 0; $i < $len; $i += $int)
					{
						$kol += $int;
						if ($kol < $len)
							$sel = array_slice($select, $i, $int);
						else
							$sel = array_slice($select, $i, $len - $i);
							
						$res = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ACTIVE" => "Y", "INCLUDE_SUBSECTIONS" => "Y", "SECTION_ID" => $sec['ID']), false, false, $sel);
						
						while($arElem = $res->Fetch())
						{
							$arElems[] = $arElem;
						}
						
						$varsTemp["arElemsSecMore15"] = $arElems;
					}
				}
			}
		}
	}
	else
	{
		if (count($select) <= 15)
		{
			$arElems = $vars['vars']["arElemsLess15"];

			if (empty($arElems))
			{
				$res = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ACTIVE" => "Y", "INCLUDE_SUBSECTIONS" => "Y"), false, false, $select);
				while($arElem = $res->Fetch())
				{
					$arElems[] = $arElem;
				}
				$varsTemp["arElemsLess15"] = $arElems;
			}
		}
		else
		{
			
			$arElems = $vars['vars']["arElemsMore15"];
			
			if (empty($arElems))
			{
				$int = 15;
				$len = count($select);
				$kol = 0;
				for($i = 0; $i < $len; $i += $int)
				{
					$kol += $int;
					if ($kol < $len)
						$sel = array_slice($select, $i, $int);
					else
						$sel = array_slice($select, $i, $len - $i);
						
					$res = CIBlockElement::GetList(array(), array("IBLOCK_ID" => $arParams['IBLOCK_ID'], "ACTIVE" => "Y", "INCLUDE_SUBSECTIONS" => "Y"), false, false, $sel);
					
					while($arElem = $res->Fetch())
					{
						$arElems[] = $arElem;
					}
				}
				
				$varsTemp["arElemsMore15"] = $arElems;
			}
		}
	}

	global $ids;
    $ids = array();
			
	$allValues = array();
	$allValuesOf = array();
  
if(CModule::IncludeModule('catalog'))
{
	$check["OFFERS"] 	= $vars['vars']['OFFERS'];
	$check["IBLOCK_ID"] = $vars['vars']['IBLOCK_ID']; 
		
	if (empty($check["OFFERS"]))
	{
		
		$cat = CCatalog::GetList(array(), array());
		
		$check["OFFERS"] = "N";
		while($rcat = $cat->GetNext())
		{
			if($rcat['PRODUCT_IBLOCK_ID'] == $arParams['IBLOCK_ID'])
			{
				$check["OFFERS"] = "Y";
				$check["IBLOCK_ID"] = $rcat['IBLOCK_ID'];
				break;
			}
		}
		
		$varsTemp['OFFERS'] 	= $check["OFFERS"];
		$varsTemp['IBLOCK_ID'] 	= $check["IBLOCK_ID"];
	}
}

$allValuesOf = $vars['vars']["allValuesOf"];
if (empty($allValuesOf)) $getFromCache = 1;

foreach($arElems as $arElem)
{		
	if(CModule::IncludeModule('catalog'))
	{
		// offers
		// $check = CCatalog::GetByIDExt($arElem["IBLOCK_ID"]);
		if($check["OFFERS"] != "N")
		{
			if ($getFromCache)
			{
				$ofob = CIBLockElement::GetList(array(), array("IBLOCK_ID" => $check["IBLOCK_ID"], "PROPERTY_CML2_LINK" => $arElem["ID"]), false, false, $selectOf);
				
				while($elof = $ofob->Fetch())
				{
					foreach($elof as $key=>$value)
					{
						if($key == 'TAGS')
						{
							$v = str_replace(',', ',', $value);
							$v = explode(',', $v);
							foreach($v as $vi)
							{
								if(!in_array($vi, $allValuesOf['TAGS']) && $vi)
									$allValuesOf['TAGS'][] = $vi;
							}
						}
						else
						{
							if(is_array($value))
							{
								foreach($value as $ky => $v)
								{
									if(!in_array($v, $allValuesOf[$key]) && $v != "")
									{
										if(!$allValuesOf[$key][$ky])
											$allValuesOf[$key][$ky] = $v;
										else
											$allValuesOf[$key][] = $v;
									}
								}
							}
							else
							{
								if (is_null($allValuesOf[$key])) $allValuesOf[$key] = Array();
								if(!in_array($value, $allValuesOf[$key]) && $value != "")
									$allValuesOf[$key][] = $value;
								
								if (is_null($allValuesOf['TAGS'])) $allValuesOf['TAGS'] = Array();
								asort($allValuesOf['TAGS']);
							}
						}
					}
				}
			}
		} // offers
	}

	$ids[] = $arElem["ID"];

    foreach($arElem as $key=>$value)
	{
		if($key == 'TAGS')
		{
			$v = str_replace(', ', ',', $value);
			$v = explode(',', $v);
			foreach($v as $vi)
			{
				if (is_null($allValues['TAGS'])) $allValues['TAGS'] = Array();
				if(!in_array($vi, $allValues['TAGS']) && $vi)
				{
					$allValues['TAGS'][] = $vi;
					asort($allValues['TAGS']);
				}
			}
		}
		else
		{
			if(is_array($value))
			{
				foreach($value as $ky => $v)
				{
					if(!in_array($v, $allValues[$key]) && $v != "")
					{
						if(!$allValues[$key][$ky])
							$allValues[$key][$ky] = $v;
						else
							$allValues[$key][] = $v;
					}
				}
			}
			else
			{
				if (is_null($allValues[$key])) $allValues[$key] = Array();
				if(!in_array($value, $allValues[$key]) && $value != "")	  
					$allValues[$key][] = $value;
			}
		}
	}
} // end foreach ( $arElem = $res->Fetch() )

$varsTemp["allValuesOf"] = $allValuesOf;

$arDateFields = array(
	"ACTIVE_DATE" => array(
		"from" => "_ACTIVE_DATE_1",
		"to" => "_ACTIVE_DATE_2",
		"days_to_back" => "_ACTIVE_DATE_1_DAYS_TO_BACK",
		"filter_from" => ">=DATE_ACTIVE_FROM",
		"filter_to" => "<=DATE_ACTIVE_TO",
	),
	"DATE_ACTIVE_FROM" => array(
		"from" => "_DATE_ACTIVE_FROM_1",
		"to" => "_DATE_ACTIVE_FROM_2",
		"days_to_back" => "_DATE_ACTIVE_FROM_1_DAYS_TO_BACK",
		"filter_from" => ">=DATE_ACTIVE_FROM",
		"filter_to" => "<=DATE_ACTIVE_FROM",
	),
	"DATE_ACTIVE_TO" => array(
		"from" => "_DATE_ACTIVE_TO_1",
		"to" => "_DATE_ACTIVE_TO_2",
		"days_to_back" => "_DATE_ACTIVE_TO_1_DAYS_TO_BACK",
		"filter_from" => ">=DATE_ACTIVE_TO",
		"filter_to" => "<=DATE_ACTIVE_TO",
	),
	"DATE_CREATE" => array(
		"from" => "_DATE_CREATE_1",
		"to" => "_DATE_CREATE_2",
		"days_to_back" => "_DATE_CREATE_1_DAYS_TO_BACK",
		"filter_from" => ">=DATE_CREATE",
		"filter_to" => "<=DATE_CREATE",
	),
);

/* Init filter values */
$arrPFV = array();
$arrCFV = array();
$arrFFV = array();	// Element fields value
$arrDFV = array();	// Element date fields
$arrOFV = array();	// Offer fields values
$arrODFV = array();	// Offer date fields
$arrOPFV = array();	// Offer properties fields
foreach($arDateFields as $id => $arField)
{
	$arField["from"] = array(
		"name" => $FILTER_NAME.$arField["from"],
		"value" => "",
	);
	$arField["to"] = array(
		"name" => $FILTER_NAME.$arField["to"],
		"value" => "",
	);
	$arField["days_to_back"] = array(
		"name" => $FILTER_NAME.$arField["days_to_back"],
		"value" => "",
	);
	$arrDFV[$id] = $arField;

	$arField["from"]["name"] = "OF_".$arField["from"]["name"];
	$arField["to"]["name"] = "OF_".$arField["to"]["name"];
	$arField["days_to_back"]["name"] = "OF_".$arField["days_to_back"]["name"];
	$arrODFV[$id] = $arField;
}

/*Leave filter values empty*/
if(strlen($_REQUEST["del_filter"]) > 0)
{
	foreach($arrDFV as $id => $arField)
		$GLOBALS[$arField["days_to_back"]["name"]] = "";

	foreach($arrODFV as $id => $arField)
		$GLOBALS[$arField["days_to_back"]["name"]] = "";
}
/*Read filter values from request*/
elseif(strlen($_REQUEST["set_filter"]) > 0)
{
	if(isset($_REQUEST[$FILTER_NAME."_pf"]))
		$arrPFV = $_REQUEST[$FILTER_NAME."_pf"];
	if(isset($_REQUEST[$FILTER_NAME."_cf"]))
		$arrCFV = $_REQUEST[$FILTER_NAME."_cf"];
	if(isset($_REQUEST[$FILTER_NAME."_ff"]))
		$arrFFV = $_REQUEST[$FILTER_NAME."_ff"];
	if(isset($_REQUEST[$FILTER_NAME."_of"]))
		$arrOFV = $_REQUEST[$FILTER_NAME."_of"];
	if(isset($_REQUEST[$FILTER_NAME."_op"]))
		$arrOPFV = $_REQUEST[$FILTER_NAME."_op"];

	$now = time();
	foreach($arrDFV as $id => $arField)
	{
		$name = $arField["from"]["name"];
		if(isset($_REQUEST[$name]))
			$arrDFV[$id]["from"]["value"] = $_REQUEST[$name];

		$name = $arField["to"]["name"];
		if(isset($_REQUEST[$name]))
			$arrDFV[$id]["to"]["value"] = $_REQUEST[$name];

		$name = $arField["days_to_back"]["name"];
		if(isset($_REQUEST[$name]))
		{
			$value = $arrDFV[$id]["days_to_back"]["value"] = $_REQUEST[$name];
			if(strlen($value) > 0)
				$arrDFV[$id]["from"]["value"] = GetTime($now - 86400*intval($value));
		}
	}

	foreach($arrODFV as $id => $arField)
	{
		$name = $arField["from"]["name"];
		if(isset($_REQUEST[$name]))
			$arrODFV[$id]["from"]["value"] = $_REQUEST[$name];

		$name = $arField["to"]["name"];
		if(isset($_REQUEST[$name]))
			$arrODFV[$id]["to"]["value"] = $_REQUEST[$name];

		$name = $arField["days_to_back"]["name"];
		if(isset($_REQUEST[$name]))
		{
			$value = $arrODFV[$id]["days_to_back"]["value"] = $_REQUEST[$name];
			if(strlen($value) > 0)
				$arrODFV[$id]["from"]["value"] = GetTime($now - 86400*intval($value));
		}
	}
}
/*No action specified, so read from the session (if parameter is set)*/
elseif($arParams["SAVE_IN_SESSION"])
{
	if(isset($_SESSION[$FILTER_NAME."arrPFV"]))
		$arrPFV = $_SESSION[$FILTER_NAME."arrPFV"];
	if(isset($_SESSION[$FILTER_NAME."arrCFV"]))
		$arrCFV = $_SESSION[$FILTER_NAME."arrCFV"];
	if(isset($_SESSION[$FILTER_NAME."arrFFV"]))
		$arrFFV = $_SESSION[$FILTER_NAME."arrFFV"];
	if(isset($_SESSION[$FILTER_NAME."arrOFV"]))
		$arrOFV = $_SESSION[$FILTER_NAME."arrOFV"];
	if(isset($_SESSION[$FILTER_NAME."arrOPFV"]))
		$arrOPFV = $_SESSION[$FILTER_NAME."arrOPFV"];
	if(isset($_SESSION[$FILTER_NAME."arrDFV"]) && is_array($_SESSION[$FILTER_NAME."arrDFV"]))
	{
		foreach($_SESSION[$FILTER_NAME."arrDFV"] as $id => $arField)
		{
			$arrDFV[$id]["from"]["value"] = $arField["from"]["value"];
			$arrDFV[$id]["to"]["value"] = $arField["to"]["value"];
			$arrDFV[$id]["days_to_back"]["value"] = $arField["days_to_back"]["value"];
		}
	}
	if(isset($_SESSION[$FILTER_NAME."arrODFV"]) && is_array($_SESSION[$FILTER_NAME."arrODFV"]))
	{
		foreach($_SESSION[$FILTER_NAME."arrODFV"] as $id => $arField)
		{
			$arrODFV[$id]["from"]["value"] = $arField["from"]["value"];
			$arrODFV[$id]["to"]["value"] = $arField["to"]["value"];
			$arrODFV[$id]["days_to_back"]["value"] = $arField["days_to_back"]["value"];
		}
	}
}

/*Save filter values to the session*/
if($arParams["SAVE_IN_SESSION"])
{
	$_SESSION[$FILTER_NAME."arrPFV"] = $arrPFV;
	$_SESSION[$FILTER_NAME."arrCFV"] = $arrCFV;
	$_SESSION[$FILTER_NAME."arrFFV"] = $arrFFV;
	$_SESSION[$FILTER_NAME."arrOFV"] = $arrOFV;
	$_SESSION[$FILTER_NAME."arrDFV"] = $arrDFV;
	$_SESSION[$FILTER_NAME."arrODFV"] = $arrODFV;
	$_SESSION[$FILTER_NAME."arrOPFV"] = $arrOPFV;
}

if($this->StartResultCache(false, ($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups())))
{
	$arResult["arrProp"] = array();
	$arResult["arrPrice"] = array();
	$arResult["arrSection"] = array();
	$arResult["arrOfferProp"] = array();

	// simple fields
	if (in_array("SECTION_ID", $arParams["FIELD_CODE"]))
	{
		$arResult["arrSection"][0] = GetMessage("CC_BCF_TOP_LEVEL");
		$rsSection = CIBlockSection::GetList(
			Array("left_margin"=>"asc"),
			Array(
				"IBLOCK_ID"=>$arParams["IBLOCK_ID"],
				"ACTIVE"=>"Y",
			),
			false,
			Array("ID", "DEPTH_LEVEL", "NAME")
		);
		while($arSection = $rsSection->Fetch())
		{
			$arResult["arrSection"][$arSection["ID"]] = str_repeat(" . ", $arSection["DEPTH_LEVEL"]).$arSection["NAME"];
		}
	}

	// prices
	if(CModule::IncludeModule("catalog"))
	{
		$rsPrice = CCatalogGroup::GetList($v1, $v2);
		while($arPrice = $rsPrice->Fetch())
		{	
			if(($arPrice["CAN_ACCESS"] == "Y" || $arPrice["CAN_BUY"] == "Y") && in_array($arPrice["NAME"],$arParams["PRICE_CODE"]))
				$arResult["arrPrice"][$arPrice["NAME"]] = array("ID"=>$arPrice["ID"], "TITLE"=>$arPrice["NAME_LANG"]);
		}
	}
	else
	{
		$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arParams["IBLOCK_ID"]));
		while($arProp = $rsProp->Fetch())
		{
			if(in_array($arProp["CODE"],$arParams["PRICE_CODE"]) && in_array($arProp["PROPERTY_TYPE"], array("N")))
				$arResult["arrPrice"][$arProp["CODE"]] = array("ID"=>$arProp["ID"], "TITLE"=>$arProp["NAME"]);
		}
	}
	
	// properties
	$rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arParams["IBLOCK_ID"]));
	while ($arProp = $rsProp->Fetch())
	{
		if(in_array($arProp["CODE"],$arParams["PROPERTY_CODE"]) && $arProp["PROPERTY_TYPE"] != "F")
		{
			$arTemp = array(
				"CODE" => $arProp["CODE"],
				"NAME" => $arProp["NAME"],
				"PROPERTY_TYPE" => $arProp["PROPERTY_TYPE"],
				"USER_TYPE" => $arProp["USER_TYPE"],
				"MULTIPLE" => $arProp["MULTIPLE"],
				"LIST_TYPE" => $arProp["LIST_TYPE"],
			);
			if ($arProp["PROPERTY_TYPE"] == "L")
			{
				$arrEnum = array();
				$rsEnum = CIBlockProperty::GetPropertyEnum($arProp["ID"], array("sort"=>"asc", "value"=>"asc"));
				while($arEnum = $rsEnum->Fetch())
				{
					if (in_array( $arEnum["VALUE"], $allValues['PROPERTY_'.strtoupper($arProp["CODE"]).'_VALUE'] ))
						$arrEnum[$arEnum["ID"]] = $arEnum["VALUE"];
				}
				$arTemp["VALUE_LIST"] = $arrEnum;
			}
			
			/* if ($arProp["PROPERTY_TYPE"] == "E")
			{
				$ar = array();
				$rs = CIBlockElement::GetList( array("sort"=>"asc", "name"=>"asc"), array( "PROPERTY_".$arProp["CODE"]) );
				while($arRes = $rs->GetNext())
				{
					if (in_array( $arRes["ID"], $allValues['PROPERTY_'.strtoupper($arProp["CODE"]).'_VALUE'] ))
						$ar[] = $arRes["ID"];
				}
					
				// $allValues['PROPERTY_'.strtoupper($arProp["CODE"]).'_VALUE'] = $ar;
			} */
			
			$arResult["arrProp"][$arProp["ID"]] = $arTemp;
		}
	}

	
	
	// offer properties
	$arOffersIBlock = CIBlockPriceTools::GetOffersIBlock($arParams["IBLOCK_ID"]);
	if(is_array($arOffersIBlock))
	{	
		$rsProp = CIBlockProperty::GetList(Array(), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$arOffersIBlock["OFFERS_IBLOCK_ID"]));
		while ($arProp = $rsProp->Fetch())
		{
			if(in_array($arProp["CODE"], $arParams["OFFERS_PROPERTY_CODE"]) && $arProp["PROPERTY_TYPE"] != "F")
			{
				$arTemp = array(
					"CODE" => $arProp["CODE"],
					"NAME" => $arProp["NAME"],
					"PROPERTY_TYPE" => $arProp["PROPERTY_TYPE"],
					"MULTIPLE" => $arProp["MULTIPLE"],
				);
				if ($arProp["PROPERTY_TYPE"] == "L")
				{
					$arrEnum = array();
					$rsEnum = CIBlockProperty::GetPropertyEnum($arProp["ID"], array("sort"=>"asc", "value"=>"asc"));
					while($arEnum = $rsEnum->Fetch())
					{
						if (in_array( $arEnum["VALUE"], $allValuesOf['PROPERTY_'.strtoupper($arProp["CODE"]).'_VALUE'] ))
							$arrEnum[$arEnum["ID"]] = $arEnum["VALUE"];
						
						$arrEnum[$arEnum["ID"]] = $arEnum["VALUE"];
					}
					$arTemp["VALUE_LIST"] = $arrEnum;
				}
				
				/* if ($arProp["PROPERTY_TYPE"] == "E")
				{	
					$ar = array();
					$rs = CIBlockElement::GetList( array("sort"=>"asc", "name"=>"asc"), array( "PROPERTY_".$arProp["CODE"]) );
					while($arRes = $rs->GetNext())
					{
						if (in_array( $arRes["ID"], $allValuesOf['PROPERTY_'.strtoupper($arProp["CODE"]).'_VALUE'] ))
							$ar[] = $arRes["ID"];
					}
						
					// $allValuesOf['PROPERTY_'.strtoupper($arProp["CODE"]).'_VALUE'] = $ar;
				} */
				
				$arResult["arrOfferProp"][$arProp["ID"]] = $arTemp;
			}
		}
	}

$this->EndResultCache();
}

$arResult["FORM_ACTION"] = isset($_SERVER['REQUEST_URI'])? htmlspecialchars($_SERVER['REQUEST_URI']): "";
$arResult["FILTER_NAME"] = $FILTER_NAME;

/*************************************************************************
		Adding the titles and input fields
*************************************************************************/

$arResult["arrInputNames"] = array(); // array of the input field names; is being used in the function $APPLICATION->GetCurPageParam

// simple fields
$arResult["ITEMS"] = array();

foreach($arParams["FIELD_CODE"] as $field_code)
{
	$field_res = "";
	$arResult["arrInputNames"][$FILTER_NAME."_ff"]=true;
	$name = $FILTER_NAME."_ff[".$field_code."]";
	$value = $arrFFV[$field_code];
	switch ($field_code)
	{
		case "CODE":
		case "XML_ID":
		case "NAME":
		case "PREVIEW_TEXT":
		case "DETAIL_TEXT":
		case "IBLOCK_TYPE_ID":
		case "IBLOCK_ID":
		case "IBLOCK_CODE":
		case "IBLOCK_NAME":
		case "IBLOCK_EXTERNAL_ID":
		case "SEARCHABLE_CONTENT":
			$field_res = '<input type="text" name="'.$name.'" size="'.$arParams["TEXT_WIDTH"].'" value="'.htmlspecialchars($value).'" />';

			if (strlen($value)>0)
				${$FILTER_NAME}["?".$field_code] = $value;

			break;
		case "TAGS":
			$name = $FILTER_NAME."_ff[".$field_code."]";
			$value = $arrFFV[$field_code];
			$field_res .= '<select multiple name="'.$name.'[]"><option value="">'.GetMessage("CC_BCF_ALL").'</option>';
			foreach($allValues[$field_code] as $vt)
			{	
				if (is_null($value)) $value = Array();
				if($vt == $value || in_array($vt, $value))
				{
					$checked = 'selected="selected"';
				}
				else 
					$checked="";
					
				$field_res .= '<option '.$checked.' value="'.$vt.'">'.$vt.'</option>';
			}			
			$field_res .= '</select>';

			foreach($value as &$vt) {$vt = "%".$vt."%";}
			${$FILTER_NAME}[$field_code] = $value;			
		      break;
		case "ID":
		case "SORT":
		case "SHOW_COUNTER":
			$name = $FILTER_NAME."_ff[".$field_code."][LEFT]";
			$value = $arrFFV[$field_code]["LEFT"];
			$field_res = GetMessage("CC_BCF_OT").'&nbsp;<input type="text" name="'.$name.'" size="'.$arParams["NUMBER_WIDTH"].'" value="'.htmlspecialchars($value).'" />';

			if(strlen($value)>0)
				${$FILTER_NAME}[">=".$field_code] = intval($value);

			$name = $FILTER_NAME."_ff[".$field_code."][RIGHT]";
			$value = $arrFFV[$field_code]["RIGHT"];
			$field_res .= '&nbsp;'.GetMessage("CC_BCF_DO").'&nbsp;<input type="text" name="'.$name.'" size="'.$arParams["NUMBER_WIDTH"].'" value="'.htmlspecialchars($value).'" />';

			if(strlen($value)>0)
				${$FILTER_NAME}["<=".$field_code] = intval($value);

			break;
		case "SECTION_ID":
			$arrRef = array("reference" => array_values($arResult["arrSection"]), "reference_id" => array_keys($arResult["arrSection"]));
			$field_res = SelectBoxFromArray($name, $arrRef, $value, " ", "");

			if ($value!="NOT_REF" && strlen($value)>0)
				${$FILTER_NAME}[$field_code] = intval($value);

			$_name = $FILTER_NAME."_ff[INCLUDE_SUBSECTIONS]";
			$_value = $arrFFV["INCLUDE_SUBSECTIONS"];
			$field_res .= "<br>".InputType("checkbox", $_name, "Y", $_value, false, "", "")."&nbsp;".GetMessage("CC_BCF_INCLUDE_SUBSECTIONS");

			if (strlen($value)>0 && $_value=="Y") ${$FILTER_NAME}["INCLUDE_SUBSECTIONS"] = "Y";

			break;

		case "ACTIVE_DATE":
		case "DATE_ACTIVE_FROM":
		case "DATE_ACTIVE_TO":
		case "DATE_CREATE":
			$arDateField = $arrDFV[$field_code];
			$arResult["arrInputNames"][$arDateField["from"]["name"]]=true;
			$arResult["arrInputNames"][$arDateField["to"]["name"]]=true;
			$arResult["arrInputNames"][$arDateField["days_to_back"]["name"]]=true;

			$field_res = CalendarPeriod(
				$arDateField["from"]["name"], $arDateField["from"]["value"],
				$arDateField["to"]["name"], $arDateField["to"]["value"],
				$FILTER_NAME."_form", "Y", "class=\"inputselect\"", "class=\"inputfield\""
			);

			if(strlen($arDateField["from"]["value"]) > 0)
				${$FILTER_NAME}[$arDateField["filter_from"]] = $arDateField["from"]["value"];

			if(strlen($arDateField["to"]["value"]) > 0)
				${$FILTER_NAME}[$arDateField["filter_to"]] = $arDateField["to"]["value"];
			break;
	}
	if($field_res)
		$arResult["ITEMS"][] = array(
			"NAME" => htmlspecialchars(GetMessage("IBLOCK_FIELD_".$field_code)),
			"INPUT" => $field_res,
			"FIELD_CODE" => $field_code,
			"INPUT_NAME" => $name,
			"INPUT_VALUE" => htmlspecialchars($value),
			"~INPUT_VALUE" => $value,
		);
}

$numEList = 0;
$numList  = 0;

foreach($arResult["arrProp"] as $prop_id => $arProp)
{
	$res = "";
	$arResult["arrInputNames"][$FILTER_NAME."_pf"] = true;
	switch ($arProp["PROPERTY_TYPE"])
	{
		case "L":

			$arProp["MULTIPLE"] = "Y";

			$name = $FILTER_NAME."_pf[".$arProp["CODE"]."]";
			$value = $arrPFV[$arProp["CODE"]];

			if($arProp["LIST_TYPE"] == "L")
			{

				if ($arProp["MULTIPLE"]=="Y")
					$res .= '<select class="select" multiple name="'.$name.'[]" size="'.$arParams["LIST_HEIGHT"].'">';
				else
					$res .= '<select class="select" multiple name="'.$name.'[]">';

				$res .= '<option value="">'.GetMessage("CC_BCF_ALL").'</option>';
	
				/* $arProp["VALUE_LIST"] = array();
				foreach($allValues['PROPERTY_'.strtoupper($arProp["CODE"]).'_ENUM_ID'] as $key=>$v)
				{
				    
				    $arProp["VALUE_LIST"][$v] = $allValues['PROPERTY_'.strtoupper($arProp["CODE"]).'_VALUE'][$key];
				}
                
                if(!count($arProp["VALUE_LIST"]))
				    $arProp["VALUE_LIST"] = $allValues['PROPERTY_'.strtoupper($arProp["CODE"]).'_VALUE'];
                
                natsort($arProp["VALUE_LIST"]); */

				foreach($arProp["VALUE_LIST"] as $key=>$val)
				{
					$res .= '<option';

					if (($arProp["MULTIPLE"] == "Y") && is_array($value))
					{
						if(in_array($key, $value))
							$res .= ' selected';
					}
					else
					{
						if($key == $value)
							$res .= ' selected';
					}

					$res .= ' value="'.htmlspecialchars($key).'">'.htmlspecialchars($val).'</option>';
				}
				$res .= '</select>';

			}
			else
			{

				$arProp["VALUE_LIST"] = array();
				
				foreach($allValues['PROPERTY_'.strtoupper($arProp["CODE"]).'_ENUM_ID'] as $key=>$v)
				{
				    
				    $arProp["VALUE_LIST"][$v] = $allValues['PROPERTY_'.strtoupper($arProp["CODE"]).'_VALUE'][$key];
				}
				
				 if(!count($arProp["VALUE_LIST"]))
				    $arProp["VALUE_LIST"] = $allValues['PROPERTY_'.strtoupper($arProp["CODE"]).'_VALUE'];

                natsort($arProp["VALUE_LIST"]);

				foreach($arProp["VALUE_LIST"] as $key=>$val)
				{
					
					if (is_null($value)) $value = Array();
					if($key == $value || in_array($key, $value)) $checked = 'checked="checked"';
					else $checked = "";
					$res .= '<label class="checkbox"><input '.$checked.' type="checkbox" name="'.$name.'[]" value="'.$key.'" />'.$val."</label>";
					
				}
			}

			if ($arProp["MULTIPLE"] == "Y")
			{
				if (is_array($value) && count($value) > 0)
					${$FILTER_NAME}["PROPERTY"][$arProp["CODE"]] = $value;
			}
			else
			{
				if (strlen($value) > 0)
					${$FILTER_NAME}["PROPERTY"][$arProp["CODE"]] = $value;
			}
			
			$value = "";
			
			break;

		case "N":

			$isFloat = 0;
			
			$drop_left = false; $drop_right = false;

			$min['VALUE'] = min($allValues['PROPERTY_'.strtoupper($arProp["CODE"]).'_VALUE']);
			$max['VALUE'] = max($allValues['PROPERTY_'.strtoupper($arProp["CODE"]).'_VALUE']);
			
			$min['VALUE'] += 0;
			$max['VALUE'] += 0;
			
			if (is_float($min['VALUE']) == true || is_float($max['VALUE']) == true) $isFloat = 1;

			$name = $FILTER_NAME."_pf[".$arProp["CODE"]."][LEFT]";
			$value = $arrPFV[$arProp["CODE"]]["LEFT"];
			if($value == "") $value = $min['VALUE'];
			if($value == $min['VALUE']) $drop_left = true;

			$vmin = $value;
			
			$res .= GetMessage("CC_BCF_OT").'&nbsp;<input id="'.$arProp["CODE"].'-min" type="text" name="'.$name.'" size="'.$arParams["NUMBER_WIDTH"].
				'" value="'.htmlspecialchars($value).'" />';

			if (strlen($value) > 0)
				${$FILTER_NAME}["PROPERTY"][">=".$arProp["CODE"]] = doubleval($value);

			$name = $FILTER_NAME."_pf[".$arProp["CODE"]."][RIGHT]";
			$value = $arrPFV[$arProp["CODE"]]["RIGHT"];
			if($value == "") $value = $max['VALUE'];
			if($value == $max['VALUE']) $drop_right = true;

			$vmax = $value;
			
			$res .= '&nbsp;'.GetMessage("CC_BCF_DO").'&nbsp;<input id="'.$arProp["CODE"].'-max" type="text" name="'.$name.'" size="'.$arParams["NUMBER_WIDTH"].'" value="'.htmlspecialchars($value).'" />';

			if (strlen($value) > 0)
				${$FILTER_NAME}["PROPERTY"]["<=".$arProp["CODE"]] = doubleval($value);
			
			$value = "";

			if($drop_left && $drop_right && $arParams["DROP_MIN_MAX"] == "Y")
			{
				unset(${$FILTER_NAME}["PROPERTY"]["<=".$arProp["CODE"]]);
				unset(${$FILTER_NAME}["PROPERTY"][">=".$arProp["CODE"]]);
			}

			break;

		case "S":
		    
			$name = $FILTER_NAME."_pf[".$arProp["CODE"]."][]";
			
			if ( count($allValues['PROPERTY_'.strtoupper($arProp["CODE"]).'_VALUE']) > 0 )
			{
				$res .= '<select multiple name="'.$name.'"><option value="">'.GetMessage("CC_BCF_ALL").'</option>';
				
				asort($allValues['PROPERTY_'.strtoupper($arProp["CODE"]).'_VALUE']);
				
				foreach($allValues['PROPERTY_'.strtoupper($arProp["CODE"]).'_VALUE'] as $v)
				{
					$value = $arrPFV[$arProp["CODE"]];
					if($v == $value || in_array($v, $value))
						$checked = 'selected="selected"';
					else
						$checked = "";
					$res .= '<option '.$checked.' value="'.htmlspecialchars($v).'">'.$v.'</option>';
				}			
				$res .= '</select>';
				${$FILTER_NAME}["PROPERTY"][$arProp["CODE"]] = $value;
				
				$value = "";
			}
			break;
			
		case "E":		    
		    if($arProp['USER_TYPE'] == 'EList')
		    {
				$name = $FILTER_NAME."_pf[".$arProp["CODE"]."][]";
				$value = "";
				$res .= '<select multiple name="'.$name.'"><option value="">'.GetMessage("CC_BCF_ALL").'</option>';
				
				$flag = 0; // 1 - hit in cache
				
				$arElist = $vars['vars']["EList"][$numEList];
					
				if (!empty($arElist))
				{
					$flag = 1;
				}
				
				foreach($allValues['PROPERTY_'.strtoupper($arProp["CODE"]).'_VALUE'] as $v)
				{
					$value = $arrPFV[$arProp["CODE"]];
						
					$num = $v;
						
					if(!$flag)
					{
						$v = CIBlockElement::GetByID($v)->Fetch();
						$arElist[$num]['ID'] = $v['ID'];
						$arElist[$num]['NAME'] = $v['NAME'];
					}
							
					if (is_null($value)) $value = Array();
					if($arElist[$num]['ID'] == $value || in_array($arElist[$num]['ID'], $value))
						$checked = 'selected="selected"';
					else
						$checked = "";

					$res .= '<option '.$checked.' value="'.$arElist[$num]['ID'].'">'.$arElist[$num]['NAME'].'</option>';
				}
				
				$varsTemp["EList"][$numEList] = $arElist;
				
				$res .= '</select>';
				
				$numEList++;
			}
		    else
			{
				$flag = 0;
				
				$arList = $vars['vars']["List"][$numList];
					
				if (!empty($arList))
				{
					$flag = 1;
				}

				$name = $FILTER_NAME."_pf[".$arProp["CODE"]."][]";
				
				foreach($allValues['PROPERTY_'.strtoupper($arProp["CODE"]).'_VALUE'] as $v)
				{
					$num = $v;
						
					if(!$flag)
					{
						$v = CIBlockElement::GetByID($v)->Fetch();
						$arList[$num]['ID'] = $v['ID'];
						$arList[$num]['NAME'] = $v['NAME'];
					}
					
					$value = $arrPFV[$arProp["CODE"]];
					if (is_null($value)) $value = Array();
					if($arList[$num]['ID'] == $value || in_array($arList[$num]['ID'], $value))
						$checked = 'checked="checked"';
					else
						$checked = "";
							
					$res .= '<label class="checkbox"><input '.$checked.' type="checkbox" name="'.$name.'" value="'.$arList[$num]['ID'].'" />'.$arList[$num]['NAME']."</label>";
				}
				
				$varsTemp["List"][$numList] = $arList;
				
				$numList++;
		    }
			
			/* if($arProp['USER_TYPE'] == 'EList')			
		    {
				$name = $FILTER_NAME."_pf[".$arProp["CODE"]."][]";	
				$value = "";
				$res .= '<select multiple name="'.$name.'"><option value="">'.GetMessage("CC_BCF_ALL").'</option>';
				foreach($allValues['PROPERTY_'.strtoupper($arProp["CODE"]).'_VALUE'] as $v)
				{
					$value = $arrPFV[$arProp["CODE"]];
					$v = CIBlockElement::GetByID($v)->Fetch();
					
					$arNames[] = $v['NAME'];
					
					if($v['ID'] == $value || in_array($v['ID'], $value))
						$checked = 'selected="selected"';
					else
						$checked = "";
						
					$res .= '<option '.$checked.' value="'.$v['ID'].'">'.$v['NAME'].'</option>';
				}			
				$res .= '</select>';
		    }
		    else
			{
				$name = $FILTER_NAME."_pf[".$arProp["CODE"]."][]";	
				foreach($allValues['PROPERTY_'.strtoupper($arProp["CODE"]).'_VALUE'] as $v)
				{
					$v = CIBlockElement::GetByID($v)->Fetch();
					$value = $arrPFV[$arProp["CODE"]];
					if($v['ID'] == $value || in_array($v['ID'], $value))
						$checked = 'checked="checked"';
					else 
						$checked = "";
						
					$res .= '<label class="checkbox"><input '.$checked.' type="checkbox" name="'.$name.'" value="'.$v['ID'].'" />'.$v['NAME']."</label>";
				}
		    } */

		    ${$FILTER_NAME}["PROPERTY"][$arProp["CODE"]] = $value;
		    $value = "";
			
			break;
		case "G":

			$name = $FILTER_NAME."_pf[".$arProp["CODE"]."]";
			$value = $arrPFV[$arProp["CODE"]];
			$res .= '<input type="text" name="'.$name.'" size="'.$arParams["TEXT_WIDTH"].'" value="'.htmlspecialchars($value).'" />';

			if (strlen($value) > 0)
				${$FILTER_NAME}["PROPERTY"]["?".$arProp["CODE"]] = $value;
			$value="";
			break;
	} // end switch ( $arProp["PROPERTY_TYPE"] )
	if($res)
		$arResult["ITEMS"][] = array(
			"NAME" => htmlspecialchars($arProp["NAME"]),
			"INPUT" => $res,
			"PROPERTY_TYPE" => $arProp["PROPERTY_TYPE"],
			"CODE" => $arProp["CODE"],
			"VALUES" => array("MIN" => $min['VALUE'], "MAX" => $max['VALUE'], "MIN_VALUE" => $vmin, "MAX_VALUE" => $vmax),
			"INPUT_NAME" => $name,
			"INPUT_VALUE" => htmlspecialchars($value),
			"~INPUT_VALUE" => $value,
			"IS_FLOAT" => $isFloat
 		);
} // end foreach ( $arResult["arrProp"] as $prop_id => $arProp )


$bHasOffersFilter = false;
foreach($arParams["OFFERS_FIELD_CODE"] as $field_code)
{
	$field_res = "";
	$arResult["arrInputNames"][$FILTER_NAME."_of"]=true;
	$name = $FILTER_NAME."_of[".$field_code."]";
	$value = $arrOFV[$field_code];
	switch ($field_code)
	{
		case "CODE":
		case "XML_ID":
		case "NAME":
		case "PREVIEW_TEXT":
		case "DETAIL_TEXT":
		case "IBLOCK_TYPE_ID":
		case "IBLOCK_ID":
		case "IBLOCK_CODE":
		case "IBLOCK_NAME":
		case "IBLOCK_EXTERNAL_ID":
		case "SEARCHABLE_CONTENT":
			$field_res = '<input type="text" name="'.$name.'" size="'.$arParams["TEXT_WIDTH"].'" value="'.htmlspecialchars($value).'" />';

			if (strlen($value) > 0)
				${$FILTER_NAME}["OFFERS"]["?".$field_code] = $value;

			break;
		case "ID":
		case "SORT":
		case "SHOW_COUNTER":
			$name = $FILTER_NAME."_of[".$field_code."][LEFT]";
			$value = $arrOFV[$field_code]["LEFT"];
			$field_res = GetMessage("CC_BCF_OT").'&nbsp;<input type="text" name="'.$name.'" size="'.$arParams["NUMBER_WIDTH"].'" value="'.
				htmlspecialchars($value).'" />';

			if(strlen($value)>0)
				${$FILTER_NAME}["OFFERS"][">=".$field_code] = intval($value);

			$name = $FILTER_NAME."_of[".$field_code."][RIGHT]";
			$value = $arrOFV[$field_code]["RIGHT"];
			$field_res .= '&nbsp;'.GetMessage("CC_BCF_DO").'&nbsp;<input type="text" name="'.$name.'" size="'.$arParams["NUMBER_WIDTH"].'" value="'.htmlspecialchars($value).'" />';

			if(strlen($value)>0)
				${$FILTER_NAME}["OFFERS"]["<=".$field_code] = intval($value);

			break;

		case "ACTIVE_DATE":
		case "DATE_ACTIVE_FROM":
		case "DATE_ACTIVE_TO":
		case "DATE_CREATE":
			$arDateField = $arrODFV[$field_code];
			$arResult["arrInputNames"][$arDateField["from"]["name"]]=true;
			$arResult["arrInputNames"][$arDateField["to"]["name"]]=true;
			$arResult["arrInputNames"][$arDateField["days_to_back"]["name"]]=true;

			$field_res = CalendarPeriod(
				$arDateField["from"]["name"], $arDateField["from"]["value"],
				$arDateField["to"]["name"], $arDateField["to"]["value"],
				$FILTER_NAME."_form", "Y", "class=\"inputselect\"", "class=\"inputfield\""
			);

			if(strlen($arDateField["from"]["value"]) > 0)
				${$FILTER_NAME}["OFFERS"][$arDateField["filter_from"]] = $arDateField["from"]["value"];

			if(strlen($arDateField["to"]["value"]) > 0)
				${$FILTER_NAME}["OFFERS"][$arDateField["filter_to"]] = $arDateField["to"]["value"];
			break;
	}
	if($field_res)
	{
		$bHasOffersFilter = true;
		$arResult["ITEMS"][] = array(
			"NAME" => htmlspecialchars(GetMessage("IBLOCK_FIELD_".$field_code)),
			"INPUT" => $field_res,
			"INPUT_NAME" => $name,
			"INPUT_VALUE" => htmlspecialchars($value),
			"~INPUT_VALUE" => $value,
		);
	}
}

foreach($arResult["arrOfferProp"] as $prop_id => $arProp)
{	
	$res = "";
	$arResult["arrInputNames"][$FILTER_NAME."_op"]=true;
	switch ($arProp["PROPERTY_TYPE"])
	{
		case "L":
		
		// $arProp["LIST_TYPE"] = $vars['vars']["LIST_TYPE"];
		$arProp["LIST_TYPE"] = $vars['vars']["LIST_TYPE"][$allValuesOf["IBLOCK_ID"][0]][$arProp["CODE"]];

		if (empty($arProp["LIST_TYPE"]))
		{
			$pr = CIBlockProperty::GetList(array(), array("CODE" => $arProp["CODE"], "IBLOCK_ID" => $allValuesOf["IBLOCK_ID"][0]))->Fetch();
			$arProp["LIST_TYPE"] = $pr["LIST_TYPE"];
			
			$varsTemp["LIST_TYPE"][$allValuesOf["IBLOCK_ID"][0]][$arProp["CODE"]] = $arProp["LIST_TYPE"];
		}
		
		// ----------
		
		//$arProp["MULTIPLE"] = "Y";

			$name = $FILTER_NAME."_op[".$arProp["CODE"]."]";
			$value = $arrOPFV[$arProp["CODE"]];

			if($arProp["LIST_TYPE"] == "L")
			{	
				if ($arProp["MULTIPLE"] == "Y")
					$res .= '<select class="select" multiple name="'.$name.'[]" size="'.$arParams["LIST_HEIGHT"].'">';
				else
					$res .= '<select class="select" multiple name="'.$name.'">';

				$res .= '<option value="">'.GetMessage("CC_BCF_ALL").'</option>';

				/* $arProp["VALUE_LIST"] = array();
				foreach($allValuesOf['PROPERTY_'.strtoupper($arProp["CODE"]).'_ENUM_ID'] as $key=>$v)
				{
				    
				    $arProp["VALUE_LIST"][$v] = $allValuesOf['PROPERTY_'.strtoupper($arProp["CODE"]).'_VALUE'][$key];
				}
                
                if(!count($arProp["VALUE_LIST"]))
				    $arProp["VALUE_LIST"] = $allValuesOf['PROPERTY_'.strtoupper($arProp["CODE"]).'_VALUE']; */

				foreach($arProp["VALUE_LIST"] as $key=>$val)
				{
					$res .= '<option';

					if (($arProp["MULTIPLE"] == "Y") && is_array($value))
					{
						if(in_array($key, $value))
							$res .= ' selected';
					}
					else
					{
						if($key == $value)
							$res .= ' selected';
					}

					$res .= ' value="'.htmlspecialchars($key).'">'.htmlspecialchars($val).'</option>';
				}
				$res .= '</select>';
			}
			else
			{
				/* $arProp["VALUE_LIST"] = array();
				
				foreach($allValuesOf['PROPERTY_'.strtoupper($arProp["CODE"]).'_ENUM_ID'] as $key=>$v)
				{
				    
				    $arProp["VALUE_LIST"][$v] = $allValuesOf['PROPERTY_'.strtoupper($arProp["CODE"]).'_VALUE'][$key];
				}
				
				if(!count($arProp["VALUE_LIST"]))
				    $arProp["VALUE_LIST"] = $allValuesOf['PROPERTY_'.strtoupper($arProp["CODE"]).'_VALUE']; */

				foreach($arProp["VALUE_LIST"] as $key=>$val)
				{

					if($key == $value || in_array($key, $value))
						$checked = 'checked="checked"';
					else
						$checked = "";
						
					if($arProp["MULTIPLE"] == "Y")
						$res .= '<label class="checkbox"><input '.$checked.' type="checkbox" name="'.$name.'[]" value="'.$key.'" />'.$val."</label>";
					else
						$res .= '<label class="checkbox"><input '.$checked.' type="checkbox" name="'.$name.'" value="'.$key.'" />'.$val."</label>";
				}
			}

			if ($arProp["MULTIPLE"] == "Y")
			{
				if (is_array($value) && count($value) > 0)
					${$FILTER_NAME}["OFFERS"]["PROPERTY"][$arProp["CODE"]] = $value;
			}
			else
			{
				if (strlen($value) > 0)
					${$FILTER_NAME}["OFFERS"]["PROPERTY"][$arProp["CODE"]] = $value;
			}
			$value = "";
		
		// ----------
		
/*
			$name = $FILTER_NAME."_op[".$arProp["CODE"]."]";
			$value = $arrOPFV[$arProp["CODE"]];
			if ($arProp["MULTIPLE"]=="Y")
				$res .= '<select multiple name="'.$name.'[]" size="'.$arParams["LIST_HEIGHT"].'">';
			else
				$res .= '<select name="'.$name.'">';
			$res .= '<option value="">'.GetMessage("CC_BCF_ALL").'</option>';
			foreach($arProp["VALUE_LIST"] as $key=>$val)
			{
				$res .= '<option';

				if (($arProp["MULTIPLE"] == "Y") && is_array($value))
				{
					if(in_array($key, $value))
						$res .= ' selected';
				}
				else
				{
					if($key == $value)
						$res .= ' selected';
				}

				$res .= ' value="'.htmlspecialchars($key).'">'.htmlspecialchars($val).'</option>';
			}
			$res .= '</select>';

			if ($arProp["MULTIPLE"]=="Y")
			{
				if (is_array($value) && count($value)>0)
					${$FILTER_NAME}["OFFERS"]["PROPERTY"][$arProp["CODE"]] = $value;
			}
			else
			{
				if (strlen($value)>0)
					${$FILTER_NAME}["OFFERS"]["PROPERTY"][$arProp["CODE"]] = $value;
			}
*/	
			break;

		case "N":
			
			$name = $FILTER_NAME."_op[".$arProp["CODE"]."][LEFT]";
			$value = $arrOPFV[$arProp["CODE"]]["LEFT"];
			$res .= GetMessage("CC_BCF_OT").'&nbsp;<input type="text" name="'.$name.'" size="'.$arParams["NUMBER_WIDTH"].'" value="'.htmlspecialchars($value).
				'" />';

			if (strlen($value)>0)
				${$FILTER_NAME}["OFFERS"]["PROPERTY"][">=".$arProp["CODE"]] = intval($value);

			$name = $FILTER_NAME."_op[".$arProp["CODE"]."][RIGHT]";
			$value = $arrOPFV[$arProp["CODE"]]["RIGHT"];
			$res .= '&nbsp;'.GetMessage("CC_BCF_DO").'&nbsp;<input type="text" name="'.$name.'" size="'.$arParams["NUMBER_WIDTH"].'" value="'.htmlspecialchars($value).'" />';

			if (strlen($value)>0)
				${$FILTER_NAME}["OFFERS"]["PROPERTY"]["<=".$arProp["CODE"]] = doubleval($value);

			break;

		case "S":
		
			$name = $FILTER_NAME."_op[".$arProp["CODE"]."]";
			$value = $arrOPFV[$arProp["CODE"]];
			
			$res .= '<select multiple name="'.$name.'"><option value="">'.GetMessage("CC_BCF_ALL").'</option>';
			asort($allValuesOf['PROPERTY_'.strtoupper($arProp["CODE"]).'_VALUE']);
			foreach($allValuesOf['PROPERTY_'.strtoupper($arProp["CODE"]).'_VALUE'] as $v)
			{
				$value = $arrOPFV[$arProp["CODE"]];
				if($v == $value || in_array($v, $value)) $checked = 'selected="selected"'; else $checked="";
				$res .= '<option '.$checked.' value="'.$v.'">'.$v.'</option>';
			}			
			$res .= '</select>';
			if (strlen($value) > 0)
				${$FILTER_NAME}["OFFERS"]["PROPERTY"]["?".$arProp["CODE"]] = $value;
			$value="";
			break;
		
		case "E":
		case "G":
		
			$name = $FILTER_NAME."_op[".$arProp["CODE"]."]";
			$value = $arrOPFV[$arProp["CODE"]];
			
			$res .= '<select multiple name="'.$name.'"><option value="">'.GetMessage("CC_BCF_ALL").'</option>';
			foreach($allValuesOf['PROPERTY_'.strtoupper($arProp["CODE"]).'_VALUE'] as $v)
			{
				$value = $arrOPFV[$arProp["CODE"]];
				if (is_null($value)) $value = Array();
				if($v == $value || in_array($v, $value)) $checked = 'selected="selected"'; else $checked = "";
				$res .= '<option '.$checked.' value="'.$v.'">'.$v.'</option>';
			}		
			$res .= '</select>';
			if (strlen($value)>0)
				${$FILTER_NAME}["OFFERS"]["PROPERTY"]["?".$arProp["CODE"]] = $value;
			$value = "";
			break;
	}
	if($res)
	{
		$bHasOffersFilter = true;
		$arResult["ITEMS"][] = array(
			"NAME" => htmlspecialchars($arProp["NAME"]),
			"INPUT" => $res,
			"INPUT_NAME" => $name,
			"INPUT_VALUE" => htmlspecialchars($value),
			"~INPUT_VALUE" => $value,
 		);
	}
} // end foreach( $arResult["arrOfferProp"] as $prop_id => $arProp )

if($bHasOffersFilter)
{
	// This will force to use catalog.section offers price filter
	if(!isset(${$FILTER_NAME}["OFFERS"]))
		${$FILTER_NAME}["OFFERS"] = array();
}

foreach($arResult["arrPrice"] as $price_code => $arPrice)
{
	
	$min = $vars['vars']["min"];
	$max = $vars['vars']["max"];
	$min2 = $vars['vars']["min2"];
	$max2 = $vars['vars']["max2"];
	
	if($sec['ID'] != "")
	{
		if ( empty($min) )
		{
			$min = CIBlockElement::GetList(array('CATALOG_PRICE_'.$arPrice['ID'] => 'asc'), array('!CATALOG_PRICE_'.$arPrice['ID'] => false, "IBLOCK_ID" => $arParams['IBLOCK_ID'],  "INCLUDE_SUBSECTIONS" => "Y", "ACTIVE" => "Y", "SECTION_ID" => $sec['ID']), false, array('nTopCount' => 1), array('ID'))->Fetch();
			
			$max = CIBlockElement::GetList(array('CATALOG_PRICE_'.$arPrice['ID'] => 'desc'), array('!CATALOG_PRICE_'.$arPrice['ID'] => false, "IBLOCK_ID" => $arParams['IBLOCK_ID'], "INCLUDE_SUBSECTIONS" => "Y", "ACTIVE" => "Y", "SECTION_ID" => $sec['ID']), false, array('nTopCount' => 1), array('ID'))->Fetch();
			
			$varsTemp['min'] = $min;
			$varsTemp['max'] = $max;
		
			if($check["IBLOCK_ID"])
			{		
				$min2 = CIBlockElement::GetList(array('CATALOG_PRICE_'.$arPrice['ID'] => 'asc'), array('IBLOCK_ID' => $check["IBLOCK_ID"], '!CATALOG_PRICE_'.$arPrice['ID'] => false, "PROPERTY_CML2_LINK" => $ids,  "INCLUDE_SUBSECTIONS" => "Y", "ACTIVE" => "Y"), false, array('nTopCount' => 1), array('ID'))->Fetch();
				$max2 = CIBlockElement::GetList(array('CATALOG_PRICE_'.$arPrice['ID'] => 'desc'), array('IBLOCK_ID' => $check["IBLOCK_ID"], '!CATALOG_PRICE_'.$arPrice['ID'] => false, "PROPERTY_CML2_LINK" => $ids,  "INCLUDE_SUBSECTIONS" => "Y", "ACTIVE" => "Y"), false, array('nTopCount' => 1), array('ID'))->Fetch();
				
				$varsTemp['min2'] = $min2;
				$varsTemp['max2'] = $max2;
			}
		}
	}
	else
	{
		if ( empty($min) )
		{
			$min = CIBlockElement::GetList(array('CATALOG_PRICE_'.$arPrice['ID'] => 'asc'), array('!CATALOG_PRICE_'.$arPrice['ID'] => false, "IBLOCK_ID" => $arParams['IBLOCK_ID'],  "INCLUDE_SUBSECTIONS" => "Y", "ACTIVE" => "Y"), false, array('nTopCount' => 1), array('ID'))->Fetch();
			
			$max = CIBlockElement::GetList(array('CATALOG_PRICE_'.$arPrice['ID'] => 'desc'), array('!CATALOG_PRICE_'.$arPrice['ID'] => false, "IBLOCK_ID" => $arParams['IBLOCK_ID'], "INCLUDE_SUBSECTIONS" => "Y", "ACTIVE" => "Y"), false, array('nTopCount' => 1), array('ID'))->Fetch();
			
			$varsTemp['min'] = $min;
			$varsTemp['max'] = $max;
			
			if($check["IBLOCK_ID"])
			{
				$min2 = CIBlockElement::GetList(array('CATALOG_PRICE_'.$arPrice['ID'] => 'asc'), array('IBLOCK_ID' => $check["IBLOCK_ID"], '!CATALOG_PRICE_'.$arPrice['ID'] => false, "PROPERTY_CML2_LINK" => $ids,  "INCLUDE_SUBSECTIONS" => "Y", "ACTIVE" => "Y"), false, array('nTopCount' => 1), array('ID'))->Fetch();
				
				$max2 = CIBlockElement::GetList(array('CATALOG_PRICE_'.$arPrice['ID'] => 'desc'), array('IBLOCK_ID' => $check["IBLOCK_ID"], '!CATALOG_PRICE_'.$arPrice['ID'] => false, "PROPERTY_CML2_LINK" => $ids, /*"PROPERTY_CML2_LINK" => $ids,*/  "INCLUDE_SUBSECTIONS" => "Y", "ACTIVE" => "Y"), false, array('nTopCount' => 1), array('ID'))->Fetch();
				
				$varsTemp['min2'] = $min2;
				$varsTemp['max2'] = $max2;
			}
		}
	}
	
	if ($cache_time > 0)
	{
		// $data = gzcompress($vars);
		if ($cache instanceof CPHPCache)
		{
			$cache->StartDataCache($cache_time, $cache_id);
			$cache->EndDataCache(array('vars'=>$varsTemp ) );
		}
	}
	
	unset($cache);
	
	if($min2['CATALOG_PRICE_'.$arPrice['ID']] && $min['CATALOG_PRICE_'.$arPrice['ID']])
	{
		 if($min['CATALOG_PRICE_'.$arPrice['ID']] > $min2['CATALOG_PRICE_'.$arPrice['ID']])
			$min['CATALOG_PRICE_'.$arPrice['ID']] = $min2['CATALOG_PRICE_'.$arPrice['ID']];
	}
	if($max2['CATALOG_PRICE_'.$arPrice['ID']] && $max['CATALOG_PRICE_'.$arPrice['ID']])
	{
		 if($max['CATALOG_PRICE_'.$arPrice['ID']] < $max2['CATALOG_PRICE_'.$arPrice['ID']])
			$max['CATALOG_PRICE_'.$arPrice['ID']] = $max2['CATALOG_PRICE_'.$arPrice['ID']];
	}
	
	if (empty($max['CATALOG_PRICE_'.$arPrice['ID']]))
		$max['CATALOG_PRICE_'.$arPrice['ID']] = $max2['CATALOG_PRICE_'.$arPrice['ID']];
		
	if (empty($min['CATALOG_PRICE_'.$arPrice['ID']]))
		$min['CATALOG_PRICE_'.$arPrice['ID']] = $min2['CATALOG_PRICE_'.$arPrice['ID']];
	
	$res_price = "";
	$arResult["arrInputNames"][$FILTER_NAME."_cf"] = true;

	$name = $FILTER_NAME."_cf[".$arPrice["ID"]."][LEFT]";
	$value = $arrCFV[$arPrice["ID"]]["LEFT"];
	if($value == "") $value = $min['CATALOG_PRICE_'.$arPrice['ID']];
	$vmin = $value;

	$drop_left = false;
        if($value == $min['CATALOG_PRICE_'.$arPrice['ID']]) $drop_left = true;

	if (strlen($value) > 0)
	{
		if(CModule::IncludeModule("catalog"))
			${$FILTER_NAME}[">=CATALOG_PRICE_".$arPrice["ID"]] = $value;
		else
			${$FILTER_NAME}[">=PROPERTY_".$arPrice["ID"]] = $value;
	}
	if($drop_left && $drop_right)
	{
		unset(${$FILTER_NAME}[">=CATALOG_PRICE_".$arPrice["ID"]]);
		unset(${$FILTER_NAME}[">=PROPERTY_".$arPrice["ID"]]);
	}
	
	$res_price .= GetMessage("CC_BCF_OT").'&nbsp;<input id="CATALOG_PRICE_'.$arPrice['ID'].'-min" type="text" name="'.$name.'" size="'.$arParams["NUMBER_WIDTH"].'" value="'.
		htmlspecialchars(round($value)).'" />';

	$name = $FILTER_NAME."_cf[".$arPrice["ID"]."][RIGHT]";
	$value = $arrCFV[$arPrice["ID"]]["RIGHT"];
	if($value == "") $value = $max['CATALOG_PRICE_'.$arPrice['ID']];
	$vmax = $value;

	$drop_right = false;
        if($value == $max['CATALOG_PRICE_'.$arPrice['ID']]) $drop_right = true;

	if (strlen($value) > 0)
	{
		if(CModule::IncludeModule("catalog"))
			${$FILTER_NAME}["<=CATALOG_PRICE_".$arPrice["ID"]] = $value;
		else
			${$FILTER_NAME}["<=PROPERTY_".$arPrice["ID"]] = $value;
	}

	if($drop_left && $drop_right && $arParams["DROP_MIN_MAX"] == "Y")
	{
		unset(${$FILTER_NAME}[">=CATALOG_PRICE_".$arPrice["ID"]]);
		unset(${$FILTER_NAME}[">=PROPERTY_".$arPrice["ID"]]);
		unset(${$FILTER_NAME}["<=CATALOG_PRICE_".$arPrice["ID"]]);
		unset(${$FILTER_NAME}["<=PROPERTY_".$arPrice["ID"]]);
	}

	$res_price .= '&nbsp;'.GetMessage("CC_BCF_DO").'&nbsp;<input  id="CATALOG_PRICE_'.$arPrice['ID'].'-max" type="text" name="'.$name.'" size="'.$arParams["NUMBER_WIDTH"].'" value="'.htmlspecialchars(round($value)).'" />';

	$arResult["ITEMS"][] = array(
		"NAME" => htmlspecialchars($arPrice["TITLE"]), 
		"INPUT" => $res_price,
		"VALUES" => array("MIN" => $min['CATALOG_PRICE_'.$arPrice['ID']], "MAX" => $max['CATALOG_PRICE_'.$arPrice['ID']], "MIN_VALUE" => $vmin, "MAX_VALUE" => $vmax),
		"PROPERTY_TYPE" => "N",
		"CODE" => 'CATALOG_PRICE_'.$arPrice['ID'],
	);

} // end foreach ( $arResult["arrPrice"] as $price_code => $arPrice )

$arResult["arrInputNames"]["set_filter"] = true;
$arResult["arrInputNames"]["del_filter"] = true;

if($arParams['FILTER_BY_QUANTITY'] == 'Y')
	$arResult["arrInputNames"]['f_Quantity'] = true ;

$arSkip = array(
	"AUTH_FORM" => true,
	"TYPE" => true,
	"USER_LOGIN" => true,
	"USER_CHECKWORD" => true,
	"USER_PASSWORD" => true,
	"USER_CONFIRM_PASSWORD" => true,
	"USER_EMAIL" => true,
	"captcha_word" => true,
	"captcha_sid" => true,
	"login" => true,
	"Login" => true,
	"backurl" => true,
	"arrFilter_op[CML2_LINK]" => true,
);

foreach(array_merge($_GET, $_POST) as $key=>$value)
{
	if(
		!array_key_exists($key, $arResult["arrInputNames"])
		&& !array_key_exists($key, $arSkip)
	)
	{	
		$arResult["ITEMS"][] = array(
			"HIDDEN" => true,
			"INPUT" => '<input type="hidden" name="'.htmlspecialchars($key).'" value="'.htmlspecialchars($value).'" />',
		);
	}
}

foreach(${$FILTER_NAME}['OFFERS']["PROPERTY"] as $k=>$v)
{
    if(is_array($v))
        if(!$v[0])
            unset(${$FILTER_NAME}['OFFERS']["PROPERTY"][$k]);        
    else
        if(!$v) 
            unset(${$FILTER_NAME}['OFFERS']["PROPERTY"][$k]);
}

if(count(${$FILTER_NAME}['OFFERS']["PROPERTY"]) == 0)
    unset(${$FILTER_NAME}['OFFERS']["PROPERTY"]);

if(!isset($_REQUEST["set_filter"]) || !empty($_REQUEST["del_filter"]))
{
	${$FILTER_NAME} = array();
}

if ($arParams['FILTER_BY_QUANTITY'] == 'Y' && ( ($_REQUEST["set_filter"] && $_REQUEST['f_Quantity']) || !isset($_REQUEST["set_filter"]) || !empty($_REQUEST["del_filter"]) ) )
{
	${$FILTER_NAME}['>CATALOG_QUANTITY'] = '0';
	$arResult['CHECKED_QUANTITY'] = 'Y';
}
elseif ($arParams['FILTER_BY_QUANTITY'] == 'Y')
	${$FILTER_NAME}[] = Array("LOGIC" => "OR",
        array('CATALOG_QUANTITY' => false),
        array('<=CATALOG_QUANTITY' => 0)) ;

$arResult['COUNT'] = count($arElems);

$this->IncludeComponentTemplate();

?>