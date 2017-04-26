<?
/**
 * @author Ilya Faleyev <isfaleev@gmail.com>
 * @copyright 2004-2014 (c) ROMZA
 */

if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule("iblock"))
	return;

global $arComponentParameters;

$arComponentParameters['GROUPS']['COMMON_REQUIRED_PARAMS'] = array(
	'NAME' => GetMessage('COMMON_REQUIRED_PARAMS'),
	'SORT' => 2000
);

$arComponentParameters['GROUPS']['COMMON_OPTIONAL_PARAMS'] = array(
	'NAME' => GetMessage('COMMON_OPTIONAL_PARAMS'),
	'SORT' => 2100
);

$arComponentParameters['GROUPS']['LOCATION_REQUIRED_PARAMS_ALL'] = array(
	'NAME' => GetMessage('LOCATION_REQUIRED_PARAMS_ALL'),
	'SORT' => 2200
);

$arComponentParameters['GROUPS']['LOCATION_REQUIRED_PARAMS_CITY'] = array(
	'NAME' => GetMessage('LOCATION_REQUIRED_PARAMS_CITY'),
	'SORT' => 2300
);

$arComponentParameters['GROUPS']['LOCATION_OPTIONAL_PARAMS'] = array(
	'NAME' => GetMessage('LOCATION_OPTIONAL_PARAMS'),
	'SORT' => 2400
);

$arComponentParameters['GROUPS']['SALES_AGENT'] = array(
	'NAME' => GetMessage('SALES_AGENT'),
	'SORT' => 2500
);

$arComponentParameters['GROUPS']['DEAL'] = array(
	'NAME' => GetMessage('DEAL'),
	'SORT' => 2600
);

$arComponentParameters['GROUPS']['OBJECT'] = array(
	'NAME' => GetMessage('OBJECT'),
	'SORT' => 2700
);

$arComponentParameters['GROUPS']['LIVING_SPACE_REQUIRED'] = array(
	'NAME' => GetMessage('LIVING_SPACE_REQUIRED'),
	'SORT' => 2800
);

$arComponentParameters['GROUPS']['LIVING_SPACE_OPTIONAL'] = array(
	'NAME' => GetMessage('LIVING_SPACE_OPTIONAL'),
	'SORT' => 2850
);

$arComponentParameters['GROUPS']['BUILDING'] = array(
	'NAME' => GetMessage('BUILDING'),
	'SORT' => 2900
);

$arComponentParameters['GROUPS']['COUNTRYSIDE'] = array(
	'NAME' => GetMessage('COUNTRYSIDE'),
	'SORT' => 3000
);

unset($arComponentParameters['PARAMETERS']['SITE']);
unset($arComponentParameters['PARAMETERS']['COMPANY']);
unset($arComponentParameters['PARAMETERS']['SKU_NAME']);
unset($arComponentParameters['PARAMETERS']['SKU_PROPERTY']);
//unset($arComponentParameters['PARAMETERS']['IBLOCK_ORDER']);
unset($arComponentParameters['PARAMETERS']['IBLOCK_QUANTITY']);
unset($arComponentParameters['PARAMETERS']['LOCAL_DELIVERY_COST']);
//unset($arComponentParameters['PARAMETERS']['DETAIL_TEXT_PRIORITET']);
unset($arComponentParameters['PARAMETERS']['NAME_PROP']);

$arComponentParameters['PARAMETERS']['IBLOCK_ORDER']['NAME'] = GetMessage('IBLOCK_ORDER_REALTY');
$arComponentParameters['PARAMETERS']['IBLOCK_ORDER']['DEFAULT'] = 'Y';
$arCurrentValues['IBLOCK_ORDER'] = 'Y';

$arProp = array();
$arNProp = array();
$arDTProp = array();

if (is_array($arCurrentValues['IBLOCK_ID_IN']) && !empty($arCurrentValues['IBLOCK_ID_IN'][0])) {
	$arIblockID = $arCurrentValues['IBLOCK_ID_IN'];
}
else {
	$arIblockID = (array)$GLOBALS['YS_YM_IBLOCK_ID'];
}

