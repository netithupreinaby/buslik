<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if (!CModule::IncludeModule("learning"))
	return;

$arCourses = Array();
$courses = CCourse::GetList(Array("SORT"=>"ASC"));
while ($arRes = $courses->Fetch())
	$arCourses[$arRes["ID"]] = $arRes["NAME"]; 

$arYesNo = Array(
	"Y" => GetMessage("LEARNING_DESC_YES"),
	"N" => GetMessage("LEARNING_DESC_NO"),
);

$arComponentParameters = array(
	"PARAMETERS" => array(

		"COURSE_ID" => array(
			"NAME" => GetMessage("LEARNING_COURSE_ID"),
			"TYPE" => "LIST",
			"VALUES" => $arCourses,
			"PARENT" => "BASE",
			"ADDITIONAL_VALUES"	=> "Y",
			"DEFAULT"=>'={$_REQUEST["COURSE_ID"]}'
		),

		"CHECK_PERMISSIONS" => Array(
			"NAME"=>GetMessage("LEARNING_CHECK_PERMISSIONS"), 
			"TYPE"=>"LIST", 
			"MULTIPLE"=>"N", 
			"DEFAULT"=>"Y", 
			"PARENT" => "ADDITIONAL_SETTINGS",
			"VALUES"=>$arYesNo, 
			"ADDITIONAL_VALUES"=>"N"
		),

		"SET_TITLE" => Array(),
		"CACHE_TIME" => Array("DEFAULT"=>"3600"),
	)
);
?>