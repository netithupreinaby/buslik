<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


                <div class="ord_menu">
                    <ul>
                        <li><a href="#"><?echo GetMessage("S1")?></a></li>
                        <li><a href="#"><?echo GetMessage("S2")?></a></li>						 
                        <li><a href="#"><?echo GetMessage("S3")?></a></li>                       
                        <li><a href="#"><?echo GetMessage("S5")?></a></li>
						<li class="active"><a href="#"><?echo GetMessage("S4")?></a></li>
                        <li><a href="#"><?echo GetMessage("S6")?></a></li>
                        <li><a href="#"><?echo GetMessage("S7")?></a></li>
                    </ul>
                </div><!--.ord_menu-->
                <h2><?echo GetMessage("VIB3")?></h2>
                <div class="order_accept">
                    <div class="order_param_descr">
                    	<?echo GetMessage("STOF_PRIVATE_NOTES")?>
                    </div><!--.order_param_descr-->
                    <div class="order_content">
                    	<p><?echo GetMessage("STOF_PAYMENT_NOTES")?></p>

						<?
				foreach($arResult["PAY_SYSTEM"] as $arPaySystem)
				{
					
				?>
						<p>
						<input type="radio" class="radio"  id="ID_PAY_SYSTEM_ID_<?= $arPaySystem["ID"] ?>" name="PAY_SYSTEM_ID" value="<?= $arPaySystem["ID"] ?>"<?if ($arPaySystem["CHECKED"]=="Y") echo " checked";?>>
						<label for="ID_PAY_SYSTEM_ID_<?= $arPaySystem["ID"] ?>"><?= $arPaySystem["PSA_NAME"] ?></label>	</p>				
					<?
					
				
				} // endforeach
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

				
				<br/>
				<br/>
				<br/>
                    </div><!--.order_param-->
               </div>


