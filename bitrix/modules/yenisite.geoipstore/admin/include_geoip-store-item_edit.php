<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($DOCUMENT_ROOT."/bitrix/modules/main/include/prolog_admin_after.php");

CModule::IncludeModule('catalog');
CModule::IncludeModule('sale');
CModule::IncludeModule('statistic');
CModule::IncludeModule("yenisite.geoipstore");

IncludeModuleLangFile(__FILE__);

global $USER;
if(!$USER->IsAdmin())
	return;

$id = htmlspecialcharsEx($_REQUEST["id"]);
$action = (!empty($_REQUEST["action"])) ? htmlspecialcharsEx($_REQUEST["action"]) : htmlspecialcharsEx($_REQUEST["action_button"]);
$name 	= htmlspecialcharsEx($_REQUEST["name"]);
$location_del_id = htmlspecialcharsEx($_REQUEST["location_delivery_id"]);
$domain_name = htmlspecialcharsEx($_REQUEST["domain_name"]);
$group_id = htmlspecialcharsEx($_REQUEST["group"]);
$site_id = htmlspecialcharsEx($_REQUEST["site"]);
$meta_title = htmlspecialcharsEx($_REQUEST["meta_title"]);
$meta_keywords = htmlspecialcharsEx($_REQUEST["meta_keywords"]);
$meta_description = htmlspecialcharsEx($_REQUEST["meta_description"]);

foreach($_REQUEST["location_stat_id"] as $sid)
{
	$locationStatId[] = htmlspecialcharsEx($sid);
}

foreach($_REQUEST["price_id"] as $price)
{
	$tmp = explode('-', htmlspecialcharsEx($price), 2);
	$tmpAr['ID'] = $tmp[0];
	$tmpAr['NAME'] = $tmp[1];
	$priceId[] = $tmpAr;
}

foreach($_REQUEST["store_id"] as $store)
{
	$storeId[] = htmlspecialcharsEx($store);
}

$apply = htmlspecialcharsEx($_REQUEST["apply"]);
$save = htmlspecialcharsEx($_REQUEST["save"]);

$arFields = array(
	'NAME' => $name,
	'LOCATION_ID_DELIVERY' => $location_del_id,
	'DOMAIN_NAME' => $domain_name,
	'GROUP_ID' => $group_id,
	'SITE_ID' => $site_id,
	'LOCATION_ID_STATISTIC' => $locationStatId,
	'PRICE_ID' => $priceId,
	'STORE_ID' => $storeId,
	'META_TITLE' => $meta_title,
	'META_KEYWORDS' => $meta_keywords,
	'META_DESCRIPTION' => $meta_description,
);

if($action == 'add' && ($apply || $save))
{
	CYSGeoIPStore::AddItem($arFields);
}

if($action == 'edit' && $id > 0 && ($apply || $save))
{
	CYSGeoIPStore::UpdateItem($id, $arFields);
}

if($save) Localredirect("/bitrix/admin/ys-geoip-store-items.php?lang=".LANG."");

$aMenu = array(
	array(
		"TEXT" => GetMessage("RECORDS_LIST"),
		"ICON" => "btn_list",
		"LINK" => "/bitrix/admin/ys-geoip-store-items.php?lang=".LANGUAGE_ID
	),
	array(
		"TEXT" => GetMessage("ADD_NEW"),
		"ICON" => "btn_new",
		"LINK" => "/bitrix/admin/ys-geoip-store-item_edit.php?lang=".LANGUAGE_ID."&action=add"
	)
);

$context = new CAdminContextMenu($aMenu);
$context->Show();

$aTabs = array(
	array("DIV" => "edit_common", "TAB" => GetMessage("PARAMS"), "ICON" => "catalog", "TITLE" => ""),
);

$tabControl = new CAdminTabControl("tabControl", $aTabs);
$tabControl->Begin();

$tabControl->BeginNextTab();
?>
<form method="POST">

<?if(!empty($id)):?>
	<?$item = CYSGeoIPStore::GetByID($id);?>
	<tr>
		<td class="column-name">ID:</td>
		<td class="column-value"><?echo $id;?></td>
		<input type="hidden" name="action" value="edit" />
	</tr>
<?else:?>
	<input type="hidden" name="action" value="add" />
