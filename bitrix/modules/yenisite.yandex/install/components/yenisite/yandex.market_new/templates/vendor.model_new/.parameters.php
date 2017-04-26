<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule("iblock"))die();

global $arComponentParameters;

$arComponentParameters["GROUPS"]["YENISITE_YM_VENDOR"]= array(
	"NAME" => GetMessage("YS_YM_VENDOR_NAME"),
	"SORT" => 2000,
);

$arComponentParameters["GROUPS"]["MANDATORY_PARAMS"]= array(
	"NAME" => GetMessage("MANDATORY_PARAMS"),
	"SORT" => 2000,
);

$arComponentParameters["GROUPS"]["OPTIONAL_PARAMS"]= array(
	"NAME" => GetMessage("OPTIONAL_PARAMS"),
	"SORT" => 2000,
);

foreach($arCurrentValues["IBLOCK_ID_IN"] as $id)
if ($id > 0)
{
	$arProp = array();
	
	if ( CModule::IncludeModule('catalog') )
	{
		$andreytroll = CCatalog::GetList(array(),array("PRODUCT_IBLOCK_ID"=> $id), false, false, array());
		$check = $andreytroll->Fetch();
			
		$rsProp = CIBlockProperty::GetList(array("sort" => "desc"), array("IBLOCK_ID" => $check["IBLOCK_ID"]) );
			
		while( $arr = $rsProp->Fetch() )
		{
			if ( !in_array($arr["NAME"], $arProp) && ($arr["PROPERTY_TYPE"] == "E" || $arr["PROPERTY_TYPE"] == "L" ||
				$arr["PROPERTY_TYPE"] == "S" || $arr["PROPERTY_TYPE"] == "N") )
			{
				$arProp[$arr["CODE"]] = "SKU_".$arr["NAME"];
			}
		}
	}
		
	$rsProp = CIBlockProperty::GetList(array("sort" => "desc"), array("IBLOCK_ID" => $id) );
		
	while ( $arr = $rsProp->Fetch() )
	{
		if ( !in_array($arr["NAME"], $arProp) && ($arr["PROPERTY_TYPE"] == "E" || $arr["PROPERTY_TYPE"] == "L" ||
			$arr["PROPERTY_TYPE"] == "S" || $arr["PROPERTY_TYPE"] == "N") )
		{
			$arProp[$arr["CODE"]] = $arr["NAME"];
		}
	}
}

	$arProp["EMPTY"] = "				"; 
	natsort($arProp);
	
