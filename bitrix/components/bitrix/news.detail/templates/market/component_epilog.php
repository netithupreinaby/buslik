<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $USER;

if ($arResult['CREATED_BY'] != $USER->GetID()
||  $arResult['PROPERTIES']['SITE_ID']['VALUE'] != SITE_ID) {

	LocalRedirect($arResult['LIST_PAGE_URL']);
}