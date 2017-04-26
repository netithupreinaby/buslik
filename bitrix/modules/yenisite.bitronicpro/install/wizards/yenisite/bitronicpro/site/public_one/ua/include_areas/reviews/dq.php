<?if(CModule::IncludeModule('yenisite.disqus')):?>
<?$APPLICATION->IncludeComponent("yenisite:disqus", ".default", array(
	"DISQUS_SHORTNAME" => "example"
	),
	false
);?>
<?endif;?>