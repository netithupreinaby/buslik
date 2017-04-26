<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$ElementID = $_REQUEST['PRODUCT_ID'];


if($_SERVER["REQUEST_METHOD"] == "POST" && $_REQUEST["submit"] <> '' && !defined('BX_UTF')) 
{
	$_REQUEST["FASTORDER_NAME"] = $APPLICATION->ConvertCharset($_REQUEST["FASTORDER_NAME"], 'utf-8', LANG_CHARSET);
}

$APPLICATION->IncludeComponent("bitrix:main.include", "", Array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include_areas/counter_ya_metrika.php", "EDIT_TEMPLATE" => "include_areas_template.php"), false, array("HIDE_ICONS" => "Y"));

$APPLICATION->IncludeComponent("grain:fastorder", "popup", array(
	"USE_CAPTCHA" => "Y",
	"OK_TEXT" => GetMessage("CATALOG_POPUP_TITLE_IN_ONE_CLICK"),
	"SHOW_FIELDS" => array(
		0 => "NAME",
		1 => "EMAIL",
		2 => "PHONE",
	),
	"REQUIRED_FIELDS" => array(
		0 => "PHONE",
	),
	"MODIFY_CART" => "REMOVE",
	"PRODUCT_ID" => array(
		0 => $ElementID,
		1 => "",
	),
	"PERSON_TYPE" => "1",
	"PROP_NAME" => "1",
	"PROP_EMAIL" => "2",
	"PROP_PHONE" => "3"
	),
	false,
    array("HIDE_ICONS" => "Y")
);
?>
<script>
    $(document).ready(function(){
		var popup = $('#fastorder_form')
        popup.validate({
            errorPlacement: function(error, element)
            {
				YSErrorPlacement(error, element, popup);
            },
            success: $.noop,
            rules: {
                'FASTORDER_EMAIL': {
                    email: true,
                },
                'FASTORDER_PHONE': {
                    required: true,
                },
           },
           
           submitHandler: function(form) {
               //$('input[name=submit]').val('sad');
                
//                var loaderObj = $('input[name=submit]');
                var loaderObj = $('#ys-fastorder-popup button[type=submit] span.notloader');
                YSstartButtonLoader(loaderObj);
                var formData = $(form).serialize() + '&submit=submit';
                var url = $(form).attr('action');
                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    dataType : "html",
                    success: function(data){
                        html = $.parseHTML( data );

                        var theme           = 'ok';
                        var success_text    = undefined;
                        var error_text      = undefined;
                        if ($(html).find('span.success').length > 0)
                        {
                            var success_text = $(html).find('span.success');
                            var theme = 'ok';

                            $('#mask').click();
                        }

                        if ($(html).find('span.error_message').length > 0)
                        {
                            var error_text = $(html).find('span.error_message');
                            var theme = 'error';
                        }

                        var text = error_text || success_text;

                        $(text).each(function(idx, val){
                            jGrowl($(val).html(), theme);
                        });

                    }
                });
                return false;
           }
        });
        
        popup.valid();
        $('#fastorder_form input:text:first').focus();
    });
</script>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>
