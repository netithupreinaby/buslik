<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();
?>
<section class="main-content">
	<!-- breadcrumbs start --> <?$APPLICATION->IncludeComponent(
		"bitrix:breadcrumb",
		"",
		Array(
			"PATH" => "",
			"SITE_ID" => "s1",
			"START_FROM" => "0"
		)
	);?> <!-- breadcrumbs end -->
	<p class="section-title">
		<span><?php $APPLICATION->ShowTitle(); ?></span>
	</p>
<div class="row">

<?if($USER->IsAuthorized()):?>

<p class="text-center"><?echo GetMessage("MAIN_REGISTER_AUTH")?></p>

<?else:?>

<?
if (count($arResult["ERRORS"]) > 0) {
	foreach ($arResult["ERRORS"] as $key => $error)
		if (intval($key) == 0 && $key !== 0) 
			$arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;".GetMessage("REGISTER_FIELD_".$key)."&quot;", $error);

} /* elseif($arResult["USE_EMAIL_CONFIRMATION"] === "Y") {
?>
<p><?echo GetMessage("REGISTER_EMAIL_WILL_BE_SENT")?></p>
<? } */?>
	<div class="col-md-9 col-sm-8 col-xs-12">
		<?php if (count($arResult['ERRORS']) > 0) { ?>
		<div class="alert alert-danger alert-dismissable">
			<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
			<?php foreach ($arResult['ERRORS'] as $error) { ?>
			<p><?php echo $error; ?></p>
			<?php } ?>
		</div>
		<?php } ?>
		<form id="regForm" class="simple-form form-margin30 text-wrap" method="post" action="<?=POST_FORM_ACTION_URI?>" name="regform" enctype="multipart/form-data">
			<div class="row">
				<div class="col-md-9 col-sm-12 col-xs-12">
					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="form-group">
								<label class="required-field" for="FIELD_NAME">Имя</label>
								<input class="form-control validate[required]" id="FIELD_NAME" type="text" name="REGISTER[NAME]" value="<?php echo $arResult['VALUES']['NAME']; ?>">
							</div>
							<div class="form-group">
								<label class="required-field" for="LAST_NAME">Фамилия</label>
								<input class="form-control validate[required]" id="LAST_NAME" type="text" name="REGISTER[LAST_NAME]" value="<?php echo $arResult['VALUES']['LAST_NAME']; ?>">
							</div>
							<div class="form-group">
								<label class="required-field" for="EMAIL">Адрес электронной почты</label>
								<input class="form-control validate[required,custom[email]]" id="EMAIL" type="text" name="REGISTER[EMAIL]" value="<?php echo $arResult['VALUES']['EMAIL']; ?>">
							</div>
							<div class="form-group">
								<label class="required-field" for="PHONE">Телефон</label>
								<input class="form-control validate[required,custom[phone]]" id="PHONE" type="text" name="REGISTER[WORK_PHONE]" value="<?php echo $arResult['VALUES']['WORK_PHONE']; ?>">
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="form-group">
								<label class="required-field" for="PASSWORD">Пароль</label>
								<div class="pass">
									<input class="form-control validate[required]" id="PASSWORD" type="password" name="REGISTER[PASSWORD]">
									<a href="#" class="eye"></a>
								</div>
							</div>
							<div class="form-group">
								<label class="required-field" for="CONFIRM_PASSWORD">Повторите пароль</label>
								<div class="pass">
									<input class="form-control validate[required]" id="CONFIRM_PASSWORD" type="password" name="REGISTER[CONFIRM_PASSWORD]">
									<a href="#" class="eye"></a>
								</div>
							</div>
						</div>
					</div>
					<div class="get_first_sale">
						<a id="additionalInfoSwitcher" href="" class="dashed-link sale_btn"><?php echo $arParams['DISCOUNT_TEXT']; ?></a>
					</div>
					<div class="row sale_first">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								<div class="col-md-6 col-sm-6 col-xs-12">
									<div class="form-group">
										<input type="hidden" name="REGISTER[ADDITIONAL_INFO]">
										<label for="SECOND_NAME">Отчество</label>
										<input class="form-control" id="SECOND_NAME" type="text" name="REGISTER[SECOND_NAME]" value="<?php echo $arResult['VALUES']['SECOND_NAME']; ?>">
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="form-group">
								<label class="required-field" for="PERSONAL_BIRTHDAY">Дата рождения</label>
								<div class="input-group date" id='datetimepicker6'>
											<span class="input-group-addon">
												<span class="glyphicon glyphicon-calendar"></span>
											</span>
									<input type='text' class="form-control" name="REGISTER[PERSONAL_BIRTHDAY]" value="<?php echo $arResult['VALUES']['PERSONAL_BIRTHDAY']; ?>">
								</div>
							</div>
							<div class="form-group">
								<label class="required-field" for="PERSONAL_STATE">Область</label>
								<input class="form-control" id="PERSONAL_STATE" type="text" name="REGISTER[PERSONAL_STATE]" value="<?php echo $arResult['VALUES']['PERSONAL_STATE']; ?>">
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="form-group">
								<label class="required-field" for="asexample456">Пол</label>
								<ul class="sex-radio nav-pills clearfix">
									<li>
										<input type="radio" id="PERSONAL_GENDER_M" checked name="REGISTER[PERSONAL_GENDER]" value="M">
										<label for="PERSONAL_GENDER_M">муж.</label>
									</li>
									<li>
										<input type="radio" id="PERSONAL_GENDER_F" name="REGISTER[PERSONAL_GENDER]" value="F">
										<label for="PERSONAL_GENDER_F">жен.</label>
									</li>
								</ul>
							</div>
							<div class="form-group">
								<label class="required-field" for="PERSONAL_CITY">Город (или ближайший населенный пункт)</label>
								<select id="PERSONAL_CITY" class="chosen-select" tabindex="2" name="REGISTER[PERSONAL_CITY]">
									<option value="United States">Минск</option>
									<option value="United Kingdom">НеМинск</option>
									<option value="Aland Islands">Минск</option>
									<option value="Albania">НеМинск</option>
								</select>
							</div>
						</div>
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								<div class="col-md-6 col-sm-6 col-xs-12">
									<div class="form-group">
										<label class="required-field" for="PERSONAL_STREET">Улица</label>
										<input class="form-control" id="PERSONAL_STREET" type="text" name="REGISTER[PERSONAL_STREET]" value="<?php echo $arResult['VALUES']['PERSONAL_STREET']; ?>">
									</div>
								</div>
								<div class="col-md-6 col-sm-6 col-xs-12">
									<div class="row">
										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="form-group">
												<label class="required-field" for="UF_PERSONAL_HOME">Дом</label>
												<input class="form-control" id="UF_PERSONAL_HOME" type="text" name="REGISTER[UF_PERSONAL_HOME]" value="<?php echo $arResult['VALUES']['UF_PERSONAL_HOME']; ?>">
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="form-group">
												<label for="UF_PERSONAL_HOUSING">Корпус</label>
												<input class="form-control" id="UF_PERSONAL_HOUSING" type="text" name="REGISTER[UF_PERSONAL_HOUSING]" value="<?php echo $arResult['VALUES']['UF_PERSONAL_HOUSING']; ?>">
											</div>
										</div>
										<div class="col-md-4 col-sm-4 col-xs-12">
											<div class="form-group">
												<label for="UF_PERSONAL_FLAT">Квартира</label>
												<input class="form-control" id="UF_PERSONAL_FLAT" type="text" name="REGISTER[UF_PERSONAL_FLAT]" value="<?php echo $arResult['VALUES']['UF_PERSONAL_FLAT']; ?>">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="form-group">
								<ul class="checkout-block">
									<li>
										<input type="checkbox" id="UF_DELIVERY_ADDRESS_CHECKBOX" name="REGISTER[UF_DELIVERY_ADDRESS_CHECKBOX]" value="Y">
										<label for="UF_DELIVERY_ADDRESS_CHECKBOX">Сделать адресом доставки </label>
									</li>
								</ul>
							</div>
						</div>
						<?php if ($arParams['SPECIAL_OFFERS']) { ?>
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="cart-banner ">
								<p><?php echo $arParams['SPECIAL_OFFERS']; ?></p>
							</div>
						</div>
						<?php } ?>
						<div class="col-md-12 col-sm-12 col-xs-12 children-count">
							<div class="form-group">
								<h4>Дети</h4>
							</div>
							<div class="row">
								<div class="col-md-6 col-sm-6 col-xs-12">
									<label class="required-field" for="UF_PERSONAL_CHILDREN_COUNT">Количество детей</label>
									<input class="form-control" id="UF_PERSONAL_CHILDREN_COUNT" type="text" name="REGISTER[UF_PERSONAL_CHILDREN_COUNT]" value="<?php echo $arResult['VALUES']['UF_PERSONAL_CHILDREN_COUNT']; ?>">
								</div>
							</div>
							<hr>
						</div>
					</div>
					<div class="form-group">
						<ul class="checkout-block">
							<li>
								<input type="checkbox" id="UF_PERSONAL_EMAIL_SUBSCRIBE" name="REGISTER[UF_PERSONAL_EMAIL_SUBSCRIBE]" value="Y">
								<label for="UF_PERSONAL_EMAIL_SUBSCRIBE">Отправлять мне уведомления на е-mail об акциях </label>
							</li>
							<li>
								<input type="checkbox" id="UF_PERSONAL_SMS_SUBSCRIBE" name="REGISTER[UF_PERSONAL_SMS_SUBSCRIBE]" value="Y">
								<label for="UF_PERSONAL_SMS_SUBSCRIBE">Отправлять мне СМС на телефон об акциях </label>
							</li>
						</ul>
					</div>
					<div class="form-group">
						<div class="g-recaptcha" data-sitekey="<?=RE_SITE_KEY?>"></div>
						<?php /*<div class="captcha">
							<iframe src="https://www.google.com/recaptcha/api2/anchor?k=6Le-wvkSAAAAAPBMRTvw0Q4Muexq9bi0DJwx_mJ-&amp;co=aHR0cHM6Ly93d3cuZ29vZ2xlLmNvbTo0NDM.&amp;hl=ru&amp;v=r20161123095123&amp;size=normal&amp;cb=ovghva8bez9p" title="виджет reCAPTCHA" width="304" height="78" role="presentation" frameborder="0" scrolling="no" name="undefined"></iframe>
						</div>*/ ?>
					</div>
					<div class="row form-group">
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="form-group">
								<input type="hidden" name="register_submit_button" value="<?=GetMessage("AUTH_REGISTER")?>"/>
								<input class="btn-danger btn-white-color" value="Зарегистрироваться" type="submit">
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="form-group">
								<a href="" class="pt8">Войти</a>
							</div>
						</div>
					</div>
					<div class="form-group">
						<label class="black-text">Регистрируясь на сайте, Вы автоматически соглашаетесь с положениями в <a href="" class="black-text">Договоре публичной оферты</a>.</label>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div class="col-md-3 col-sm-4 col-xs-12">
		<?php /*<div class="links-bg clearfix">
			<img src="img/images/delivery-02.jpg" alt="">
			<div class="banner delivery-links">
				<ul>
					<li>
						<span class="delivery-links-number">1</span>
						<a class="links-fix" href="#">Подпишись на рассылку <span>Мы не рассылаем спам</span></a>
					</li>
					<li>
						<span class="delivery-links-number">2</span>
						<a class="links-fix" href="#">Узнавай о скидках первым <span>И пользуйся специальными бонусами</span></a>
					</li>
					<li>
						<span class="delivery-links-number">3</span>
						<a class="links-fix" href="#">Зарегистрируйся <span>И получи скидку 5% на первую покупку.</span></a>
					</li>
				</ul>
			</div>
		</div>*/?>
	</div>