$arTemplateParams = array (	'DEFAULT_CATEGORY' => array(
										'MANDATORY_PARAMS' => array(
											'DEVELOPER', 'MODEL'),
										'OPTIONAL_PARAMS' => array(
											'COUNTRY', 'VENDOR_CODE', 'MANUFACTURER_WARRANTY') ),
							'TIRE' => 
								array('MANDATORY_PARAMS' => array('DEVELOPER', 'SERIES', 'WIDTH_PR', 'DIAMSH', 'IND_NAGR', 'IND_MAX_SPEED'), 'OPTIONAL_PARAMS' => array('COUNTRY')),
							'ACOUSTIC_SYSTEM' =>
								array('MANDATORY_PARAMS' => array('DEVELOPER', 'SERIES', 'MODEL'), 'OPTIONAL_PARAMS' => array('COUNTRY')),
							'CLOTHES' =>
								array('MANDATORY_PARAMS' => array('DEVELOPER', 'COUNTRY', 'CLOTHES_CATEGORY')),
							'BATH' =>
								array('MANDATORY_PARAMS' => array('DEVELOPER', 'MODEL', 'SIZE', 'COMPL'), 'OPTIONAL_PARAMS' => array('COUNTRY')),
							'ROLLER_SKATES' =>
								array('MANDATORY_PARAMS' => array('DEVELOPER', 'MODEL', 'SEASON', 'APPOINTMENT'), 'OPTIONAL_PARAMS' => array('COUNTRY')),
							'PROCESSOR' =>
								array('MANDATORY_PARAMS' => array('DEVELOPER', 'MODEL', 'PROCESSOR_SOCKET', 'PROCESSOR_FREQUECY', 'PROCESSOR_L2'), 'OPTIONAL_PARAMS' => array('PROCESSOR_BUS', 'PROCESSOR_CORE', 'PROCESSOR_CODE', 'VENDOR_CODE')),
							'LENS' =>
								array('MANDATORY_PARAMS' => array('DEVELOPER', 'MODEL', 'LENS_BAYONET'), 'OPTIONAL_PARAMS' => array('LENS_FOCUS', 'LENS_DIAFRAGMA', 'LENS_MARKER')),
							'LAPTOP' =>
								array('MANDATORY_PARAMS' => array('DEVELOPER', 'MODEL', 'NOTE_PROC_TYPE', 'NOTE_PROC_FREQUECY', 'NOTE_MEMORY', 'NOTE_SCREEN', 'NOTE_HDD', 'NOTE_ROM', 'NOTE_OS', 'NOTE_WIFI', 'NOTE_BLUETOOTH', 'NOTE_3G', 'NOTE_VIDEO'), 'OPTIONAL_PARAMS' => array('NOTE_PROC_MARKER', 'VENDOR_CODE')),
							'CONDITIONER' =>
								array('MANDATORY_PARAMS' => array('DEVELOPER', 'MODEL', 'CONDITIONER_SPLIT')),
							'BEAUTY' =>
								array('MANDATORY_PARAMS' => array('DEVELOPER', 'MODEL', 'PRODUCT_LINE', 'COLOR', 'VOLUME', 'VOLUME_UNIT' ), 'OPTIONAL_PARAMS' => array('COLOR_RGB', 'GENDER')),
							'CHRISTMAS_TREE' =>
								array('MANDATORY_PARAMS' => array('DEVELOPER', 'MODEL', 'ARTICUL', 'SIZE', 'COLOR', 'FIR_TREE_HEIGHT', 'FIR_TREE_DIAM', 'FIR_TREE_DIAM_UNIT')),
							'COMPUTER_ACOUSTIC' =>
								array('MANDATORY_PARAMS' => array('DEVELOPER', 'MODEL', 'VENDOR_CODE')),
							'SETS_ACOUSTIC' =>
								array('MANDATORY_PARAMS' => array('DEVELOPER', 'SERIES', 'ACOUSTICS_COMPLEKT')),
							'WHEEL' =>
								array('MANDATORY_PARAMS' => array('DEVELOPER', 'MODEL', 'WHEELS_WIDTH', 'WHEELS_DIAMETER', 'WHEELS_FIXING', 'WHEELS_BOOM', 'COLOR')),
							'SHOWER_ROOM' =>
								array('MANDATORY_PARAMS' => array('DEVELOPER', 'MODEL', 'SIZE', 'SHOWER_MODIFICATION', 'SHOWER_COMPLECT')),
							'BABY_BUGGY' =>
								array('MANDATORY_PARAMS' => array('DEVELOPER', 'MODEL', 'BABY_TYPE', 'BABY_CHASSIS', 'BABY_CRADLE')),
							'DOME' =>
								array('MANDATORY_PARAMS' => array('DEVELOPER', 'MODEL', 'HOOD_TYPE', 'COLOR', 'HOOD_PERFORMANCE', 'HOOD_CONTROL')),
							'VIDEO_CARD' =>
								array('MANDATORY_PARAMS' => array('DEVELOPER', 'MODEL', 'VIDEO_PROC', 'VIDEO_TYPE_CONN', 'VIDEO_MEMORY_SIZE', 'VIDEO_PROC_FREQ', 'VIDEO_MEMORY_FREQ', 'VIDEO_MEMORY_TYPE', 'VIDEO_BUS', 'VIDEO_INTERFACE', 'VIDEO_HDCP', 'VIDEO_COOLING')),
							'BICYCLE' =>
								array('MANDATORY_PARAMS' => array('DEVELOPER', 'MODEL', 'YEAR')),
							'RAM' =>
								array('MANDATORY_PARAMS' => array("DEVELOPER", "MODEL", "VENDOR_CODE","MEMORY_TYPE","MEMORY_FORM_FACTOR","MEMORY_COUNT","MEMORY_SIZE","MEMORY_SIZE_UNIT","MEMORY_FREQUECY",	   "MEMORY_FREQUECY_UNIT"),
								'OPTIONAL_PARAMS' => array("MEMORY_CL", "MEMORY_LOW_PROFILE","MEMORY_ECC","MEMORY_BUFFER", "MEMORY_RADIATOR")),
							'SNOWBOARD' => array( 
								'MANDATORY_PARAMS'=>array("DEVELOPER","MODEL","SEASON"),
								'OPTIONAL_PARAMS'=>array( )	
										),				
							'DIGITAL_CAMERA' => array (
								'MANDATORY_PARAMS'=>array("DEVELOPER", "MODEL", "CAMERA_LENS", "CAMERA_FOCUS")
										),
						);
						
