<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
                <div class="ord_menu">
                    <ul>
                        <li><a href="#"><?echo GetMessage("S1")?></a></li>
                        <li><a href="#"><?echo GetMessage("S2")?></a></li>						 
                        <li><a href="#"><?echo GetMessage("S3")?></a></li>                       
                        <li><a href="#"><?echo GetMessage("S5")?></a></li>
						<li><a href="#"><?echo GetMessage("S4")?></a></li>
                        <li class="active"><a href="#"><?echo GetMessage("S6")?></a></li>
                        <li><a href="#"><?echo GetMessage("S7")?></a></li>
                    </ul>
                </div><!--.ord_menu-->
                <h2><?echo GetMessage("VIB4")?></h2>
                <div class="order_accept">				
                	<h3><?echo GetMessage("STOF_ORDER_PARAMS")?></h3>
                    <div class="order_param_descr">
                    	<?echo GetMessage("STOF_CORRECT_PROMT_NOTE")?><br /><br />						
						<?echo GetMessage("STOF_CORRECT_ADDRESS_NOTE")?><br /><br />
					</div><!--.order_param_descr-->
                    <div class="order_param">
                    	<h4><?echo GetMessage("PAYER")?></h4>
                        <table>
						
							<?
							foreach($arResult["ORDER_PROPS_PRINT"] as $arProperties)
							{								
								if(strLen($arProperties["VALUE_FORMATED"])>0)
								{
									?>
									<tr>
										<td class="tl">
											<?= $arProperties["NAME"] ?>:
										</td>
										<td><?=$arProperties["VALUE_FORMATED"]?></td>
									</tr>
									<?
								}
							}
						?>
						
                        </table>
                    	<h4><?echo GetMessage("PD")?></h4>
                        <table>
                        	<tr>
                            	<td class="tl"><?echo GetMessage("DELIVERY")?>:</td>
                            	<td>					
								<?
								if (is_array($arResult["DELIVERY"]))
								{
									echo $arResult["DELIVERY"]["NAME"];
									if (is_array($arResult["DELIVERY_ID"]))
									{
										echo " (".$arResult["DELIVERY"]["PROFILES"][$arResult["DELIVERY_PROFILE"]]["TITLE"].")";
									}
								}
								elseif ($arResult["DELIVERY"]=="ERROR")
								{
									echo ShowError(GetMessage("SALE_ERROR_DELIVERY"));
								}
								else
								{
									echo GetMessage("SALE_NO_DELIVERY");
								}
								?>
					
							</td>
                            </tr>
                        	<tr>
                            	<td class="tl"><?echo GetMessage("PAYMENT")?>:</td>
                            	<td>
								<?
								if (is_array($arResult["PAY_SYSTEM"]))
								{
									echo $arResult["PAY_SYSTEM"]["PSA_NAME"];
								}
								elseif ($arResult["PAY_SYSTEM"]=="ERROR")
								{
									echo ShowError(GetMessage("SALE_ERROR_PAY_SYS"));
								}
								elseif($arResult["PAYED_FROM_ACCOUNT"] != "Y")
								{
									echo GetMessage("STOF_NOT_SET");
								}
								if($arResult["PAYED_FROM_ACCOUNT"] == "Y")
									echo " (".GetMessage("STOF_PAYED_FROM_ACCOUNT").")";
								?>			
								</td>
                            </tr>
                        </table>
                    </div><!--.order_param-->
                    <div style="clear:both;"></div>
                	<h3><?= GetMessage("SALE_ORDER_CONTENT")?></h3>
                    <div class="order_cont">
                    	<table>
                        	<tr>
							
								<th><?echo GetMessage("SALE_CONTENT_NAME")?></th>								

								<th><?echo GetMessage("SALE_CONTENT_QUANTITY")?></th>
								<th><?echo GetMessage("SALE_CONTENT_PRICE")?></th>

                            </tr>
							<? foreach($arResult["BASKET_ITEMS"] as $arBasketItems)
							{?>
								<tr>
									<td><a href="<?=$arBasketItems["DETAIL_PAGE_URL"]?>"><?=$arBasketItems["NAME"]?></a></td>
									<td><?=$arBasketItems["QUANTITY"]?></td>
									<td>
									<?=str_replace(GetMessage('RUB_REPLACE'), '<span class="rubl">'.GetMessage('RUB').'</span>',  $arBasketItems["PRICE_FORMATED"])?>
									</td>
								</tr>
							<?
							}
							?>                            
                            <tr class="sum">
                            	<td><?=GetMessage("ITOGO")?>:</td>
                            	<td></td>
                            	<td>
									<?=str_replace(GetMessage('RUB_REPLACE'), '<span class="rubl">'.GetMessage('RUB').'</span>',  $arResult["ORDER_PRICE_FORMATED"])?>
									
								</td>
                            </tr>
                        </table>
                    </div><!--.order_cont-->
                	<h3><?= GetMessage("SALE_ADDIT_INFO_PROMT")?>:</h3>
                    <div class="dop_info">
                    	<!--<p>       :</p>
                        <span class="textarea_info">(  250 )</span>
                        <textarea class="dop_info_t"></textarea>-->
						<textarea class="dop_info_t" name="ORDER_DESCRIPTION"><?=$arResult["ORDER_DESCRIPTION"]?></textarea>
                    </div><!--.dop_info-->
                    <div class="form-submit">
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
                    </div><!--.form-submit-->
                </div><!--.order_accept-->
	


		

