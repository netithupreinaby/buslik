<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("");?><?$APPLICATION->SetTitle("Избранное");?>
<section class="main-content">
	<section class="wishes">
<?$APPLICATION->IncludeComponent(
	"bitrix:menu",
	"dropdownPersonal",
	Array(
		"ALLOW_MULTI_SELECT" => "N",
		"CHILD_MENU_TYPE" => "left",
		"COMPONENT_TEMPLATE" => "topmenu2",
		"DELAY" => "N",
		"MAX_LEVEL" => "1",
		"MENU_CACHE_GET_VARS" => array(),
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_TYPE" => "N",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"ROOT_MENU_TYPE" => "user",
		"USE_EXT" => "Y"
	)
);?>
	<section class="cart-header">
		<div class="row">
			<div class="col-lg-12 col-sm-12">
				<div class="heading">
					<p class="page-title hidden-xs hidden-sm"><?php $APPLICATION->ShowTitle(); ?></p>
				</div>
			</div>
		</div>
	</section>

	<?php global $arrFilter; ?>
	<?php if ($_SESSION['USER_FAVORITES']) { ?>
		<?php $arrFilter = array("ID"=>$_SESSION['USER_FAVORITES']); ?>
	<?php } else { ?>
		<?php $arrFilter = array("ID"=>0); ?>
	<?php } ?>
	 <?$APPLICATION->IncludeComponent(
	"bitrix:catalog.sections.top", 
	"buslik", 
	array(
		"ACTION_VARIABLE" => "action",
		"BASKET_URL" => "/personal/basket.php",
		"CACHE_FILTER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"CONVERT_CURRENCY" => "N",
		"DETAIL_URL" => "",
		"DISPLAY_COMPARE" => "N",
		"ELEMENT_COUNT" => "9",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_FIELD2" => "id",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_ORDER2" => "desc",
		"FILTER_NAME" => "arrFilter",
		"HIDE_NOT_AVAILABLE" => "N",
		"IBLOCK_ID" => "58",
		"IBLOCK_TYPE" => "1c_catalog",
		"LINE_ELEMENT_COUNT" => "6",
		"PRICE_CODE" => array(
		),
		"PRICE_VAT_INCLUDE" => "Y",
		"PRODUCT_ID_VARIABLE" => "id",
		"PRODUCT_PROPERTIES" => array(
		),
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PROPERTY_CODE" => array(
			0 => "NOVINKA",
			1 => "POPULYARNYYTOVAR",
			2 => "LUCHSHEEPREDLOZHENIE",
			3 => "",
		),
		"SECTION_COUNT" => "20",
		"SECTION_FIELDS" => array(
			0 => "",
			1 => "",
		),
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"SECTION_SORT_FIELD" => "sort",
		"SECTION_SORT_ORDER" => "asc",
		"SECTION_URL" => "",
		"SECTION_USER_FIELDS" => array(
			0 => "UF_NEW",
			1 => "UF_DISCOUNT",
			2 => "",
		),
		"SHOW_PRICE_COUNT" => "1",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"USE_PRICE_COUNT" => "N",
		"USE_PRODUCT_QUANTITY" => "N",
		"COMPONENT_TEMPLATE" => "buslik"
	),
	false
);?>

 </section> </section>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>