foreach($arIblockID as $id) {
	
	if ($id < 1) continue;
	
	$rsProp = CIBlockProperty::GetList(
		array('sort' => 'desc'),
		array('IBLOCK_ID' => $id,
			array('LOGIC' => 'OR',
				array('PROPERTY_TYPE' => 'L'),
				array('PROPERTY_TYPE' => 'E'),
				array('PROPERTY_TYPE' => 'S'),
				array('PROPERTY_TYPE' => 'N')
			)
		)
	);
	
	while ($arr = $rsProp->Fetch()) {
		if (array_key_exists($arr['CODE'], $arProp)) continue;
		//if (in_array($arr['NAME'], $arProp)) continue;
		
		$arProp[$arr['CODE']] = '[' . $arr['CODE'] . '] ' . $arr['NAME'];
		
		if ($arr['PROPERTY_TYPE'] == 'N')     $arNProp[$arr['CODE']] = '[' . $arr['CODE'] . '] ' . $arr['NAME'];
		if ($arr['USER_TYPE'] == 'DateTime') $arDTProp[$arr['CODE']] = '[' . $arr['CODE'] . '] ' . $arr['NAME'];
	}
}

$arProp['EMPTY'] = $arNProp['EMPTY'] = $arDTProp['EMPTY'] = '				';
natsort($arProp);
natsort($arNProp);
natsort($arDTProp);

