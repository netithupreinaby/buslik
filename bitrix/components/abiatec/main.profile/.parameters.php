<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arFormFields = array(
	"EMAIL"=>1,
	"TITLE"=>1,
	"NAME"=>1,
	"SECOND_NAME"=>1,
	"LAST_NAME"=>1,
	"AUTO_TIME_ZONE"=>1,
	"PERSONAL_PROFESSION"=>1,
	"PERSONAL_WWW"=>1,
	"PERSONAL_ICQ"=>1,
	"PERSONAL_GENDER"=>1,
	"PERSONAL_BIRTHDAY"=>1,
	"PERSONAL_PHOTO"=>1,
	"PERSONAL_PHONE"=>1,
	"PERSONAL_FAX"=>1,
	"PERSONAL_MOBILE"=>1,
	"PERSONAL_PAGER"=>1,
	"PERSONAL_STREET"=>1,
	"PERSONAL_MAILBOX"=>1,
	"PERSONAL_CITY"=>1,
	"PERSONAL_STATE"=>1,
	"PERSONAL_ZIP"=>1,
	"PERSONAL_COUNTRY"=>1,
	"PERSONAL_NOTES"=>1,
	"WORK_COMPANY"=>1,
	"WORK_DEPARTMENT"=>1,
	"WORK_POSITION"=>1,
	"WORK_WWW"=>1,
	"WORK_PHONE"=>1,
	"WORK_FAX"=>1,
	"WORK_PAGER"=>1,
	"WORK_STREET"=>1,
	"WORK_MAILBOX"=>1,
	"WORK_CITY"=>1,
	"WORK_STATE"=>1,
	"WORK_ZIP"=>1,
	"WORK_COUNTRY"=>1,
	"WORK_PROFILE"=>1,
	"WORK_LOGO"=>1,
	"WORK_NOTES"=>1,
	"PASSWORD"=>1,
	"NEW_PASSWORD"=>1,
);

if(!CTimeZone::Enabled())
	unset($arFormFields["AUTO_TIME_ZONE"]);

$arUserFields = array();
foreach ($arFormFields as $value=>$dummy)
{
	$arUserFields[$value] = "[".$value."] ".GetMessage("REGISTER_FIELD_".$value);
}


$arRes = $GLOBALS["USER_FIELD_MANAGER"]->GetUserFields("USER", 0, LANGUAGE_ID);
$userProp = array();
if (!empty($arRes))
{
	foreach ($arRes as $key => $val)
		$userProp[$val["FIELD_NAME"]] = "[".$key."] ". (strLen($val["EDIT_FORM_LABEL"]) > 0 ? $val["EDIT_FORM_LABEL"] : $val["FIELD_NAME"]);
}

$mandatoryFields = array_merge($arUserFields, $userProp);

$templateType = array(
	"LOGIN" => "Логин",
	"DATA" => "Данные",
	"CONTACTS" => "Контакты",
	"CHILDREN" => "Дети",
	"SUBSCRIBE" => "Подписка",
);

$arComponentParameters = array(
	"PARAMETERS" => array(
		"SET_TITLE" => array(),
		"AJAX_MODE" => array(),
		"TEMPLATE_TYPE"=>array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("TEMPLATE_TYPE"),
			"TYPE" => "LIST",
			"VALUES" => $templateType,
			"DEFAULT" => array(),
		),
		"MANDATORY_FIELDS"=>array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("MANDATORY_FIELDS"),
			"TYPE" => "LIST",
			"VALUES" => $mandatoryFields,
			"MULTIPLE" => "Y",
			"DEFAULT" => array(),
		),
		"USER_PROPERTY"=>array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("USER_PROPERTY"),
			"TYPE" => "LIST",
			"VALUES" => $userProp,
			"MULTIPLE" => "Y",
			"DEFAULT" => array(),
		),
		"SEND_INFO"=>array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("SEND_INFO"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"CHECK_RIGHTS"=>array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("CHECK_RIGHTS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
	),
);
?>