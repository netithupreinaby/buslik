<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
use Bitrix\Sale\DiscountCouponsManager;

if (!empty($arResult["ERROR_MESSAGE"]))
	ShowError($arResult["ERROR_MESSAGE"]);

$bDelayColumn  = false;
$bDeleteColumn = false;
$bWeightColumn = false;
$bPropsColumn  = false;
$bPriceType    = false;

if ($normalCount > 0):



?>
<?//printr(json_encode($arResult))?>
<section class="cart-body">
<div id="basket_items_list">
		<table class="table" id="basket_items">
			<thead>
				<tr>
					<!--<th id="col_DELAY" class="testblock"></th>
					<th id="col_DISCOUNT" colspan="2">Товары (<span><?=$normalCount;?></span>)</th>
					<th id="col_QUANTITY">Количество</th>
					<th id="col_DELETE" >Цена за 1шт.</th>
					<th id="col_SUM">Сумма</th>
					<th></th>
					<th class="testblock"></th>-->
					<?
					$bDelayColumn  = true;
					$bDeleteColumn = true;
					$bPropsColumn = true;
					?>
					
					<?
					$j = 0; 
					$arrayTitle = array(
									"Товары (<span>".$normalCount."</span>)",
									"Количество",
									"Цена за 1шт.",
									"Сумма",
								);?>
					
					<th class="testblock"></th>
					<?
					foreach ($arResult["GRID"]["HEADERS_MY"] as $id => $arHeader){
						$arHeader["name"] = (isset($arHeader["name"]) ? (string)$arHeader["name"] : '');
						if ($arHeader["name"] == '')
							$arHeader["name"] = GetMessage("SALE_".$arHeader["id"]);
						$arHeaders[] = $arHeader["id"];

						// remember which values should be shown not in the separate columns, but inside other columns
						if (in_array($arHeader["id"], array("TYPE")))
						{
							$bPriceType = true;
							continue;
						}
						elseif ($arHeader["id"] == "PROPS")
						{
							$bPropsColumn = true;
							continue;
						}
						elseif ($arHeader["id"] == "DELAY")
						{
							$bDelayColumn = true;
							continue;
						}
						elseif ($arHeader["id"] == "DELETE")
						{
							$bDeleteColumn = true;
							continue;
						}
						elseif ($arHeader["id"] == "WEIGHT")
						{
							$bWeightColumn = true;
						}

						if ($arHeader["id"] == "NAME"):
						?>
							<th <?if($j>4){?>style='display:none;'<?}?> class="item" colspan="2" id="col_<?=$arHeader["id"];?>">
						<?
						elseif ($arHeader["id"] == "PRICE"):
						?>
							<th <?if($j>4){?>style='display:none;'<?}?> class="price" id="col_<?=$arHeader["id"];?>">
						<?
						else:
						?>
							<th <?if($j>4){?>style='display:none;'<?}?> class="custom" id="col_<?=$arHeader["id"];?>">
						<?
						endif;
						?>
							<?=$arrayTitle[$j]; ?>
							<?$j++;?>
							</th>
							<?if($j==6){?>

							<?}?>
						
					<?}
					
					if ($bDeleteColumn || $bDelayColumn):
					?>
						<th class="testblock"></th>
					<?
					endif;
					?>
				</tr>
			</thead>

			<tbody>
				<?
				foreach ($arResult["GRID"]["ROWS"] as $k => $arItem){

				$notAvailable = false;
				$delayANDcanBUY = false;
				$delay = false;
				
				if (isset($arItem["NOT_AVAILABLE"]) && $arItem["NOT_AVAILABLE"] == true){
				$notAvailable = true;
				}
				/*if ($arItem["DELAY"] == "Y" && $arItem["CAN_BUY"] == "Y"){
				//$delayANDcanBUY = true;
				$delayANDcanBUY = false;
				}*/
				if($arItem["DELAY"] == "Y"){
				$delay = true;
				}
				
				if($notAvailable==true || $delayANDcanBUY == true || $delay == true){
					$showClassName = true;
				}

				//TODO Remove when configure delay items functionality
				//$showClassName = true;

				?>
				
							<tr id="<?=$arItem["ID"]?>" class="table-line <?if($showClassName==true){echo('out-of-stock');}?>">
                                <td class="testblock <?if($showClassName==true){echo('out-of-stock');}?>"></td>
                                    <td class="table-img">
											<?
											if (strlen($arItem["PREVIEW_PICTURE_SRC"]) > 0):
												$url = $arItem["PREVIEW_PICTURE_SRC"];
											elseif (strlen($arItem["DETAIL_PICTURE_SRC"]) > 0):
												$url = $arItem["DETAIL_PICTURE_SRC"];
											else:
												$url = $templateFolder."/images/no_photo.png";
											endif;
											?>

											<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?><a href="<?=$arItem["DETAIL_PAGE_URL"] ?>"><?endif;?>
												<img src="<?=$url?>" alt="">
											<?if (strlen($arItem["DETAIL_PAGE_URL"]) > 0):?></a><?endif;?>
										<?/*
										if (!empty($arItem["BRAND"])):
										?>
											<img alt="" src="<?=$arItem["BRAND"]?>" />
										<?
										endif;*/
										?>
									</td>
                                    <td class="table-item" colspan=2>
                                        <div class="">
                                           <p class="product-name"><?=$arItem["NAME"]?></p>
											<?php if (!empty($arItem['COLUMN_PROPERTIES']['IDTORGOVOYMARKIFILTR_VALUE']) || !empty($arItem['COLUMN_PROPERTIES']['CML2_ARTICLE_VALUE'])) { ?>
                                            <p class="item-brand">
												<?php if (!empty($arItem['COLUMN_PROPERTIES']['IDTORGOVOYMARKIFILTR_VALUE'])) { ?>
												<?$manufacturer = CommonMethods::getObjectByXmlId($arItem['COLUMN_PROPERTIES']['IDTORGOVOYMARKIFILTR_VALUE']);?>
                                                <a href="<?php echo $manufacturer['DETAIL_PAGE_URL']; ?>" class="brand-name"><?php echo $manufacturer['NAME']; ?></a>
												<?php }?>
												<?php if (!empty($arItem['COLUMN_PROPERTIES']['CML2_ARTICLE_VALUE'])) { ?>
                                                <span class="iten-number">Артикул: <?php echo $arItem['COLUMN_PROPERTIES']['CML2_ARTICLE_VALUE']; ?></span>
												<?php }?>
                                            </p>

											<?php if (!empty($arItem['PROPS'])) { ?>
											<p class="item-parameter">
												<?php foreach ($arItem['PROPS'] as $itemProperty) { ?>
													<?php if (!empty($itemProperty['VALUE'])) { ?>
													<?php echo $itemProperty['NAME'];?>: <span class="item-height"><?php echo $itemProperty['VALUE'];?></span>
													<?php } ?>
												<?php } ?>
											</p>
											<?php } ?>
											<?php } ?>
                                        </div>

										<div class="number clearfix">
										
											<?
													$ratio = isset($arItem["MEASURE_RATIO"]) ? $arItem["MEASURE_RATIO"] : 0;
													$max = isset($arItem["AVAILABLE_QUANTITY"]) ? "max=\"".$arItem["AVAILABLE_QUANTITY"]."\"" : "";
													$useFloatQuantity = ($arParams["QUANTITY_FLOAT"] == "Y") ? true : false;
													$useFloatQuantityJS = ($useFloatQuantity ? "true" : "false");

													?>
											
											<span class="counter-dec counter-button" onclick="setQuantity(<?=$arItem["ID"]?>, 1, 'down', <?=$useFloatQuantityJS?>);"></span>
													<input
														class="quantity"
														type="text"
														size="3"
														id="QUANTITY_INPUT_<?=$arItem["ID"]?>"
														name="QUANTITY_INPUT_<?=$arItem["ID"]?>"
														size="2"
														maxlength="18"
														min="0"
														<?=$max?>
														step="<?=$ratio?>"
														style="max-width: 50px"
														value="<?=$arItem["QUANTITY"]?>"
														data-stock="<?=$arItem["AVAILABLE_QUANTITY"]; ?>"
													>
											<span class="counter-inc counter-button" onclick="setQuantity(<?=$arItem["ID"]?>, 1, 'up', <?=$useFloatQuantityJS?>);" ></span>
											<input type="hidden" id="QUANTITY_<?=$arItem['ID']?>" name="QUANTITY_<?=$arItem['ID']?>" value="<?=$arItem["QUANTITY"]?>" />
										</div>
										
										<?if($showClassName==true){?>
										<p class="stock-message">Здесь будет какой-то поясняющий текст. Либо про наличие товара, его отсутствие. Либо про то, что товар закончился и нужно подождать.</p>
										<?}?>
									</td>
                                    <td class="table-price clearfix">
										
										<div id="current_price_<?=$arItem["ID"]?>"><?=$arItem["FULL_PRICE_FORMATED"]?></div>
										<?if (floatval($arItem["DISCOUNT_PRICE_PERCENT"]) > 0):?>
										<span class="discount" id="old_price_<?=$arItem["ID"]?>"><?=$arItem["DISCOUNT_PRICE_DIFF_FORMATED"]?></span>
										<?endif;?>

										<?if ($bPriceType && strlen($arItem["NOTES"]) > 0):?>
											<div class="type_price"><?=GetMessage("SALE_TYPE")?></div>
											<div class="type_price_value"><?=$arItem["NOTES"]?></div>
										<?endif;?>
										
                                    </td>
                                    <td class="table-total">
										<div id="sum_<?=$arItem["ID"]?>"><?=$arItem["SUM"]?></div> 
										
										<?php if (0) { ?>
                                        <div class="bonus-block">
                                            <span class="bonus">
                                                <img src="<?php echo SITE_TEMPLATE_PATH; ?>/static/img/icons/bonus.png" alt="">
                                            </span>
                                            <span class="bonus-count">10</span>
                                        </div>
										<?php } ?>
                                    </td>
                                   
									<?if ($bDelayColumn || $bDeleteColumn):
									?>
										<td class="table-buttons">
											<ul>
											<?
											if ($bDelayColumn):
											?>
												<li class="wishlist"><span class="tooltips"><a href="<?=str_replace("#ID#", $arItem["ID"], $arUrls["delay"])?>"><?=GetMessage("SALE_DELAY")?></a></span></li>
											<?
											endif;
											if ($bDeleteColumn):
											?>
												<li class="trash"><span class="tooltips"><a href="<?=str_replace("#ID#", $arItem["ID"], $arUrls["delete"])?>"><?=GetMessage("SALE_DELETE")?></a></span></li>
											<?
											endif;
											?>
											</ul>
											<?if($showClassName==true) { ?>
											<div class="wait-list clearfix" data-href="<?=str_replace("#ID#", $arItem["ID"], $arUrls["delay"])?>">
												<img src="<?php echo SITE_TEMPLATE_PATH; ?>/static/img/icons/watch.png" alt="">
												<p>Положить в ожидание</p>
											</div>
											<?php } ?>
										</td>
									<?
									endif;?>
                                    <td class="testblock <?if($showClassName==true){echo('out-of-stock');}?>"></td>
                                </tr>
                                
				<?}?>
			</tbody>
		</table>
		 <div class="cart-foot">
			<div class="cart-summary-block">
				<div class="row">
					<div class="overall col-sm-10 col-xs-8"><?echo GetMessage('SALE_TOTAL')?></div>
					<div class="cart-summary col-sm-2 col-xs-4" id="allSum_FORMATED"><?=$arResult['allSum_FORMATED']?></div>
				</div>
			</div>

			<div class="cart-banner">
				<p><?echo GetMessage('SALE_BOXING')?></p>
			</div>
		</div>
		
	<input type="hidden" id="column_headers" value="<?=CUtil::JSEscape(implode($arHeaders, ","))?>" />
	<input type="hidden" id="offers_props" value="<?=CUtil::JSEscape(implode($arParams["OFFERS_PROPS"], ","))?>" />
	<input type="hidden" id="action_var" value="<?=CUtil::JSEscape($arParams["ACTION_VARIABLE"])?>" />
	<input type="hidden" id="quantity_float" value="<?=$arParams["QUANTITY_FLOAT"]?>" />
	<input type="hidden" id="count_discount_4_all_quantity" value="<?=($arParams["COUNT_DISCOUNT_4_ALL_QUANTITY"] == "Y") ? "Y" : "N"?>" />
	<input type="hidden" id="price_vat_show_value" value="<?=($arParams["PRICE_VAT_SHOW_VALUE"] == "Y") ? "Y" : "N"?>" />
	<input type="hidden" id="hide_coupon" value="<?=($arParams["HIDE_COUPON"] == "Y") ? "Y" : "N"?>" />
	<input type="hidden" id="use_prepayment" value="<?=($arParams["USE_PREPAYMENT"] == "Y") ? "Y" : "N"?>" />
	<input type="hidden" id="auto_calculation" value="<?=($arParams["AUTO_CALCULATION"] == "N") ? "N" : "Y"?>" />
	
	
