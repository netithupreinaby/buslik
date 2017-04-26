<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>

<div class="param_block">
<form method="GET" action="<?= $arResult["CURRENT_PAGE"] ?>" name="bfilter">
                <div class="form-items">
                	<label><?=GetMessage("SPOL_T_F_ID");?>:</label><input type="text" name="filter_id"  class="txt w80 mr" value="<?=htmlspecialchars($_REQUEST["filter_id"])?>"/> 
                	<label><?=GetMessage("SPOL_T_F_DATE");?>:</label>
					
<?$APPLICATION->IncludeComponent("bitrix:main.calendar", "calendar", Array(
	"SHOW_INPUT" => "Y",	//   
	"FORM_NAME" => "bfilter",	//  
	"INPUT_NAME" => "filter_date_from",	//    
	"INPUT_NAME_FINISH" => "filter_date_to",	//    
	"INPUT_VALUE" => $_REQUEST["filter_date_from"],	//    
	"INPUT_VALUE_FINISH" => $_REQUEST["filter_date_to"],	//    
	"SHOW_TIME" => "N",	//   
	),
	false
);?>

                <div class="form-items">
                </div><!--.form-items-->
                    <label><?=GetMessage("SPOL_T_F_STATUS")?>:</label>
					<select class="w80" name="filter_status">
						<option value=""><?=GetMessage("SPOL_T_F_ALL")?></option>
						<?
						foreach($arResult["INFO"]["STATUS"] as $val)
						{
							if ($val["ID"]!="F")
							{
								?><option value="<?echo $val["ID"]?>"<?if($_REQUEST["filter_status"]==$val["ID"]) echo " selected"?>>[<?=$val["ID"]?>] <?=$val["NAME"]?></option><?
							}
						}
						?>
					</select>
					
					
					
					
                 
                <label><?=GetMessage("SPOL_T_F_PAYED")?>:</label>
				<select class="w80" name="filter_payed">
					<option value=""><?echo GetMessage("SPOL_T_F_ALL")?></option>
					<option value="Y"<?if ($_REQUEST["filter_payed"]=="Y") echo " selected"?>><?=GetMessage("SPOL_T_YES")?></option>
					<option value="N"<?if ($_REQUEST["filter_payed"]=="N") echo " selected"?>><?=GetMessage("SPOL_T_NO")?></option>
				</select>
				
					
                    <label><?=GetMessage("SPOL_T_F_CANCELED")?>:</label>
					<select class="w80" name="filter_canceled">
						<option value=""><?=GetMessage("SPOL_T_F_ALL")?></option>
						<option value="Y"<?if ($_REQUEST["filter_canceled"]=="Y") echo " selected"?>><?=GetMessage("SPOL_T_YES")?></option>
						<option value="N"<?if ($_REQUEST["filter_canceled"]=="N") echo " selected"?>><?=GetMessage("SPOL_T_NO")?></option>
					</select>

                <label><?=GetMessage("SPOL_T_F_HISTORY")?>:</label>
					
				<select class="w80" name="filter_history">				
					<option value="N"<?if($_REQUEST["filter_history"]=="N") echo " selected"?>><?=GetMessage("SPOL_T_NO")?></option>
					<option value="Y"<?if($_REQUEST["filter_history"]=="Y") echo " selected"?>><?=GetMessage("SPOL_T_YES")?></option>
				</select>
                
                    </div><!--.form-items-->
                    <div class="form-submit">
<span class="submit">
<input id="set" type="hidden" name="filter" value="" />
<button onclick="$('#set').attr('value', 'Y');" class="button"><?=GetMessage("SPOL_T_F_SUBMIT")?></button>
</span>