$arClothesCategoryParams = array('NONE' => 
									array('MANDATORY_PARAMS' => array('SIZE', 'SIZE_UNIT', 'COLOR', 'GENDER', 'AGE', 'MATERIAL')),
								'GEANS_MAN' =>
									array('MANDATORY_PARAMS' => array('SIZE', 'SIZE_UNIT', 'GROWTH', 'GROWTH_UNIT', 'GENDER', 'COLOR', 'AGE', 'MATERIAL')),
								'GEANS_WOMAN' =>
									array('MANDATORY_PARAMS' => array('SIZE', 'SIZE_UNIT', 'GROWTH', 'GROWTH_UNIT', 'GENDER', 'COLOR', 'AGE', 'MATERIAL')),
								'PANTS_MAN' =>
									array('MANDATORY_PARAMS' => array('SIZE', 'SIZE_UNIT', 'GROWTH', 'GROWTH_UNIT', 'GENDER', 'COLOR', 'AGE', 'MATERIAL')),
								'PANTS_WOMAN' =>
									array('MANDATORY_PARAMS' => array('SIZE', 'SIZE_UNIT', 'GROWTH', 'GROWTH_UNIT', 'GENDER', 'COLOR', 'AGE', 'MATERIAL')),
								'SHIRTS_MAN' =>
									array('MANDATORY_PARAMS' => array('CHEST', 'CHEST_UNIT', 'NECK_GIRTH', 'NECK_GIRTH_UNIT', 'GENDER', 'COLOR', 'AGE', 'MATERIAL')),
								'COSTUMES_MAN' =>
									array('MANDATORY_PARAMS' => array('CHEST', 'CHEST_UNIT', 'GROWTH', 'GROWTH_UNIT', 'WAIST_UNIT', 'GENDER', 'COLOR', 'AGE', 'MATERIAL')),
								'COSTUMES_WOMAN' =>
									array('MANDATORY_PARAMS' => array('CHEST', 'CHEST_UNIT', 'GROWTH', 'GROWTH_UNIT', 'WAIST_UNIT', 'GENDER', 'COLOR', 'AGE', 'MATERIAL')),
								'CORSAGES_WOMAN' =>
									array('MANDATORY_PARAMS' => array('CHEST', 'CHEST_UNIT', 'GIRTH_AT_BREAST', 'GIRTH_AT_BREAST_UNIT', 'CUP', 'CUP_UNIT', 'GENDER', 'COLOR', 'AGE', 'MATERIAL')),
								'BRAS_WOMAN' =>
									array('MANDATORY_PARAMS' => array('CHEST', 'CHEST_UNIT', 'GIRTH_AT_BREAST', 'GIRTH_AT_BREAST_UNIT', 'CUP', 'CUP_UNIT', 'GENDER', 'COLOR', 'AGE', 'MATERIAL')),
								'MODELING_WOMAN' =>
									array('MANDATORY_PARAMS' => array('CHEST', 'CHEST_UNIT', 'GIRTH_AT_BREAST', 'GIRTH_AT_BREAST_UNIT', 'CUP', 'CUP_UNIT', 'GENDER', 'COLOR', 'AGE', 'MATERIAL')),
								'MOM_LINEN_WOMAN' =>
									array('MANDATORY_PARAMS' => array('CHEST', 'CHEST_UNIT', 'GIRTH_AT_BREAST', 'GIRTH_AT_BREAST_UNIT', 'CUP', 'CUP_UNIT', 'GENDER', 'COLOR', 'AGE', 'MATERIAL')),
								'KITS_WOMAN' =>
									array('MANDATORY_PARAMS' => array('CHEST', 'CHEST_UNIT', 'GIRTH_AT_BREAST', 'GIRTH_AT_BREAST_UNIT', 'CUP', 'CUP_UNIT', 'SIZE_UNDERWEAR', 'SIZE_UNDERWEAR_UNIT', 'GENDER', 'COLOR', 'AGE', 'MATERIAL')),
								'BATHING_WOMAN' =>
									array('MANDATORY_PARAMS' => array('CHEST', 'CHEST_UNIT', 'GIRTH_AT_BREAST', 'GIRTH_AT_BREAST_UNIT', 'CUP', 'CUP_UNIT', 'SIZE_UNDERWEAR', 'SIZE_UNDERWEAR_UNIT', 'GENDER', 'COLOR', 'AGE', 'MATERIAL')),
								'MOM_BATHING_WOMAN' =>
									array('MANDATORY_PARAMS' => array('CHEST', 'CHEST_UNIT', 'GIRTH_AT_BREAST', 'GIRTH_AT_BREAST_UNIT', 'CUP', 'CUP_UNIT', 'SIZE_UNDERWEAR', 'SIZE_UNDERWEAR_UNIT', 'GENDER', 'COLOR', 'AGE', 'MATERIAL')),
);
	
