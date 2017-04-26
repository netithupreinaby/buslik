<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if (!defined("WIZARD_SITE_ID"))
	return;

if (!defined("WIZARD_SITE_DIR"))
	return;

$copy_components = array('bitronic.settings', 'catalog.accessories', 'catalog.accessories.list', 'catalog.section.all', 'catalog.sets', 'stickers');
foreach($copy_components as $comp)
{
	$path_source = str_replace("//", "/", WIZARD_ABSOLUTE_PATH."/site/public/ru/bitrix/components/yenisite/".$comp);
	$path_distance = $_SERVER["DOCUMENT_ROOT"]."/bitrix/components/yenisite/".$comp;
	CopyDirFiles($path_source, $path_distance, true, true);
}
/*
$path_source = str_replace("//", "/", WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/bestseller");
$path_distance = $_SERVER["DOCUMENT_ROOT"].'/bestseller' ;

if(!file_exists($path_distance.'/index.php'))
	CopyDirFiles($path_source, $path_distance, true, true);

$path_source = str_replace("//", "/", WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/hit");
$path_distance = $_SERVER["DOCUMENT_ROOT"].'/hit' ;
if(!file_exists($path_distance.'/index.php'))
	CopyDirFiles($path_source, $path_distance, true, true);

$path_source = str_replace("//", "/", WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/new");
$path_distance = $_SERVER["DOCUMENT_ROOT"].'/new' ;
if(!file_exists($path_distance.'/index.php'))
	CopyDirFiles($path_source, $path_distance, true, true);
	
$path_source = str_replace("//", "/", WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/sale");
$path_distance = $_SERVER["DOCUMENT_ROOT"].'/sale' ;
if(!file_exists($path_distance.'/index.php'))
	CopyDirFiles($path_source, $path_distance, true, true);
*/	
$path_source = str_replace("//", "/", WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/include_areas/validator.php");
$path_distance = $_SERVER["DOCUMENT_ROOT"].'/include_areas/validator.php' ;
if(!file_exists($path_distance))
	copy ($path_source, $path_distance) ;
	
$path_source = str_replace("//", "/", WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/include_areas/icq.php");
$path_distance = $_SERVER["DOCUMENT_ROOT"].'/include_areas/icq.php' ;
if(!file_exists($path_distance))
	copy ($path_source, $path_distance) ;
	
$path_source = str_replace("//", "/", WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/include_areas/social_boxes.php");
$path_distance = $_SERVER["DOCUMENT_ROOT"].'/include_areas/social_boxes.php' ;
if(!file_exists($path_distance))
	copy ($path_source, $path_distance) ;
	
$path_source = str_replace("//", "/", WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/include_areas/feedback.php");
$path_distance = $_SERVER["DOCUMENT_ROOT"].'/include_areas/feedback.php' ;
if(!file_exists($path_distance))
	copy ($path_source, $path_distance) ;

$path_source = str_replace("//", "/", WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/include_areas/qrcode.php");
$path_distance = $_SERVER["DOCUMENT_ROOT"].'/include_areas/qrcode.php' ;
if(!file_exists($path_distance))
	copy ($path_source, $path_distance) ;
	
$path_source = str_replace("//", "/", WIZARD_ABSOLUTE_PATH."/.help.menu.php");
$path_distance = $_SERVER["DOCUMENT_ROOT"].'/' ;
if(!file_exists($path_distance.'/.help.menu.php'))
	CopyDirFiles($path_source, $path_distance, true, true);
	
$path_source = str_replace("//", "/", WIZARD_ABSOLUTE_PATH."/personal/create_profile.php");
$path_distance = $_SERVER["DOCUMENT_ROOT"].'/personal' ;
if(!file_exists($path_distance.'/create_profile.php'))
	CopyDirFiles($path_source, $path_distance, true, true);
	
$path_source = str_replace("//", "/", WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/pricelist");
$path_distance = $_SERVER["DOCUMENT_ROOT"].'/pricelist' ;
if(!file_exists($path_distance.'/index.php'))
	CopyDirFiles($path_source, $path_distance, true, true);
	
$path_source = str_replace("//", "/", WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/FAQ");
$path_distance = $_SERVER["DOCUMENT_ROOT"].'/FAQ' ;
if(!file_exists($path_distance.'/.section.php'))
	CopyDirFiles($path_source, $path_distance, true, true);
?>