<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
 // !!!!!!!! START DEBUG MODE 
/*
	$arParams['ACCESSORIES_PROPS'] = array (
			'0'=>Array('PARENT_PROPERTY'=>'STR_ACCESS', 'IBLOCK_TYPE'=>'catalog_computers_and_laptops', 'IBLOCK_ID'=>169, 'PROPERTY_CODE'=>'STRING_TO_ACCESS'),
			'1'=>Array('PARENT_PROPERTY'=>'NUM_ACCESS', 'IBLOCK_TYPE'=>'catalog_computers_and_laptops', 'IBLOCK_ID'=>169, 'PROPERTY_CODE'=>'NUMBER_ACCESS'),
			'2'=>Array('PARENT_PROPERTY'=>'LIST_ACCESS', 'IBLOCK_TYPE'=>'catalog_computers_and_laptops', 'IBLOCK_ID'=>169, 'PROPERTY_CODE'=>'LIST_ACCESS'),
			//'3'=>Array('PARENT_PROPERTY'=>'DICT_ACCESS', 'IBLOCK_TYPE'=>'catalog_computers_and_laptops', 'IBLOCK_ID'=>169, 'PROPERTY_CODE'=>2749)
		) ;
*/
// !!!!!!!! END DEBUG MODE
// обработка параметров
$arParams['IBLOCK_ID'] = IntVal($arParams['IBLOCK_ID']) ;
$arParams['ELEMENT_ID'] = IntVal($arParams['ELEMENT_ID']) ;

if(!$arParams['IBLOCK_ID'] || !$arParams['ELEMENT_ID'])
	return 0;
if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 86400;


$arParams['ACCESSORIES_PROPS'] = unserialize(base64_decode($arParams['ACCESSORIES_PROPS']));

if(strlen($arParams["FILTER_NAME"]) <= 0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"]))
	$arParams["FILTER_NAME"] = "arrFilter";
$FILTER_NAME = $arParams["FILTER_NAME"];

global $$FILTER_NAME;
$$FILTER_NAME = array();

$obCache = new CPHPCache ;

if( $obCache -> InitCache($arParams['CACHE_TIME'], "yenisite_accessories_{$arParams['ELEMENT_ID']}", '/yenisite_accessories/'))
	$arResult = $obCache -> GetVars() ;
else
{
	// инициализиурем arResult
	$arResult = array () ;
	$arResult['ELEMENT_ID'] = $arParams['ELEMENT_ID'] ;
	$arResult['IBLOCK_ID']  = $arParams['IBLOCK_ID'] ;
	// параметр привязки к элементу
	if(is_array($arParams['ACCESSORIES_LINK']))
	{
		foreach ($arParams['ACCESSORIES_LINK'] as $attached_property)
		{
			if(IntVal($attached_property) > 0)
			{
				$arProps = CIBlockElement::GetProperty ($arParams['IBLOCK_ID'], $arParams['ELEMENT_ID'], Array('sort'=>'asc'), Array('ID'=>IntVal($attached_property))) ;
				while ($arProp = $arProps -> Fetch())
				{
					$arResult['ACCESSORIES_ID'][$arProp['VALUE']] = 1 ;
				}
			}
			elseif($attached_property)
			{
				$arProps = CIBlockElement::GetProperty ($arParams['IBLOCK_ID'], $arParams['ELEMENT_ID'], Array('sort'=>'asc'), Array('CODE'=>$attached_property)) ;
				while ($arProp = $arProps -> Fetch())
				{
					if(!empty($arProp['VALUE']))
						$arResult['ACCESSORIES_ID'][$arProp['VALUE']] = 1 ;
				}
			}
		}
	}
	if(is_array($arParams['ACCESSORIES_PROPS']['PROPERTY']))
	{
		foreach($arParams['ACCESSORIES_PROPS']['PROPERTY'] as &$arProperty)
		{
			// 1. Сбор значений свойств товара
			if($arProperty['PARENT_PROPERTY'])
			{
				$dbElementProp = CIBlockElement::GetProperty($arParams['IBLOCK_ID'], $arParams['ELEMENT_ID'], Array('sort' => 'asc'), Array('CODE' => $arProperty['PARENT_PROPERTY']));

				while( $arProp = $dbElementProp -> Fetch() )
				{
					switch($arProp['PROPERTY_TYPE'])
					{
						case 'S':
						case 'N':
						case 'E':
							$arProperty['VALUE'][] = $arProp['VALUE'] ;
						break ;

						case 'L':
							$arProperty['VALUE'][] = $arProp['VALUE_ENUM'] ;
						break;
					}
				}
			}
			// 2. Сбор сведений о свойствах в инфоблоке с аксессуарами 
			if($arProperty['PROPERTY_CODE'] && $arProperty['IBLOCK_ID'])
			{
				$dbAccessoriesProp = CIBlockProperty::GetByID($arProperty['PROPERTY_CODE'], $arProperty['IBLOCK_ID']) ;
				if( $arProp = $dbAccessoriesProp -> Fetch() )
				{
					$arProperty['PROPERTY_TYPE'] = $arProp['PROPERTY_TYPE'] ;
				}
			}

			// 3. Поиск аксессуаров
			if($arProperty['VALUE'])
			{
				$prop_code = "PROPERTY_{$arProperty['PROPERTY_CODE']}" ;
				if($arProperty['PROPERTY_TYPE'] == 'L')
					$prop_code .= '_VALUE' ;
				
				$arFilterProp = array("LOGIC" => "OR");
				foreach($arProperty['VALUE'] as $value)
					$arFilterProp[] = array($prop_code => $value);
				$arFilter = Array('IBLOCK_ID' => $arProperty['IBLOCK_ID'], $arFilterProp, '!ID' => $arParams['ELEMENT_ID']) ;

				$dbAccessories = CIBlockElement::GetList(Array(), $arFilter, false, false, Array('ID')) ;

				while($arAccessories = $dbAccessories -> Fetch())
				{
					if(!is_array($arResult['ACCESSORIES_ID'][$arAccessories['ID']]))
					{
						$arResult['ACCESSORIES_ID'][$arAccessories['ID']] = 2;
					}
				}
			}
		}
	}
	if(is_array($arResult['ACCESSORIES_ID']))
	{
	foreach ($arResult['ACCESSORIES_ID'] as $accessoires_id => $arAccessories)
	{
		$arResult['ID_ARRAY'][] = $accessoires_id ; 
	}
	}
}

if($obCache->StartDataCache())
    $obCache->EndDataCache($arResult) ;

if(count($arResult['ID_ARRAY']))
	$$FILTER_NAME = array('ID' => $arResult['ID_ARRAY']) ;
?>