$arValues = array (
	'DEFAULT_CATEGORY'  => GetMessage('DEFAULT_CATEGORY'),
	'TIRE'              => GetMessage("TIRE"), 
	'ACOUSTIC_SYSTEM'   => GetMessage("ACOUSTIC_SYSTEM"), 
	'BATH'              => GetMessage("BATH"), 
	'BICYCLE'           => GetMessage("BICYCLE"), 
	'VIDEO_CARD'        => GetMessage("VIDEO_CARD"), 
	'DOME'              => GetMessage("DOME"), 
	'BABY_BUGGY'        => GetMessage("BABY_BUGGY"), 
	'SHOWER_ROOM'       => GetMessage("SHOWER_ROOM"), 
	'WHEEL'             => GetMessage("WHEEL"), 
	'SETS_ACOUSTIC'     => GetMessage("SETS_ACOUSTIC"), 
	'COMPUTER_ACOUSTIC' => GetMessage("COMPUTER_ACOUSTIC"), 
	'CONDITIONER'       => GetMessage("CONDITIONER"),
	'BEAUTY'            => GetMessage('BEAUTY'),
	'CHRISTMAS_TREE'    => GetMessage("CHRISTMAS_TREE"), 
	'LAPTOP'            => GetMessage("LAPTOP"), 
	'LENS'              => GetMessage("LENS"), 
	'CLOTHES'           => GetMessage("CLOTHES"), 
	'RAM'               => GetMessage("RAM"), 
	'PROCESSOR'         => GetMessage("PROCESSOR"), 
	'ROLLER_SKATES'     => GetMessage("ROLLER_SKATES"), 
	'SNOWBOARD'         => GetMessage("SNOWBOARD"), 
	'DIGITAL_CAMERA'    => GetMessage("DIGITAL_CAMERA")
);
							
$arTemplateParameters["CATEGORY"] = Array(
									"PARENT" => "YENISITE_YM_VENDOR",
									"NAME" =>  GetMessage("CATEGORY"),
									"TYPE" => "LIST",
									"VALUES" => $arValues,	
									"REFRESH" => "Y",
								);
								
$arTemplateParameters["MARKET_CATEGORY_CHECK"] = Array(
									"PARENT" => "YENISITE_YM_VENDOR",
									"NAME" => GetMessage("MARKET_CATEGORY_CHECK"),
									"TYPE" => "CHECKBOX",
									"DEFAULT" => "N",
								);

$arTemplateParameters["MARKET_CATEGORY_PROP"] = Array(
									"PARENT" => "YENISITE_YM_VENDOR",
									"NAME" => GetMessage("MARKET_CATEGORY_PROP"),
									"TYPE" => "LIST",
									"VALUES" => $arProp,	
								);
							
