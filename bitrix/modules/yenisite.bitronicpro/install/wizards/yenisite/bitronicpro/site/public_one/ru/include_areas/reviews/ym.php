<?if(CModule::IncludeModule('yenisite.review')):?>
<?$APPLICATION->IncludeComponent("yenisite:yandex.market_review", ".default", array(
	"ELEMENT_ID" => $arParams["ELEMENT_ID"],
	),
	false
);?>
<?endif;?>