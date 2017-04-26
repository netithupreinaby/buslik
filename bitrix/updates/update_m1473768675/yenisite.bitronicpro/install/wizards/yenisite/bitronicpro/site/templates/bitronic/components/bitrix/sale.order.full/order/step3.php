<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


                <div class="ord_menu">
                    <ul>
                        <li><a href="#"><?echo GetMessage("S1")?></a></li>
                        <li><a href="#"><?echo GetMessage("S2")?></a></li>						 
                        <li><a href="#"><?echo GetMessage("S3")?></a></li>                       
                        <li  class="active"><a href="#"><?echo GetMessage("S5")?></a></li>
						<li><a href="#"><?echo GetMessage("S4")?></a></li>
                        <li><a href="#"><?echo GetMessage("S6")?></a></li>
                        <li><a href="#"><?echo GetMessage("S7")?></a></li>
                    </ul>
                </div><!--.ord_menu-->
                <h2><?echo GetMessage("VIB2")?></h2>
                <div class="order_accept">
                    <div class="order_param_descr">
                    	<?echo GetMessage("STOF_PRIVATE_NOTES")?>
                    </div><!--.order_param_descr-->
                    <div class="order_content">
                    	<p><?echo GetMessage("STOF_DELIVERY_NOTES")?></p>
						<?
				foreach ($arResult["DELIVERY"] as $delivery_id => $arDelivery)
				{
					if ($delivery_id !== 0 && intval($delivery_id) <= 0):?>
					<p class="auto_delivery">
						<?=$arDelivery["TITLE"]?>
						<?if (strlen($arDelivery["DESCRIPTION"]) > 0):?>
							<br /><?=nl2br($arDelivery["DESCRIPTION"])?><br />
						<?endif;?>
					</p>
						<?foreach ($arDelivery["PROFILES"] as $profile_id => $arProfile):?>
						<div class="auto_delivery_profile">
							<input type="radio" class="radio" id="ID_DELIVERY_<?=$delivery_id?>_<?=$profile_id?>" name="<?=$arProfile["FIELD_NAME"]?>" value="<?=$delivery_id.":".$profile_id;?>" <?=$arProfile["CHECKED"] == "Y" ? "checked=\"checked\"" : "";?> />
							<label for="ID_DELIVERY_<?=$delivery_id?>_<?=$profile_id?>">
								<small><b><?=$arProfile["TITLE"]?></b><?if (strlen($arProfile["DESCRIPTION"]) > 0):?><br />
								<?=nl2br($arProfile["DESCRIPTION"])?><?endif;?></small>
							</label>
							<?
								$APPLICATION->IncludeComponent('bitrix:sale.ajax.delivery.calculator', '', array(
									"NO_AJAX" => $arParams["SHOW_AJAX_DELIVERY_LINK"] == 'S' ? 'Y' : 'N',
									"DELIVERY" => $delivery_id,
									"PROFILE" => $profile_id,
									"ORDER_WEIGHT" => $arResult["ORDER_WEIGHT"],
									"ORDER_PRICE" => $arResult["ORDER_PRICE"],
									"LOCATION_TO" => $arResult["DELIVERY_LOCATION"],
									"LOCATION_ZIP" => $arResult['DELIVERY_LOCATION_ZIP'],
									"CURRENCY" => $arResult["BASE_LANG_CURRENCY"],
								));
							?>
							<?if ($arParams["SHOW_AJAX_DELIVERY_LINK"] == 'N'):?>
							<script type="text/javascript">deliveryCalcProceed({STEP:1,DELIVERY:'<?=CUtil::JSEscape($delivery_id)?>',PROFILE:'<?=CUtil::JSEscape($profile_id)?>',WEIGHT:'<?=CUtil::JSEscape($arResult["ORDER_WEIGHT"])?>',PRICE:'<?=CUtil::JSEscape($arResult["ORDER_PRICE"])?>',LOCATION:'<?=intval($arResult["DELIVERY_LOCATION"])?>',CURRENCY:'<?=CUtil::JSEscape($arResult["BASE_LANG_CURRENCY"])?>'})</script>
							<?endif;?>
						</div>
						<?endforeach;?>
					<?else:?>
						<p><input type="radio" class="radio" id="ID_DELIVERY_ID_<?= $arDelivery["ID"] ?>" name="<?=$arDelivery["FIELD_NAME"]?>" value="<?= $arDelivery["ID"] ?>"<?if ($arDelivery["CHECKED"]=="Y") echo " checked";?>>
						<label for="ID_DELIVERY_ID_<?= $arDelivery["ID"] ?>"><?= $arDelivery["NAME"] ?></label></p>
					<?endif;?>
				
			<?	} // endforeach
			?>
			
						<?if(!($arResult["SKIP_FIRST_STEP"] == "Y" && $arResult["SKIP_SECOND_STEP"] == "Y"))
						{
							?>
							<button  onclick="$('#backButton').attr('value','Y');" class="button1"><?echo GetMessage("SALE_BACK_BUTTON")?></button>
							<input type="hidden" id="backButton" name="backButton" value="">
							<?
						}
						?>
			
						<input type="hidden" id="contButton" name="contButton" value="">
						<button  onclick="$('#contButton').attr('value','Y');" class="button1"><?= GetMessage("SALE_CONTINUE")?></button>
				
				
				
                    </div><!--.order_param-->
               </div>

