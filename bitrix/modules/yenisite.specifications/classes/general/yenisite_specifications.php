<?
/* -------------------------  */
//Developer: Denis Zimin
//SKYPE:	dioonisss
//E-MAIL:	denis@yenisite.ru
//MODULE: 	SPECIFICATIONS_FROM_YM
/* -------------------------  */
IncludeModuleLangFile(__FILE__);

Class CSpecifications extends Yenisite\CoreParser\YandexParser
{
	const moduldeID = 'yenisite.specifications';
	const PARSE_IMAGES      = 1; //0b00000001
	const PARSE_PROPERTIES  = 2; //0b00000010
	const PARSE_DESCRIPTION = 4; //0b00000100
	const DETAIL_TEXT       = 1; //0b00000001
	const PREVIEW_TEXT      = 2; //0b00000010
	const CACHE_TIME        = 3600;
	const CACHE_PATH        = '/yenisite.specifications/';

	/**
	 * Handler for module "main" event "OnAdminListDisplay"
	 */
	public static function OnAdminListDisplay(&$list)
	{
		switch ($GLOBALS['APPLICATION']->GetCurPage()) {
			case '/bitrix/admin/iblock_element_admin.php':
			case '/bitrix/admin/iblock_list_admin.php':
			case '/bitrix/admin/cat_product_list.php': break;
			default: return;
		}
		if (!CModule::IncludeModule("iblock"))
			return;

		IncludeModuleLangFile(__FILE__);
		// add button to header of elementList Table
		if ($_REQUEST['rz_demo_parser'] == 'Y') {
			$list->context->items[] = array('TEXT'=> GetMessage('ys_spec_pp_demo'), 'ICON' => 'ys_spec_btn_demo', 'LINK'=> 'javascript:void(0)' );
		}
		$list->context->items[] = array('TEXT'=> GetMessage('ys_spec_pp_open'), 'ICON' => 'ys_spec_btn_load', 'LINK'=> 'javascript:void(0)' );

		// add column to elementList Table
		$add_column = array(
			array('id'=> 'YS_SPEC_INDICATOR', 'content' => GetMessage('ys_spec_indicator_title'), 'default' => true),
		);
		$list->AddHeaders($add_column);
		$list->AddVisibleHeaderColumn('YS_SPEC_INDICATOR');

		foreach($list->aRows as $row)
		{
			if(!array_key_exists('TYPE', $row->arRes) || $row->arRes['TYPE'] == 'E')
			{
				// add element value for new column
				$arProp = CIBlockElement::GetProperty($row->arRes['IBLOCK_ID'],$row->arRes['ID'], array(), array("CODE" => "TURBO_YANDEX_STATUS"))->Fetch();
				if(empty($arProp['VALUE']))
					$arProp['VALUE'] = 'grey';

				$row->AddViewField('YS_SPEC_INDICATOR', "<img src='/bitrix/images/" . self::moduldeID . "/".$arProp['VALUE'].".gif' alt='" .GetMessage('ys_spec_indicator_'.$arProp['VALUE']). "' title='" .GetMessage('ys_spec_indicator_'.$arProp['VALUE']). "' class='ys_".$arProp['VALUE']."'>");
			}
		}

		$arOptions = array(
			"interval_min" => COption::GetOptionString(self::moduldeID, 'interval_min', '12'),
			"interval_max" => COption::GetOptionString(self::moduldeID, 'interval_max', '18')
		);
		CJSCore::Init('jquery');
		$GLOBALS['APPLICATION']->AddHeadString('<script type="text/javascript">function ys_spec_set_params_from_PHP(){
				ys_spec.tableObj='.$list->table_id.';
				ys_spec.PARAMS = '. CUtil::PhpToJSObject($arOptions) . ';
			}
		</script>');
	}

	static function OnEpilog()
	{
		if (!defined("ADMIN_SECTION") || ADMIN_SECTION !== true || $_GET['bxpublic'] == 'Y') {
			return;
		}

		switch ($GLOBALS['APPLICATION']->GetCurPage()) {
			case '/bitrix/admin/iblock_element_edit.php':
			case '/bitrix/admin/iblock_element_admin.php':
			case '/bitrix/admin/iblock_list_admin.php':
			case '/bitrix/admin/cat_product_edit.php':
			case '/bitrix/admin/cat_product_list.php': break;
			default: return;
		}

		CJSCore::RegisterExt(
			'ys_spec_lang',
			array('lang' => '/bitrix/modules/' . self::moduldeID . '/lang/' . LANGUAGE_ID . '/classes/general/yenisite_specifications.php')
		);
		CJSCore::RegisterExt(
			'ys_spec_script',
			array(
				'js'   => '/bitrix/js/' . self::moduldeID . '/script.js',
				'css'  => '/bitrix/js/' . self::moduldeID . '/styles.css',
				'lang' => '/bitrix/modules/' . self::moduldeID . '/lang/' . LANGUAGE_ID . '/js.php',
				'rel'  => array('jquery','ys_spec_lang', 'ys_core_parser'),
				)
		);

		CJSCore::Init('ys_spec_script');

		switch ($GLOBALS['APPLICATION']->GetCurPage()) {
			case '/bitrix/admin/iblock_element_edit.php':
			case '/bitrix/admin/cat_product_edit.php':
				$arOptions = array(
					"brands" => COption::GetOptionString(self::moduldeID, 'brands', 'N')
				);
				$GLOBALS['APPLICATION']->AddHeadString('<script type="text/javascript">$(function(){ys_spec.init();});
				function ys_spec_set_params_from_PHP(){
					ys_spec.PARAMS = '. CUtil::PhpToJSObject($arOptions) . ';
				}</script>');
				break;
			case '/bitrix/admin/iblock_element_admin.php':
			case '/bitrix/admin/iblock_list_admin.php':
			case '/bitrix/admin/cat_product_list.php':
				$GLOBALS['APPLICATION']->AddHeadString('<script type="text/javascript">$(function(){ys_spec.init_list();});</script>');
				break;
			default: break;
		}

		IncludeModuleLangFile(__FILE__);
	}

	static function getModelListByName($name)
	{
		$arReturn = array();
		$arExeptionWords = COption::GetOptionString(self::moduldeID, 'exception_words', '');
		$arExeptionWords = explode(chr(10),$arExeptionWords);
		foreach($arExeptionWords as $word)
		{
			$word = trim($word);
			$name = str_replace($word, '', $name);
		}
		if(!defined('BX_UTF'))
		{
			$name = $GLOBALS['APPLICATION']->ConvertCharset($name, LANG_CHARSET, 'utf-8');
		}
		$link = self::getSearchLink($name);

		$arReturn = self::getModelListByURL($link);
		$arReturn['name'] = $name;
		return $arReturn;
	}

	static function ProcessAjax($params)
	{
		self::setLogFile($params['id']);
		switch($params['action'])
		{
			case "getlistfromurl":
				if(isset($params['url']))
				{
					self::$fileLog = $_SERVER['DOCUMENT_ROOT'].'/bitrix/js/yenisite.specifications/log/log-demo.txt';
					self::clearLog() ;
					return self::getModelListByURL($params['url']);
				}
				return array('length' => 0, 'error' => 'empty url');
			break;
			case "getlist":
				if(!isset($params['ya_id']) && isset($params['iblock_id'])&& isset($params['id']))
				{
					if(COption::GetOptionString(self::moduldeID, 'not_search_again', '')=='Y')
					{
						$yandexIdProp = COption::GetOptionString(self::moduldeID, 'yml_property', 'TURBO_YANDEX_LINK');
						$res = CIBlockElement::GetProperty($params['iblock_id'], $params['id'], array(), Array("CODE"=>$yandexIdProp));
						if($ar_res = $res->Fetch())
						{
							if(intval($ar_res['VALUE'])>0)
							{
								$arReturn['ya_id'] = $ar_res['VALUE'];
								$arReturn['iblock_id'] = $params['iblock_id'];
								$arReturn['element_id'] = $params['id'];
								return $arReturn;
							}
						}
					}
					$res = CIBlockElement::GetByID($params['id']);
					if($ar_res = $res->GetNext())
					{
						$name = $ar_res['NAME'];
						$nameProp = COption::GetOptionString(self::moduldeID, 'name_property', '');
						if (!empty($nameProp)) {
							$res = CIBlockElement::GetProperty($params['iblock_id'], $params['id'], array(), Array("CODE"=>$nameProp));
							if (($arProp = $res->Fetch()) && !empty($arProp['VALUE'])) {
								$name = $arProp['VALUE'];
							}
						}
						$arReturn = array();
						$arReturn = self::getModelListByName($name);
					}
					$arReturn['SHOW_POPUP'] = 'Y';
					return $arReturn;
				}
			break;
			case "fill":
				if(isset($params['ya_id']) && isset($params['iblock_id']) && isset($params['id']))
				{
					$iblock_id = intval($params['iblock_id']);
					$element_id = intval($params['id']);
					$ya_id = intval($params['ya_id']);
					$arReturn = self::fillElement($ya_id, $iblock_id, $element_id);

					$status = ($arReturn['status'] == 'ok') ? 'green' : 'orange';
					self::setElementStatus($iblock_id, $element_id, $status);

					return $arReturn;
				}
			break;
			case "fillnoid":
				if(isset($_REQUEST['iblock_id']) && isset($_REQUEST['id']))
				{
					$iblock_id = intval($_REQUEST['iblock_id']);
					$element_id = intval($_REQUEST['id']);
					$arReturn = self::fillElementNoId($iblock_id, $element_id, $_REQUEST['image'], $_REQUEST['descr']);

					$status = ($arReturn['status'] == 'ok') ? 'green' : 'orange';
					self::setElementStatus($iblock_id, $element_id, $status);

					return $arReturn;
				}
			break;
			case "getandfill":
				if(($elementId = intval($params['id'])) <= 0)
				{
					return 'error';
				}
				$arError = array();
				$bNotSearchAgain   = ('Y' == COption::GetOptionString(self::moduldeID, 'not_search_again', ''));
				$bNotSearchSection = ('Y' == COption::GetOptionString(self::moduldeID, 'not_search_section', ''));
				if($bNotSearchAgain || $bNotSearchSection)
				{
					$yandexIdProp = COption::GetOptionString(self::moduldeID, 'yml_property', 'TURBO_YANDEX_LINK');
					$res = CIBlockElement::GetProperty($params['iblock_id'], $elementId, array(), Array("CODE"=>$yandexIdProp));
					if($ar_res = $res->Fetch())
					{
						if(intval($ar_res['VALUE'])>0)
						{
							$arReturn = self::fillElement($ar_res['VALUE'], $params['iblock_id'], $elementId);
							$status = ($arReturn['status'] == 'ok') ? 'green' : 'orange';
							self::setElementStatus($params['iblock_id'], $elementId, $status);
							if ($arReturn['status'] == 'error') {
								$arError['error'] = $arReturn['error'];
							}
							return array("STATUS" => $status, "ID" => $elementId) + $arError;
						}
					}
				}
				if ($bNotSearchSection) {
					return array("STATUS" => 'grey', "ID" => $elementId);
				}
				$res = CIBlockElement::GetByID($elementId);
				if($ar_res = $res->GetNext()) {
					$arReturn = array();
					$arReturn = self::getModelListByName($ar_res['NAME']);
				} else {
					return array('STATUS' => 'grey', 'error' => 'element '.$elementId.' not found');
				}
				$bParseNotCards = (COption::GetOptionString(self::moduldeID, 'getandfill_noid', 'N') == 'Y');
				if (array_key_exists('error', $arReturn))                    $status = 'orange';
				elseif ($arReturn['length'] < 1 && $arReturn['length2'] < 1) $status = 'red';
				elseif ($arReturn['length'] < 1 && !$bParseNotCards)         $status = 'red';
				elseif ($arReturn['length'] > 1 || ($bParseNotCards && $arReturn['length2'] > 1)) {
					$status = 'yellow';
					$length = $bParseNotCards ? $arReturn['length2'] : $arReturn['length'];
					if(COption::GetOptionString(self::moduldeID, 'auto_detect', 'N') == 'Y') {
						for ($i = 0; $i<$length; $i++) {
							if (trim($ar_res['NAME']) != trim(strip_tags($arReturn[$i]['NAME']))) continue;

							$arReturn = array($arReturn[$i], 'length' => 1);
							break;
						}
					}
				}
				if ($arReturn['length'] == 1)
				{
					$status = 'green';
					$res = self::fillElement($arReturn[0]['ID'], $ar_res['IBLOCK_ID'], $elementId);
					if ($res['status'] == 'error') {
						$status = 'orange';
					}
				}
				elseif ($arReturn['length2'] == 1 && $bParseNotCards)
				{
					$status = 'green';
					$res = self::fillElementNoId($ar_res['IBLOCK_ID'], $elementId, $arReturn[0]['IMAGES'], $arReturn[0]['DESCRIPTION']);
					if ($res['status'] == 'error') {
						$status = 'orange';
					}
				}

				if ($status == 'orange') {
					if (array_key_exists('error', $arReturn)) {
						$arError['error'] = $arReturn['error'];
					} else {
						$arError['error'] = $res['error'];
					}
				}

				self::setElementStatus($ar_res['IBLOCK_ID'],$elementId,$status);

				return array("STATUS" => $status, "ID" => $elementId) + $arError;
			break;
			case "getiblockelements":

				$arFilter = array("IBLOCK_ID" => IntVal($params['iblock_id']));
				$res = CIBlockElement::GetList(Array(), $arFilter, false, false, array("ID"));
				while($ar_fields = $res->GetNext())
				{
					$arReturn[] = $ar_fields["ID"];
				}

				return $arReturn;
			break;
			case "createelement":
				if (isset($params["iblock_id"]) && isset($params["name"]) && isset($params["price"]))
				{
					$iblock_id = intval($params['iblock_id']);
					$section_id = intval($params['section_id']);
					$price = floatval($params['price']);
					if ($section_id < 0) $section_id = 0;

					return self::createElement($iblock_id, $params["name"], $price, $section_id);
				}
				return 0;
			break;
			default:
				return 'action error';
			break;
		}
	}

	static function createElement($iblock_id, $name, $price, $section_id = 0)
	{
		if (!defined('BX_UTF')) $name = iconv('utf-8', 'cp1251', $name);
		$params = Array(
			"max_len" => "100",
			"change_case" => "L",
			"replace_space" => "_",
			"replace_other" => "_",
			"delete_repeat_replace" => "true",
			"use_google" => "false"
		);
		$arFields = array(
			"ACTIVE" => "Y",
			'IBLOCK_SECTION_ID' => ($section_id > 0 ? $section_id : false),
			"MODIFIED_BY" => $GLOBALS['USER']->GetID(),
			"IBLOCK_ID" => $iblock_id,
			"NAME" => $name,
			"CODE" => CUtil::translit($name, "ru" , $params),
		);
		$obElement = new CIBlockElement;
		$ID = $obElement->Add($arFields);

		if ($ID == false) return 0;

		if (CModule::IncludeModule('catalog')) {
			$arEmptyProduct = array(
				'ID' => $ID,
				'QUANTITY' => 0,
				'QUANTITY_TRACE' => 'N',
				'CAN_BUY_ZERO' => 'N',
				'NEGATIVE_AMOUNT_TRACE' => 'N'
			);
			if (CCatalogProduct::Add($arEmptyProduct, false)) {
				$arBaseGroup = CCatalogGroup::GetBaseGroup();
				$arFields = Array(
					"PRODUCT_ID" => $ID,
					"CATALOG_GROUP_ID" => $arBaseGroup['ID'],
					"PRICE" => $price,
					"CURRENCY" => "RUB"
				);
				CPrice::Add($arFields);
			}
		}
/*
		if (isset($_REQUEST['image']) || isset($_REQUEST['descr'])) {
			self::fillElementNoId($iblock_id, $ID, $_REQUEST['image'], $_REQUEST['descr']);
		}*/
		return $ID;
	}

	static function fillElementNoId($iblock_id, $element_id, $image, $descr)
	{
		$obRes = CIBlockElement::GetList(Array(), $arFilter=Array("ID"=>$element_id), false, false, $arSelect = array('DETAIL_PICTURE', 'DETAIL_TEXT', 'PREVIEW_TEXT'));
		$arElement = $obRes->Fetch();
		if ($arElement === false) return array('status' => 'error', 'stage' => 'fetch');

		// -------------	GET AND SET IMAGE PROPERTY	------------------- //
		$arImageProp = array(
			"CODE" => 'MORE_PHOTO',
			"MULTIPLE" => "Y",
			"TYPE" => "F",
		);
		$arImages = false;
		$what_to_parse = COption::GetOptionInt(self::moduldeID, 'what_to_parse', 255);
		if (self::PARSE_IMAGES & $what_to_parse
		&&  (  (  CSpecificationsServiceProp::checkProperty($iblock_id, $arImageProp["CODE"], $arImageProp)
		       && CSpecificationsServiceProp::checkImgLimit($iblock_id, $arImageProp["CODE"], $element_id)
		       )
		    || (  COption::GetOptionString(self::moduldeID, 'detail_picture', 'Y') == 'Y'
		       && (  COption::GetOptionString(self::moduldeID, 'detail_picture_overwrite', 'N') == 'Y'
		       	  || empty($arElement['DETAIL_PICTURE'])
		       	  )
		       )
		    )
		)
		{
			$arImages = array(
				preg_replace('/_[12]?\d{2}x[12]?\d{2}/', '_600x600', $image),
				preg_replace('/_[12]?\d{2}x[12]?\d{2}/', '_300x300', $image),
				$image,
			);
			self::disableLog();
			$count = self::setImagesForElement($element_id, $iblock_id, $arImageProp["CODE"], $arImages, $bFirstOnly = true);
			self::enableLog();
			if ($count == 0) return array('status' => 'error', 'stage' => 'images');
		}
		//	---------------------------------------------------------------	//

		// ------------------  SET DESCRIPTION TEXT  ---------------------- //
		$strDescr = trim($descr);
		if (!empty($strDescr) && ($what_to_parse & self::PARSE_DESCRIPTION)) {
			if (!defined('BX_UTF')) {
				$strDescr = iconv('utf-8', 'cp1251', $strDescr);
			}

			$whereToSave = COption::GetOptionInt(self::moduldeID, 'where_save_text', 255);
			$bOverwriteText = (COption::GetOptionString(self::moduldeID, 'overwrite_text', 'N') == 'Y');

			$arFields = array();
			if ($whereToSave & self::DETAIL_TEXT) {
				if (empty($arElement['DETAIL_TEXT']) || $bOverwriteText) {
					$arFields['DETAIL_TEXT'] = $strDescr;
				}
			}
			if ($whereToSave & self::PREVIEW_TEXT) {
				if (empty($arElement['PREVIEW_TEXT']) || $bOverwriteText) {
					$arFields['PREVIEW_TEXT'] = $strDescr;
				}
			}
			if (!empty($arFields)) {
				$obElement = new CIBlockElement;
				$obElement->Update($element_id, $arFields);
			}
		}
		//	---------------------------------------------------------------	//
		return array('status' => 'ok');
	}

	static function fillElement($ya_id, $iblock_id, $element_id)
	{
		$arSpecifications = array();
		// -------------	CHECK, CREATE AND SET YANDEX_ID PROPERTY	------------- //
		$arYandexProp = array(
			"CODE" => COption::GetOptionString(self::moduldeID, 'yml_property', 'TURBO_YANDEX_LINK'),
			"MULTIPLE" => "N",
			"TYPE" => "S",
		);

		if(!CSpecificationsServiceProp::checkProperty($iblock_id, $arYandexProp["CODE"], $arYandexProp))
			return array('status' => 'error', 'stage' => 'yandexprop');

		CIBlockElement::SetPropertyValues($element_id, $iblock_id, Array("VALUE"=>$ya_id), $arYandexProp["CODE"]);
		//	---------------------------------------------------------------	//

		/*
		IT LOOKS LIKE THEY HAVE BEEN UNIFIED
		if (COption::GetOptionString(self::moduldeID, 'brands', 'N') == 'Y') {
			return self::fillElementFromBrands($ya_id, $iblock_id, $element_id);
		}
		*/

		// -------------	GET AND SET IMAGE PROPERTY	------------------- //
		$arImageProp = array(
			"CODE" => 'MORE_PHOTO',
			"MULTIPLE" => "Y",
			"TYPE" => "F",
		);
		$arImages = false;
		$what_to_parse = COption::GetOptionInt(self::moduldeID, 'what_to_parse', 255);

		if (self::PARSE_IMAGES & $what_to_parse
		&&  (  (  CSpecificationsServiceProp::checkProperty($iblock_id, $arImageProp["CODE"], $arImageProp)
		       && CSpecificationsServiceProp::checkImgLimit($iblock_id, $arImageProp["CODE"], $element_id)
		       )
		    || (  COption::GetOptionString(self::moduldeID, 'detail_picture', 'Y') == 'Y'
		       && (  COption::GetOptionString(self::moduldeID, 'detail_picture_overwrite', 'N') == 'Y'
		       	  || !CSpecificationsServiceProp::hasDetailPicture($element_id)
		       	  )
		       )
		    )
		)
		{
			$arImages = self::getImagesById($ya_id);
			if (self::$errCode > 0) return array('status' => 'error', 'stage' => 'images', 'error' => self::$errCode);
		}
		//	---------------------------------------------------------------	//

		// -------------	GET AND SET ALL TEXT PROPERTIES	--------------- //
		$arSpecifications = false;
		if ($what_to_parse & (self::PARSE_PROPERTIES | self::PARSE_DESCRIPTION)) {
			$arSpecifications = self::getSpecificationsById($ya_id);
			if (self::$errCode > 0) return array('status' => 'error', 'stage' => 'specifications', 'error' => self::$errCode);
		}

		if ($arSpecifications) {
			self::setRatingForElement($element_id, $iblock_id, $arSpecifications['RATING']);
			unset($arSpecifications['RATING']);

			$strDescr = $arSpecifications['DESCRIPTION'];
			unset($arSpecifications['DESCRIPTION']);

			if (count($arSpecifications) > 0 && $what_to_parse & self::PARSE_PROPERTIES) {
				self::setPropsForElement($element_id, $iblock_id, $arSpecifications);
			}

			if ($what_to_parse & self::PARSE_DESCRIPTION && !empty($strDescr)) {
				self::setDescriptionForElement($element_id, $strDescr);
			}
		}
		if ($arImages) {
			self::setImagesForElement($element_id, $iblock_id, $arImageProp["CODE"], $arImages);
		}
		//	---------------------------------------------------------------	//
		self::releaseProxy();

		return array('status' => 'ok');
	}

	static function fillElementFromBrands($ya_id, $iblock_id, $element_id)
	{
		$link = 'https://market.yandex.ru/model/' . urlencode($ya_id) . '/';
		$content = self::browser($link);
		$arImages = array('page' => $link);

		if (self::$errCode > 0) return array('status' => 'error', 'stage' => 'images', 'error' => self::$errCode);

		$dom = new DomDocument();
		$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
		$xpath = new DomXPath( $dom );

		$what_to_parse = COption::GetOptionInt(self::moduldeID, 'what_to_parse', 255);

		$strDescr = trim($xpath->evaluate("string(.//div[@class='b-wear-product-head__descr']/text())"));

		if ($what_to_parse & self::PARSE_DESCRIPTION && !empty($strDescr)) {
			if (!defined('BX_UTF')) $strDescr = iconv('utf-8', 'cp1251', $strDescr);

			$obRes = CIBlockElement::GetList(Array(), $arFilter=Array("ID"=>$element_id), false, false, $arSelect = array('DETAIL_PICTURE', 'DETAIL_TEXT', 'PREVIEW_TEXT'));
			$arElement = $obRes->Fetch();
			if ($arElement === false) return array('status' => 'error', 'stage' => 'fetch');

			$whereToSave = COption::GetOptionInt(self::moduldeID, 'where_save_text', 255);
			$bOverwriteText = (COption::GetOptionString(self::moduldeID, 'overwrite_text', 'N') == 'Y');

			$arFields = array();
			if ($whereToSave & self::DETAIL_TEXT) {
				if (empty($arElement['DETAIL_TEXT']) || $bOverwriteText) {
					$arFields['DETAIL_TEXT'] = $strDescr;
				}
			}
			if ($whereToSave & self::PREVIEW_TEXT) {
				if (empty($arElement['PREVIEW_TEXT']) || $bOverwriteText) {
					$arFields['PREVIEW_TEXT'] = $strDescr;
				}
			}
			if (!empty($arFields)) {
				$obElement = new CIBlockElement;
				$obElement->Update($element_id, $arFields);
			}
		}

		if ($what_to_parse & self::PARSE_IMAGES) {
			$arImageObjs = $xpath->query(".//*[@class='b-zoombox__thumbs__inner']/div");

			foreach( $arImageObjs as $image )
			{
				$diz = $image->getAttribute('data-image-zoom');
				$arZoom = json_decode($diz, true);
				$arImages[]	= empty($arZoom['large']) ? $arZoom['small'] : $arZoom['large'];
			}

			if (count($arImages) > 1) self::setImagesForElement($element_id, $iblock_id, 'MORE_PHOTO', $arImages);
		}
		self::releaseProxy();

		return array('status' => 'ok');
	}

	static function setElementStatus($iblock_id, $element_id, $status)
	{
		$arStatusProp = array(
			"CODE" => "TURBO_YANDEX_STATUS",
			"MULTIPLE" => "N",
			"TYPE" => "S",
		);
		if(!CSpecificationsServiceProp::checkProperty($iblock_id, $arStatusProp["CODE"], $arStatusProp))
			return 'error';

		CIBlockElement::SetPropertyValues(intval($element_id), $iblock_id, Array("VALUE"=>$status), $arStatusProp["CODE"]);
	}

	static function setDescriptionForElement($elementId, $strDescr)
	{
		if (empty($strDescr)) return;
		if (!defined('BX_UTF')) $strDescr = iconv('utf-8', 'cp1251', $strDescr);

		$obRes = CIBlockElement::GetList(Array(), $arFilter=Array("ID"=>$elementId), false, false, $arSelect = array('DETAIL_PICTURE', 'DETAIL_TEXT', 'PREVIEW_TEXT'));
		$arElement = $obRes->Fetch();
		if ($arElement === false) return;// array('status' => 'error', 'stage' => 'fetch');

		$whereToSave = COption::GetOptionInt(self::moduldeID, 'where_save_text', 255);
		$bOverwriteText = (COption::GetOptionString(self::moduldeID, 'overwrite_text', 'N') == 'Y');

		$arFields = array();
		if ($whereToSave & self::DETAIL_TEXT) {
			if (empty($arElement['DETAIL_TEXT']) || $bOverwriteText) {
				$arFields['DETAIL_TEXT'] = $strDescr;
			}
		}
		if ($whereToSave & self::PREVIEW_TEXT) {
			if (empty($arElement['PREVIEW_TEXT']) || $bOverwriteText) {
				$arFields['PREVIEW_TEXT'] = $strDescr;
			}
		}
		if (!empty($arFields)) {
			$obElement = new CIBlockElement;
			$obElement->Update($elementId, $arFields);
		}
	}
	//########################################################//
	//################    WORK WITH IMAGES    ################//
	//########################################################//
	static function getImagesById($yandex_id)
	{
		$obCache = new CPHPCache;
		$cacheID = 'getImagesById_'.$yandex_id;
		if ($obCache->InitCache(self::CACHE_TIME, $cacheID, self::CACHE_PATH)) {
			return $obCache->getVars();
		}
		$link = "https://market.yandex.ru/product/".urlencode($yandex_id).'/';
		$content = self::browser($link);
		$arReturn = array('page' => $link);

		if (self::$errCode > self::ERROR_NONE) return $arReturn;

		$dom = new DomDocument();
		$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
		$xpath = new DomXPath( $dom );

		//new markup
		$arImages = $xpath->query(".//li[@class='n-gallery__thumbs-item']/img");
		if ($arImages->length < 1) {
			//old markup
			$arImages = $xpath->query(".//div[@class='product-card-gallery__thumbs']/ul/li/a");
		}
		else
		{
			$bImgHtml = true;
		}
		if ($arImages->length < 1) {
			//old old markup
			$arImages = $xpath->query(".//*[@id='model-pictures']/tr/td/span/a");
		}
		foreach ($arImages as $image) {
			if($bImgHtml)
			{
				$src = $image->getAttribute('src');
				$arReturn[]	= substr($src, 0, strpos($src, '&size'));
			}				
			else
				$arReturn[]	= $image->getAttribute('href');
		}

		if (count($arReturn) < 2) {
			//old markup - no big image or single image
			if($arImages->length < 1) $arImages = $xpath->query(".//*[@class='product-card-gallery__image-container one']/img");
			if($arImages->length < 1) $arImages = $xpath->query(".//*[@id='model-pictures']/tr/td/span/img");
			if($arImages->length < 1) $arImages = $xpath->query(".//*[@class='b-model-pictures']/img");

			foreach ($arImages as $image) {
				$arReturn[]	= $image->getAttribute('src');
			}
		}

		if ($obCache->StartDataCache()) {
			$obCache->EndDataCache($arReturn);
		}

		return $arReturn;
	}

	static function setImagesForElement($element_id, $iblock_id, $imageProp, $arImages, $bFirstOnly = false)
	{
		if (!CModule::IncludeModule("iblock"))
			return 0;

		IncludeModuleLangFile(__FILE__);

		$referer = false;
		if (array_key_exists('page', $arImages)) {
			$referer = $arImages['page'];
			unset($arImages['page']);
		}

		$obElement = CIBlockElement::GetList(Array(), $arFilter=Array("ID"=>IntVal($element_id)), false, false, $arSelect = array('DETAIL_PICTURE'));
		$arElement = $obElement->Fetch();

		$bDetailPicture = true;
		if (COption::GetOptionString(self::moduldeID, 'detail_picture', 'Y') != 'Y'
		|| (COption::GetOptionString(self::moduldeID, 'detail_picture_overwrite', 'N') != 'Y'
		    && !empty($arElement['DETAIL_PICTURE']))
		)
		{
			$bDetailPicture = false;
		}

		$loadedCount = 0;
		$bFirstImage = true;
		foreach($arImages as $image_url)
		{
			if (!$bFirstImage && $bFirstOnly) break;

			self::writeLog(10);
			$im = self::browser($image_url, $referer, true, 0, '', $bImage = true);
			if (strpos($image_url, '_300x300')) self::enableLog();
			if (self::$errCode > 0) continue;

			$bFirstImage = false;
			$loadedCount++;

			if(!is_dir($_SERVER['DOCUMENT_ROOT']."/upload/tmp/"))
				mkdir($_SERVER['DOCUMENT_ROOT']."/upload/tmp/");
			$tmp_image_path = $_SERVER['DOCUMENT_ROOT']."/upload/tmp/i.jpg";
			file_put_contents($tmp_image_path, $im);

			$arFile = CFile::MakeFileArray($tmp_image_path);
			$arFile["MODULE_ID"] = "iblock";

			if ($bDetailPicture) {
				$arFile2 = CFile::GetById($arElement['DETAIL_PICTURE'])->Fetch();
				if ($arFile2['ORIGINAL_NAME'] == 'i.jpg'
				&&  $arFile2['FILE_SIZE'] == $arFile['size']) {
					unlink($tmp_image_path);
					$bDetailPicture = false;
					continue;
				}
			}

			$obRes = CIBlockElement::GetProperty($iblock_id, $element_id, array("sort" => "asc"), array("CODE" => $imageProp));
			while ($arRes = $obRes->Fetch()) {
				$arFile2 = CFile::GetById($arRes['VALUE'])->Fetch();
				if ($arFile2['ORIGINAL_NAME'] == 'i.jpg'
				&&  $arFile2['FILE_SIZE'] == $arFile['size']) {
					unlink($tmp_image_path);
					$bDetailPicture = false;
					continue 2;
				}
			}

			if ($bDetailPicture) {
				$arElement['DETAIL_PICTURE'] = $arFile;
				$el = new CIBlockElement;
				$el->Update($element_id, array('del' => 'Y'));
				$el->Update($element_id, $arElement, false, false, true);
				$bDetailPicture = false;
			} else {
				CIBlockElement::SetPropertyValues($element_id, $iblock_id, Array("VALUE"=>$arFile), $imageProp );
			}
			unlink($tmp_image_path);
		}
		return $loadedCount;
	}
	//##############    END WORK WITH IMAGES    ##############//
	//########################################################//


	static function convertNameToCode($name)
	{
		// IncludeModuleLangFile(__FILE__);
		// global $MESS;
		// $translit = $MESS["TRANSLIT"];

		// $code = strtoupper(strtr($name, $translit));
		//if(is_numeric($code{0}))

		$arParams = array("replace_space"=>"_","replace_other"=>"_", "change_case" => "U");
		$code = Cutil::translit($name,"ru",$arParams);
		$prefix = COption::GetOptionString(self::moduldeID, 'property_prefix', '');
			$code= $prefix.$code;
		//$code = preg_replace('/\(.*\)/i', '', $code); // old method
		// $code = str_replace(array("(",")"), '', $code);
		// $code = str_replace(array("\\","/"), '_', $code);
		// $code = preg_replace('/[^a-z_0-9]+/i', '', $code);
		// $code = trim($code,'_');

		return $code;
	}

	//########################################################//
	//##############    WORK WITH PROPETIES    ###############//
	//########################################################//
	static function getSpecificationsById($yandex_id)
	{
		$obCache = new CPHPCache;
		$cacheID = 'getSpecificationsById_'.$yandex_id;
		if ($obCache->InitCache(self::CACHE_TIME, $cacheID, self::CACHE_PATH)) {
			return $obCache->getVars();
		}
		self::writeLog(11, $yandex_id);
		$arReturn = array();
		$link = "https://market.yandex.ru/product/".urlencode($yandex_id).'/spec?track=tabs';
		$referer = "https://market.yandex.ru/product/".urlencode($yandex_id).'/';
		$content = self::browser($link, $referer);

		if (self::$errCode > 0) return $arReturn;

		$dom = new DomDocument();
		$dom->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
		$xpath = new DomXPath( $dom );

		$i=0;

		//new markup
		$obGroupList = $xpath->query(".//div[@class='product-spec-wrap__body']");

		foreach ($obGroupList as $obGroup) {
			$obGroupTitle = $xpath->query("./h2", $obGroup);
			if ($obGroupTitle->length) {
				$obGroupTitle = $obGroupTitle->item(0);
				$groupName = trim(str_replace(array('&nbsp;', "\xC2\xA0"), ' ', $obGroupTitle->nodeValue));
				$obSpec = $obGroupTitle->nextSibling;
			} else {
				$obSpec = $xpath->query("./dl", $obGroup)->item(0);
			}
			while($obSpec != NULL) {
				if ($obSpec->nodeName != 'dl') continue;
				$obNameRes = $xpath->query("./dt/span[position() = 1]/text()[position() = 1]", $obSpec);
				if ($obNameRes->length < 1) continue;
				$obValueRes = $xpath->query("./dd/span[position() = 1]", $obSpec);
				if ($obValueRes->length < 1) continue;

				$arReturn[$i]['GROUP'] = $groupName;
				$arReturn[$i]['TITLE'] = trim(str_replace(array('&nbsp;', "\xC2\xA0"), ' ', $obNameRes->item(0)->nodeValue));
				$arReturn[$i]['VALUE'] = trim(str_replace(array('&nbsp;', "\xC2\xA0"), ' ', $obValueRes->item(0)->nodeValue));

				$obSpec = $obSpec->nextSibling;
				$i++;
			}
		}

		if ($i == 0) {
			//old markup
			$spec_value = $xpath->query(".//*[@class='b-properties']/tbody/tr/td[@class='b-properties__value']");
			$rows = $xpath->query(".//*[@class='b-properties']/tbody/tr/th");

			$i=0;
			foreach($rows as $row)
			{
				if($row->getAttribute('class')=='b-properties__title') {
					$group = $row->nodeValue;
					continue;
				}

				$arReturn[$i]['GROUP'] = trim($group);
				$arReturn[$i]['TITLE'] = trim($row->nodeValue);
				$arReturn[$i]['VALUE'] = trim($spec_value->item($i)->nodeValue);
				$i++;
			}
		}

		self::writeLog(8, $i);
		//########		GET RATING		########//
		$rating = 0;

		$obNodeList = $xpath->query(".//div[@itemprop='aggregateRating']/meta[@itemprop='ratingValue']");
		if ($obNodeList->length > 0) {
			$obAttr = $obNodeList->item(0)->attributes->getNamedItem("content");
			if ($obAttr != NULL) {
				$rating = floatval($obAttr->nodeValue);
			}
		}

		$arReturn['RATING'] = $rating;
		//####################################//

		$arReturn['DESCRIPTION'] = trim($xpath->evaluate("string(.//div[@class='product-spec-wrap__desc']/text())"));

		if($obCache->StartDataCache()) {
			$obCache->EndDataCache($arReturn);
		}

		return $arReturn;
	}

	static function setRatingForElement($element_id, $iblock_id, $rating_value)
	{
		if($rating_value<=0)
			return false;
		$res = CIBlockProperty::GetList(Array(), Array("IBLOCK_ID"=>$iblock_id, "CODE"=>"rating"));
		if(!$ar_res = $res->GetNext())
		{
			$obProperty = new CIBlockProperty;
			$res = $obProperty->Add(array(
					"IBLOCK_ID" => $iblock_id,
					"ACTIVE" => "Y",
					"PROPERTY_TYPE" => "N",
					"MULTIPLE" => "N",
					"NAME" => GetMessage("YS_RATING"),
					"CODE" => "rating",
				));
		}
		else
		{
			$res = CIBlockElement::GetProperty($iblock_id, $element_id, array("sort" => "asc"), Array("CODE"=>"rating"));
			if($ar_res = $res->Fetch())
				if(IntVal($ar_res["VALUE"]<=0))
				{
					CIBlockElement::SetPropertyValuesEx($element_id, $iblock_id, array(
							"rating" => array(
								"VALUE" => $rating_value
							),
						));
				}
		}


	}
	static function setPropsForElement($element_id, $iblock_id, $arSpecifications)
	{
		IncludeModuleLangFile(__FILE__);
		if (!CModule::IncludeModule("iblock"))
			return;

		$use_in_smart_filter = COption::GetOptionString(self::moduldeID, 'use_in_smart_filter', 'Y');
		$property_by_section = COption::GetOptionString(self::moduldeID, 'property_by_section', '');
		$arForCYenisiteInfoblockpropsplus = array();
		$template_for_split_value = "/[;,]+/";

		if($property_by_section=='Y' || $use_in_smart_filter=='Y')
		{
			$arIBlock = CIBlock::GetArrayByID($iblock_id);
			if($property_by_section=='Y')
				$needValue = "Y";
			else
				$needValue = "N";
			if($arIBlock["SECTION_PROPERTY"] != $needValue)
			{
				$ib = new CIBlock;
				$res = $ib->Update($iblock_id, array(
					"SECTION_PROPERTY" => $needValue,
				));
				unset($ib);
			}
		}

		$arElement = CIBlockElement::GetByID($element_id)->GetNext();
		if(COption::GetOptionString(self::moduldeID, 'group_by_section', '')=='Y')
			$section_id = $arElement['IBLOCK_SECTION_ID'];
		else
			$section_id = 0;

		$rsParentSection = CIBlockSection::GetByID($arElement['IBLOCK_SECTION_ID']);
		$arParentSection = $rsParentSection->GetNext();

		foreach($arSpecifications as $specification)
		{
			if(!defined('BX_UTF'))
			{
				//$specification['GROUP'] = iconv("utf-8", "windows-1251", $specification['GROUP']);
				$specification['TITLE'] = iconv("utf-8", "windows-1251", $specification['TITLE']);
				$specification['VALUE'] = iconv("utf-8", "windows-1251", $specification['VALUE']);
			}

			if(is_numeric(strpos($specification['TITLE'], GetMessage('YS_WEIGHT'))) && CModule::IncludeModule("catalog"))
			{
				$specification['VALUE'] = self::setWeightForElement($element_id, $specification['VALUE']) . ' ' . GetMessage('YS_WEIGHT_G');
				//continue;
			}
			$specification['TITLE_CODE'] = self::convertNameToCode($specification['TITLE']);
			if($property_by_section=='Y')
				$specification['TITLE_CODE'] .= '_'.$arElement['IBLOCK_SECTION_ID'];

			// SEARCH THIS PROPERTIES
			$arFilter = array(
				'IBLOCK_ID' => $iblock_id,
				'NAME' => $specification['TITLE'],
			);
			if(COption::GetOptionString(self::moduldeID, 'only_active_props', 'Y')=='Y')
				$arFilter['ACTIVE'] = 'Y';

			$res = CIBlockProperty::GetList(Array(), $arFilter);
			$notFindPropety=true;
			// IF PROPERTY WITH NAME=$specification['TITLE'] IS EXISTS
			while($property = $res->GetNext())
			{
				if(!in_array($property['PROPERTY_TYPE'], array('S','N','L','E')))
					continue;
				if($property_by_section=='Y')
				{
					$SECTION_PROP = CIBlockSectionPropertyLink::GetArray($iblock_id,$arElement['IBLOCK_SECTION_ID']);

					if(array_key_exists($property['ID'],$SECTION_PROP))
					{
						if ($specification['GROUP']) {
							$arForCYenisiteInfoblockpropsplus[$specification['GROUP']][] = $property['ID'];
						}
						self::setExistPropForElement($property,$specification['VALUE'],$element_id,$iblock_id,$template_for_split_value);
						$notFindPropety = false;
						break;
					}
					else
					{
						if ($arParentSection)
						{
							$arFilter = array(
								'IBLOCK_ID' => $arParentSection['IBLOCK_ID'],
								'>LEFT_MARGIN' => $arParentSection['LEFT_MARGIN'],
								'<RIGHT_MARGIN' => $arParentSection['RIGHT_MARGIN'],
								'>DEPTH_LEVEL' => $arParentSection['DEPTH_LEVEL']
							); // select ALL child section of parent_section
							$arSelect = Array("ID");
							$rsSect = CIBlockSection::GetList(array('right_margin' => 'asc'),$arFilter,false,$arSelect);
							while ($arSect = $rsSect->GetNext())
							{
								$arFields = Array(
									'SMART_FILTER' => $use_in_smart_filter,
									'IBLOCK_ID' => $iblock_id,
								);
								$SECTION_PROP = CIBlockSectionPropertyLink::GetArray($iblock_id,$arSect['ID']);
								if(array_key_exists($property['ID'],$SECTION_PROP))
								{
									CIBlockSectionPropertyLink::DeleteByProperty($property['ID']);
									CIBlockSectionPropertyLink::Add($arElement['IBLOCK_SECTION_ID'],$property['ID'],$arFields);
									$notFindPropety = false;
									self::setExistPropForElement($property,$specification['VALUE'],$element_id,$iblock_id,$template_for_split_value);
									break;
								}
							}
						}
					}
				}
				else
				{
					if ($specification['GROUP']) {
						$arForCYenisiteInfoblockpropsplus[$specification['GROUP']][] = $property['ID'];
					}
					self::setExistPropForElement($property,$specification['VALUE'],$element_id,$iblock_id,$template_for_split_value);
					$notFindPropety = false;
					break;
				}

			}
			if($notFindPropety)
			{
				$PropID = self::setNotExistPropForElement($specification,$element_id,$iblock_id,$template_for_split_value);
				if ($specification['GROUP']) {
					$arForCYenisiteInfoblockpropsplus[$specification['GROUP']][] = $PropID;
				}
			}
		}

		self::CYenisiteInfoblockpropsplus_SetPropsToYandexGroup($iblock_id,$arForCYenisiteInfoblockpropsplus,$section_id);

	}

	static function setExistPropForElement($arProperty,$arPropertyValue,$element_id, $iblock_id,$template_for_split_value = "/[;,]+/")
	{
		$rewrite_props       = COption::GetOptionString(self::moduldeID, 'rewrite_props', 'N');
		$property_by_section = COption::GetOptionString(self::moduldeID, 'property_by_section', '');

		$bDeleteRootLink = false;
		if ($property_by_section == 'Y') {
			$arLinks = CIBlockSectionPropertyLink::GetArray($iblock_id);
			if (!array_key_exists($arProperty['ID'], $arLinks)) {
				$bDeleteRootLink = true;
			}
		}

		switch($arProperty['PROPERTY_TYPE']){
		// IF THIS PROPERTY IS LIST
		case 'L':
			if(count($values = preg_split($template_for_split_value,$arPropertyValue))>1)
			{
				if($arProperty['MULTIPLE']!='Y')
				{
					$arFields = Array(
						 'MULTIPLE'=>'Y',
						 'LIST_TYPE'=>'C',
					);
					$ibp = new CIBlockProperty;
					$ibp->Update($arProperty['ID'], $arFields);
				}
			}
			foreach($values as $value)
			{
				$value = trim($value);
				$property_enums = CIBlockPropertyEnum::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>$iblock_id, "CODE"=>$arProperty['CODE']));
				while($enum_fields = $property_enums->GetNext())
				{
					if(strtolower(htmlspecialchars_decode($enum_fields["VALUE"]))==strtolower($value))
					{
						$val_id = $enum_fields["ID"];
						continue;
					}
				}
				if(isset($val_id ))
				{
					$arValues[] = array('VALUE' => $val_id);
				}
				else
				{
					$ibpenum = new CIBlockPropertyEnum;
					$new_val_id = $ibpenum->Add(Array('PROPERTY_ID'=>$arProperty['ID'], 'VALUE'=>$value));
					$arValues[] = array('VALUE' => $new_val_id);
				}
				unset($val_id);
			}
			$prop = CIBlockElement::GetProperty($iblock_id, $element_id, Array(), Array("CODE"=>$arProperty['CODE']));
			$ar_prop = $prop->Fetch();
			if( empty($ar_prop["VALUE"]) || $rewrite_props=='Y')
			{
				CIBlockElement::SetPropertyValues($element_id, $iblock_id, $arValues, $arProperty['CODE']);
			}
			unset($arValues);
			break;
		// IF THIS PROPERTY IS BIND TO ELEMENTS OF IBLOCK
		case 'E':
			if(count($values = preg_split($template_for_split_value,$arPropertyValue))>1)
			{
				if($arProperty['MULTIPLE']!='Y')
				{
					$arFields = Array('MULTIPLE'=>'Y');
					$ibp = new CIBlockProperty;
					$ibp->Update($arProperty['ID'], $arFields);
				}
			}
			foreach($values as $value)
			{
				$arSelect = Array("ID");
				$arFilter = Array("IBLOCK_ID"=>IntVal($arProperty['LINK_IBLOCK_ID']), 'NAME'=>$value ,"ACTIVE"=>"Y");
				$res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
				if($ob = $res->GetNextElement())
				{
					$arFields = $ob->GetFields();
					$arValues[] = array('VALUE' => $arFields['ID']);
				}
				else
				{
					$el = new CIBlockElement;
					$arFields = Array(
					  "IBLOCK_ID"      => IntVal($arProperty['LINK_IBLOCK_ID']),
					  "NAME"           => $value,
					  "ACTIVE"         => "Y",
					);
					$arValues[] = array('VALUE' => $el->Add($arFields));

				}
			}
			$prop = CIBlockElement::GetProperty($iblock_id, $element_id, Array(), Array("CODE"=>$arProperty['CODE']));
			$ar_prop = $prop->Fetch();
			if( empty($ar_prop["VALUE"]) || $rewrite_props=='Y')
			{
				CIBlockElement::SetPropertyValues($element_id, $iblock_id, $arValues, $arProperty['CODE']);
			}
			unset($arValues);
			break;
		// IF THIS PROPERTY IS STRING
		case 'S':
			$prop = CIBlockElement::GetProperty($iblock_id, $element_id, Array(), Array("CODE"=>$arProperty['CODE']));
			$ar_prop = $prop->Fetch();
			if( empty($ar_prop["VALUE"]) || $rewrite_props=='Y')
			{
				CIBlockElement::SetPropertyValues($element_id, $iblock_id, $arPropertyValue, $arProperty['CODE']);
			}
			break;
		// IF THIS PROPERTY IS NUMBER
		case 'N':
			$arMatches = array();
			if(COption::GetOptionString(self::moduldeID, 'measure_check', 'N') == 'Y') {
				//CHECK MEASURES
				if (preg_match(self::getMeasureRegExp(), $arPropertyValue, $arMatches)) {
					$arPropertyValue = $arMatches[1];
				}
			}
			if(!is_numeric($arPropertyValue))
			{
				$arFields = Array('PROPERTY_TYPE'=>'S');
				$ibp = new CIBlockProperty;
				$ibp->Update($arProperty['ID'], $arFields);
			}
			if (count($arMatches)) {
				$arPropertyValue = array('VALUE' => $arPropertyValue, 'DESCRIPTION' => $arMatches[3]);
			}
			$prop = CIBlockElement::GetProperty($iblock_id, $element_id, Array(), Array("CODE"=>$arProperty['CODE']));
			$ar_prop = $prop->Fetch();
			if( empty($ar_prop["VALUE"]) || $rewrite_props=='Y')
			{
				if (is_array($arPropertyValue)
				&& array_key_exists('DESCRIPTION', $arPropertyValue)
				&& $ar_prop['WITH_DESCRIPTION'] != 'Y'){
					$arFields = array('WITH_DESCRIPTION' => 'Y');
					$ibp = new CIBlockProperty;
					$ibp->Update($arProperty['ID'], $arFields);
				}
				CIBlockElement::SetPropertyValues($element_id, $iblock_id, $arPropertyValue, $arProperty['CODE']);
			}
			break;
		}

		if ($bDeleteRootLink && isset($ibp)) {
			CIBlockSectionPropertyLink::Delete(0, $arProperty['ID']);
		}
	}

	static function setNotExistPropForElement($specification,$element_id, $iblock_id,$template_for_split_value = "/[;,]+/")
	{
		//--- ADD NEW PROPERTY ---//
		$arFields = Array(
		  "NAME" => $specification['TITLE'],
		  "ACTIVE" => "Y",
		  "SORT" => "1000",
		  "CODE" => $specification['TITLE_CODE'],
		  "PROPERTY_TYPE" => "L",
		  "IBLOCK_ID" => $iblock_id,
		 );

		//--- NEED TO CHECK PROPERTY CODE ---//
		$arFilter = array('IBLOCK_ID' => $iblock_id, 'CODE' => &$arFields['CODE']);
		$i = 0;
		do {
			$obRes = CIBlockProperty::GetList(array(), $arFilter);
			if ($obRes->SelectedRowsCount() < 1) break;
			$arFields['CODE'] = $specification['TITLE_CODE'] . ++$i;
		} while (1);
		unset($arFilter);
		//--- NEED TO CHECK PROPERTY CODE END ---//

		if(count($values = preg_split($template_for_split_value,$specification['VALUE']))>1)
		{
			$arFields["MULTIPLE"]  = 'Y';
			$arFields["LIST_TYPE"] = 'C';
		}
		elseif(is_numeric(current($values))) {
			$arFields["PROPERTY_TYPE"] = 'N';
		}
		elseif(COption::GetOptionString(self::moduldeID, 'measure_check', 'N') == 'Y') {
			//CHECK MEASURES
			$arMatches = array();
			if (preg_match(self::getMeasureRegExp(), $specification['VALUE'], $arMatches)) {
				$arFields["PROPERTY_TYPE"] = 'N';
				$arFields["WITH_DESCRIPTION"] = 'Y';
				$values = array(
					array('VALUE' => $arMatches[1], 'DESCRIPTION' => $arMatches[3])
					);
			}
		}

		if($arFields["PROPERTY_TYPE"] == 'L')
		{
			$values = array_unique($values);
			foreach($values as &$value)
			{
				$value = trim($value);
				$arFields["VALUES"][]=Array(
					"VALUE" => $value,
					"DEF" => "N",
					//"SORT" => "1000"
				);
				$value = strtolower($value);
			}
		}
		$ibp = new CIBlockProperty;

		$PropID = $ibp->Add($arFields);

		$arSmartField = Array(
			'SMART_FILTER' => COption::GetOptionString(self::moduldeID, 'use_in_smart_filter', 'Y'),
			'IBLOCK_ID' => $iblock_id,
			);

		if(COption::GetOptionString(self::moduldeID, 'property_by_section', '')=='Y')
		{
			$res = CIBlockElement::GetByID($element_id);
			$ar_res = $res->GetNext();
			$section_id = !empty($ar_res['IBLOCK_SECTION_ID'])?$ar_res['IBLOCK_SECTION_ID']:0;

			CIBlockSectionPropertyLink::DeleteByProperty($PropID);
			CIBlockSectionPropertyLink::Add($section_id,$PropID,$arSmartField);
		}
		else
		{
			$ibp->Update($PropID, $arSmartField);
		}

		unset($ibp);
		//--- SET VALUE OF NEW PROPERTY FOR ELEMENT---//
		if($arFields["PROPERTY_TYPE"] == 'L')
		{
			$property_enums = CIBlockPropertyEnum::GetList(Array("SORT"=>"ASC"), Array("IBLOCK_ID"=>$iblock_id, "CODE"=>$arFields['CODE']));

			while($enum_fields = $property_enums->GetNext())
			{
				if(in_array(trim(strtolower(htmlspecialchars_decode($enum_fields["VALUE"]))), $values))
				{
					$arValues[] = array('VALUE' => $enum_fields["ID"]);
				}
			}
		}
		elseif($arFields["PROPERTY_TYPE"] == 'N')
			$arValues = current($values);

		CIBlockElement::SetPropertyValues($element_id, $iblock_id, $arValues, $arFields['CODE']);


		unset($arFields,$arValues);

		return $PropID;
	}

	static function setWeightForElement($element_id, $weight_value)
	{
		if(is_numeric(strpos($weight_value,GetMessage('YS_WEIGHT_KG'))))
		{
			$weight_value = floatval($weight_value)*1000;
		}
		elseif( is_numeric(strpos($weight_value,GetMessage('YS_WEIGHT_GR')))||
			is_numeric(strpos($weight_value,GetMessage('YS_WEIGHT_G'))))
		{
			$weight_value = floatval($weight_value);
		}

		else
		{
			return false;
		}

		CCatalogProduct::Update($element_id, array("WEIGHT"=>$weight_value));

		return $weight_value;
	}

	//############    END WORK WITH PROPETIES    #############//
	//########################################################//

	static function getMeasureRegExp()
	{
		static $measureRegExp = NULL;
		if ($measureRegExp != NULL) return $measureRegExp;

		$measureStr = COption::GetOptionString(self::moduldeID, 'measure_types', '');
		$arMeasures = explode(chr(13), $measureStr);
		$arUnsetKeys = array();
		foreach ($arMeasures as $key => &$measure) {
			$measure = preg_quote( trim($measure) );
			if (empty($measure)) {
				$arUnsetKeys[] = $key;
			}
		}
		foreach ($arUnsetKeys as $key) {
			unset($arMeasures[$key]);
		}

		$measureStr = count($arMeasures) ? implode('|', $arMeasures) : '.+';

		$measureRegExp = '/^(\d+(\.\d+)?)[\s]+[\(]?(' . $measureStr . ')[\)]?$/i';

		return $measureRegExp;
	}

	//###########################################################################//
	//###########    WORK WITH MODULE YENISITE.INFOBLOCKPROPSPLUS    ############//
	//###########################################################################//
	const tblPropsToGroups = 'yen_ipep_props_to_groups';

	static function CYenisiteInfoblockpropsplus_SetPropsToYandexGroup($iblock_id,$arGroupWithProps,$section_id=0)
	{
		if (!CModule::IncludeModule('yenisite.infoblockpropsplus'))
			return;

		$rewrite_props = COption::GetOptionString(self::moduldeID, 'rewrite_props', 'N');
		foreach($arGroupWithProps as $group_name=>$props)
		{
			$group_id = CYenisiteInfoblockpropsplus::AddGroup($iblock_id, $group_name,1000,$section_id);
			self::CYenisiteInfoblockpropsplus_AddPropsToGroup($props, $iblock_id, $group_id, $rewrite_props);
		}
		global $CACHE_MANAGER;
		$CACHE_MANAGER->ClearByTag(CYenisiteInfoblockpropsplus::cacheID);
		unset($CACHE_MANAGER);
	}
	static function CYenisiteInfoblockpropsplus_AddPropsToGroup($props, $iblock_id, $group_id, $rewrite_props)
	{
		global $DB;

		$group_id = intval($group_id);
		$iblock_id = intval($iblock_id);

		if ($group_id <= 0 || !is_array($props) || $iblock_id <= 0) {
			return false;
		}

		//$DB->Query('delete from ' . self::tblPropsToGroups . ' where group_id = ' . $group_id, true);
		foreach ($props as $k => $v) {
			if (intval($v) > 0) {
				if($rewrite_props == 'Y')
				{
					$DB->Query('delete from ' . self::tblPropsToGroups . ' where PROPERTY_ID = ' . intval($v), true);
					$DB->Query('insert into ' . self::tblPropsToGroups . ' ( `PROPERTY_ID`, `IBLOCK_ID`, `GROUP_ID`) values ( ' . intval($v) . ', ' . intval($iblock_id) . ', ' . intval($group_id) . ')', true);
				}
				else
				{
					$res = $DB->Query('select ID from ' . self::tblPropsToGroups . ' where PROPERTY_ID = "' . intval($v) . '" and iblock_id = ' . intval($iblock_id))->Fetch();
					if($res['ID']<=0)
					{
						$DB->Query('insert into ' . self::tblPropsToGroups . ' ( `PROPERTY_ID`, `IBLOCK_ID`, `GROUP_ID`) values ( ' . intval($v) . ', ' . intval($iblock_id) . ', ' . intval($group_id) . ')', true);
					}
				}
			}
		}
	}
	//#########    END WORK WITH MODULE YENISITE.INFOBLOCKPROPSPLUS    ##########//
	//###########################################################################//

}

