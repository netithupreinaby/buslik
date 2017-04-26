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
		<div class="row">
			<form class="simple-form form-margin30 text-wrap">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<label for="example12">Адреса доставки</label>
				</div>
				<div class="col-md-8 col-sm-12 col-xs-12">
					<div class="form-group">
						<select class="chosen-select" data-placeholder="Выберите адрес для редактирования" tabindex="2" data-form-type="ADDRESS">
							<option value=""></option>
							<?php foreach ($arResult['ADDRESSES'] as $addressKey => $address) { ?>
							<option value="<?php echo $addressKey; ?>"><?php showFullAddress($address);?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="col-md-4 col-sm-12 col-xs-12">
					<div class="form-group clearfix">
						<a href="" class="edit-field pull-left" data-form-type="ADDRESS">
							<img src="<?php echo SITE_TEMPLATE_PATH; ?>/static/img/images/add-field.png" alt="">
						</a>
						<a href="" class="add-field pull-left" data-form-type="ADDRESS"><span class="dashed-link">Добавить адрес</span></a>
					</div>
				</div>
			</form>
			<form id="addressForm" class="simple-form form-margin30 text-wrap new-fields-form" data-form-type="ADDRESS">
				<input type="hidden" name="recordId" value="0">
				<div class="col-md-8 col-sm-12 col-xs-12">
					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="form-group">
								<label class="required-field" for="example112">Область</label>
								<select class="chosen-select" data-placeholder="Выберите область" tabindex="2" name="PERSONAL_STATE">
									<option value="0">Брестская</option>
									<option value="1">Витебская</option>
									<option value="2">Гомельская</option>
									<option value="3">Гродненская</option>
									<option value="4">Минская</option>
									<option value="5">Могилевская</option>
								</select>
							</div>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<div class="form-group">
								<label class="required-field" for="example112">Город (или ближайший населенный пункт)</label>
								<select class="chosen-select" data-placeholder="Выберите город" tabindex="2" name="PERSONAL_CITY">
									<option value="0">Брест</option>
									<option value="1">Витебск</option>
									<option value="2">Гомель</option>
									<option value="3">Гродно</option>
									<option value="4">Минск</option>
									<option value="5">Могилев</option>
									<option value="6">Борисов</option>
								</select>
							</div>
						</div>
					</div>
				</div>

				<div class="col-md-12">
					<div class="row-special clearfix">
						<div class="form-group medium-inp form-clear">
							<label class="required-field" for="street">Улица</label>
							<input class="form-control" id="street" type="text" name="PERSONAL_STREET">
						</div>

						<div class="form-group small-inp">
							<label class="required-field" for="house">Дом</label>
							<input class="form-control" id="house" type="text" name="UF_PERSONAL_HOME">
						</div>

						<div class="form-group small-inp">
							<label for="corp">Корпус</label>
							<input class="form-control" id="corp" type="text" name="UF_PERSONAL_BUILD">
						</div>

						<div class="form-group small-inp">
							<label for="apartment">Квартира</label>
							<input class="form-control" id="apartment" type="text" name="UF_PERSONAL_FLAT">
						</div>

						<div class="form-group small-inp">
							<label for="floor">Этаж</label>
							<input class="form-control" id="floor" type="text" name="UF_PERSONAL_FLOOR">
						</div>

						<div class="form-group small-inp sm-lc">
							<label for="door-pass">Код домофона</label>
							<input class="form-control" id="door-pass" type="text" name="UF_PERSONAL_INTERCOM">
						</div>
					</div>
				</div>

				<div class="col-md-8 col-sm-12 col-xs-12">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<div class="form-group">
								<ul class="checkout-block">
									<li>
										<input type="checkbox" id="isMain" name="IS_MAIN" value="Y">
										<label for="isMain">Сделать основным адресом доставки</label>
									</li>
								</ul>
							</div>
						</div>
					</div>
					<div class="buttons-block">
						<div class="row">
							<div class="col-md-4 col-sm-4 col-xs-12">
								<div class="form-group">
									<input class="border-btn dart-border btn-white-color reset" value="Отменить" type="submit" data-form-type="ADDRESS">
								</div>
							</div>
							<div class="col-md-4 col-sm-4 col-xs-12">
								<div class="form-group">
									<input type="hidden" name="action" value="setData">
									<input type="hidden" name="actionType" value="ADDRESS">
									<input class="btn-blue btn-white-color send" value="Сохранить" type="submit">
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="row">
			<form class="simple-form form-margin30 text-wrap">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<label for="example12">ФИО получателей</label>
				</div>
				<div class="col-md-8 col-sm-12 col-xs-12">
					<div class="form-group">
						<select class="chosen-select" data-placeholder="Выберите ФИО для редактирования" tabindex="2" data-form-type="FULLNAME">
							<option value=""></option>
							<?php foreach ($arResult['FULLNAMES'] as $fullnameKey => $fullname) { ?>
								<option value="<?php echo $fullnameKey; ?>"><?php showFullname($fullname);?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="col-md-4 col-sm-12 col-xs-12">
					<div class="form-group clearfix">
						<a href="" class="edit-field pull-left" data-form-type="FULLNAME">
							<img src="<?php echo SITE_TEMPLATE_PATH; ?>/static/img/images/add-field.png" alt="">
						</a>
						<a href="" class="add-field pull-left" data-form-type="FULLNAME"><span class="dashed-link ">Добавить ФИО</span></a>
					</div>
				</div>
			</form>
			<form id="fullnameForm" class="simple-form form-margin30 text-wrap new-fields-form" data-form-type="FULLNAME">
				<input type="hidden" name="recordId" value="0">
				<div class="clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<div class="form-group sm-lc">
							<label class="required-field" for="LAST_NAME">Фамилия</label>
							<input type="text" id="LAST_NAME" class="form-control validate[required]" name="LAST_NAME">
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<div class="form-group sm-lc">
							<label class="required-field" for="NAME">Имя</label>
							<input type="text" id="NAME" class="form-control validate[required]" name="NAME">
						</div>
					</div>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 form-clear">
						<div class="form-group sm-lc">
							<label for="SECOND_NAME">Отчество</label>
							<input type="text" id="SECOND_NAME" class="form-control" name="SECOND_NAME">
						</div>
					</div>
				</div>
				<div class="clearfix">
					<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="form-group">
							<input class="border-btn dart-border btn-white-color reset" value="Отменить" type="submit">
						</div>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="form-group">
							<input type="hidden" name="action" value="setData">
							<input type="hidden" name="actionType" value="FULLNAME">
							<input class="btn-blue btn-white-color send" value="Сохранить" type="submit">
						</div>
					</div>
				</div>
			</form>
		</div>
		<div class="row">
			<form class="simple-form form-margin30 text-wrap">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<label for="example12">Телефоны получателей</label>
				</div>
				<div class="col-md-8 col-sm-12 col-xs-12">
					<div class="form-group">
						<select class="chosen-select" data-placeholder="Выберите телефон для редактирования" tabindex="2" data-form-type="PHONE" name="WORK_PHONE">
							<option value=""></option>
							<?php foreach ($arResult['PHONES'] as $phoneKey => $phone) { ?>
								<option value="<?php echo $phoneKey; ?>"><?php echo $phone;?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<div class="col-md-4 col-sm-12 col-xs-12">
					<div class="form-group clearfix">
						<a href="" class="edit-field pull-left" data-form-type="PHONE">
							<img src="<?php echo SITE_TEMPLATE_PATH; ?>/static/img/images/add-field.png" alt="">
						</a>
						<a href="" class="add-field pull-left" data-form-type="PHONE"><span class="dashed-link ">Добавить телефон</span></a>
					</div>
				</div>
			</form>
			<form id="phoneForm" class="simple-form form-margin30 text-wrap new-fields-form" data-form-type="PHONE">
				<input type="hidden" name="recordId" value="0">
				<div class="clearfix">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<div class="form-group sm-lc">
							<label class="required-field" for="WORK_PHONE">Телефон</label>
							<input type="text" id="WORK_PHONE" class="form-control validate[required]" name="WORK_PHONE">
						</div>
					</div>
				</div>
				<div class="clearfix">
					<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="form-group">
							<input class="border-btn dart-border btn-white-color reset" value="Отменить" type="submit">
						</div>
					</div>
					<div class="col-md-4 col-sm-4 col-xs-12">
						<div class="form-group">
							<input type="hidden" name="action" value="setData">
							<input type="hidden" name="actionType" value="PHONE">
							<input class="btn-blue btn-white-color send" value="Сохранить" type="submit">
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<div class="col-md-3 col-sm-3 col-xs-12 hidden-xs">
		<div class="banner">
			<a href="#"><img src="<?php echo SITE_TEMPLATE_PATH; ?>/static/img/images/beneficial_04.jpg" alt=""></a>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){

		BX.message({
			COMPONENT_TEMPLATE_PATH: '<?php echo $templateFolder; ?>'
		});
	});

</script>