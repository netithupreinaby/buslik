<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");?>
<?$APPLICATION->SetTitle("Вход на сайт");?>

<section class="main-content">
    <section class="cart">
<?$APPLICATION->IncludeComponent(
    "bitrix:menu",
    "dropdownPersonal",
    array(
        "ALLOW_MULTI_SELECT" => "N",
        "CHILD_MENU_TYPE" => "left",
        "DELAY" => "N",
        "MAX_LEVEL" => "1",
        "MENU_CACHE_GET_VARS" => array(
        ),
        "MENU_CACHE_TIME" => "3600",
        "MENU_CACHE_TYPE" => "N",
        "MENU_CACHE_USE_GROUPS" => "Y",
        "ROOT_MENU_TYPE" => "user",
        "USE_EXT" => "Y",
        "COMPONENT_TEMPLATE" => "topmenu2"
    ),
    false
);?>

<?$APPLICATION->IncludeComponent(
    "bitrix:menu",
    "profileTabs",
    array(
        "ALLOW_MULTI_SELECT" => "N",
        "CHILD_MENU_TYPE" => "left",
        "DELAY" => "N",
        "MAX_LEVEL" => "1",
        "MENU_CACHE_GET_VARS" => array(
        ),
        "MENU_CACHE_TIME" => "3600",
        "MENU_CACHE_TYPE" => "N",
        "MENU_CACHE_USE_GROUPS" => "Y",
        "ROOT_MENU_TYPE" => "left",
        "USE_EXT" => "Y",
        "COMPONENT_TEMPLATE" => "topmenu2"
    ),
    false
);?>

    <div class="row">
        <div class="col-md-9 col-sm-9 col-xs-12">
            <div class="row">
                <form class="simple-form form-margin30 text-wrap">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <label for="example12">Дети</label>
                    </div>
                    <div class="col-md-8 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <select class="chosen-select" data-placeholder="Выберите данные о ребенке для редактирования" tabindex="2">
                                <option value=""></option>
                                <option value="United States">Минск</option>
                                <option value="United Kingdom">НеМинск</option>
                                <option value="Aland Islands">Минск</option>
                                <option value="Albania">НеМинск</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="form-group clearfix">
                            <a href="" class="edit-field pull-left"><img src="<?php echo SITE_TEMPLATE_PATH; ?>/static/img/images/add-field.png" alt=""></a> <a href="" class="add-field pull-left"><span class="dashed-link">Добавить ребенка</span></a>
                        </div>
                    </div>
                </form>
                <form class="simple-form form-margin30 text-wrap new-fields-form">
                    <div class="col-md-8 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="form-group">
                                    <h4>Дети</h4>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <label class="required-field" for="dsexample12">Количество детей</label>
                                        <input class="form-control" id="dsexample12" type="text">
                                    </div>
                                </div>
                                <hr>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="required-field" for="example12sdf12">Имя</label>
                                    <input class="form-control" id="example12sdf12" type="text">
                                </div>
                                <div class="form-group">
                                    <label class="required-field" for="exsdample12sdf">Дата рождения</label>
                                    <div class="input-group date" id='datetimepicker7'>
                                                    <span class="input-group-addon">
                                                    <span class="glyphicon glyphicon-calendar"></span>
                                                    </span>
                                        <input type='text' class="form-control" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="required-field" for="examplesd112">Пол</label>
                                    <ul class="sex-radio nav-pills clearfix">
                                        <li>
                                            <input type="radio" id="radio1" checked name="radio1">
                                            <label for="radio1">муж.</label>
                                        </li>
                                        <li>
                                            <input type="radio" id="radio2" name="radio1">
                                            <label for="radio2">жен.</label>
                                        </li>
                                    </ul>
                                </div>
                                <div class="form-group">
                                    <label class="required-field" for="asexample978">Родственная связь</label>
                                    <select class="chosen-select" tabindex="2">
                                        <option value="United States">Минск</option>
                                        <option value="United Kingdom">НеМинск</option>
                                        <option value="Aland Islands">Минск</option>
                                        <option value="Albania">НеМинск</option>
                                    </select>
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
    </section>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>