<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if($arParams['IT_IS_AJAX_CALL'] != 'Y')
{
	$curParamsSerialize = serialize($arParams) ;
	$arCompareListParams = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "CompareListParams_{$arParams['IBLOCK_ID']}", '');
	if($curParamsSerialize != $arCompareListParams)
	{
		COption::SetOptionString(CYSBitronicSettings::getModuleId(), "CompareListParams_{$arParams['IBLOCK_ID']}", $curParamsSerialize);
	}
}?>