</div>
</section>
<script src='https://www.google.com/recaptcha/api.js?hl=ru'></script>
<?php return; ?>
<?php if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($arResult['ERRORS'])) {
	$arResult['VALUES'] = '';
} ?>
<div class="form-block">
	<div class="row">
		<div class="col-xs-5">
			<h3><?php echo GetMessage('AUTH_GENERAL_INFORMATION'); ?></h3>
		</div>
		<div class="col-xs-7">
<?
if($arResult["BACKURL"] <> ''):
	?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
	<?
endif;
?>
<?foreach ($arResult["SHOW_FIELDS"] as $FIELD) { ?>
	<?if($FIELD == "AUTO_TIME_ZONE" && $arResult["TIME_ZONE_ENABLED"] == true) {?>
			<td><?echo GetMessage("main_profile_time_zones_auto")?><?if ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"):?><span class="starrequired">*</span><?endif?></td>
			<td>
				<select name="REGISTER[AUTO_TIME_ZONE]" onchange="this.form.elements['REGISTER[TIME_ZONE]'].disabled=(this.value != 'N')">
					<option value=""><?echo GetMessage("main_profile_time_zones_auto_def")?></option>
					<option value="Y"<?=$arResult["VALUES"][$FIELD] == "Y" ? " selected=\"selected\"" : ""?>><?echo GetMessage("main_profile_time_zones_auto_yes")?></option>
					<option value="N"<?=$arResult["VALUES"][$FIELD] == "N" ? " selected=\"selected\"" : ""?>><?echo GetMessage("main_profile_time_zones_auto_no")?></option>
				</select>
			</td>
		<tr>
			<td><?echo GetMessage("main_profile_time_zones_zones")?></td>
			<td>
				<select name="REGISTER[TIME_ZONE]"<?if(!isset($_REQUEST["REGISTER"]["TIME_ZONE"])) echo 'disabled="disabled"'?>>
		<?foreach($arResult["TIME_ZONE_LIST"] as $tz=>$tz_name):?>
					<option value="<?=htmlspecialcharsbx($tz)?>"<?=$arResult["VALUES"]["TIME_ZONE"] == $tz ? " selected=\"selected\"" : ""?>><?=htmlspecialcharsbx($tz_name)?></option>
		<?endforeach?>
				</select>
			</td>
		</tr>
	<? } else { ?>
			<?
	switch ($FIELD)
	{
		case "PASSWORD":
			?><input size="30" type="password" name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" autocomplete="off" class="form-control<?php if ($arResult['ERRORS']['PASSWORD']) { ?> error<?php } ?>" placeholder="<?=GetMessage("REGISTER_FIELD_".$FIELD)?> <?if ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"):?>*<?endif?>"/>
			<?php if ($arResult['ERRORS']['PASSWORD']) { ?>
				<p class="error"><?php echo $arResult['ERRORS']['PASSWORD']; ?></p>
			<?php } ?>

		<?if($arResult["SECURE_AUTH"]) {?>
				<span class="bx-auth-secure" id="bx_auth_secure" title="<?echo GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
					<div class="bx-auth-secure-icon"></div>
				</span>
				<noscript>
				<span class="bx-auth-secure" title="<?echo GetMessage("AUTH_NONSECURE_NOTE")?>">
					<div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
				</span>
				</noscript>
<script type="text/javascript">
document.getElementById('bx_auth_secure').style.display = 'inline-block';
</script>
<? } ?>
<?
			break;
		case "CONFIRM_PASSWORD":
			?><input size="30" type="password" name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" autocomplete="off" class="form-control<?php if ($arResult['ERRORS']['CONFIRM_PASSWORD']) { ?> error<?php } ?>" placeholder="<?=GetMessage("REGISTER_FIELD_".$FIELD)?> <?if ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"):?>*<?endif?>" />
			<?php if ($arResult['ERRORS']['CONFIRM_PASSWORD']) { ?>
			<p class="error"><?php echo $arResult['ERRORS']['CONFIRM_PASSWORD']; ?></p>
			<?php } ?>
			<?
			break;

		case "PERSONAL_GENDER":
			?><select name="REGISTER[<?=$FIELD?>]">
				<option value=""><?=GetMessage("USER_DONT_KNOW")?></option>
				<option value="M"<?=$arResult["VALUES"][$FIELD] == "M" ? " selected=\"selected\"" : ""?>><?=GetMessage("USER_MALE")?></option>
				<option value="F"<?=$arResult["VALUES"][$FIELD] == "F" ? " selected=\"selected\"" : ""?>><?=GetMessage("USER_FEMALE")?></option>
			</select><?
			break;

		case "PERSONAL_COUNTRY":
		case "WORK_COUNTRY":
			?><select name="REGISTER[<?=$FIELD?>]"><?
			foreach ($arResult["COUNTRIES"]["reference_id"] as $key => $value)
			{
				?><option value="<?=$value?>"<?if ($value == $arResult["VALUES"][$FIELD]):?> selected="selected"<?endif?>><?=$arResult["COUNTRIES"]["reference"][$key]?></option>
			<?
			}
			?></select><?
			break;

		case "PERSONAL_PHOTO":
		case "WORK_LOGO":
			?><input size="30" type="file" name="REGISTER_FILES_<?=$FIELD?>" /><?
			break;

		case "PERSONAL_NOTES":
		case "WORK_NOTES":
			?><textarea cols="30" rows="5" name="REGISTER[<?=$FIELD?>]"><?=$arResult["VALUES"][$FIELD]?></textarea><?
			break;
		default:
			if ($FIELD == "PERSONAL_BIRTHDAY"):?><small><?=$arResult["DATE_FORMAT"]?></small><br /><?endif;
			?><input size="30" type="text" name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" class="form-control<?php if ($arResult['ERRORS'][$FIELD]) { ?> error<?php } ?>" placeholder="<?=GetMessage("REGISTER_FIELD_".$FIELD)?> <?if ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"):?>*<?endif?>" />
			<?php if ($arResult['ERRORS'][$FIELD]) { ?>
			<p class="error"><?php echo $arResult['ERRORS'][$FIELD]; ?></p>
			<?php } ?>
			<? if ($FIELD == "PERSONAL_BIRTHDAY")
					$APPLICATION->IncludeComponent(
						'bitrix:main.calendar',
						'',
						array(
							'SHOW_INPUT' => 'N',
							'FORM_NAME' => 'regform',
							'INPUT_NAME' => 'REGISTER[PERSONAL_BIRTHDAY]',
							'SHOW_TIME' => 'N'
						),
						null,
						array("HIDE_ICONS"=>"Y")
					);
				?><?
	}?>
	<? } ?>
<? } ?>
			</div>
		</div>
	</div>