</div>
 </section>
 <section class="cart-delivery">
	<div class="row">
		<div class="col-lg-8 col-sm-6">
			<div class="row">
					<?
					if ($arParams["HIDE_COUPON"] != "Y")
					{
					?>
						<div id="coupons_block" class="col-lg-6">
							<div class="promocode">
								<p class="h5">
									<?=GetMessage("STB_COUPON_PROMT")?>
								</p>
								<!-- PROMOCODE -->
								<p class="promocode-label"><?=GetMessage("STB_COUPON_TITLE")?></p>
								<div class="input-group">
								  <div class="form-group has-feedback has-clear">
									<input type="text" id="coupon" class="form-control" name="COUPON" value="" onchange="enterCoupon();">
									<span class="form-control-clear glyphicon glyphicon-remove form-control-feedback hidden"></span>
								  </div>
								  <span class="input-group-btn">
									<a class="btn btn-primary" id="exampleButton1" href="javascript:void(0)" onclick="enterCoupon();" title="<?=GetMessage('SALE_COUPON_APPLY_TITLE'); ?>"><?=GetMessage('SALE_COUPON_APPLY'); ?></a>
								  </span>
								</div>
								<!-- PROMOCODE -->
							</div>
						</div>
							<?
							/*if (!empty($arResult['COUPON_LIST']))
							{
								foreach ($arResult['COUPON_LIST'] as $oneCoupon)
								{
									$couponClass = 'disabled';
									switch ($oneCoupon['STATUS'])
									{
										case DiscountCouponsManager::STATUS_NOT_FOUND:
										case DiscountCouponsManager::STATUS_FREEZE:
											$couponClass = 'bad';
											break;
										case DiscountCouponsManager::STATUS_APPLYED:
											$couponClass = 'good';
											break;
									}
									?>
									<div class="bx_ordercart_coupon">
										<input disabled readonly type="text" name="OLD_COUPON[]" value="<?=htmlspecialcharsbx($oneCoupon['COUPON']);?>" class="<? echo $couponClass; ?>">
										<span class="<? echo $couponClass; ?>" data-coupon="<? echo htmlspecialcharsbx($oneCoupon['COUPON']); ?>"></span>
										<div class="bx_ordercart_coupon_notes">
										<?if (isset($oneCoupon['CHECK_CODE_TEXT']))
										{
											echo (is_array($oneCoupon['CHECK_CODE_TEXT']) ? implode('<br>', $oneCoupon['CHECK_CODE_TEXT']) : $oneCoupon['CHECK_CODE_TEXT']);
										}
										?>
										</div></div><?
								}
								unset($couponClass, $oneCoupon);
							}*/
					}
					else
					{
						?>&nbsp;<?
					}
					?>

					<div class="col-lg-6">
                                            <p class="h5 clearfix">
                                                Способ доставки
                                                <?php /*<a class="more">Подробнее</a>*/?>
                                            </p>
											<?if($arResult["DELIVERY_TYPE"]){?>
                                            <ul id="delivery" class="radio-block">
												<?foreach($arResult["DELIVERY_TYPE"] as $key=>$arDType):?>
												<li>
													<?php
													switch($arDType['ID']){
														case "2": $deliveryChecked = ($_SESSION['DELIVERY_TYPE'] == 'pick_up') ? true : false;
															break;
														case "3": $deliveryChecked = ($_SESSION['DELIVERY_TYPE'] == 'delivery') ? true : false;
															break;
														default: $deliveryChecked = true;
													}
													?>
													<input type="radio" name="DELIVERY_TYPE" id="delivery-our_<?=$arDType["ID"]?>" value="<?=$arDType["ID"]?>" <?if(!$deliveryChecked):?>checked<?endif;?> />
                                                    <label data-id="<?=$arDType["ID"]?>" class="delivery" for="delivery-our_<?=$arDType["ID"]?>">
                                                        <p><?=$arDType["NAME"]?></p>
                                                    </label>													
                                                    <div class="notes clearfix">
														<?if(strlen($arDType['DESCRIPTION'])>0 && $arDType['ID']!='3'){?>
                                                        <div class="alert alert-info" role="alert">
                                                            <p style="margin none;  font-size:none; margin-left: 37px;" class="alert-text"><?=$arDType['DESCRIPTION']?></p>
                                                        </div>
														<?}?>
                                                        <?if($arDType['ID']=='2' && (float)$arResult['allSum_FORMATED'] <=30 ){?>
														<?php $deliveryNotEnouth = ' style=display: none;'; ?>
														<?}?>
														<?php if (0) { ?>
                                                        <div id="delivery-not-enough" class="alert alert-danger" role="alert"<?php echo $deliveryNotEnouth; ?>>
                                                            <p class="red-text">Суммы заказа не хватает для бесплатной доставки.</p>
                                                            <p class="alert-text">
                                                                Нужна бесплатная доставка? Можно добрать сумму товарами из корзины или выбрать новый товар из <a href="">Каталога</a>. Хотите сохранитьстоимость доставки? Кликните "Самовывоз" ниже и выберите оптимальный для Вас вариант.
                                                            </p>
                                                        </div>
														<?php } ?>
														<?if($arDType['ID']=='3'){?>
														<div class="alert alert-delivery" role="alert">
															<p style="margin none;  font-size:none; margin-left: 37px;" class="alert-text">Адрес самовывоза:  Бобруйск, ул. Ульяновская, 60 (ТЦ "Стрелец") <?php if (0) {?><a href="">Изменить</a><?php } ?></p>
														</div>
														<?}?>
                                                    </div>
                                                </li>
												<?endforeach;?>
                                            </ul>
											<?}?>
					</div>
			</div>
		</div>
		<?php $discount = ""; ?>
		<div class="col-lg-4 col-sm-6">
					<div class="delivery-discount">
						<table>
							<?php if(0) { ?>
							<tr>
								<td class="delivery-discount-label"><p>Скидка по промокоду:</p></td>
								<td class="delivery-discount-value"><p>20.00 руб.</p></td>
							</tr>
							<?php } ?>
							<?php if ($arResult) {?>
							<tr>
								<td class="delivery-discount-label"><p>Стоимость доставки:</p></td>
								<td class="delivery-discount-value"><p>5.00 руб.</p></td>
							</tr>
							<?php } ?>
							<?php if(0) { ?>
							<tr>
								<td class="delivery-discount-label"><p>Количество бонусов начисляемых за ваш заказ:</p></td>
								<td class="bonus-block" rowspan=2>
									<span class="bonus clearfix">
										<img src="<?php echo SITE_TEMPLATE_PATH; ?>/static/img/icons/bonus.png" alt="">
									</span>
									<span class="bonus-amount">14</span>
								</td>
							</tr>
							<?php } ?>
							<?php if (0) { ?>
							<tr>
								<td class="delivery-discount-label">
									<a id="bonusPayOpen" href="">Рассчитаться бонусами</a>
								</td>
							</tr>
							<tr id="bonusPayForm" style="display: none">
								<td colspan="2">
									<label>Сколько засчитать в стоимость заказа:</label>
									<input class="form-control" type="text" name="bonusCost">
									<input type="submit" name="bonusSubmit">
								</td>
							</tr>
							<?php } ?>
						</table>
						<div class="summary-row clearfix">
							<div class="summary-label fls"><p><?=GetMessage("SALE_TOTAL")?></p></div>
							<div class="summary-amount fls"><p id="allSum_order_FORMATED"><?=str_replace(" ", "&nbsp;", $arResult["allSum_FORMATED"])?></p></div>
						</div>
					</div>
					<?if(false){?>
						<div>
						<div class="bx_ordercart_order_pay_right">
							<table class="bx_ordercart_order_sum">
								<?if ($bWeightColumn && floatval($arResult['allWeight']) > 0):?>
									<tr>
										<td class="custom_t1"><?=GetMessage("SALE_TOTAL_WEIGHT")?></td>
										<td class="custom_t2" id="allWeight_FORMATED"><?=$arResult["allWeight_FORMATED"]?>
										</td>
									</tr>
								<?endif;?>
								<?if ($arParams["PRICE_VAT_SHOW_VALUE"] == "Y"):?>
									<tr>
										<td><?echo GetMessage('SALE_VAT_EXCLUDED')?></td>
										<td id="allSum_wVAT_FORMATED"><?=$arResult["allSum_wVAT_FORMATED"]?></td>
									</tr>
								<?if (floatval($arResult["DISCOUNT_PRICE_ALL"]) > 0):?>
									<tr>
										<td class="custom_t1"></td>
										<td class="custom_t2" style="text-decoration:line-through; color:#828282;" id="PRICE_WITHOUT_DISCOUNT">
											<?=$arResult["PRICE_WITHOUT_DISCOUNT"]?>
										</td>
									</tr>
								<?endif;?>
								<?
								if (floatval($arResult['allVATSum']) > 0):
									?>
									<tr>
										<td><?echo GetMessage('SALE_VAT')?></td>
										<td id="allVATSum_FORMATED"><?=$arResult["allVATSum_FORMATED"]?></td>
									</tr>
									<?
								endif;
								?>
								<?endif;?>
									<tr>
										<td class="fwb"><?=GetMessage("SALE_TOTAL")?></td>
										<td class="fwb" id="allSum_FORMATED"><?=str_replace(" ", "&nbsp;", $arResult["allSum_FORMATED"])?></td>
									</tr>


							</table>
							<div style="clear:both;"></div>
						</div>
						<div style="clear:both;"></div>
						<div class="bx_ordercart_order_pay_center">

							<?if ($arParams["USE_PREPAYMENT"] == "Y" && strlen($arResult["PREPAY_BUTTON"]) > 0):?>
								<?=$arResult["PREPAY_BUTTON"]?>
								<span><?=GetMessage("SALE_OR")?></span>
							<?endif;?>
							<?
							if ($arParams["AUTO_CALCULATION"] != "Y")
							{
								?>
								<a href="javascript:void(0)" onclick="updateBasket();" class="checkout refresh"><?=GetMessage("SALE_REFRESH")?></a>
								<?
							}
							?>
							<a href="javascript:void(0)" onclick="checkOut();" class="checkout"><?=GetMessage("SALE_ORDER")?></a>
						</div>
						</div>
					<?}?>
		</div>
	
		<!--col-lg-4 col-sm-6-->
	</div>
	<div class="delivery-buts">
		<div class="row">
			<div class="col-lg-3 col-sm-3 col-xs-3">
				<div class="submit">
					<a href="/catalog/" class="btn btn-default">Продолжить покупки</a>
				</div>
			</div>
			<div class="col-lg-3 col-lg-offset-6 col-sm-3 col-sm-offset-6 col-xs-3 col-xs-offset-6">
				<div class="reserve clearfix">
					<a href="javascript:void(0)" onclick="checkOut();" class="btn btn-danger"><?=GetMessage("SALE_ORDER")?></a>
					<a href="javascript:void(0)" class="btn btn-danger"><?=GetMessage("SALE_ORDER_RESERVE")?></a>
					<?php /*<a href="javascript:void(0)" onclick="updateBasket();"class="btn btn-danger"><?=GetMessage("SALE_REFRESH")?></a> */ ?>
				</div>
			</div>
		</div>
	</div>
</section>
<?
else:
?>

<section class="cart-body">
	<div id="basket_items_list">
		<div class="text-center mb30"><?=GetMessage("SALE_NO_ITEMS");?></div>
	</div>
</section>
<?
endif;
?>