<span class="submit">
<input id="del" type="hidden" name="del_filter" value="" />
<button onclick="$('#del').attr('value', 'Y');" class="button"><?=GetMessage("SPOL_T_F_DEL")?></button>
</span>


                    </div><!--.form-submit-->
                </form>
                </div><!--.param_block-->
                <table class="bills">
                	<tr>					
						<th><?=GetMessage("SPOL_T_ID")?></th>
						<th><?=GetMessage("SPOL_T_PRICE")?></th>
						<th><?=GetMessage("SPOL_T_STATUS")?></th>
						<th><?=GetMessage("SPOL_T_BASKET")?></th>
						<th><?=GetMessage("SPOL_T_PAYED")?></th>
						<th><?=GetMessage("SPOL_T_CANCELED")?></th>
						<th><?=GetMessage("SPOL_T_PAY_SYS")?></th>
						<th><?=GetMessage("SPOL_T_ACTION")?></th>
                    </tr>
					<?foreach($arResult["ORDERS"] as $val):?>

					<tr>
						<td><?=$val["ORDER"]["ACCOUNT_NUMBER"]?><span class="date"><?=GetMessage("SPOL_T_FROM")?> <?=$val["ORDER"]["DATE_INSERT_FORMAT"]?></span></td>
						<td>
						<?=str_replace(GetMessage('RUB_REPLACE'), '<span class="rubl">'.GetMessage('RUB').'</span>',  $val["ORDER"]["FORMATED_PRICE"]);?>
						</td>
						<td><?=$arResult["INFO"]["STATUS"][$val["ORDER"]["STATUS_ID"]]["NAME"]?><br /><?=$val["ORDER"]["DATE_STATUS"]?></td>
						<td><?
							$bNeedComa = False;
							foreach($val["BASKET_ITEMS"] as $vval)
							{
								?><h3 class='color-black'><?
								if (strlen($vval["DETAIL_PAGE_URL"])>0) 
									echo '<a href="'.$vval["DETAIL_PAGE_URL"].'">';
								echo $vval["NAME"];
								if (strlen($vval["DETAIL_PAGE_URL"])>0) 
									echo '</a>';
									echo ' - '.$vval["QUANTITY"].' '.GetMessage("STPOL_SHT");
								?></h3><?
							}
						?></td>
						<td><?=(($val["ORDER"]["PAYED"]=="Y") ? GetMessage("SPOL_T_YES") : GetMessage("SPOL_T_NO"))?></td>
						<td><?=(($val["ORDER"]["CANCELED"]=="Y") ? GetMessage("SPOL_T_YES") : GetMessage("SPOL_T_NO"))?></td>
						<td>
							<?=$arResult["INFO"]["PAY_SYSTEM"][$val["ORDER"]["PAY_SYSTEM_ID"]]["NAME"]?> / 
							<?if (strpos($val["ORDER"]["DELIVERY_ID"], ":") === false):?>
								<?=$arResult["INFO"]["DELIVERY"][$val["ORDER"]["DELIVERY_ID"]]["NAME"]?>
							<?else:
								$arId = explode(":", $val["ORDER"]["DELIVERY_ID"]);
							?>
								<?=$arResult["INFO"]["DELIVERY_HANDLERS"][$arId[0]]["NAME"]?> (<?=$arResult["INFO"]["DELIVERY_HANDLERS"][$arId[0]]["PROFILES"][$arId[1]]["TITLE"]?>)
							<?endif?>
						</td>
						<td>
						
						<ul class="act">
							<li><a title="<?=GetMessage("SPOL_T_DETAIL_DESCR")?>" href="<?=$val["ORDER"]["URL_TO_DETAIL"]?>"><?=GetMessage("SPOL_T_DETAIL")?></a></li>
							<li><a title="<?=GetMessage("SPOL_T_COPY_ORDER_DESCR")?>" href="<?=$val["ORDER"]["URL_TO_COPY"]?>"><?=GetMessage("SPOL_T_COPY_ORDER")?></a></li>
							<?if($val["ORDER"]["CAN_CANCEL"] == "Y"):?>
								<li><a title="<?=GetMessage("SPOL_T_DELETE_DESCR")?>" href="<?=$val["ORDER"]["URL_TO_CANCEL"]?>"><?=GetMessage("SPOL_T_DELETE")?></a></li>
							<?endif;?>
						</ul>
							
						</td>
					</tr>
				<?endforeach;?>
                </table>
