<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(!$_SESSION['YENISITE_BSF']['arParams'])
{
	$_SESSION['YENISITE_BSF']['arParams'] = $arParams; 
	$_SESSION['YENISITE_BSF']['arParams']['IT_IS_AJAX_CALL'] = 'Y'; 
}
if(!$_SESSION['YENISITE_BSF']['TemplateName']) 
	$_SESSION['YENISITE_BSF']['TemplateName'] = $this->GetTemplateName();
?>