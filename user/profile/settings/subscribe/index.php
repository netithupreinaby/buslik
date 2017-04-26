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
            <form class="simple-form form-margin30 text-wrap">
                <div class="row">
                    <div class="col-md-9 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <ul class="checkout-block">
                                <li>
                                    <input type="checkbox" id="shape_type2_1">
                                    <label for="shape_type2_1">Отправлять мне СМС на ?какой-актив.бонусной? телефон об акциях </label>
                                </li>
                                <li>
                                    <input type="checkbox" id="shape_type2_2">
                                    <label for="shape_type2_2">Отправлять мне уведомления на е-mail об акциях </label>
                                </li>
                            </ul>
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
    </section>
</section>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>