<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

              <?/*  <div class="ord_menu">
                    <ul>
                        <li class="active"><a href="#"><?echo GetMessage("S1")?></a></li>
                        <li><a href="#"><?echo GetMessage("S2")?></a></li>						 
                        <li><a href="#"><?echo GetMessage("S3")?></a></li>                       
                        <li><a href="#"><?echo GetMessage("S5")?></a></li>
						<li><a href="#"><?echo GetMessage("S4")?></a></li>
                        <li><a href="#"><?echo GetMessage("S6")?></a></li>
                        <li><a href="#"><?echo GetMessage("S7")?></a></li>
                    </ul>
                </div><!--.ord_menu-->
			*/?>
                <h2><?echo GetMessage("RL")?></h2>
				<div class="ordering">
                    <div class="req_block">
						<span class="req">*</span> &mdash; <?echo GetMessage("REQ")?>
					</div><!--.req_block-->
                    <div class="col">
                    	<h3><?echo GetMessage("LOGIN")?></h3>
                        <form method="post" action="<?= $arParams["PATH_TO_ORDER"] ?>" name="order_auth_form">
                            <?=bitrix_sessid_post()?>
                            <div class="form-item">
                                <label><?echo GetMessage("STOF_LOGIN")?>:</label>          
								<input class="txt w240" type="text" name="USER_LOGIN" maxlength="30" size="30" value="<?=$arResult["USER_LOGIN"]?>" />
                            </div><!--.form-item-->
                            <div class="form-item">
                                <label><?echo GetMessage("STOF_PASSWORD")?>:</label>
								<input  class="txt w240" type="password" name="USER_PASSWORD" maxlength="30" size="30" />                                
                            </div><!--.form-item-->
                            <div class="form-item">

								<a href="<?=$arParams["PATH_TO_AUTH"]?>?forgot_password=yes&amp;back_url=<?= urlencode($arParams["PATH_TO_ORDER"]); ?>"><?echo GetMessage("STOF_FORGET_PASSWORD")?></a>                               

                            </div><!--.form-item-->                          
                            <div class="form-submit">								
								<input type="hidden" name="do_authorize" value="Y">
                                <button class="button1"><?echo GetMessage("STOF_NEXT_STEP")?></button>
                            </div><!--.form-submit-->
                        </form>
                    </div><!--.col-->
                    <div class="col">
                    	<h3><?echo GetMessage("REGISTER")?></h3>
                        <form method="post" action="<?= $arParams["PATH_TO_ORDER"]?>" name="order_reg_form">
                            <?=bitrix_sessid_post()?>
							<div id="sof_choose_login">
								<div class="form-item">
									<label><?echo GetMessage("STOF_LOGIN")?><span class="req">*</span>:</label>                                
									<input  class="txt w240" type="text" name="NEW_LOGIN" maxlength="30" size="30" value="<?=$arResult["USER_LOGIN"]?>">
								</div><!--.form-item-->
								<div class="form-item">
									<label><?echo GetMessage("STOF_PASSWORD")?><span class="req">*</span>:</label>
									<input class="txt w240" type="password" name="NEW_PASSWORD" size="30">								
								</div><!--.form-item-->
								<div class="form-item">
									<label><?echo GetMessage("STOF_RE_PASSWORD")?><span class="req">*</span>:</label>                                
									<input type="password" class="txt w240" name="NEW_PASSWORD_CONFIRM" size="30" />
								</div><!--.form-item-->
							</div>
                            <div class="form-item">
                                <label>E-mail<span class="req">*</span>:</label>
								<input class="txt w240" type="text" name="NEW_EMAIL" size="40" value="<?=$arResult["POST"]["NEW_EMAIL"]?>"/>                                
                            </div><!--.form-item-->
                            <div class="form-item">
                                <label><?echo GetMessage("STOF_NAME")?>:</label>
								<input class="txt w240" type="text" name="NEW_NAME" size="40" value="<?=$arResult["POST"]["NEW_NAME"]?>" />                                
                            </div><!--.form-item-->
                            <div class="form-item">
                                <label><?echo GetMessage("STOF_LASTNAME")?>:</label>
								<input class="txt w240" type="text" name="NEW_LAST_NAME" size="40" value="<?=$arResult["POST"]["NEW_LAST_NAME"]?>" />
                            </div><!--.form-item-->       
							<div class="form-item">
								<div>
									<input type="radio" class="radio" id="NEW_GENERATE_N" name="NEW_GENERATE" value="N" OnClick="ChangeGenerate(false)"<?if ($arResult["POST"]["NEW_GENERATE"] == "N") echo " checked";?>>
									<label for="NEW_GENERATE_N" class="stof_register"> <?echo GetMessage("STOF_MY_PASSWORD")?></label>
								</div>
								<div>
									<input type="radio" class="radio" id="NEW_GENERATE_Y" name="NEW_GENERATE" value="Y" OnClick="ChangeGenerate(true)"<?if ($arResult["POST"]["NEW_GENERATE"] != "N") echo " checked";?>>
									<label for="NEW_GENERATE_Y" class="stof_register"> <?echo GetMessage("STOF_SYS_PASSWORD")?></label>
								</div>
								<script language="JavaScript">
								<!--
								ChangeGenerate(<?= (($arResult["POST"]["NEW_GENERATE"] != "N") ? "true" : "false") ?>);
								//-->
								</script>
                            </div><!--.form-item-->                          
                           
							<div class="form-item captcha">
							
							<?
						if($arResult["AUTH"]["captcha_registration"] == "Y") //CAPTCHA
						{
							?>
							<label><?=GetMessage("CAPTCHA_REGF_TITLE")?></label>
							
							 
							 <input class="txt w100" type="text" name="captcha_word"  value="">
							<input   type="hidden" name="captcha_sid" value="<?=$arResult["AUTH"]["capCode"]?>" />
                             <span class="captcha_img"><img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["AUTH"]["capCode"]?>" width="180" height="40" alt="CAPTCHA"></span>
							
							
							
							<?
						}
						?>
                            </div><!--.form-item-->
                            
                            <div class="form-submit">
                                <input type="hidden" name="do_register" value="Y">
                            	<button class="button1"><?echo GetMessage("STOF_NEXT_STEP")?></button>
                            </div><!--.form-submit-->
                        </form>
                    </div><!--.col-->
                    <div style="clear:both;"></div>
                </div><!--.ordering-->


