<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("� ��������");?><b>�. ���</b> 
<div><b>���. �������������</b></div>
<div><b>�. 198</b></div>
<div><b>E-mail: tech@bitronic.ru</b></div>
<div><b>���. 234-23-23</b></div>
<div> 
	<div>
		<br/>
		<?$APPLICATION->IncludeComponent(
	"bitrix:map.google.view",
	"",
	array()
);?>
	</div>
	<div>
		<br />
		<?$APPLICATION->IncludeComponent(
	"bitrix:map.yandex.view",
	"",
	array()
);?> 	 
	</div>
<? if (CModule::IncludeModule('simai.maps2gis')): ?>
	<div>
		<br/>
		<?$APPLICATION->IncludeComponent(
	"simai:maps.2gis.simple",
	"",
	array()
);?>
	</div> 
 <?endif?>
	<br /><br />
<? if (CModule::IncludeModule('yenisite.ymrs')): ?>
	<div>
		<?$APPLICATION->IncludeComponent(
	"yenisite:yandex.market_reviews_store",
	".default",
	array(
	"SHOPID" => "155",	// ID �������� �� �.�
		"ACCESSTOKEN" => "",	// ��������������� ����
		"SORT_TYPE" => "date",
		"SORT_HOW" => "desc",
		"GRADE" => "0",	// ���������� �� ������
		"PAGE" => "1",
		"COUNT" => "10",	// ���������� ������� �� ����� ��������
	)
);?>
	</div>
<?endif?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>