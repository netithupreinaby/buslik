<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?
//==================== edost START
  if (isset($arResult['edost']['javascript'])) echo $arResult['edost']['javascript'];
  if (isset($arResult['edost']['warning'])) echo $arResult['edost']['warning'].'<br>';
//==================== edost END
?>
<script type="text/javascript">
function fShowStore(id)
{
	//var strUrl = '/bitrix/components/bitrix/sale.order.ajax/templates/visual/map.php';
	var strUrl = '<?=SITE_TEMPLATE_PATH?>/components/bitrix/sale.order.ajax/bitronic_visual/map.php';
	var strUrlPost = 'delivery=' + id;

	var storeForm = new BX.CDialog({
		'title': '<?=GetMessage('SOA_ORDER_GIVE')?>',
		head: '',
		'content_url': strUrl,
		'content_post': strUrlPost,
		'width':700,
		'height':450,
		'resizable':false,
		'draggable':false
	});

	var button = [
			{
				title: '<?=GetMessage('SOA_POPUP_SAVE')?>',
				id: 'crmOk',
				'action': function ()
				{
					GetBuyerStore();
					BX.WindowManager.Get().Close();
				}
			},
			BX.CDialog.btnCancel
		];
	storeForm.ClearButtons();
	storeForm.SetButtons(button);
	storeForm.Show();
}

function GetBuyerStore()
{
	BX('BUYER_STORE').value = BX('POPUP_STORE_ID').value;
	//BX('ORDER_DESCRIPTION').value = '<?=GetMessage("SOA_ORDER_GIVE_TITLE")?>: '+BX('POPUP_STORE_NAME').value;
	BX('store_desc').innerHTML = BX('POPUP_STORE_NAME').value;
	BX.show(BX('select_store'));
}
</script>

<style><?// styles from EDOST template?>
#order_form_div .edost_div select { padding: 2px; margin: 0px; width: auto; }

