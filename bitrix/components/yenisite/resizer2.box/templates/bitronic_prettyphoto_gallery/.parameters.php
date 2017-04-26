<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();



CModule::IncludeModule("yenisite.resizer2");
$arSets = CResizer2Set::GetList();
while($arr = $arSets->Fetch())
{
	$arSet[$arr["id"]] = "[".$arr["id"]."] ".$arr["NAME"];
}


$arOpacity = array("0" => "0", "0.1" => "0.1", "0.2" => "0.2", "0.3" => "0.3", "0.4" => "0.4", "0.5" => "0.5", "0.6" => "0.6", "0.7" => "0.7", "0.8" => "0.8", "0.9" => "0.9", "1" => "1");
$arOverlay = array("true" => "Y", "false" => "N");

global $arComponentParameters;

$arComponentParameters["GROUPS"]["YENISITE_RESIZER2_PARAMETERS"]= array(
	"NAME" => GetMessage("YENISITE_RESIZER2_PARAMETERS"),
	"SORT" => 2000,
);


$arTemplateParameters = array(
    "SET_DETAIL" => array(
        "PARENT" => "BASE",
        "NAME" => GetMessage("SET_DETAIL"),
        "TYPE" => "LIST",
        "VALUES" => $arSet,    
	"DEFAULT" => "2"     
    ),
    
    "SET_BIG_DETAIL" => array(
        "PARENT" => "BASE",
        "NAME" => GetMessage("SET_BIG_DETAIL"),
        "TYPE" => "LIST",
        "VALUES" => $arSet,        
	"DEFAULT" => "1"  
    ),
    
    "SET_SMALL_DETAIL" => array(
        "PARENT" => "BASE",
        "NAME" => GetMessage("SET_SMALL_DETAIL"),
        "TYPE" => "LIST",
        "VALUES" => $arSet, 
	"DEFAULT" => "6"       
    ),
		
    "SHOW_DESCRIPTION"	=> Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("SHOW_DESCRIPTION"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",			
		),
	
	"THEME"	=> Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("THEME"),
			"TYPE" => "LIST",
			"VALUES" => array("dark_rounded" => "dark_rounded", "dark_square" => "dark_square", "facebook" => "facebook", "light_rounded" => "light_rounded", "light_square" => "light_square"),			
		),
	
    "ANIMATION_SPEED" => array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("ANIMATION_SPEED"),
			"TYPE" => "LIST",
			"VALUES" => array( "slow" => GetMessage("SLOW"), "normal" => GetMessage("NORMAL"), "fast" => GetMessage("FAST") ),
		),
		
    "OVERLAY_OPACITY" => array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("OVERLAY_OPACITY"),
			"TYPE" => "LIST",
			"VALUES" => $arOpacity,
		),
		
	"SLIDE_SHOW" => array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("SLIDE_SHOW"),
			"TYPE" => "LIST",
			"VALUES" => array("Y" => "Y", "N" => "N"),
		),
		
	"SLIDE_SHOW_INTERVAL" => array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("SLIDE_SHOW_INTERVAL"),
			"TYPE" => "STRING",
			"DEFAULT" => "1500",
		),
		
	"AUTOPLAY_SLIDE_SHOW"	=> Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("AUTOPLAY_SLIDE_SHOW"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",			
		),
		
	"ALLOW_RESIZE"	=> Array(
			"PARENT" => "YENISITE_RESIZER2_PARAMETERS",
			"NAME" => GetMessage("ALLOW_RESIZE"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",			
		),
);
?>
