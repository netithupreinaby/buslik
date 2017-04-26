<?
if (!\Bitrix\Main\Loader::includeModule('yenisite.coreparser')) return false;

CModule::AddAutoloadClasses('yenisite.specifications', array(
	'CSpecifications' => 'classes/general/yenisite_specifications.php',
));

