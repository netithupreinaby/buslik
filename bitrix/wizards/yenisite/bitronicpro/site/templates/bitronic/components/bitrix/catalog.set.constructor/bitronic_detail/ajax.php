<?
define("NOT_CHECK_PERMISSIONS", true);

if (isset($_REQUEST['site_id']) && !empty($_REQUEST['site_id']))
{
	$strSiteID = (string)$_REQUEST['site_id'];
	if (preg_match('/^[a-z0-9_]{2}$/i', $strSiteID) === 1)
	{
		define('SITE_ID', $strSiteID);
	}
}

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if(!CModule::IncludeModule("catalog"))
{
	return;
}

if ($_SERVER["REQUEST_METHOD"]=="POST" && strlen($_POST["action"])>0 && check_bitrix_sessid())
{
	$APPLICATION->RestartBuffer();

	switch ($_POST["action"])
	{
		case "catalogSetAdd2Basket":
			if (is_array($_POST["set_ids"]))
			{
				foreach($_POST["set_ids"] as $itemID)
				{
					$product_properties = true;
					if (!empty($_POST["setOffersCartProps"]))
					{
						$product_properties = CIBlockPriceTools::GetOfferProperties(
							$itemID,
							$_POST["iblockId"],
							$_POST["setOffersCartProps"]
						);
					}
					$ratio = 1;
					if ($_POST["itemsRatio"][$itemID])
						$ratio = $_POST["itemsRatio"][$itemID];

					if (intval($itemID))
						Add2BasketByProductID(intval($itemID), $ratio, array("LID" => $_POST["lid"]), $product_properties);
				}
			}
			break;
		case "ajax_recount_prices":
			IncludeTemplateLangFile($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/header.php');
			include_once $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/functions/yenisitecatalog.php';
			if (strlen($_POST["currency"])>0)
			{
				$arPices = array("formatSum" => "", "formatOldSum" => "", "formatDiscDiffSum" => "");
				if ($_POST["sumPrice"])
					$arPices["formatSum"] = yenisite_rubleSign(FormatCurrency($_POST["sumPrice"], $_POST["currency"]));
				if ($_POST["sumOldPrice"] && $_POST["sumOldPrice"] != $_POST["sumPrice"])
					$arPices["formatOldSum"] = yenisite_rubleSign(FormatCurrency($_POST["sumOldPrice"], $_POST["currency"]));
				if ($_POST["sumDiffDiscountPrice"])
					$arPices["formatDiscDiffSum"] = yenisite_rubleSign(FormatCurrency($_POST["sumDiffDiscountPrice"], $_POST["currency"]));

				if (SITE_CHARSET != "utf-8")
					$arPices = $APPLICATION->ConvertCharsetArray($arPices, SITE_CHARSET, "utf-8");
				echo json_encode($arPices);
			}
			break;
	}

	die();
}
?>