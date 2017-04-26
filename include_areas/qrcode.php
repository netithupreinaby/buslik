<? if (CModule::IncludeModule('altasib.qrcode')): ?>
<? $APPLICATION->IncludeComponent("altasib:qrcode", ".default", array(
							"QR_TYPE_INF" => "URL",
							"QR_URL" => "http://yenisite.ru/",
							"QR_SIZE_VAL" => "7",
							"QR_ERROR_CORECT" => "L",
							"QR_SQUARE" => "2",
							"QR_COLOR" => "000000",
							"QR_COLORBG" => "FFFFFF",
							"QR_MINI" => "40",
							"QR_COPY" => "Y",
							"QR_TEXT" => "",
							"QR_DEL_CHACHE" => "Y",
							"CACHE_TYPE" => "A",
							"CACHE_TIME" => "2592000"
							),
							false
						); ?>
<?endif?>