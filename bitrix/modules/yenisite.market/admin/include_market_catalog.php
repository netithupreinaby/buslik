<?
define("ADMIN_MODULE_NAME", "yenisite.market");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
IncludeModuleLangFile(__FILE__);
if (!CModule::IncludeModule("iblock")){
    die(GetMessage('IBLOCKS_NOT_INSTALL'));
}

if (!CModule::IncludeModule("yenisite.market")){
    die(GetMessage('MARKET_NOT_INSTALL'));
}

$res = CIBlock::GetList(array(), array("CODE" => "YENISITE_MARKET_ORDER"));
$iblock = $res->GetNext();

if (!$iblock){
    die(GetMessage("MARKET_IBLOCK_NOT_INSTALL"));
}

global $USER;
if(!$USER->IsAdmin())
	return;


global $APPLICATION;
$action = htmlspecialchars($_REQUEST["action"]);

$catalog = CMarketCatalog::GetList();
$already_catalog = array();
while($cat = $catalog->GetNext()) {
    $already_catalog[$cat["iblock_id"]] = array('ID' => $cat["iblock_id"], 'USE_QUANTITY' => $cat['use_quantity']);
}

if($action)
{
    $catalogList = array();
    CMarketCatalog::Delete("*");
    foreach($_REQUEST as $key=>$value)
    {
        if(strpos($key,"IS_CATALOG_") !== 0) continue;
        
        $id = str_replace("IS_CATALOG_","",$key);

        if(CIBlock::GetArrayByID($id, "SECTION_PROPERTY") !== "Y") {
            $ib = new CIBlock;
            $ib->Update($id, array("SECTION_PROPERTY" => "Y"));
        }
        $arPropLinks = CIBlockSectionPropertyLink::GetArray($id);

        $dbprice = CMarketPrice::GetList();
        while($price = $dbprice->GetNext())
        {
            $properties = CIBlockProperty::GetList(Array(), Array("IBLOCK_ID"=>$id, "CODE" => $price["code"]));
            if($arProp = $properties->GetNext()) {
                $PropID = $arProp['ID'];
                if (array_key_exists($PropID, $arPropLinks))
                if ($arPropLinks[$PropID]['SMART_FILTER'] === 'Y') continue;
                $arFields = Array(
                  "SMART_FILTER" => "Y",
                  "DISPLAY_TYPE" => "A",
                  "DISPLAY_EXPANDED" => "Y"
                  );
                $ibp = new CIBlockProperty;
                $ibp->Update($PropID, $arFields);
            } else {
                $arFields = Array(
                  "NAME" => $price["name"],
                  "ACTIVE" => "Y",
                  "SORT" => "5555",
                  "CODE" => $price["code"],
                  "PROPERTY_TYPE" => "N",
                  "IBLOCK_ID" => $id,
                  "SMART_FILTER" => "Y",
                  "DISPLAY_TYPE" => "A",
                  "DISPLAY_EXPANDED" => "Y"
                  );
                $ibp = new CIBlockProperty;
                $PropID = $ibp->Add($arFields);
            }
        }

        $properties = CIBlockProperty::GetList(Array(), Array("IBLOCK_ID"=>$id, "CODE" => 'MARKET_QUANTITY'));
        if(!$properties->GetNext())
        {
            $arFields = Array(
              "NAME" => GetMessage('YEN_MARKET_QUANTITY_NAME'),
              "ACTIVE" => "Y",
              "SORT" => "5555",
              "CODE" => 'MARKET_QUANTITY',
              "PROPERTY_TYPE" => "N",
              "DEFAULT_VALUE" => "0",
              "IBLOCK_ID" => $id
              );
            $ibp = new CIBlockProperty;
            $PropID = $ibp->Add($arFields);
        }

        $use_quantity = array_key_exists('USE_QUANTITY_'.$id, $_REQUEST) ? 1 : 0;
        CMarketCatalog::Add($id, $use_quantity);

        $catalogList[$id] = array('ID' => $id, 'USE_QUANTITY' => $use_quantity);
        
    }//foreach($_REQUEST as $key=>$value)

    if (defined('BX_COMP_MANAGED_CACHE') && is_object($GLOBALS['CACHE_MANAGER']))
    foreach ($catalogList as $id => $cat) {
        if(!array_key_exists($id, $already_catalog) || $already_catalog[$id]['USE_QUANTITY'] != $use_quantity) {
            $GLOBALS['CACHE_MANAGER']->ClearByTag("iblock_id_".$id);
        }
    }

    $already_catalog = $catalogList;

}//if($action)

?>




<?
$aTabs = array(
	array("DIV" => "edit1", "TAB" => GetMessage("YEN_TAB_1_NAME"), "TITLE" => GetMessage("YEN_TAB_1"))
	
);

$tabControl = new CAdminTabControl("tabControl", $aTabs);
?>
<?
$tabControl->Begin();
?>

<?$tabControl->BeginNextTab();?>
<tr><td>
<form method="POST" action="<?=$APPLICATION->GetCurUri()?>">
    <input type="hidden" value="Y" name="action"/>
    <table width="100%" cellspacing="0" cellpadding="0" border="0" class="internal">
            <tr class="heading">
                    <td width="70%" valign="top"><?=GetMessage("YEN_IBLOCK_SELECT_NAME")?></td>
                    <td valign="top"><?=GetMessage("YEN_IBLOCK_SELECT")?></td>
                    <td valign="top"><?=GetMessage("YEN_IBLOCK_USE_QUANTITY")?></td>
            </tr>
            <?
            $db_res = CIBlock::GetList(Array("iblock_type"=>"asc", "name"=>"asc"));
            while ($res = $db_res->GetNext())
            {
                if($res["IBLOCK_TYPE_ID"] == "yenisite_market")
                    continue;
                $quanityChecked = '';
                if(array_key_exists($res["ID"], $already_catalog)) {
                    $checked = 'checked="checked"';
                    if ($already_catalog[$res['ID']]['USE_QUANTITY'] > 0) {
                        $quanityChecked = 'checked="checked"';
                    }
                } else {
                    $checked = "";
                }

            ?>
                    <tr>
                            <td><?="[<a title='".GetMessage("CO_IB_TYPE_ALT")."' href='iblock_admin.php?type=".$res["IBLOCK_TYPE_ID"]."&lang=".LANGUAGE_ID."'>".$res["IBLOCK_TYPE_ID"]."</a>] <a title='".GetMessage("CO_IB_ELEM_ALT")."' href='iblock_element_admin.php?type=".$res["IBLOCK_TYPE_ID"]."&IBLOCK_ID=".$res["ID"]."&lang=".LANGUAGE_ID."&filter_section=-1&&filter=Y&set_filter=Y'>".$res["NAME"]."</a> (<a href='site_edit.php?LID=".$res["LID"]."&lang=".LANGUAGE_ID."' title='".GetMessage("CO_SITE_ALT")."'>".$res["LID"]."</a>)"?></td>
                            <td align="center"><input type="checkbox" name="IS_CATALOG_<?echo $res["ID"] ?>" <?=$checked?> value="Y" /></td>
                            <td align="center"><input type="checkbox" name="USE_QUANTITY_<?=$res['ID']?>" <?=$quanityChecked?> value="Y" /></td>
                    </tr>
            <?
            }
            ?>
                    
    </table>
    <br/>
    <input type="submit" name="Update" value="<?echo GetMessage("MAIN_SAVE")?>">
</form>
</td></tr>



<? $tabControl->End(); ?>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>