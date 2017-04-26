<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$component_path = '/bitrix/components/yenisite/catalog.accessories/' ;

__IncludeLang($_SERVER['DOCUMENT_ROOT'].$component_path.'lang/'.LANGUAGE_ID.'/settings.php') ;

define ("YS_EMPTY_VALUE", "YS_EMPTY_VALUE") ;

if(!function_exists('yenisite_GetIBlockProps'))
{
	function yenisite_GetIBlockProps($iblock_id)
	{	
		if( (($iblock_id = IntVal($iblock_id)) > 0)  && CModule::IncludeModule('iblock') )
		{
			$dbIblockProperties = CIblockProperty::GetList(array('sort'=>'asc'), array('ACTIVE'=>'Y', 'IBLOCK_ID' => $iblock_id)) ;
			$arIblockProperties = array('NAME' => array(GetMessage('YS_ACCESSORIES_EMPTY')), 'CODE'=>array(YS_EMPTY_VALUE)) ;
			while($arIblockProperty = $dbIblockProperties -> Fetch())
			{
				$arIblockProperties['NAME'][] = "{$arIblockProperty['NAME']} [{$arIblockProperty['CODE']}]" ;
				$arIblockProperties['CODE'][]   = $arIblockProperty['CODE'] ;
			}
			return array('REFERENCE' => $arIblockProperties['NAME'], 'REFERENCE_ID' => $arIblockProperties['CODE']) ;
		}
		return false ;
	}
}

if(!function_exists('yenisite_GetIBlockTypes'))
{
	function yenisite_GetIBlockTypes()
	{
		if(!CModule::IncludeModule('iblock'))
			return false ;
		
		$dbIBlockTypes = CIBlockType::GetList(array('sort'=>'asc')) ;
		$arIBlockTypes = array('NAME' => array(GetMessage('YS_ACCESSORIES_EMPTY')), 'ID'=>array(YS_EMPTY_VALUE)) ;
		while($arIBlockType = $dbIBlockTypes->Fetch())
		{
			if($arIBlockType = CIBlockType::GetByIDLang($arIBlockType["ID"], LANG))
			{
				$arIBlockTypes['NAME'][] = $arIBlockType['NAME'] ;
				$arIBlockTypes['ID'][]	 = $arIBlockType['ID'] ;
			}   
		}

		return array('REFERENCE' => $arIBlockTypes['NAME'], 'REFERENCE_ID' => $arIBlockTypes['ID']) ;		
	}
}

if(!function_exists('yenisite_GetIBlocks'))
{
	function yenisite_GetIBlocks($iblock_type)
	{
		if(!CModule::IncludeModule('iblock'))
			return false ;
			
		$dbIBlocks = CIBlock::GetList(array('sort'=>'asc'), array('TYPE'=>$iblock_type)) ;
		$arIBlocks = array('NAME' => array(GetMessage('YS_ACCESSORIES_EMPTY')), 'ID'=>array(YS_EMPTY_VALUE)) ;
		
		while($arIBlock = $dbIBlocks->Fetch())
		{
			$arIBlocks['NAME'][] = $arIBlock['NAME'] ;
			$arIBlocks['ID'][]   = $arIBlock['ID'] ;
		}
		return array('REFERENCE' => $arIBlocks['NAME'], 'REFERENCE_ID' => $arIBlocks['ID']) ;
	}
}

if(!function_exists('yenisite_PrintIBlocksRow'))
{
	function yenisite_PrintIBlocksRow($n, $arSelectIBlocks, $SelectedIBlock = '')
	{
		echo '<tr class="row'.$n.'" id="iblock_id_row'.$n.'"><td>&nbsp;</td><td colspan="2">' ;
		echo GetMessage('YS_ACCESSORIES_IBLOCK_ID').'<br/>';
		echo SelectBoxFromArray("PROPERTY[{$n}][IBLOCK_ID]", $arSelectIBlocks, $SelectedIBlock, '', 'class="iblock_id_select"', false) ;
		echo '</td></tr>' ;
	}
}

if(!function_exists('yenisite_PrintPropertyRow'))
{
	function yenisite_PrintPropertyRow($n, $arSelectIBlockProperty, $SelectedProperty = '')
	{
		echo '<tr class="row'.$n.'" id="iblock_prop_row'.$n.'"><td>&nbsp;</td><td colspan="2">';
		echo GetMessage('YS_ACCESSORIES_PROPERTY').'<br/>';
		echo SelectBoxFromArray("PROPERTY[{$n}][PROPERTY_CODE]", $arSelectIBlockProperty, $SelectedProperty, '', 'class="iblock_prop_select"', false) ;
		echo '</td></tr>';
	}
}

if(!function_exists('yenisite_PrintTypesRow'))
{
	function yenisite_PrintTypesRow($n, $arSelectParentIBlockProperty, $arSelectIBlockTypes, $SelectedProperty = '', $SelectedType = '')
	{
		echo '<tr class="row'.$n.'" id="iblock_type_row'.$n.'"><td width="40%">';
		echo GetMessage('YS_ACCESSORIES_PARENT_PROPERTY').'<br/>' ;
		echo SelectBoxFromArray("PROPERTY[{$n}][PARENT_PROPERTY]", $arSelectParentIBlockProperty, $SelectedProperty, '', '', false) ;
		echo '</td><td>';
		echo GetMessage('YS_ACCESSORIES_TYPE').'<br/>' ;
		echo SelectBoxFromArray("PROPERTY[{$n}][IBLOCK_TYPE]", $arSelectIBlockTypes, $SelectedType, '', 'class="iblock_type_select"', false) ;
		echo '</td><td><input type="button" class="ys_access_del_button" id="del_property'.$n.'" value="'.GetMessage('YS_ACCESSORIES_DEL').'"/></td></tr>' ;
	}
}
?>