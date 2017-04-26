<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$APPLICATION->IncludeComponent(
	"yenisite:feedback.add",
	"popup",
	Array(
		"IBLOCK_TYPE" => "#IBLOCK_TYPE#",
		"IBLOCK" => "#IBLOCK_ID#",
		"NAME_FIELD" => "EMAIL",
                "COLOR_SCHEME" => "green",
                "TITLE" => "#TITLE#",
		"SUCCESS_TEXT" => "#SUCCESS_TEXT#",
		"USE_CAPTCHA" => "N",
                "SHOW_SECTIONS" => "Y",
		"PRINT_FIELDS" => "",
		"AJAX_MODE" => "Y",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "300",
		"ACTIVE" => "Y",
		"EVENT_NAME" => "",
		"TEXT_REQUIRED" => "N",
                "TEXT_SHOW" => "N",
                "NAME" => 'NAME',
		"EMAIL" => 'EMAIL',
		"PHONE" => 'PHONE',
                "ELEMENT_ID" => $_REQUEST['ELEMENT_ID'],
		"MESSAGE" => $_POST["romza_feedback"]["text"],
	),
    false,
    array("HIDE_ICONS" => "Y")
);
?>

<script>
    $(document).ready(function(){
		var popupDivID = 'ys-element_exist-popup';
        var popup = $('#'+popupDivID+' #ys-guestbook')
        popup.validate({
            errorPlacement: function(error, element)
            {
                YSErrorPlacement(error, element, popup);
            },
            success: $.noop,
            rules: {
                'romza_feedback[EMAIL]': {
                    required: true,
                    email: true,
                },
           },
           
           submitHandler: function(form) {
                var loaderObj = popup.find('button.button2 span.notloader');
                YSstartButtonLoader(loaderObj);
                form.submit();
           }
        });
        init_ys_guestbook_popup(popupDivID);
    });
</script>

<style>
.bx-core-waitwindow{
   display: none !important;
}
</style>

<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>