class CSpecificationsServiceProp{
	static function checkProperty($iblock_id, $code, $param)
	{
		// -------------	CHECK AND CREATE NEED PROPERTY	------------- //
		$res = CIBlockProperty::GetList(Array(), Array("ACTIVE"=>"Y", "IBLOCK_ID"=>$iblock_id, "CODE"=>$code));
		if(!$ar_res = $res->GetNext())
		{
			$param["MULTIPLE"] = in_array($param["MULTIPLE"], array("Y", "N")) ? $param["MULTIPLE"] : "N";
			$param["TYPE"] = in_array($param["TYPE"], array("S", "N", "L", "F", "G", "E")) ? $param["TYPE"] : "S";
			$param["NAME"] = (GetMessage($code)=='') ? $code : GetMessage($code);
			$arFields = Array(
				"NAME" => $param["NAME"],
				"MULTIPLE" => $param["MULTIPLE"],
				"ACTIVE" => "Y",
				"SORT" => "12000",
				"CODE" => $code,
				"PROPERTY_TYPE" => $param["TYPE"],
				"IBLOCK_ID" => $iblock_id
			);

			$ibp = new CIBlockProperty;
			$PropID = $ibp->Add($arFields);
			if(intval($PropID)<=0)
				return false;
			$arFields = Array(
				'SMART_FILTER' => 'N',
				'IBLOCK_ID' => $iblock_id,
			);
			CIBlockSectionPropertyLink::Add(0,$PropID,$arFields);
		}
		return true;
	}

	static function checkImgLimit($iblock_id, $code, $element_id)
	{
		$imageLimit = 4;
		$res = CIBlockElement::GetProperty($iblock_id, $element_id, array("sort" => "asc"), Array("CODE"=>$code));
		if(intval($res->SelectedRowsCount())>=$imageLimit)
			return false;
		else
			return true;
	}

	static function hasDetailPicture($element_id)
	{
		$obRes = CIBlockElement::GetList(Array(), $arFilter=Array("ID"=>IntVal($element_id)), false, false, $arSelect = array('DETAIL_PICTURE'));
		$arRes = $obRes->Fetch();

		if (empty($arRes))                   return false;
		if (empty($arRes['DETAIL_PICTURE'])) return false;

		return true;
	}
}
?>