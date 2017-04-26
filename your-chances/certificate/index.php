<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Подарочные Сертификаты");?><section class="main-content wishes">
<?$APPLICATION->IncludeComponent(
	"bitrix:breadcrumb",
	"",
	Array(
		"PATH" => "",
		"SITE_ID" => "s1",
		"START_FROM" => "0"
	)
);?>
<div class="l-menu-wrap clearfix ">
	<p class="page-title">
		 Подарочные Сертификаты
	</p>
	<div class="sidebar">
		 <?$APPLICATION->IncludeComponent(
	"bitrix:menu",
	"left",
	Array(
		"ALLOW_MULTI_SELECT" => "N",
		"CHILD_MENU_TYPE" => "left",
		"DELAY" => "N",
		"MAX_LEVEL" => "2",
		"MENU_CACHE_GET_VARS" => array(),
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_TYPE" => "N",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"ROOT_MENU_TYPE" => "left",
		"USE_EXT" => "N"
	)
);?>
	</div>
	<div class="r-content">
		<div class="content-wrapper">
 <section class="certificates-wrap">
			<p class="black-text">
				 Подарочные карты «Буслiк» - всегда удачный подарок!
			</p>
			<p class="text-wrap">
				 Не знаете, что подарить, но хотите сделать полезный и нужный подарок?<br>
				 Подарите удовольствие от выбора вместе с подарочными картами «Буслiк»:
			</p>
			<ol class="colored-list">
				<li>
				<p class="text-wrap">
					 Благодаря нашей карте Ваши близкие смогут сами выбрать действительно нужную им вещь;
				</p>
 </li>
				<li>
				<p class="text-wrap">
					 Карта действует на весь ассортимент товаров сети «Буслiк», при этом все акции и скидки сохраняются;
				</p>
 </li>
				<li>
				<p class="text-wrap">
					 Вы можете выбрать подходящий именно Вам номинал карты - на 20, 50 или 100 руб.;
				</p>
 </li>
				<li>
				<p class="text-wrap">
					 Вы экономите свое время на поиске и выборе подарка;
				</p>
 </li>
				<li>
				<p class="text-wrap">
					 Карту можно приобрести по наличному или безналичному расчету в любом магазине сети!
				</p>
 </li>
			</ol>
			<p class="black-text">
				 Пусть Ваши подарки всегда будут удачными!
			</p>
			<div class="certificate-img-row">
				<div class="row">
					<div class="col-md-4 col-sm-4 col-xs-4">
 <img src="/local/templates/buslik/static/img/images/sertifikat50.jpg" alt="">
					</div>
					<div class="col-md-4 col-sm-4 col-xs-4">
 <img src="/local/templates/buslik/static/img/images/sertifikat50.jpg" alt="">
					</div>
					<div class="col-md-4 col-sm-4 col-xs-4">
 <img src="/local/templates/buslik/static/img/images/sertifikat50.jpg" alt="">
					</div>
				</div>
			</div>
			<p class="black-text">
				 Правила использования подарочной карты:
			</p>
			<ol class="colored-list">
				<li>
				<p class="text-wrap">
					 Подарочную карту можно приобрести в любом супермаркете «Буслiк» (и в интернет-магазине) за наличный или безналичный расчет;
				</p>
 </li>
				<li>
				<p class="text-wrap">
					 Подарочная карта дает право на приобретение любых товаров в сети «Буслiк» в рамках указанного на ней номинала, при этом все акции и скидки сохраняются;
				</p>
 </li>
				<li>
				<p class="text-wrap">
					 Номинал подарочной карты должен быть использован полностью при разовой покупке. Стоимость единовременно приобретаемых товаров должна быть равной либо выше номинала карты. Если сумма покупки выше номинала, то возникшую разницу предъявитель карты доплачивает в кассу магазина. В случае, если сумма покупки меньше указанного номинала, карта не может быть использована.
				</p>
 </li>
				<li>
				<p class="text-wrap">
					 Одновременно может быть использовано несколько подарочных карт при соответствии условиям пункта №3 настоящих правил;
				</p>
 </li>
				<li>
				<p class="text-wrap">
					 Карта не является именной и может быть безвозмездно передана третьим лицам;
				</p>
 </li>
				<li>
				<p class="text-wrap">
					 Карта не подлежит возврату либо обмену на денежные средства в кассах магазинов сети;
				</p>
 </li>
				<li>
				<p class="text-wrap">
					 Карты подлежат изъятию кассиром в момент совершения покупки.
				</p>
 </li>
				<li>
				<p class="text-wrap">
					 В случае утери, кражи, порчи и т.п. карта не подлежит восстановлению.
				</p>
 </li>
				<li>
				<p class="text-wrap">
					 Срок действия карты - 1 год с момента приобретения.
				</p>
 </li>
			</ol>
			<p class="black-text">
				 Сертификаты
			</p>
			<p class="text-wrap">
				 Выберите подходящие Вам номиналы сертификатов и нажмите кнопку "Купить сертификат"
			</p>
			<div class="certificate-order">
				<div class="row">
					<div class="col-md-6">
 <img src="/local/templates/buslik/static/img/images/cert-big.jpg" alt="">
					</div>
					<div class="col-md-6">
						<p class="black-text">
							 Подарочная карта
						</p>
						<p class="text-wrap">
							 Описание сертификата. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aenean euismod bibendum laoreet. Proin gravida dolor sit amet lacus accumsan et viverra justo commodo. Proin sodales pulvinar tempor. Описание сертификата.
						</p>
						<?$APPLICATION->IncludeComponent(
	"bitrix:news.list",
	"certificate",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ADD_SECTIONS_CHAIN" => "N",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "Y",
		"DETAIL_URL" => "#SITE_DIR#",
		"DISPLAY_BOTTOM_PAGER" => "N",
		"DISPLAY_DATE" => "N",
		"DISPLAY_NAME" => "N",
		"DISPLAY_PICTURE" => "N",
		"DISPLAY_PREVIEW_TEXT" => "N",
		"DISPLAY_TOP_PAGER" => "N",
		"FIELD_CODE" => array("ID","CODE",""),
		"FILE_404" => "",
		"FILTER_NAME" => "",
		"HIDE_LINK_WHEN_NO_DETAIL" => "N",
		"IBLOCK_ID" => "70",
		"IBLOCK_TYPE" => "1c_catalog",
		"INCLUDE_IBLOCK_INTO_CHAIN" => "N",
		"INCLUDE_SUBSECTIONS" => "N",
		"MESSAGE_404" => "",
		"NEWS_COUNT" => "",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
		"PAGER_SHOW_ALL" => "N",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_TEMPLATE" => ".default",
		"PAGER_TITLE" => "Новости",
		"PARENT_SECTION" => "",
		"PARENT_SECTION_CODE" => "",
		"PREVIEW_TRUNCATE_LEN" => "",
		"PROPERTY_CODE" => array("",""),
		"SET_BROWSER_TITLE" => "N",
		"SET_LAST_MODIFIED" => "N",
		"SET_META_DESCRIPTION" => "N",
		"SET_META_KEYWORDS" => "N",
		"SET_STATUS_404" => "N",
		"SET_TITLE" => "N",
		"SHOW_404" => "N",
		"SORT_BY1" => "SORT",
		"SORT_BY2" => "SORT",
		"SORT_ORDER1" => "ASC",
		"SORT_ORDER2" => "ASC"
	)
);?>
						<div class="add-certificate">
 <a href="">Добавить еще один сертификат</a>
						</div>
						 <a class="btn btn-danger buy-Certificate" type="submit" >Купить</a><br>
 <br>
 <br>
					</div>
				</div>
			</div>
			 </section> <section class="seo-text text-wrap">
			<p>
				 Гипертекст здесь отдельно нужен? , содержит сведения о доставке и прочее.. Доставка товаров курьером осуществляется в пределах г.Минска и до 5 км от МКАД, в Боровляны, п.Лесной, Колодищи, Сосны, Валерьянова, Ратомка ежедневно с 12.00 – 22.00 БЕЗ ВЫХОДНЫХ. <br>
				 Доставка осуществляется день в день, если прием заказа осуществлен до 15.00. Если адрес доставки лежит за пределами МКАД, прием заказа осуществляется до 13 00.
			</p>
			<p>
				 Обязательно укажите точный адрес доставки и контактный номер телефона. Перед тем, как доставить заказ, курьер обязательно созвонится с вами для уточнения времени доставки.
			</p>
			<p>
				 Бесплатная доставка осуществляется при заказе на сумму от 300 000 рублей! Вы также можете воспользоваться услугами платной доставки - 40 000 руб., если ваш заказ на сумму менее 300 000 руб.
			</p>
 </section>
		</div>
	</div>
</div>
 </section><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>