$arClothesCategory = Array(	"NONE" 				=> GetMessage("NONE"),
							"GEANS_MAN" 		=> GetMessage("GEANS_MAN"),
							"GEANS_WOMAN" 		=> GetMessage("GEANS_WOMAN"),
							"PANTS_MAN" 		=> GetMessage("PANTS_MAN"),
							"PANTS_WOMAN" 		=> GetMessage("PANTS_WOMAN"),
							"SHIRTS_MAN" 		=> GetMessage("SHIRTS_MAN"),
							"COSTUMES_MAN" 		=> GetMessage("COSTUMES_MAN"),
							"COSTUMES_WOMAN" 	=> GetMessage("COSTUMES_WOMAN"),
							"CORSAGES_WOMAN" 	=> GetMessage("CORSAGES_WOMAN"),
							"BRAS_WOMAN" 		=> GetMessage("BRAS_WOMAN"),
							"KITS_WOMAN" 		=> GetMessage("KITS_WOMAN"),
							"BATHING_WOMAN" 	=> GetMessage("BATHING_WOMAN"),
							"MODELING_WOMAN" 	=> GetMessage("MODELING_WOMAN"),
							"MOM_LINEN_WOMAN" 	=> GetMessage("MOM_LINEN_WOMAN"),
							"MOM_BATHING_WOMAN" => GetMessage("MOM_BATHING_WOMAN"));

$arSizeTypes = Array(	"RU" => "RU",
						"EU" => "EU",
						"DE" => "DE",
						"IT" => "IT",
						"FR" => "FR",
						"UK" => "UK",
						"US" => "US",
						"AU" => "AU",
						"Japan" => "Japan",
						"INT" 	=> "INT",
						GetMessage("YENISITE_YANDEX_DUYM") 	=> GetMessage("INCH"),
						GetMessage("YENISITE_YANDEX_SM") 	=> GetMessage("SM"),
						"Months" => GetMessage("MONTHS"),
						"Years" => GetMessage("YEARS_SIZE"),
						GetMessage("YENISITE_YANDEX_SM") => GetMessage("ROUND_SM"),
						GetMessage("GROWTH") => GetMessage("ROST_SIZE"),
						GetMessage("YENISITE_YANDEX_OKRUJNOSTQ_GOLOVY") => GetMessage("ROUND_HEAD"),
						"BRAND" => GetMessage("BRAND") );
						
$CurrentCategory = $arCurrentValues["CATEGORY"];

