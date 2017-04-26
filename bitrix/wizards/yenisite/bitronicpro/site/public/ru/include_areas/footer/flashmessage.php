<?if (CModule::IncludeModule('beono.flashmessage')):
	$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/components/beono/flashmessage/bitronic/style.css');
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/components/beono/flashmessage/bitronic/script.js');
?>
<?if(class_exists('Bitrix\Main\Page\Frame'))Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID('ys-beono-flashmessage');?>
<div id="ys-beono-flashmessage">
	<?$APPLICATION->IncludeComponent("beono:flashmessage", "bitronic", array(), "", array("HIDE_ICONS"=>"Y"));?>
</div>
<?if(class_exists('Bitrix\Main\Page\Frame'))Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID('ys-beono-flashmessage');?>
<?endif?>