<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("О магазине");?>
<b>г. Москва</b> 
<div><b>ул. Джамбульская</b></div>
<div><b>д. 198</b></div>
<div><b>E-mail: tech@bitronic.ru</b></div>
<div><b>тел. 234-23-23</b></div>
<div> 
	<div>
		<br/>
		<?$APPLICATION->IncludeComponent(
		"bitrix:map.google.view",
		"",
		Array(
		)
		);?>
	</div>
	<div>
		<br />
		<?$APPLICATION->IncludeComponent(
			"bitrix:map.yandex.view",
			"",
			Array(),
			false
		);?> 	 
	</div>
<? if (CModule::IncludeModule('simai.maps2gis')): ?>
	<div>
		<br/>
		<?$APPLICATION->IncludeComponent(
			"simai:maps.2gis.simple",
			"",
			Array(
			)
		);?>
	</div> 
 <?endif?>
	<br /><br />
<? if (CModule::IncludeModule('yenisite.ymrs')): ?>
	<div>
		<?$APPLICATION->IncludeComponent(
			"yenisite:yandex.market_reviews_store",
			".default",
			Array(
			"SHOPID" => "155",
			"ACCESSTOKEN" => "",
			"SORT_TYPE" => "date",
			"SORT_HOW" => "desc",
			"GRADE" => "0",
			"PAGE" => "1",
			"COUNT" => "10"
			)
		);?>
	</div>
<?endif?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
