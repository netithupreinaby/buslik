<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>

		<?//=ShowError($arResult["strProfileError"]);	OLD ERROR MESSAGE (04.03.2013)?>
		<?	/*	NEW ERROR MESSAGE	*/
			$arResult["strProfileError"] = trim($arResult["strProfileError"],"<br />");
			$ERROR = preg_split("/<br>/",$arResult["strProfileError"]);
			//foreach($ERROR as $k=>$v)
				//if(!empty($v))
					//print_r( "<script>jGrowl('".$v."','error');</script>");
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
		<div class="alrt-wrapper">
			<?php if ($arResult['arUser']['INFO_MESSAGE']) {?>
			<div class="alert alrt alert-info alert-dismissible" role="alert">
				<a href="" type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">×</span>
				</a>
				<p class="alert-text">Этот блок содержит инфо общего значения. Не акция. Не реклама. Какой-то информационный текст.</p>
			</div>
			<?php } ?>
			<?php if ($arResult['arUser']['WARNING_MESSAGE']) {?>
			<div class="alert alrt alert-warning alert-dismissible" role="alert">
				<a href="" type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">×</span>
				</a>
				<p class="alert-text">В блоке такого типа будет какой-либо предупреждающий текст. Например о наличии товара, есть он или нет и что-то в таком плане. Для такого рода текста используется значок предупреждения, чтобы пользователь обратил внимание!</p>
			</div>
			<?php } ?>
		</div>
		<form id="loginForm" class="simple-form form-margin30 text-wrap" method="post">
			<?=$arResult["BX_SESSION_CHECK"]?>
			<div class="row">
				<div class="col-md-9 col-sm-12 col-xs-12">
					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="form-group relative">
								<label class="required-field" for="email">Адрес электронной почты</label>
								<input class="form-control validate[required,custom[email]]" id="email" type="text" name="EMAIL" value="<?php echo $arResult['arUser']['EMAIL']; ?>">
							</div>
							<div class="form-group relative">
								<label class="required-field" for="NEW_PASSWORD">Пароль</label>
								<div class="pass">
									<input type="password" class="form-control validate[optional,minSize[6]]" id="NEW_PASSWORD" name="NEW_PASSWORD">
									<a href="#" class="eye"></a>
								</div>
							</div>
						</div>
						<div class="col-md-12 col-sm-12 col-xs-12 buttons-block">
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
			<a href="#"><img src="img/images/beneficial_04.jpg" alt=""></a>
		</div>
	</div>
</div>
