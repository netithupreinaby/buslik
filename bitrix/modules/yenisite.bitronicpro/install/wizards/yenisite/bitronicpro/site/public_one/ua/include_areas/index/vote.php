<?if(CModule::IncludeModule('vote')):?>
<?$APPLICATION->IncludeComponent("bitrix:voting.current", "bitronic", array(
	"CHANNEL_SID" => "BITRONIC_DEMO",
	"VOTE_ID" => "",
	"VOTE_ALL_RESULTS" => "N",
	"AJAX_MODE" => "Y",
	"AJAX_OPTION_JUMP" => "N",
	"AJAX_OPTION_STYLE" => "Y",
	"AJAX_OPTION_HISTORY" => "N",
	"CACHE_TYPE" => "A",
	"CACHE_TIME" => "3600",
	"AJAX_OPTION_ADDITIONAL" => ""
),
false
);?>
<?endif;?>