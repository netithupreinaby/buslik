<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>

		<?//=ShowError($arResult["strProfileError"]);	OLD ERROR MESSAGE (04.03.2013)?>
		<?	/*	NEW ERROR MESSAGE	*/
			$arResult["strProfileError"] = trim($arResult["strProfileError"],"<br />");
			$ERROR = preg_split("/<br>/",$arResult["strProfileError"]);
			foreach($ERROR as $k=>$v)
				if(!empty($v))
					print_r( "<script>jGrowl('".$v."','error');</script>");
			/*		--------		*/
		?>

		<? if ($arResult['DATA_SAVED'] == 'Y') { ?>
			<div id="data-saved" class="popup-window data-saved">
				<a class="close-popup" href=""></a>
				<?php echo GetMessage('PROFILE_DATA_SAVED'); ?>
			</div>
			<script>
				showDataSavedPopup();
			</script>
		<?php } ?>


<div class="row">
	<div class="col-md-9 col-sm-9 col-xs-12">
		<form id="dataForm" class="simple-form form-margin30 text-wrap" method="post">
			<?=$arResult["BX_SESSION_CHECK"]?>
			<div class="row">
				<div class="col-md-9 col-sm-12 col-xs-12">
					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="form-group">
								<label class="required-field" for="USER_NAME">Имя</label>
								<input class="form-control validate[required]" id="USER_NAME" type="text" name="NAME" value="<?php echo $arResult['arUser']['NAME']; ?>">
							</div>
							<div class="form-group">
								<label class="required-field" for="USER_SURNAME">Фамилия</label>
								<input class="form-control validate[required]" id="USER_SURNAME" type="text" name="LAST_NAME" value="<?php echo $arResult['arUser']['LAST_NAME']; ?>">
							</div>
							<div class="form-group">
								<label for="SECOND_NAME">Отчество</label>
								<input class="form-control" id="SECOND_NAME" type="text" name="SECOND_NAME" value="<?php echo $arResult['arUser']['SECOND_NAME']; ?>">
							</div>
							<div class="form-group">
								<label class="required-field" for="PERSONAL_BIRTHDAY">Дата рождения</label>
								<div class="input-group date" id='datetimepicker6'>
                                                    <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
									<input class="form-control validate[required,date]" id="PERSONAL_BIRTHDAY" type='text' name="PERSONAL_BIRTHDAY" value="<?php echo $arResult['arUser']['PERSONAL_BIRTHDAY']; ?>"/>
								</div>
							</div>
							<?php switch ($arResult['arUser']['PERSONAL_GENDER']) {
								case "M": $gender = "M";break;
								case "F": $gender = "F";break;
								default: $gender = "";
							} ?>
							<div class="form-group">
								<label class="required-field" for="examplesd112">Пол</label>
								<ul class="sex-radio nav-pills clearfix">
									<li>
										<input type="radio" id="PERSONAL_GENDER_M"<?php if ($gender == "M") { ?> checked<?php } ?> name="PERSONAL_GENDER" value="M">
										<label for="PERSONAL_GENDER_M">муж.</label>
									</li>
									<li>
										<input type="radio" id="PERSONAL_GENDER_F"<?php if ($gender == "F") { ?> checked<?php } ?> name="PERSONAL_GENDER" value="F">
										<label for="PERSONAL_GENDER_F">жен.</label>
									</li>
								</ul>
							</div>
						</div>
						<div class="col-md-12 col-sm-12 col-xs-12  buttons-block">
							<div class="row">
								<div class="col-md-4 col-sm-4 col-xs-12">
									<div class="form-group">
										<input class="btn-danger btn-white-color" value="Сохранить" type="submit" name="save">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
	<div class="col-md-3 col-sm-3 col-xs-12 hidden-xs">
		<div class="banner">
			<a href="#"><img src="<?php echo SITE_TEMPLATE_PATH; ?>/static/img/images/beneficial_04.jpg" alt=""></a>
		</div>
	</div>
</div>
