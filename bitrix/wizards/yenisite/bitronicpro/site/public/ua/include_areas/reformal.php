<? if (CModule::IncludeModule('yenisite.reformal')): ?>
<? $APPLICATION->IncludeComponent("yenisite:reformal", ".default", array(
	"PROJECT_ID" => "227062",
	"PROJECT_HOST" => "bitrix.reformal.ru",
	"TYPE_OF_INTEGRATION" => "widget",
	"TYPE" => "standart",
	"HEADER_TEXT" => "Отзывы и предложения",
	"TAB_ORIENTATION" => "left",
	"TAB_INDENT" => "50",
	"UNITS" => "px",
	"TAB_BG_COLOR" => "#00AEEF",
	"TAB_BORDER_COLOR" => "#FFFFFF",
	"TAB_BORDER_WIDTH" => "2",
	"ADD_LOGO" => "Y",
	"FROM_NEW_WINDOW" => "N"
	),
	false
); ?>
<?endif?>