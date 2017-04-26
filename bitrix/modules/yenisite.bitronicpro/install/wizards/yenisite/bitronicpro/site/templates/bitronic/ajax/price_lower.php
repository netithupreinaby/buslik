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
		var popupDivID = 'ys-price_lower-popup';
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
	
		var str = $("#ys_top_price");
		str.find(".oldprice").remove();
		str = str.text();
		
		var myRe = /([\D]*)([0-9\,\s]*[\,\s]*[0-9]+)([\D]*)/ig;
		var myArray = myRe.exec(str);
		
        var max = parseFloat(myArray[2].replace(/\s/g, ''));
       
		var ltext = myArray[1];
		var rtext = myArray[3];
		
		var init_min = 1;

        $('input[name="romza_feedback[PRICE]"]').hide();
        $('input[name="romza_feedback[PRICE]"]').after("<div id='slider-want_price_lower' class='ys_slider'></div>\n\<span id='want_price_lower_text'>#I_WANT_TEXT#: "+ltext+"<span id='want_price_lower_want'>"+init_min+"</span>"+rtext+"\n\#DIFF_TEXT#: "+ltext+"<span id='want_price_lower_diff'>"+(max - init_min)+"</span>"+rtext+"</span>");
        
        
        $('input[name="romza_feedback[PRICE]"]').val(init_min);
        
        $('#slider-want_price_lower').slider({
            range: "min",
            min: init_min,
            max: max,
            step: 1,
            value: init_min,
            slide: function(event, ui) {
                $('input[name="romza_feedback[PRICE]"]').val(ui.value);
                $('span#want_price_lower_want').html(ui.value);
                $('span#want_price_lower_diff').html(max - ui.value);
            }
        }); 
    });
</script>

<style>

.bx-core-waitwindow{
   display: none !important;
}
</style>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");?>