<?endif;?>
	
	<tr>
		<td class="column-name"><?=GetMessage("NAME")?>:</td>
		<td class="column-value"><input type="text" value="<?=$item['NAME']?>" name="name"/></td>
	</tr>

	<tr>
		<td class="column-name"><?=GetMessage("LOCATION_DEL")?>:</td>
		<td class="column-value">
		<select name="location_delivery_id">
			<?
			$res = CSaleLocation::GetList(array("CITY_NAME_LANG" => "ASC"), array('CITY_LID' => LANGUAGE_ID));
			$arRes = CSaleLocation::GetList(array(), array('ID' => $item['LOCATION_ID_DELIVERY']))->Fetch();
			?>
			<?while($arLoc = $res->GetNext()):?>
				<?
				if ($arLoc['COUNTRY_LID'] != LANGUAGE_ID && !empty($arLoc['COUNTRY_LID'])) continue;
				if ($arLoc['REGION_LID'] != LANGUAGE_ID && !empty($arLoc['REGION_LID'])) continue;
				
				if($arRes['ID'] == $arLoc['ID'])
					$sel = 'selected="selected"';
				else
					$sel ='';
					
				$name = $arLoc['CITY_NAME'];
				$name .= (!empty($arLoc['REGION_NAME'])) ? ", {$arLoc['REGION_NAME']}": '';
				$name .= (!empty($arLoc['COUNTRY_NAME'])) ? ", {$arLoc['COUNTRY_NAME']}": '';
				?>
				<option type="text" value="<?=$arLoc['ID']?>" <?=$sel?>><?=$name?></option>
			<?endwhile?>
		</select>
		</td>
	</tr>

	<tr>
		<td class="column-name"><?=GetMessage("LOCATION_STAT")?>:</td>
		<td class="column-value">
		<select name="location_stat_id[]" multiple size="8">
			<?
			$res = CCity::GetList(array("CITY_NAME" => "ASC"));
			if(!empty($id))
			{
				$dbRes = CYSGeoIPStore::GetList('place', array(), array('ITEM_ID' => $id), array('LOCATION_ID_STATISTIC'));
				while($arLoc = $dbRes->Fetch())
				{
					$arLocs[] = $arLoc;
				}
			}
			?>
			<?while($arSt = $res->GetNext()):?>
				<?if(!empty($arSt['CITY_NAME'])):?>
					<?
					$sel ="";
					foreach($arLocs as $loc)
					{
						if(array_search($arSt['CITY_ID'], $loc) !== false)
						{
							$sel = 'selected="selected"';
							break;
						}
					}
					?>
					<option type="text" value="<?=$arSt['CITY_ID']?>" <?=$sel?>><?echo $arSt['CITY_NAME'].', '.$arSt['REGION_NAME']." ({$arSt['CITY_ID']})"?></option>
				<?endif?>
			<?endwhile?>
		</select>
		</td>
	</tr>

	<tr>
		<td class="column-name"><?=GetMessage("DOMAIN_NAME")?>:</td>
		<td class="column-value"><input type="text" value="<?=$item['DOMAIN_NAME']?>" name="domain_name"/></td>
	</tr>

	<tr>
		<td class="column-name"><?=GetMessage("PRICE")?>:</td>
		<td class="column-value">
		<select name="price_id[]" multiple size="4">
			<?
			$res = CCatalogGroup::GetList(array("ID" => "asc"), array());
			if(!empty($id))
			{
				$dbRes = CYSGeoIPStore::GetList('price', array(), array('ITEM_ID' => $id));
				while($arRes = $dbRes->Fetch())
				{
					$arPrices[] = $arRes;
				}
			}
			?>

			<?while($p = $res->GetNext()):?>
				<?
				$sel = "";
				foreach($arPrices as $price)
				{
					if(array_search($p['NAME'], $price) !== false)
					{
						$sel = 'selected="selected"';
						break;
					}
				}
				?>
				<option type="text" value="<?echo $p['ID'].'-'.$p["NAME"]?>" <?=$sel?>><?=$p["NAME"]?></option>
			<?endwhile?>
		</select>
		</td>
	</tr>
	
	<tr>
		<td class="column-name"><?=GetMessage("STORE")?>:</td>
		<td class="column-value">
		<select name="store_id[]" multiple size="4">
			<?
			$res = CCatalogStore::GetList();
			if(!empty($id))
			{
				$dbRes = CYSGeoIPStore::GetList('store', array(), array('ITEM_ID' => $id), array('STORE_ID'));
				while($arRes = $dbRes->Fetch())
				{
					$arStores[] = $arRes;
				}
			}
			?>
			<?while($p = $res->GetNext()):?>
				<?
				$sel = "";
				foreach($arStores as $store)
				{
					if(array_search($p['ID'], $store) !== false)
					{
						$sel = 'selected="selected"';
						break;
					}
				}
				?>
				<option type="text" value="<?=$p['ID']?>" <?=$sel?>><?=$p["TITLE"]?></option>
			<?endwhile?>
		</select>
		</td>
	</tr>

	<tr>
		<td class="column-name"><?=GetMessage("GROUP")?>:</td>
		<td class="column-value">
		<select name="group">
			<?$res = CGroup::GetList();?>
			<?while($group = $res->GetNext()):?>
				<?
				if($group['ID'] == $item['GROUP_ID'])
					$sel = 'selected="selected"';
				else
					$sel ='';
				?>
				<option type="text" value="<?=$group['ID']?>" <?=$sel?>><?=$group["NAME"]?></option>
			<?endwhile?>
		</select>
		</td>
	</tr>

	<tr>
		<td class="column-name"><?=GetMessage("SITE")?>:</td>
		<td class="column-value">
		<select name="site">
			<?$res = CSite::GetList();?>
			<?while($site = $res->GetNext()):?>
				<?
				if($site['ID'] === $item['SITE_ID'])
					$sel = 'selected="selected"';
				else
					$sel ='';
				?>
				<option type="text" value="<?=$site['ID']?>" <?=$sel?>><?=$site["ID"]?></option>
			<?endwhile?>
		</select>
		</td>
	</tr>
	<tr>
		<td class="column-name"><?=GetMessage("META_TITLE")?>:</td>
		<td class="column-value"><input type="text" value="<?=$item['META_TITLE']?>" name="meta_title"/></td>
	</tr>
	<tr>
		<td class="column-name"><?=GetMessage("META_KEYWORDS")?>:</td>
		<td class="column-value"><input type="text" value="<?=$item['META_KEYWORDS']?>" name="meta_keywords"/></td>
	</tr>
	<tr>
		<td class="column-name"><?=GetMessage("META_DESCRIPTION")?>:</td>
		<td class="column-value"><input type="text" value="<?=$item['META_DESCRIPTION']?>" name="meta_description"/></td>
	</tr>
<?
$tabControl->EndTab();
?>

<?
$tabControl->Buttons(
	array(
		"back_url" => "/bitrix/admin/ys-geoip-store-items.php?lang=".LANGUAGE_ID
	)
);
?>
</form>
<?
$tabControl->End();
?>