<?
use Yenisite\Geoipstore\Currency2Country;

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

IncludeModuleLangFile(__FILE__);

CModule::IncludeModule('currency');
CModule::IncludeModule('statistic');
CModule::IncludeModule('yenisite.geoipstore');

global $USER;
if(!$USER->IsAdmin())
	return;

$arCountryList = array(
	'REFERENCE' => array(),
	'REFERENCE_ID' => array()
);
$rs = CCountry::GetList($by, $order, array(), $isFiltered);
while ($arCountry = $rs->Fetch()) {
	$arCountryList['REFERENCE'][] = $arCountry['REFERENCE'];
	$arCountryList['REFERENCE_ID'][] = $arCountry['REFERENCE_ID'];
}

$arCurrencyList = array();
$rs = CCurrency::GetList($by, $order);
while ($arCurrency = $rs->Fetch()) {
	$arCurrency['REFERENCES'] = array();
	$arCurrencyList[$arCurrency['CURRENCY']] = $arCurrency;
}

$arLinks = array();
$rs = Currency2Country::GetList();
while ($arLink = $rs->Fetch()) {
	$arLinks[$arLink['CURRENCY_ID']][$arLink['COUNTRY_ID']] = $arLink['COUNTRY_ID'];
}

$action = (!empty($_REQUEST["action"])) ? htmlspecialcharsEx($_REQUEST["action"]) : htmlspecialcharsEx($_REQUEST["action_button"]);
$apply = htmlspecialcharsEx($_REQUEST["apply"]);
$save = htmlspecialcharsEx($_REQUEST["save"]);

if ($apply || $save) {
	$bChanged = false;
	foreach ($arLinks as $currencyId => &$arReferences) {
		$arNewLink = $_REQUEST['arCurrency'.$currencyId];
		if (empty($arNewLink)) {
			$arAdd = array();
			$arDelete = $arReferences;
		} else {
			$arAdd = array_diff($arNewLink, $arReferences);
			$arDelete = array_diff($arReferences, $arNewLink);
		}
		foreach ($arAdd as $countryId) {
			$bChanged = true;
			Currency2Country::add(array('COUNTRY_ID' => $countryId, 'CURRENCY_ID' => $currencyId));
		}
		foreach ($arDelete as $countryId) {
			$bChanged = true;
			Currency2Country::delete(array('COUNTRY_ID' => $countryId, 'CURRENCY_ID' => $currencyId));
		}
		$arReferences = $arNewLink;
	}
	if (isset($arReferences)) {
		unset($arReferences);
	}
	if ($bChanged) {
		unset($arAdd, $arDelete);
		$obCache = new CPHPCache;
		$obCache->CleanDir(Currency2Country::CACHE_DIR);
		unset($obCache);
	}
}

$aTabs = array(
	array("DIV" => "edit_common", "TAB" => GetMessage('PARAMS'), "ICON" => "catalog", "TITLE" => GetMessage('PARAMS_TITLE')),
);

$tabControl = new CAdminTabControl("tabControl", $aTabs);
$tabControl->Begin();

$tabControl->BeginNextTab();
?>
<style>
	.column-value {
		text-align: center;
	}
</style>
<form method="POST">
<?
foreach ($arCurrencyList as $arCurrency):
?>
	<tr class="heading">
		<td class="column-name">&quot;<?=$arCurrency['FULL_NAME']?>&quot; - <?=GetMessage('CURRENCY_TITLE')?>:</td>
	</tr>
	<tr>
		<td class="column-value"><?=SelectBoxMFromArray("arCurrency{$arCurrency['CURRENCY']}[]", $arCountryList, $arLinks[$arCurrency['CURRENCY']], "", false, 20, '')?></td>
	</tr>
<?
endforeach;
?>
<?
$tabControl->EndTab();
?>

<?
$tabControl->Buttons(
	array(
		"back_url" => "/bitrix/admin/ys-geoip-store-currencies.php?lang=".LANGUAGE_ID
	)
);
?>
</form>
<?
$tabControl->End();
?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>