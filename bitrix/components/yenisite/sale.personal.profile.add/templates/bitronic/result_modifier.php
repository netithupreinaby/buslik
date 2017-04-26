<? if($_REQUEST["ys_st_ajax_call"] !== "y") if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();



/**/
if ($this->__folder) {
	$pathToTemplateFolder = $this->__folder;
} else {
	$pathToTemplateFolder = str_replace(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '', dirname(__FILE__));
}
/*COLOR*/
	$arColorSchemes = array ('red', 'green', 'ice', 'metal', 'pink', 'yellow') ;
	$color_scheme = 'red';
	if($arParams['COLOR_SCHEME'] && in_array($arParams['COLOR_SCHEME'], $arColorSchemes, true))
		$color_scheme = $arParams['COLOR_SCHEME'];
	$APPLICATION->SetAdditionalCSS("{$pathToTemplateFolder}/css_color/{$color_scheme}.css");
	$APPLICATION->SetAdditionalCSS("{$pathToTemplateFolder}/css/selectbox.css");
/*JQUERY*/
	if($arParams['INCLUDE_JQUERY'] == 'Y')
		CJSCore::Init(array("jquery"));
/*SELECTBOX*/
	 //$APPLICATION->AddHeadScript("{$pathToTemplateFolder}/jquery.js");
	//$APPLICATION->AddHeadScript("{$pathToTemplateFolder}/jquery.jgrowl_minimized.js");
	$APPLICATION->AddHeadScript("{$pathToTemplateFolder}/selectbox.js");
	// $APPLICATION->AddHeadScript("{$pathToTemplateFolder}/script.js");
	 $APPLICATION->AddHeadScript("{$pathToTemplateFolder}/jquery.uniform.min.js");
	 $APPLICATION->AddHeadScript("{$pathToTemplateFolder}/core.js");
?>
