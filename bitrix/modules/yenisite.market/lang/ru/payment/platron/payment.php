<?
global $MESS;

$MESS["SPCP_DTITLE"] = "Platron";
$MESS["SPCP_DDESCR"] = "универсальная система приема платежей на сайте. Система электронных платежей для интернет магазинов
	<a href=\"http://www.platron.ru/\" target=\"_blank\">Platron</a>";

$MESS["SHOP_MERCHANT_ID"] = "Идентификатор магазина в Platron ( Merchant ID )";
$MESS["SHOP_MERCHANT_ID_DESCR"] = "используется для идентификации магазига при совершении платежей.";
$MESS["SHOP_SECRET_KEY"] = "Кодовое слово ( Secret Key) ";
$MESS["SHOP_SECRET_KEY_DESCR"] = "используется для подтверждения идентификации магазина при совершении платежей.";
$MESS["SHOP_TESTING_MODE"] = "Тестовый режим ";
$MESS["SHOP_SECRET_KEY_DESCR"] = "В случае если вы находитесь в боевом режиме, но вам нужно провести тестовый транзакции, ставите Y и все транзакции будут создаваться по тестовым платежным системам.";

$MESS["ORDER_ID"] = "Номер заказа";
$MESS["ORDER_LIVETIME"] = "Время жизни счета";
$MESS["ORDER_LIVETIME_DESCR"] = "Измеряется в секундах, если вы указали пустую строку, время жизни по умолчанию 1 сутки";

$MESS["SHOULD_PAY"] = "Сумма заказа";
$MESS["SHOULD_PAY_DESCR"] = "Сумма к оплате";

$MESS["SITE_URL"] = "Site URL";
$MESS["SITE_URL_DESCR"] = "";

$MESS["CHECK_URL"] = "Check URL";
$MESS["CHECK_URL_DESCR"] = "Адресс скрипта, отвечающего на запросы check";
$MESS["RESULT_URL"] = "Result URL";
$MESS["RESULT_URL_DESCR"] = "Адресс скрипта, отвечающего на запросы result";

$MESS["REQUEST_METHOD"] = "Метод запроса на Check URL и Result URL";
$MESS["REQUEST_METHOD_DESCR"] = "Вазможные варианты: (POST и GET)";

$MESS["SUCCESS_URL"] = "Success URL";
$MESS["SUCCESS_URL_DESCR"] = "Адрес на который возрвращать клиента при удачном проведение транзакции";
$MESS["SUCCESS_URL_METHOD"] = "Метод запроса на Success URL";
$MESS["SUCCESS_URL_METHOD_DESCR"] = "Вазможные варианты: (POST, GET, AUTOPOST и AUTOGET)";
$MESS["FAILURE_URL"] = "Failure URL";
$MESS["FAILURE_URL_DESCR"] = "Адрес на который возрвращать клиента при не удачном проведение транзакции";
$MESS["FAILURE_URL_METHOD"] = "Метод запроса на Failure URL";
$MESS["FAILURE_URL_METHOD_DESCR"] = "Вазможные варианты: (POST, GET, AUTOPOST и AUTOGET)";

$MESS["ORDER_DATE"] = "Дата создания заказа";
$MESS["ORDER_DATE_DESCR"] = "";

$MESS["INVALID_PHONE"] = "Неправильно введён телефон";
$MESS["INVALID_EMAIL"] = "Неправильно введен E-Mail";
$MESS["USE_NEW"] = "Использовать новый";
$MESS["PHONE"] = "телефон";
$MESS["AND"] = "и";
$MESS["EMAIL"] = "E-mail";
?>
