<?
if(isset($_REQUEST['uri']) && !empty($_REQUEST['uri']))
{
	$_SERVER['REQUEST_URI'] = $_REQUEST['uri'];
}
include $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
header('Content-Type: application/json');

CModule::IncludeModule("sale");
CModule::IncludeModule("catalog");
CModule::IncludeModule("statistic");
CModule::IncludeModule("yenisite.geoipstore");

if (!empty($_REQUEST['action'])) {
	switch($_REQUEST['action'])
	{
		case 'getlist':
			$dbRes = CYSGeoIPStore::GetList();
			while ($arRes = $dbRes->GetNext()) {
				$dbRs = CYSGeoIPStore::GetList('store', array(), array('ITEM_ID' => $arRes['ID']));
				while ($ar = $dbRs->GetNext()) {
					$arSt = CCatalogStore::GetList(array(), array('ID' => $ar['STORE_ID']))->Fetch();
					$tmpAr['STORE_ID'] = $ar['STORE_ID'];

					if (!defined('BX_UTF')) {
						$tmpAr['TITLE'] = $GLOBALS['APPLICATION']->ConvertCharset($arSt['TITLE'], 'WINDOWS-1251', 'UTF-8');
					}
					$arRes['STORES'][] = $tmpAr;
				}

				if (!defined('BX_UTF')) {
					$arRes['CITY'] = $GLOBALS['APPLICATION']->ConvertCharset($arRes['CITY'], 'WINDOWS-1251', 'UTF-8');
					$arRes['COUNTRY'] = $GLOBALS['APPLICATION']->ConvertCharset($arRes['COUNTRY'], 'WINDOWS-1251', 'UTF-8');
					$arRes['NAME'] = $GLOBALS['APPLICATION']->ConvertCharset($arRes['NAME'], 'WINDOWS-1251', 'UTF-8');
					$arRes['REGION'] = $GLOBALS['APPLICATION']->ConvertCharset($arRes['REGION'], 'WINDOWS-1251', 'UTF-8');
				}

				$arr[] = $arRes;
			}
			die(json_encode($arr));
			break;

		case 'setActive':
			$id = htmlspecialchars($_REQUEST['id']);
			
			$isRedir = COption::GetOptionString("yenisite.geoipstore", 'is_redirect');
			if ($isRedir == "Y") {
				$activeItem = CYSGeoIPStore::GetByID($id);

				//CYSGeoIPStore::SetActiveItem($id, $activeItem["DOMAIN_NAME"].'.demo-pro.romza.ru');
				CYSGeoIPStore::SetActiveItem($id);
				die(json_encode(array('IS_REDIR' => 'Y', 'DOMAIN' => $activeItem["DOMAIN_NAME"])));
			} else {
				CYSGeoIPStore::SetActiveItem($id);
			}

			break;

		case 'isRedirect':
			$id = htmlspecialchars($_REQUEST['id']);
			$isRedir = COption::GetOptionString("yenisite.geoipstore", 'is_redirect');
			
			$activeItem = CYSGeoIPStore::GetByID($id);
			
			$rsSites = CSite::GetByID(SITE_ID);
			$arSite = $rsSites->Fetch();

			$arDomains = explode(chr(10),$arSite['DOMAINS']);
			foreach($arDomains as &$domain)
			{
				$domain = str_replace(chr(13),'',$domain);
			}
			$arServerName = (is_array($arDomains) && count($arDomains) > 1) ? $arDomains : array($arSite['SERVER_NAME']);
			if($activeItem['DEFAULT'] == 'Y') {
				$activeItem["DOMAIN_NAME"] = '';
			}
			CYSGeoIPStore::SetActiveItem($id);
			die(json_encode(array('IS_REDIRECT' => $isRedir, 'DOMAIN' => $activeItem["DOMAIN_NAME"], 'SERVER_NAME' => $arServerName)));
			break;

		case 'update':
			$arItem = CYSGeoIPStore::GetByCurrentLocation();

			if (!empty($arItem)) {
				CYSGeoIPStore::SetActiveItem($arItem['ID']);
				$arLoc = CSaleLocation::GetByID($arItem['LOCATION_ID_DELIVERY']);

				if (!defined('BX_UTF')) {
					$arLoc['CITY_NAME_LANG'] = $APPLICATION->ConvertCharset($arLoc['CITY_NAME_LANG'], 'WINDOWS-1251', 'UTF-8');
					
					$arItem = $APPLICATION->ConvertCharsetArray($arItem, 'WINDOWS-1251', 'UTF-8');
				}

				$arItem['CITY_NAME'] = $arLoc['CITY_NAME_LANG'];
				die(json_encode($arItem));
			} else {
				$arLoc = CSaleLocation::GetList(array(), array('CITY_NAME_LANG' => $arLocs[2]))->Fetch();
				if (!empty($arLoc)) {
					$arItem = CYSGeoIPStore::GetList('item', array(), array('LOCATION_ID_DELIVERY' => $arLoc['ID']), array('ID'))->Fetch();
					if (!empty($arItem)) {
						CYSGeoIPStore::SetActiveItem($arItem['ID']);

						if (!defined('BX_UTF')) {
							$arLocs[2] = $APPLICATION->ConvertCharset($arLocs[2], 'WINDOWS-1251', 'UTF-8');
						}

						die(json_encode(array('ID' => $arItem['ID'], 'CITY_NAME' => $arLocs[2])));
					}
				}
				die(json_encode(array('UPDATE' => 'N')));
			}
			break;
		default:
			break;
	}
}

/*if (!empty($_REQUEST['itemId'])) {
	$itemId = htmlspecialchars($_REQUEST['itemId']);
	$dbRes = CYSGeoIPStore::GetList('store', array(), array('ITEM_ID' => $itemId));

	while ($arRes = $dbRes->Fetch()) {
		$res = CCatalogStore::GetList(array(), array('ID' => $arRes['STORE_ID']))->Fetch();
		$tmpAr['STORE_ID'] = $arRes['STORE_ID'];
		$tmpAr['TITLE'] = $res['TITLE'];
		if (!defined('BX_UTF')) {
			$tmpAr['TITLE'] = $GLOBALS['APPLICATION']->ConvertCharset($res['TITLE'], 'WINDOWS-1251', 'UTF-8');
		}

		$arr[] = $tmpAr;
	}
	echo json_encode($arr);
}*/
?>