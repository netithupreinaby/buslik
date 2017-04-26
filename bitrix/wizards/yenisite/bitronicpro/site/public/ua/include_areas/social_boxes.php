<? // if (CModule::IncludeModule('primepix.vkontakte')): ?>
	<?  $APPLICATION->IncludeComponent("primepix:vkontakte.group", ".default", array(
	"ID_GROUP" => "74168407",
	"TYPE_FORM" => "0",
	"WIDTH_FORM" => "210",
	"HEIGHT_FORM" => "300"
	),
	false
	); ?>
<? // endif?>
<? if (CModule::IncludeModule('yenisite.okgroup')): ?>
	<br /><?  $APPLICATION->IncludeComponent("yenisite:odnoklassniki.group_widget", ".default", array(
	"GROUP_ID" => "50582132228315",
	"WIDTH_SCHEME" => "0",
	"WIDTH" => "210",
	"HEIGHT_SCHEME" => "3"
	),
	false
);    ?>
<?endif?>
<? if (CModule::IncludeModule('yenisite.fblikebox')): ?>
	<br /><?  $APPLICATION->IncludeComponent("yenisite:facebook.like_box", ".default", array(
	"PAGE_URL" => "https://www.facebook.com/1CBitrix",
	"WIDTH" => "210",
	"HEIGHT" => "250",
	"FACES" => "Y",
	"COLOR_SCHEME" => "light",
	"STREAM" => "N",
	"BORDER" => "Y",
	"HEADER" => "N"
	),
	false
);    ?>
<?endif?>
<? if (CModule::IncludeModule('yenisite.twittertimelines')): ?>
	<br /><br /><?  $APPLICATION->IncludeComponent("yenisite:twitter.timelines", ".default", array(
	"WIDGET_ID" => "367161119997050880",
	"USERNAME" => "",
	"RELATED" => "twitterapi,twitter",
	"LANG" => "RU",
	"WIDTH" => "210",
	"HEIGHT" => "300",
	"COLOR_SCHEME" => "light",
	"LINK_COLOR" => "cc0000",
	"BORDER_COLOR" => "#00AEEF",
	"CHROME" => array(
	),
	"TWEET_LIMIT" => "2"
	),
	false
);   ?>		
<?endif?>
<br /><br />		