$arTemplateParameters = array(
	'PRICE_FROM_IBLOCK' => array(
		'PARENT'  => 'PRICES',
		'NAME'    => GetMessage('PRICE_FROM_IBLOCK'),
		'TYPE'    => 'CHECKBOX',
		'DEFAULT' => 'N',
		'REFRESH' => 'Y',
	),
	'PRICE_REQUIRED' => array(
		'PARENT'  => 'PRICES',
		'NAME'    => GetMessage('PRICE_REQUIRED'),
		'TYPE'    => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'TYPE' => array(
		'PARENT' => 'COMMON_REQUIRED_PARAMS',
		'NAME'   => GetMessage('TYPE'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'PROPERTY_TYPE' => array(
		'PARENT' => 'COMMON_REQUIRED_PARAMS',
		'NAME'   => GetMessage('PROPERTY_TYPE'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'CATEGORY' => array(
		'PARENT' => 'COMMON_REQUIRED_PARAMS',
		'NAME'   => GetMessage('CATEGORY'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'CREATION_DATE' => array(
		'PARENT' => 'COMMON_REQUIRED_PARAMS',
		'NAME'   => GetMessage('CREATION_DATE'),
		'TYPE'   => 'LIST',
		'VALUES' => $arDTProp
	),
	
	'LAST_UPDATE_DATE' => array(
		'PARENT' => 'COMMON_OPTIONAL_PARAMS',
		'NAME'   => GetMessage('LAST_UPDATE_DATE'),
		'TYPE'   => 'LIST',
		'VALUES' => $arDTProp
	),
	
	'EXPIRE_DATE' => array(
		'PARENT' => 'COMMON_OPTIONAL_PARAMS',
		'NAME'   => GetMessage('EXPIRE_DATE'),
		'TYPE'   => 'LIST',
		'VALUES' => $arDTProp
	),
	
	'PAYED_ADV' => array(
		'PARENT' => 'COMMON_OPTIONAL_PARAMS',
		'NAME'   => GetMessage('PAYED_ADV'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'MANUALLY_ADDED' => array(
		'PARENT' => 'COMMON_OPTIONAL_PARAMS',
		'NAME'   => GetMessage('MANUALLY_ADDED'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'COUNTRY' => array(
		'PARENT' => 'LOCATION_REQUIRED_PARAMS_ALL',
		'NAME'   => GetMessage('COUNTRY'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'REGION' => array(
		'PARENT' => 'LOCATION_REQUIRED_PARAMS_ALL',
		'NAME'   => GetMessage('REGION'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'DISTRICT' => array(
		'PARENT' => 'LOCATION_REQUIRED_PARAMS_ALL',
		'NAME'   => GetMessage('DISTRICT'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'LOCALITY_NAME' => array(
		'PARENT' => 'LOCATION_REQUIRED_PARAMS_CITY',
		'NAME'   => GetMessage('LOCALITY_NAME'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'ADDRESS' => array(
		'PARENT' => 'LOCATION_REQUIRED_PARAMS_CITY',
		'NAME'   => GetMessage('ADDRESS'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'SUB_LOCALITY_NAME' => array(
		'PARENT' => 'LOCATION_OPTIONAL_PARAMS',
		'NAME'   => GetMessage('SUB_LOCALITY_NAME'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'NON_ADMIN_SUB_LOCALITY_NAME' => array(
		'PARENT' => 'LOCATION_OPTIONAL_PARAMS',
		'NAME'   => GetMessage('NON_ADMIN_SUB_LOCALITY_NAME'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'DIRECTION' => array(
		'PARENT' => 'LOCATION_OPTIONAL_PARAMS',
		'NAME'   => GetMessage('DIRECTION'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'DISTANCE' => array(
		'PARENT' => 'LOCATION_OPTIONAL_PARAMS',
		'NAME'   => GetMessage('DISTANCE'),
		'TYPE'   => 'LIST',
		'VALUES' => $arNProp
	),
	
	'LATITUDE' => array(
		'PARENT' => 'LOCATION_OPTIONAL_PARAMS',
		'NAME'   => GetMessage('LATITUDE'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'LONGITUDE' => array(
		'PARENT' => 'LOCATION_OPTIONAL_PARAMS',
		'NAME'   => GetMessage('LONGITUDE'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'RAILWAY_STATION' => array(
		'PARENT' => 'LOCATION_OPTIONAL_PARAMS',
		'NAME'   => GetMessage('RAILWAY_STATION'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'METRO_NAME' => array(
		'PARENT' => 'LOCATION_OPTIONAL_PARAMS',
		'NAME'   => GetMessage('METRO_NAME'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'METRO_TIME_ON_TRANSPORT' => array(
		'PARENT' => 'LOCATION_OPTIONAL_PARAMS',
		'NAME'   => GetMessage('METRO_TIME_ON_TRANSPORT'),
		'TYPE'   => 'LIST',
		'VALUES' => $arNProp
	),
	
	'METRO_TIME_ON_FOOT' => array(
		'PARENT' => 'LOCATION_OPTIONAL_PARAMS',
		'NAME'   => GetMessage('METRO_TIME_ON_FOOT'),
		'TYPE'   => 'LIST',
		'VALUES' => $arNProp
	),
	
	'AGENT_NAME' => array(
		'PARENT' => 'SALES_AGENT',
		'NAME'   => GetMessage('AGENT_NAME'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'AGENT_PHONE' => array(
		'PARENT' => 'SALES_AGENT',
		'NAME'   => GetMessage('AGENT_PHONE'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'AGENT_CATEGORY' => array(
		'PARENT' => 'SALES_AGENT',
		'NAME'   => GetMessage('AGENT_CATEGORY'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'AGENT_ORGANIZATION' => array(
		'PARENT' => 'SALES_AGENT',
		'NAME'   => GetMessage('AGENT_ORGANIZATION'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'AGENT_ID' => array(
		'PARENT' => 'SALES_AGENT',
		'NAME'   => GetMessage('AGENT_ID'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'AGENT_URL' => array(
		'PARENT' => 'SALES_AGENT',
		'NAME'   => GetMessage('AGENT_URL'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'AGENT_EMAIL' => array(
		'PARENT' => 'SALES_AGENT',
		'NAME'   => GetMessage('AGENT_EMAIL'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'AGENT_PARTNER' => array(
		'PARENT' => 'SALES_AGENT',
		'NAME'   => GetMessage('AGENT_PARTNER'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'PRICE_PERIOD' => array(
		'PARENT' => 'PRICES',
		'NAME'   => GetMessage('PRICE_PERIOD'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'PRICE_UNIT' => array(
		'PARENT' => 'PRICES',
		'NAME'   => GetMessage('PRICE_UNIT'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'NOT_FOR_AGENTS' => array(
		'PARENT' => 'DEAL',
		'NAME'   => GetMessage('NOT_FOR_AGENTS'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'HAGGLE' => array(
		'PARENT' => 'DEAL',
		'NAME'   => GetMessage('HAGGLE'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'MORTGAGE' => array(
		'PARENT' => 'DEAL',
		'NAME'   => GetMessage('MORTGAGE'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'PREPAYMENT' => array(
		'PARENT' => 'DEAL',
		'NAME'   => GetMessage('PREPAYMENT'),
		'TYPE'   => 'LIST',
		'VALUES' => $arNProp
	),
	
	'RENT_PLEDGE' => array(
		'PARENT' => 'DEAL',
		'NAME'   => GetMessage('RENT_PLEDGE'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'AGENT_FEE' => array(
		'PARENT' => 'DEAL',
		'NAME'   => GetMessage('AGENT_FEE'),
		'TYPE'   => 'LIST',
		'VALUES' => $arNProp
	),
	
	'WITH_PETS' => array(
		'PARENT' => 'DEAL',
		'NAME'   => GetMessage('WITH_PETS'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'WITH_CHILDREN' => array(
		'PARENT' => 'DEAL',
		'NAME'   => GetMessage('WITH_CHILDREN'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'RENOVATION' => array(
		'PARENT' => 'OBJECT',
		'NAME'   => GetMessage('RENOVATION'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'AREA' => array(
		'PARENT' => 'OBJECT',
		'NAME'   => GetMessage('AREA'),
		'TYPE'   => 'LIST',
		'VALUES' => $arNProp
	),
	
	'AREA_UNIT' => array(
		'PARENT' => 'OBJECT',
		'NAME'   => GetMessage('AREA_UNIT'),
		'TYPE'   => 'STRING',
		'DEFAULT' => GetMessage('SQ.M')
	),
	
	'LIVING_SPACE' => array(
		'PARENT' => 'OBJECT',
		'NAME'   => GetMessage('LIVING_SPACE'),
		'TYPE'   => 'LIST',
		'VALUES' => $arNProp
	),
	
	'LIVING_SPACE_UNIT' => array(
		'PARENT' => 'OBJECT',
		'NAME'   => GetMessage('AREA_UNIT'),
		'TYPE'   => 'STRING',
		'DEFAULT' => GetMessage('SQ.M')
	),
	
	'KITCHEN_SPACE' => array(
		'PARENT' => 'OBJECT',
		'NAME'   => GetMessage('KITCHEN_SPACE'),
		'TYPE'   => 'LIST',
		'VALUES' => $arNProp
	),
	
	'KITCHEN_SPACE_UNIT' => array(
		'PARENT' => 'OBJECT',
		'NAME'   => GetMessage('AREA_UNIT'),
		'TYPE'   => 'STRING',
		'DEFAULT' => GetMessage('SQ.M')
	),
	
	'LOT_AREA' => array(
		'PARENT' => 'OBJECT',
		'NAME'   => GetMessage('LOT_AREA'),
		'TYPE'   => 'LIST',
		'VALUES' => $arNProp
	),
	
	'LOT_AREA_UNIT' => array(
		'PARENT' => 'OBJECT',
		'NAME'   => GetMessage('AREA_UNIT'),
		'TYPE'   => 'STRING',
		'DEFAULT' => GetMessage('SQ.M')
	),
	
	'LOT_TYPE' => array(
		'PARENT' => 'OBJECT',
		'NAME'   => GetMessage('LOT_TYPE'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'ROOMS' => array(
		'PARENT' => 'LIVING_SPACE_REQUIRED',
		'NAME'   => GetMessage('ROOMS'),
		'TYPE'   => 'LIST',
		'VALUES' => $arNProp
	),
	
	'ROOMS_OFFERED' => array(
		'PARENT' => 'LIVING_SPACE_REQUIRED',
		'NAME'   => GetMessage('ROOMS_OFFERED'),
		'TYPE'   => 'LIST',
		'VALUES' => $arNProp
	),
	
	'NEW_FLAT' => array(
		'PARENT' => 'LIVING_SPACE_OPTIONAL',
		'NAME'   => GetMessage('NEW_FLAT'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'OPEN_PLAN' => array(
		'PARENT' => 'LIVING_SPACE_OPTIONAL',
		'NAME'   => GetMessage('OPEN_PLAN'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'ROOMS_TYPE' => array(
		'PARENT' => 'LIVING_SPACE_OPTIONAL',
		'NAME'   => GetMessage('ROOMS_TYPE'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'PHONE' => array(
		'PARENT' => 'LIVING_SPACE_OPTIONAL',
		'NAME'   => GetMessage('PHONE'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'INTERNET' => array(
		'PARENT' => 'LIVING_SPACE_OPTIONAL',
		'NAME'   => GetMessage('INTERNET'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'ROOM_FURNITURE' => array(
		'PARENT' => 'LIVING_SPACE_OPTIONAL',
		'NAME'   => GetMessage('ROOM_FURNITURE'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'KITCHEN_FURNITURE' => array(
		'PARENT' => 'LIVING_SPACE_OPTIONAL',
		'NAME'   => GetMessage('KITCHEN_FURNITURE'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'TELEVISION' => array(
		'PARENT' => 'LIVING_SPACE_OPTIONAL',
		'NAME'   => GetMessage('TELEVISION'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'WASHING_MACHINE' => array(
		'PARENT' => 'LIVING_SPACE_OPTIONAL',
		'NAME'   => GetMessage('WASHING_MACHINE'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'REFRIGERATOR' => array(
		'PARENT' => 'LIVING_SPACE_OPTIONAL',
		'NAME'   => GetMessage('REFRIGERATOR'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'BALCONY' => array(
		'PARENT' => 'LIVING_SPACE_OPTIONAL',
		'NAME'   => GetMessage('BALCONY'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'BATHROOM_UNIT' => array(
		'PARENT' => 'LIVING_SPACE_OPTIONAL',
		'NAME'   => GetMessage('BATHROOM_UNIT'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'FLOOR_COVERING' => array(
		'PARENT' => 'LIVING_SPACE_OPTIONAL',
		'NAME'   => GetMessage('FLOOR_COVERING'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'WINDOW_VIEW' => array(
		'PARENT' => 'LIVING_SPACE_OPTIONAL',
		'NAME'   => GetMessage('WINDOW_VIEW'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'FLOOR' => array(
		'PARENT' => 'LIVING_SPACE_OPTIONAL',
		'NAME'   => GetMessage('FLOOR'),
		'TYPE'   => 'LIST',
		'VALUES' => $arNProp
	),
	
	'FLOORS_TOTAL' => array(
		'PARENT' => 'BUILDING',
		'NAME'   => GetMessage('FLOORS_TOTAL'),
		'TYPE'   => 'LIST',
		'VALUES' => $arNProp
	),
	
	'BUILDING_NAME' => array(
		'PARENT' => 'BUILDING',
		'NAME'   => GetMessage('BUILDING_NAME'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'BUILDING_TYPE' => array(
		'PARENT' => 'BUILDING',
		'NAME'   => GetMessage('BUILDING_TYPE'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'BUILDING_SERIES' => array(
		'PARENT' => 'BUILDING',
		'NAME'   => GetMessage('BUILDING_SERIES'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'BUILDING_STATE' => array(
		'PARENT' => 'BUILDING',
		'NAME'   => GetMessage('BUILDING_STATE'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'BUILT_YEAR' => array(
		'PARENT' => 'BUILDING',
		'NAME'   => GetMessage('BUILT_YEAR'),
		'TYPE'   => 'LIST',
		'VALUES' => $arNProp
	),
	
	'READY_QUARTER' => array(
		'PARENT' => 'BUILDING',
		'NAME'   => GetMessage('READY_QUARTER'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'LIFT' => array(
		'PARENT' => 'BUILDING',
		'NAME'   => GetMessage('LIFT'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'RUBBISH_CHUTE' => array(
		'PARENT' => 'BUILDING',
		'NAME'   => GetMessage('RUBBISH_CHUTE'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'IS_ELITE' => array(
		'PARENT' => 'BUILDING',
		'NAME'   => GetMessage('IS_ELITE'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'PARKING' => array(
		'PARENT' => 'BUILDING',
		'NAME'   => GetMessage('PARKING'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'ALARM' => array(
		'PARENT' => 'BUILDING',
		'NAME'   => GetMessage('ALARM'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'CEILING_HEIGHT' => array(
		'PARENT' => 'BUILDING',
		'NAME'   => GetMessage('CEILING_HEIGHT'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'PMG' => array(
		'PARENT' => 'COUNTRYSIDE',
		'NAME'   => GetMessage('PMG'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'TOILET' => array(
		'PARENT' => 'COUNTRYSIDE',
		'NAME'   => GetMessage('TOILET'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'SHOWER' => array(
		'PARENT' => 'COUNTRYSIDE',
		'NAME'   => GetMessage('SHOWER'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'KITCHEN' => array(
		'PARENT' => 'COUNTRYSIDE',
		'NAME'   => GetMessage('KITCHEN'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'POOL' => array(
		'PARENT' => 'COUNTRYSIDE',
		'NAME'   => GetMessage('POOL'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'BILLIARD' => array(
		'PARENT' => 'COUNTRYSIDE',
		'NAME'   => GetMessage('BILLIARD'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'SAUNA' => array(
		'PARENT' => 'COUNTRYSIDE',
		'NAME'   => GetMessage('SAUNA'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'HEATING_SUPPLY' => array(
		'PARENT' => 'COUNTRYSIDE',
		'NAME'   => GetMessage('HEATING_SUPPLY'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'WATER_SUPPLY' => array(
		'PARENT' => 'COUNTRYSIDE',
		'NAME'   => GetMessage('WATER_SUPPLY'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'SEWERAGE_SUPPLY' => array(
		'PARENT' => 'COUNTRYSIDE',
		'NAME'   => GetMessage('SEWERAGE_SUPPLY'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'ELECTRICITY_SUPPLY' => array(
		'PARENT' => 'COUNTRYSIDE',
		'NAME'   => GetMessage('ELECTRICITY_SUPPLY'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
	
	'GAS_SUPPLY' => array(
		'PARENT' => 'COUNTRYSIDE',
		'NAME'   => GetMessage('GAS_SUPPLY'),
		'TYPE'   => 'LIST',
		'VALUES' => $arProp
	),
);
$arTemplateParameters['PARAMS']['HIDDEN'] = 'Y';
$arTemplateParameters['COND_PARAMS']['HIDDEN'] = 'Y';
if (!CModule::IncludeModule('catalog')) {
	unset($arTemplateParameters['PRICE_FROM_IBLOCK']);
}