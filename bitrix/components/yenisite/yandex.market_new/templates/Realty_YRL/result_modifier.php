<?
/**
 * @author Ilya Faleyev <isfaleev@gmail.com>
 * @copyright 2004-2014 (c) ROMZA
 */

$arPriceParams = array(
11 => 'PRICE_PERIOD',
12 => 'PRICE_UNIT',
);
$arCommonParams = array(
14 => 'TYPE',
15 => 'PROPERTY_TYPE',
16 => 'CATEGORY',
17 => 'CREATION_DATE',
18 => 'LAST_UPDATE_DATE',
19 => 'EXPIRE_DATE',
20 => 'PAYED_ADV',
21 => 'MANUALLY_ADDED',
);
$arLocationParams = array(
22 => 'COUNTRY',
23 => 'REGION',
24 => 'DISTRICT',
25 => 'LOCALITY_NAME',
26 => 'ADDRESS',
27 => 'SUB_LOCALITY_NAME',
28 => 'NON_ADMIN_SUB_LOCALITY_NAME',
29 => 'DIRECTION',
30 => 'DISTANCE',
31 => 'LATITUDE',
32 => 'LONGITUDE',
33 => 'RAILWAY_STATION',
);
$arMetroParams = array(
34 => 'METRO_NAME',
35 => 'METRO_TIME_ON_TRANSPORT',
36 => 'METRO_TIME_ON_FOOT'
);
$arAgentParams = array(
37 => 'AGENT_NAME',
38 => 'AGENT_PHONE',
39 => 'AGENT_CATEGORY',
40 => 'AGENT_ORGANIZATION',
41 => 'AGENT_ID',
42 => 'AGENT_URL',
43 => 'AGENT_EMAIL',
44 => 'AGENT_PARTNER'
);
$arAreaParams = array(
54 => 'AREA',
//55 => 'AREA_UNIT',
56 => 'LIVING_SPACE',
//57 => 'LIVING_SPACE_UNIT',
58 => 'KITCHEN_SPACE',
//59 => 'KITCHEN_SPACE_UNIT',
60 => 'LOT_AREA',
//61 => 'LOT_AREA_UNIT',
);
$arOtherParams = array(
45 => 'NOT_FOR_AGENTS',
46 => 'HAGGLE',
47 => 'MORTGAGE',
48 => 'PREPAYMENT',
49 => 'RENT_PLEDGE',
50 => 'AGENT_FEE',
51 => 'WITH_PETS',
52 => 'WITH_CHILDREN',
53 => 'RENOVATION',
62 => 'LOT_TYPE',
63 => 'ROOMS',
64 => 'ROOMS_OFFERED',
65 => 'NEW_FLAT',
66 => 'OPEN_PLAN',
67 => 'ROOMS_TYPE',
68 => 'PHONE',
69 => 'INTERNET',
70 => 'ROOM_FURNITURE',
71 => 'KITCHEN_FURNITURE',
72 => 'TELEVISION',
73 => 'WASHING_MACHINE',
74 => 'REFRIGERATOR',
75 => 'BALCONY',
76 => 'BATHROOM_UNIT',
77 => 'FLOOR_COVERING',
78 => 'WINDOW_VIEW',
79 => 'FLOOR',
80 => 'FLOORS_TOTAL',
81 => 'BUILDING_NAME',
82 => 'BUILDING_TYPE',
83 => 'BUILDING_SERIES',
84 => 'BUILDING_STATE',
85 => 'BUILT_YEAR',
86 => 'READY_QUARTER',
87 => 'LIFT',
88 => 'RUBBISH_CHUTE',
89 => 'IS_ELITE',
90 => 'PARKING',
91 => 'ALARM',
92 => 'CEILING_HEIGHT',
93 => 'PMG',
94 => 'TOILET',
95 => 'SHOWER',
96 => 'KITCHEN',
97 => 'POOL',
98 => 'BILLIARD',
99 => 'SAUNA',
100 => 'HEATING_SUPPLY',
101 => 'WATER_SUPPLY',
102 => 'SEWERAGE_SUPPLY',
103 => 'ELECTRICITY_SUPPLY',
104 => 'GAS_SUPPLY'
);

$arParamsWithPrefix = $arPriceParams + $arMetroParams + $arAgentParams;
$arParamsWithoutPrefix = $arCommonParams + $arLocationParams + $arAreaParams + $arOtherParams;

$arAllParams = $arParamsWithPrefix + $arParamsWithoutPrefix;

//Make list of all property codes we need

