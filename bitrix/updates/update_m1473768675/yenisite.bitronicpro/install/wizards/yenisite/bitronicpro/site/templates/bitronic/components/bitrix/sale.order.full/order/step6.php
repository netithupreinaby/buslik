<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
								<?
								if ($arResult["PAY_SYSTEM"]["NEW_WINDOW"] == "Y")
								{
									?>
									<script language="JavaScript">
										window.open('<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?= $arResult["ORDER_ID"] ?>');
									</script>
									<?= str_replace("#LINK#", $arParams["PATH_TO_PAYMENT"]."?ORDER_ID=".$arResult["ORDER_ID"], GetMessage("STOF_ORDER_PAY_WIN")) ?>
									<?
								}
								else
								{
									if (strlen($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"])>0)
									{
										include($arResult["PAY_SYSTEM"]["PATH_TO_ACTION"]);
									}
								}
								?>

            	
                <div class="ord_menu">
                    <ul>
                        <li><a href="#"><?echo GetMessage("S1")?></a></li>
                        <li><a href="#"><?echo GetMessage("S2")?></a></li>						 
                        <li><a href="#"><?echo GetMessage("S3")?></a></li>                       
                        <li><a href="#"><?echo GetMessage("S5")?></a></li>
						<li><a href="#"><?echo GetMessage("S4")?></a></li>
                        <li><a href="#"><?echo GetMessage("S6")?></a></li>
                        <li class="active"><a href="#"><?echo GetMessage("S7")?></a></li>
                    </ul>
                </div><!--.ord_menu-->
                <h2><?echo GetMessage("VIB5")?></h2>
                <div class="order_accept">	

					<div class="order_content">
                    	<h3><?echo GetMessage("STOF_ORDER_PARAMS")?></h3>
						<?= str_replace("#ORDER_DATE#", $arResult["ORDER"]["DATE_INSERT_FORMATED"], str_replace("#ORDER_ID#", $arResult["ORDER_ID"], GetMessage("STOF_ORDER_CREATED_DESCR"))); ?> <a href="/personal/orders.php?ID=<?=$arResult["ORDER_ID"]?>"><?=GetMessage('GOTO')?></a><br /><br />
						<?= str_replace("#LINK#", $arParams["PATH_TO_PERSONAL"], GetMessage("STOF_ORDER_VIEW")) ?>
                    </div><!--.order_param-->	
                </div><!--.order_accept-->
		 

		<div class="sidebar" id="sideLeft">
        	&nbsp;
		</div><!-- .sidebar#sideLeft -->

