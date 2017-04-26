<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

if (is_array($arResult['FIELDS'])) {
	if (isset($arResult['FIELDS']['CREATED_BY'])) {
		unset($arResult['FIELDS']['CREATED_BY']);
		
		$cp = $this->__component;

		if (is_object($cp))
		{
			$cp->SetResultCacheKeys(array('CREATED_BY'));
		}
	}

	if (isset($arResult['FIELDS']['DATE_CREATE'])) {
		unset($arResult['FIELDS']['DATE_CREATE']);
		
		//$arResult['DATE_CREATE_FORMATTED'] = date('d.m.Y', MakeTimeStamp($arResult['DATE_CREATE']));
	}
	
	if (count($arResult['FIELDS']) <= 0) {
		unset($arResult['FIELDS']);
	}
}