$arParamsByPropCodes = array();

foreach ($arAllParams as $param) {
	if (empty($arParams[$param])) continue;
	if ($arParams[$param] == 'EMPTY') continue;
	if (in_array($arParams[$param], $arParamsByPropCodes)) continue;

	$arParamsByPropCodes[$arParams[$param]] = $param;
}

$arPropFilter = array();

if (in_array('AGENT_URL', $arParamsByPropCodes)) {
	$agentURLCode = $arParams['AGENT_URL'];
	unset($arParamsByPropCodes[$agentURLCode]);
}

//Get all needed properties from all elements
if (count($arParamsByPropCodes) > 0)
foreach ($arResult["OFFER"] as &$arOffer) {
	$dbProps = CIBlockElement::GetProperty($arOffer['IBLOCK_ID'], $arOffer['ID'], array('sort' => 'asc'));
	while ($arProp = $dbProps->GetNext()) {
		if (!array_key_exists($arProp['CODE'], $arParamsByPropCodes)) {
			unset($arProp);
			continue;
		}
		if ($arProp['VALUE_ENUM']) {
			$arOffer['PROPERTIES'][$arParamsByPropCodes[$arProp['CODE']]] = $arProp['VALUE_ENUM'];
			unset($arProp);
			continue;
		}
		$arProp = CIBlockFormatProperties::GetDisplayValue($arOffer, $arProp, 'yr_out');
		$arOffer['PROPERTIES'][$arParamsByPropCodes[$arProp['CODE']]] = $arProp['DISPLAY_VALUE'];
		unset($arProp);
	}
	unset($dbProps);
}

//Get URL properties
if (isset($agentURLCode))
foreach ($arResult['OFFER'] as &$arOffer) {
	$arProp = CIBlockElement::GetProperty($arOffer['IBLOCK_ID'], $arOffer['ID'], array('sort' => 'asc'), array('CODE' => $agentURLCode))->GetNext();
	$arOffer['PROPERTIES']['AGENT_URL'] = $arProp['VALUE'];
	unset($arProp);
}

//Convert dates to needed format

function DateFromBitrixToAtom($dateBitrix) {
	$siteFormat = CSite::GetDateFormat('FULL');
	$phpFormat = $GLOBALS['DB']->DateFormatToPHP($siteFormat);
	$date = DateTime::createFromFormat($phpFormat, $dateBitrix);
	return $date->format(DateTime::ATOM);
}

foreach ($arResult["OFFER"] as &$arOffer) {
	if(isset($arOffer['PROPERTIES']['CREATION_DATE']) && $arOffer['PROPERTIES']['CREATION_DATE'] != '') {
		$arOffer['DATE_CREATE'] = DateFromBitrixToAtom($arOffer['PROPERTIES']['CREATION_DATE']);
	} else {
		$arOffer['DATE_CREATE'] = DateFromBitrixToAtom($arOffer['DATE_CREATE']);
	}
	if (isset($arOffer['PROPERTIES']['LAST_UPDATE_DATE']) && $arOffer['PROPERTIES']['LAST_UPDATE_DATE'] != '')
		$arOffer['PROPERTIES']['LAST_UPDATE_DATE'] = DateFromBitrixToAtom($arOffer['PROPERTIES']['LAST_UPDATE_DATE']);
	if (isset($arOffer['PROPERTIES']['EXPIRE_DATE']) && $arOffer['PROPERTIES']['EXPIRE_DATE'] != '')
		$arOffer['PROPERTIES']['EXPIRE_DATE'] = DateFromBitrixToAtom($arOffer['PROPERTIES']['EXPIRE_DATE']);
}

//Make params to tags associations

function ParamToTag($param) {
	return strtolower(str_replace('_', '-', $param));
}

//List of Param => Tag
$arResult['TAGS'] = array();

foreach ($arParamsWithoutPrefix as $param) {
	$arResult['TAGS'][$param] = ParamToTag($param);
}
foreach ($arParamsWithPrefix as $param) {
	$arResult['TAGS'][$param] = ParamToTag(substr($param, 6));
}
$arResult['TAGS']['AGENT_ID'] = 'agency-id';


$arResult['DATE'] = date(DateTime::ATOM);

$arResult['LOCATION_PARAMS'] = $arLocationParams;
$arResult['METRO_PARAMS']    = $arMetroParams;
$arResult['AGENT_PARAMS']    = $arAgentParams;
$arResult['AREA_PARAMS']     = $arAreaParams;
$arResult['OTHER_PARAMS']    = $arOtherParams;
?>