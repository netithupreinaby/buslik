<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
global $arComponentParameters;

$arParameters = array_merge(
		array(
			"ICQ_NAME" => array(
				"PARENT" => "BASE",
				"NAME" => GetMessage("ICQ_NAME"),
				"TYPE" => "STRING",
				"DEFAULT" => "NickName",
			)
		),
		$arComponentParameters["PARAMETERS"]
);

$arComponentParameters["PARAMETERS"] = $arParameters;
?>