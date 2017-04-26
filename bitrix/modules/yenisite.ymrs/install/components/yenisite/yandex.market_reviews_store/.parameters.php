<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentParameters = array(
	"GROUPS" => array(
		"GROUPS_HEAD" => array(
			"NAME" => GetMessage("GROUPS_HEAD"),
			"SORT" => 200,
		),
	),
//------------------------------------------------------------------
//------------------------------------------------------------------
	"PARAMETERS" => array(
//MAIN------------------------------------------------------------------	
		"SHOPID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("SHOPID"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),	
		"ACCESSTOKEN" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("ACCESSTOKEN"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
//HEAD------------------------------------------------------------------
		"HEAD" => array(
			"PARENT" => "GROUPS_HEAD",
			"NAME" => GetMessage("HEAD"),
			"TYPE" => "STRING",
			"DEFAULT" => GetMessage("HEAD_DEFAULT"),
		),
		"HEAD_SIZE" =>array(
			"PARENT" => "GROUPS_HEAD",
			"NAME" => GetMessage("HEAD_SIZE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"VALUES" => array(
				'h1' => "1",
				'h2' => "2",
				'h3' => "3",
				'h4' => "4",
				'h5' => "5",
				'h6' => "6",
			),
			"DEFAULT" => 'h2',
		),
//VISUAL------------------------------------------------------------------
		"SORT" => array(
			"PARENT" => "VISUAL",
			"NAME" => GetMessage("SORT_TYPE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"VALUES" => array(
				'grade' => GetMessage("SORT_TYPE_GRADE"),
				'date' => GetMessage("SORT_TYPE_DATE"),
				'rank' => GetMessage("SORT_TYPE_RANK"),
			),
			"DEFAULT" => 'date',
		),
		"HOW" => array(
			"PARENT" => "VISUAL",
			"NAME" => GetMessage("SORT_HOW"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"VALUES" => array(
				'desc' => GetMessage("SORT_HOW_DESC"),
				'asc' => GetMessage("SORT_HOW_ASC"),
			),
			"DEFAULT" => 'desc',
		),		
		"GRADE" =>array(
			"PARENT" => "VISUAL",
			"NAME" => GetMessage("GRADE"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"VALUES" => array(
				'0' => GetMessage("GRADE_0"),
				'1' => GetMessage("GRADE_1"),
				'2' => GetMessage("GRADE_2"),
				'3' => GetMessage("GRADE_3"),
				'4' => GetMessage("GRADE_4"),
				'5' => GetMessage("GRADE_5"),
			),
			"DEFAULT" => '0',
		),
		"COUNT" =>array(
			"PARENT" => "VISUAL",
			"NAME" => GetMessage("COUNT"),
			"TYPE" => "STRING",
			"DEFAULT" => "10",
		),	
//ADDITIONAL------------------------------------------------------------------
		"INCLUDE_JQUERY" => Array(
			"NAME" => GetMessage("INCLUDE_JQUERY"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => 'Y'
		),	
//CACHE------------------------------------------------------------------		
		"CACHE_TIME"  =>  Array("DEFAULT"=>"".(60*60*24)),
	),
);
?>