#order_form_div .sale_order_table.delivery .prop img { border: 1px solid #D1D1D1; padding: 4px; }
#order_form_div .sale_order_table.delivery input[type="radio"]:checked + label img { border: 4px solid #00AAFF; padding: 1px; }
#order_form_div .sale_order_table.delivery label:hover img, #order_form_div .sale_order_table.delivery #order_form_div div.logo.selected img { border: 4px solid #AAE2FF; padding: 1px; }
</style>
<input type="hidden" name="BUYER_STORE" id="BUYER_STORE" value="<?=$arResult["BUYER_STORE"]?>" />
<div class="section">
<?
if(!empty($arResult["DELIVERY"]))
{
	?>
	<div class="title"><?=GetMessage("SOA_TEMPL_DELIVERY")?></div>

	<table class="sale_order_table delivery">
		<?
		foreach ($arResult["DELIVERY"] as $delivery_id => $arDelivery)
		{
			if ($delivery_id !== 0 && intval($delivery_id) <= 0)
			{
				foreach ($arDelivery["PROFILES"] as $profile_id => $arProfile)
				{
					$s = 'padding-top: 12px;';
					if ($delivery_id == 'edost' && !(isset($arProfile['office']) || (isset($arProfile["DESCRIPTION"]) && $arProfile["DESCRIPTION"] != ''))) $s = 'padding-top: 24px;';
					?>
					<tr>
						<td class="prop">
							<input type="radio" id="ID_DELIVERY_<?=$delivery_id?>_<?=$profile_id?>" name="<?=$arProfile["FIELD_NAME"]?>" value="<?=$delivery_id.":".$profile_id;?>" <?=$arProfile["CHECKED"] == "Y" ? "checked=\"checked\"" : "";?> onclick="submitForm();" />
							<label for="ID_DELIVERY_<?=$delivery_id?>_<?=$profile_id?>">
								<? if ($delivery_id == 'edost') { ?>
<? /*
									<img src="/bitrix/images/delivery_edost_img/<?= ceil($profile_id / 2)?>.gif" width="60" height="32" border="0">
*/ ?>
									<img src="/bitrix/images/delivery_edost_img/big/<?= ceil($profile_id / 2)?>.gif" width="95" height="55" border="0">
								<? } else if (count($arDelivery["LOGOTIP"]) > 0) { ?>
									<?=CFile::ShowImage($arDelivery["LOGOTIP"], 95, 55, "border=0", "", false)?>
								<? } else {?>
									<img src="/bitrix/components/bitrix/sale.order.ajax/templates/visual/images/logo-default-d.gif" alt="" />

								<? } ?>
								<div class="name" style="width: 700px; vertical-align: middle; <?=$s?>">
										<p style="display: inline; line-height: normal; padding: 0px; vertical-align: middle;">
										<b><?=($delivery_id == 'edost' ? '' : $arDelivery["TITLE"].' - ')?><?=$arProfile["TITLE"]?></b><?=($arProfile['day'] != '' ? ', '.$arProfile['day'] : '')?><?=(isset($arProfile['price']) && $arProfile['price'] != '' ? ' - <b>'.$arProfile['price'].'</b>' : '')?>

										<? if ($delivery_id != 'edost') { ?><br><?
											$APPLICATION->IncludeComponent('bitrix:sale.ajax.delivery.calculator', '', array(
												"NO_AJAX" => $arParams["DELIVERY_NO_AJAX"],
												"DELIVERY" => $delivery_id,
												"PROFILE" => $profile_id,
												"ORDER_WEIGHT" => $arResult["ORDER_WEIGHT"],
												"ORDER_PRICE" => $arResult["ORDER_PRICE"],
												"LOCATION_TO" => $arResult["USER_VALS"]["DELIVERY_LOCATION"],
												"LOCATION_ZIP" => $arResult["USER_VALS"]["DELIVERY_LOCATION_ZIP"],
												"CURRENCY" => $arResult["BASE_LANG_CURRENCY"],
											), null, array('HIDE_ICONS' => 'Y'));

										} ?>
										</p>
								</div>
							</label>
				            <?=(isset($arProfile['office']) ? '<div class="edost_div">'.$arProfile['office'].'</div>' : '')?>
							<div class="desc">
								<? if (strlen($arProfile["DESCRIPTION"]) > 0) { ?>
									<?=nl2br($arProfile["DESCRIPTION"])?>
								<? } else if ($delivery_id != 'edost') { ?>
									<?=nl2br($arDelivery["DESCRIPTION"])?>
								<? } ?>
							</div>

						</td>
					</tr>
					<?
				} // endforeach
			}
			else
			{
				?>
				<tr>
					<td class="prop" colspan="2">
						<input type="radio" id="ID_DELIVERY_ID_<?= $arDelivery["ID"] ?>" name="<?=$arDelivery["FIELD_NAME"]?>" value="<?= $arDelivery["ID"] ?>"<?if ($arDelivery["CHECKED"]=="Y") echo " checked";?> onclick="submitForm();">
						<label for="ID_DELIVERY_ID_<?= $arDelivery["ID"] ?>" <?=(count($arDelivery["STORE"]) > 0)? 'onClick="fShowStore(\''.$arDelivery["ID"].'\');"':"";?> >
							<?if (count($arDelivery["LOGOTIP"]) > 0):?>
								<?=CFile::ShowImage($arDelivery["LOGOTIP"], 95, 55, "border=0", "", false);?>
							<?else:?>
								<img src="/bitrix/components/bitrix/sale.order.ajax/templates/visual/images/logo-default-d.gif" alt="" />
							<?endif;?>
							<div class="desc">
								<div class="name"><?= $arDelivery["NAME"] ?></div>
								<div class="desc">
								<?
								if (strlen($arDelivery["PERIOD_TEXT"])>0)
								{
									echo $arDelivery["PERIOD_TEXT"];
									?><br /><?
								}
								?>
								<?=GetMessage("SALE_DELIV_PRICE");?> <?=$arDelivery["PRICE_FORMATED"]?><br />
								<?
								if (strlen($arDelivery["DESCRIPTION"])>0)
								{
									echo $arDelivery["DESCRIPTION"]."<br />";
								}
								?>
								</div>
								<?if (count($arDelivery["STORE"]) > 0):?>
									<span id="select_store"<?if(strlen($arResult["STORE_LIST"][$arResult["BUYER_STORE"]]["TITLE"]) <= 0) echo " style=\"display:none;\"";?>>
										<span class="select_store"><?=GetMessage('SOA_ORDER_GIVE_TITLE');?>: </span>
										<span class="ora-store" id="store_desc"><?=htmlspecialcharsbx($arResult["STORE_LIST"][$arResult["BUYER_STORE"]]["TITLE"])?></span>
									</span>
								<?endif;?>
							</div>
						</label>
						<div class="clear"></div>
					</td>
				</tr>
				<?
			}
		}
		?>
	</table>
	<?
}
?>
</div>
<?
//==================== edost START
  if (isset($arResult['edost']['javascript2'])) echo $arResult['edost']['javascript2'];
//==================== edost END
?>