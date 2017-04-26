<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
if($arParams['INCLUDE_JQUERY'] == 'Y')
	CJSCore::Init(array("jquery"));
if(!is_numeric($arParams['COUNT']))
	$arParams['COUNT'] = 5;
if($arParams["MODEL"]) {
	session_start();
	$_SESSION['YMRS'] = $arParams;
	$this->IncludeComponentTemplate();
}
?>