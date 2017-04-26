<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$TabList = array(
		'NEW' => GetMessage("TAB_NEW"),
		'HIT' => GetMessage("TAB_HIT"),
		'SALE' => GetMessage("TAB_SALE"),
		'BESTSELLER' => GetMessage("TAB_BESTSELLER"),
	);
	
$arComponentParameters = array(
	"PARAMETERS" => array(
		"DEFAULT_TAB" =>array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("DEFAULT_TAB"),
			"TYPE" => "LIST",
			"MULTIPLE" => "N",
			"DEFAULT" => 'NEW',
			"VALUES" => $TabList,
		),
		"CACHE_TIME"  =>  Array("DEFAULT"=>360000),
	),

);
?>