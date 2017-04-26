<?if(CModule::IncludeModule('yenisite.torg')):?>
<?$APPLICATION->IncludeComponent("yenisite:torg", ".default", array(
		"ClientId" => 1,
		"ModelId" => $arParams["ELEMENT_ID"],
	),
	false
);?>
<?endif;?>