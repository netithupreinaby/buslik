<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


                <div class="ord_menu">
                    <ul>
                        <li><a href="#"><?echo GetMessage("S1")?></a></li>
                        <li class="active"><a href="#"><?echo GetMessage("S2")?></a></li>
                        <li><a href="#"><?echo GetMessage("S3")?></a></li>                       
                        <li><a href="#"><?echo GetMessage("S5")?></a></li>
						<li><a href="#"><?echo GetMessage("S4")?></a></li>
                        <li><a href="#"><?echo GetMessage("S6")?></a></li>
                        <li><a href="#"><?echo GetMessage("S7")?></a></li>
                    </ul>
                </div><!--.ord_menu-->
                <h2><?echo GetMessage("OF")?></h2>
                <div class="order_accept"><!--
                    <div class="order_param_descr">
						 ,    -    -  ,          ,   ,       .
                    </div>--><!--.order_param_descr-->
                    <div class="order_content">
                    	<p><?echo GetMessage("STOF_SELECT_PERS_TYPE")?></p>						
						
						<?
						foreach($arResult["PERSON_TYPE_INFO"] as $v)
						{
							?><p><input type="radio" class="radio" id="PERSON_TYPE_<?= $v["ID"] ?>" name="PERSON_TYPE" value="<?= $v["ID"] ?>"<?if ($v["CHECKED"]=="Y") echo " checked";?>> <label for="PERSON_TYPE_<?= $v["ID"] ?>"><?= $v["NAME"] ?></label></p><?
						}
						?>
						<input type="hidden" name="contButton" value="<?= GetMessage("SALE_CONTINUE")?> &gt;&gt;">
						<button class="button1"><?=GetMessage("SALE_CONTINUE")?></button>
						
                    </div><!--.order_param-->
               </div>
	