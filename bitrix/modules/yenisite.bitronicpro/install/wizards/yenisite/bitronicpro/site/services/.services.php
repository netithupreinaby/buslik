<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$wizard = &$this->GetWizard();
$setup = $wizard->GetVar("demo_install");
$install_type = $wizard->GetVar("install_type");

if ($setup == "Y") {
	$arServices = Array(
		"main" => Array(
			"NAME" => GetMessage("SERVICE_MAIN_SETTINGS"),
			"STAGES" => Array(
				"files.php", // Copy bitrix files
				"template.php", // Install template
				"mod_ini.php",
				"theme.php", // Install theme
				"add_voting.php", 
				"feedback.php", //Ajax forms for feedbacks
				"holidays.php", //Holiday stickers
			),
		),

		"iblock" => Array(
			"NAME" => GetMessage("SERVICE_IBLOCK_DEMO_DATA"),
			"STAGES" => Array(
				"types.php", 
				"news.php",		
				"campaigns.php",	
				"producer.php",	
				"os.php",	
				"countries.php",
				"services.php",
				"catalog1.php",
				"demo_data.php"
			),
		),

		"sale" => Array(
			"NAME" => GetMessage("SERVICE_SALE_DEMO_DATA"),
			"STAGES" => Array(
				"step1.php", "step2.php",
			),
		),
	);
} else {
	if ($install_type=="update") {
		$arServices = Array(
			"iblock" => Array(
				"NAME" => GetMessage("SERVICE_IBLOCK_DEMO_DATA"),
				"STAGES" => Array(
					"services.php",  // import iblock of services
				),
			),
			"main" => Array(
				"NAME" => GetMessage("SERVICE_MAIN_SETTINGS"),
				"STAGES" => Array(		
					"add_new_props.php", // create props in 1.3.8 + 1.3.9
					"count_sale_int.php", // count SALE_INT in 1.3.8
					"template.php", // Install template and remove old files
					"add_voting.php", 
					"yenisite_components.php",
					"mod_ini.php",
					"theme.php", // Install theme
					"add_new_uf_props.php", // create props 1.3.9
					"copy_dir.php", // Install need(new) dir
					"rewrite_index.php",
					"feedback.php", //Ajax forms for feedbacks
					"holidays.php", //Holiday stickers
				),
			),
			
		);
	} else {
		$arServices = Array(
			"main" => Array(
				"NAME" => GetMessage("SERVICE_MAIN_SETTINGS"),
				"STAGES" => Array(
					"files.php", // Copy bitrix files
					"template.php", // Install template
					"mod_ini.php",
					"theme.php", // Install theme
					"feedback.php", //Ajax forms for feedbacks
					"holidays.php", //Holiday stickers
				),
			),
			"iblock" => Array(
				"NAME" => GetMessage("SERVICE_IBLOCK_DEMO_DATA"),
				"STAGES" => Array(
					"news.php",	
					"campaigns.php",
					"services.php",
					"catalog1.php",
				),
			),
			"sale" => Array(
				"NAME" => GetMessage("SERVICE_SALE_DEMO_DATA"),
				"STAGES" => Array(
					"step1.php", "step2.php",
				),
			),
		);	
	}
}
?>
