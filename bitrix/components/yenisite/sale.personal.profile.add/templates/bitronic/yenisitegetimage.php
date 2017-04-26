<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if (!function_exists('yenisite_GetFieldPicSrc')) {
	function yenisite_GetFieldPicSrc($arElementPicField)
	{
		
		if(is_array($arElementPicField))
		{
			return $arElementPicField['ID'] ;
		}
		elseif(intval($arElementPicField) > 0)
		{
			return intval($arElementPicField); //CFile::GetPath(intval($arElementPicField)) ;
		}
		
		return false;
	}
}
if (!function_exists('yenisite_GetPropPicSrc')) {
	function yenisite_GetPropPicSrc($arElement, $prop_code)
	{
		if(!is_array($arElement) || !$prop_code)
			return false;
		
		$arPropFile = false ;
			
		if(is_array($arElement['PROPERTIES'][$prop_code]))
		{
			$arPropFile = $arElement['PROPERTIES'][$prop_code]['VALUE'] ;
		}
		else
		{
			$dbProp = CIBlockElement::GetProperty($arElement['IBLOCK_ID'], $arElement['ID'], array("ID" => "ASC"), Array("CODE" => $prop_code));
			if($arProp = $dbProp->Fetch())
			{	
				$arPropFile = $arProp['VALUE'] ;
			}
		}
		if($arPropFile)
		{
			if(is_array($arPropFile))
			{
				return $arPropFile[0] ;
			}
			elseif(intval($arPropFile) > 0)
			{
				return $arPropFile ;
			}
			
			
			/* if(intval($pic_id) > 0)
			{
				return CFile::GetPath(intval($pic_id)) ;
			} */
		}
		return false;
	}
}
if (!function_exists('yenisite_GetPicSrc')) {
	function yenisite_GetPicSrc ($arElement, $arParamsImage, $default_image_code = 'MORE_PHOTO')
	{
		if(!is_array($arElement))
			return false;

		$picsrc = false; $find_in_prop = false;
		if($arParamsImage != 'PREVIEW_PICTURE' && $arParamsImage != 'DETAIL_PICTURE')
		{
			$find_in_prop = true ;
			if(!$picsrc = yenisite_GetPropPicSrc($arElement, $arPramsImage))
			{
				$picsrc = yenisite_GetPropPicSrc($arElement, $default_image_code) ;
			}
		}
		
		if($arParamsImage == 'DETAIL_PICTURE' || $arParamsImage == 'PREVIEW_PICTURE')
		{
			$picsrc = yenisite_GetFieldPicSrc ($arElement[$arParamsImage]) ;
		}	
			
		if(!$picsrc)
		{
			if(!$find_in_prop)
				$picsrc = yenisite_GetPropPicSrc($arElement, $default_image_code) ;
				
			if(!$picsrc && $arParamsImage != 'DETAIL_PICTURE')
				$picsrc = yenisite_GetFieldPicSrc($arElement['DETAIL_PICTURE']) ;
			
			if(!$picsrc && $arParamsImage != 'PREVIEW_PICTURE')
				$picsrc = yenisite_GetFieldPicSrc($arElement['PREVIEW_PICTURE']) ;
		}
		return $picsrc ;
	}
}
?>