<?// ********************* User properties ***************************************************?>
<?if($arResult["USER_PROPERTIES"]["SHOW"] == "Y"):?>

	<div class="form-block">
		<div class="row">
			<div class="col-xs-5">
				<h3><?=strlen(trim($arParams["USER_PROPERTY_NAME"])) > 0 ? $arParams["USER_PROPERTY_NAME"] : GetMessage("USER_TYPE_EDIT_TAB")?></h3>
			</div>
			<div class="col-xs-7">

	<?foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField):?>

			<?$APPLICATION->IncludeComponent(
				"bitrix:system.field.edit",
				$arUserField["USER_TYPE"]["USER_TYPE_ID"],
				array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arUserField, "form_name" => "regform", 'errors' => $arResult['ERRORS']), null, array("HIDE_ICONS"=>"Y"));?>
	<?endforeach;?>
			</div>
		</div>
	</div>

<?endif;?>
<?// ******************** /User properties ***************************************************?>
<?
/* CAPTCHA */
if ($arResult["USE_CAPTCHA"] == "Y")
{
	?>
		<tr>
			<td colspan="2"><b><?=GetMessage("REGISTER_CAPTCHA_TITLE")?></b></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
				<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
			</td>
		</tr>
		<tr>
			<td><?=GetMessage("REGISTER_CAPTCHA_PROMT")?>:<span class="starrequired">*</span></td>
			<td><input type="text" name="captcha_word" maxlength="50" value="" /></td>
		</tr>
	<?
}
/* !CAPTCHA */
?>
	<div class="text-center">
		<input type="hidden" name="register_submit_button" value="<?=GetMessage("AUTH_REGISTER")?>"/>
		<a href="#pass" class="pink-btn animate"><?php echo GetMessage('AUTH_REGISTER_BUTTON'); ?></a>
	</div>

	<?php /*<input type="submit" name="register_submit_button" value="<?=GetMessage("AUTH_REGISTER")?>" />
<p><?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></p>
<p><span class="starrequired">*</span><?=GetMessage("AUTH_REQ")?></p>*/ ?>