switch($arCurrentValues["CATEGORY"])
{
		case $CurrentCategory:
		
		if ( $CurrentCategory == "CLOTHES" )
		{
			foreach($arTemplateParams[$CurrentCategory]["MANDATORY_PARAMS"] as $k)
			{
				if ( $k != 'CLOTHES_CATEGORY')
				{
					$arTemplateParameters[$k] = Array(
						"PARENT" => "MANDATORY_PARAMS",
						"NAME" =>  GetMessage($k),
						"TYPE" => "LIST",
						"VALUES" => $arProp,
					);
				}
				else
				{
					$arTemplateParameters[$k] = Array(
						"PARENT" => "MANDATORY_PARAMS",
						"NAME" => GetMessage("CLOTHES_CATEGORY"),
						"TYPE" => "LIST",
						"VALUES" => $arClothesCategory,
						"REFRESH" => "Y",
					);
				}
			}
			$currentClothesCategory = $arCurrentValues['CLOTHES_CATEGORY'];
			
			switch($arCurrentValues['CLOTHES_CATEGORY'])
			{
				case $currentClothesCategory:
				
				if ( strpos($currentClothesCategory, "_MAN") ):?>
				
				<script>
					$(document).ready(function() {
					$('input[name="GENDER"]').attr('value', '<?=GetMessageJS("YENISITE_YANDEX_MUJSKOY")?>');
					});
				</script>
				
				<?elseif ( strpos($currentClothesCategory, "_WOMAN") ):?>
				
				<script>
					$(document).ready(function() {
					$('input[name="GENDER"]').attr('value', '<?=GetMessageJS("YENISITE_YANDEX_JENSKIY")?>');
					});
				</script>
				
				<?endif;
				
				foreach($arClothesCategoryParams[$currentClothesCategory]["MANDATORY_PARAMS"] as $k)
				{
					if ( strpos($k, "_UNIT") ) 
					{
						$arTemplateParameters[$k] = Array(
							"PARENT" => "MANDATORY_PARAMS",
							"NAME" =>  GetMessage($k),
							"TYPE" => "LIST",
							"ADDITIONAL_VALUES" => "Y",
							"VALUES" => $arSizeTypes ,
						);	
					}
					elseif ( $k != "GENDER" )
					{
						$arTemplateParameters[$k] = Array(
							"PARENT" => "MANDATORY_PARAMS",
							"NAME" =>  GetMessage($k),
							"TYPE" => "LIST",
							"VALUES" => $arProp,
						);
					}
					elseif ( $k == "GENDER" && $currentClothesCategory == "NONE" )
					{
						$arTemplateParameters[$k] = Array(
							"PARENT" => "MANDATORY_PARAMS",
							"NAME" =>  GetMessage($k),
							"TYPE" => "LIST",
							"VALUES" => $arProp,
						);
					}
					else
					{
						$arTemplateParameters[$k] = Array(
							"PARENT" => "MANDATORY_PARAMS",
							"NAME" =>  GetMessage($k),
							"TYPE" => "STRING",
							"DEFAULT" => "",
						);
					}
				}
				
				$arTemplateParameters["PARAMS"] = Array(
					"PARENT" => "OPTIONAL_PARAMS",
					"NAME" => GetMessage("PARAMS"),
					"TYPE" => "LIST",
					"VALUES" => $arProp,
					"MULTIPLE" => "Y",
					"REFRESH" => "Y",
				);
				
				if ( !empty($arCurrentValues["PARAMS"]) )
				{
					$i = 1;
					foreach ( $arCurrentValues["PARAMS"] as $k=>$v)
					{
						$name = $v . "_UNIT";
						$ar = array(
								$name => Array(
								"PARENT" => "OPTIONAL_PARAMS",
								"NAME" => GetMessage("PARAMS_DOP"),
								"TYPE" => "LIST",
								"ADDITIONAL_VALUES" => "Y",
								"VALUES" => $arSizeTypes ,
							),
						);
						$arTemplateParameters = array_merge($arTemplateParameters, $ar);
						$i++;
					}
				}
				
				break;
			}
			
			$arTemplateParameters['MULTI_STRING_PROP'] = Array(
				"PARENT" => "OPTIONAL_PARAMS",
				"NAME" => GetMessage('MULTI_STRING_PROP'),
				"TYPE" => "LIST",
				"VALUES" => $arProp,
				"MULTIPLE" => "Y",
				"REFRESH" => "N",
				"SIZE" => "4",
			);
		}
		else
		{
			foreach($arTemplateParams[$CurrentCategory] as $parent => $params)
			{
				foreach($params as $k) {
					if ( strpos($k, "_UNIT") ) 
					{
						$arTemplateParameters[$k] = Array(
							"PARENT" => $parent,
							"NAME" =>  GetMessage($k),
							"TYPE" => "STRING",
							"DEFAULT" => "",
						);	
					}
					else
					{
						$arTemplateParameters[$k] = Array(
							"PARENT" => $parent,
							"NAME" =>  GetMessage($k),
							"TYPE" => "LIST",
							"VALUES" => $arProp,
						);
					}
				}
			}
			
			
			$arTemplateParameters['SECTION_AS_VENDOR'] = Array(
				"PARENT" => "OPTIONAL_PARAMS",
				"NAME" => GetMessage('SECTION_AS_VENDOR'),
				"TYPE" => "CHECKBOX",				
				"DEFAULT" => "N"
			);
			
			$arTemplateParameters['MULTI_STRING_PROP'] = Array(
				"PARENT" => "OPTIONAL_PARAMS",
				"NAME" => GetMessage('MULTI_STRING_PROP'),
				"TYPE" => "LIST",
				"VALUES" => $arProp,
				"MULTIPLE" => "Y",
				"REFRESH" => "N",
				"SIZE" => "4",
			);
		}
		
		break;
		
		default:
		
		$arTemplateParameters = array(
				"CATEGORY" => Array(
				"PARENT" => "YENISITE_YM_VENDOR",
				"NAME" =>  GetMessage("CATEGORY"),
				"TYPE" => "LIST",
				"VALUES" => $arValues,					
				"REFRESH" => "Y",
			),
		);
		
		break;
}