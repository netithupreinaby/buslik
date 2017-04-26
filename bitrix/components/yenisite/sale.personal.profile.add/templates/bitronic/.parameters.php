<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();




$arTemplateParameters = array(

	
	
	/*VISUAL*/
	"COLOR_SCHEME" => Array(
		"PARENT" => "VISUAL",
		"NAME" => GetMessage("COLOR_SCHEME"),
		"TYPE" => "LIST",
		"VALUES" => array(
			"red" => GetMessage("COLOR_SCHEME_RED"), 
			"green" => GetMessage("COLOR_SCHEME_GREEN"), 
			"ice" => GetMessage("COLOR_SCHEME_BLUE"), 
			"metal" => GetMessage("COLOR_SCHEME_METAL"), 
			"pink" => GetMessage("COLOR_SCHEME_PINK"), 
			"yellow" => GetMessage("COLOR_SCHEME_YELLOW")
		),
		"ADDITIONAL_VALUES" => "Y",
		"DEFAULT" => '={$ys_options["color_scheme"]}',
	),
	
);





?>