</form>
	<?php if($_SERVER['REQUEST_METHOD'] == 'POST' && empty($arResult['ERRORS'])) { ?>
		<div id="pass" class="popup-block popup600" style="display: none;">
			<?$APPLICATION->IncludeComponent(
				"bitrix:main.include",
				".default",
				array(
					"AREA_FILE_SHOW" => "file",
					"AREA_FILE_SUFFIX" => "inc",
					"EDIT_TEMPLATE" => "",
					"PATH" => "/include/" . LANGUAGE_ID . "/register_success.php",
					"COMPONENT_TEMPLATE" => ".default",
					"AREA_FILE_RECURSIVE" => "Y"
				),
				false
			);?>
		</div>
	<?php } elseif(!empty($arResult['ERRORS'])) { ?>
		<div id="pass" class="popup-block popup600" style="display: none;">
			<p class="title text-center"><?php echo GetMessage('AUTH_REGISTER'); ?></p>
			<?php ShowError(implode("<br />", $arResult["ERRORS"])); ?>
		</div>
	<?php }?>

<?endif?>
</div>
<script>
	$(document).ready(function(){
		$('a[href="#pass"]').on('click', function(e){
			e.preventDefault();
			$('.form-horizontal').submit();

		});

		$('input.form-control').on('change', function(e){
			$(this).removeClass('error');
			$(this).next('p').remove();
		});
		<?php if($_SERVER['REQUEST_METHOD'] == 'POST') { ?>
		$('#pass').fancybox().trigger('click');
		$('.popup-block.popup600 .btn-wrap a').on('click', function(e){
			e.preventDefault();
			e.stopPropagation();
			$.fancybox.close();
		});
		<?php }?>
	});
</script>
