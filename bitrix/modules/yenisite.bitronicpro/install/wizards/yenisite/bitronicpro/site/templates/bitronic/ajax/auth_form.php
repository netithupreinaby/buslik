<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
?>

<?
global $USER;
if ($USER->IsAuthorized()) :
    $rsSites = CSite::GetByID(SITE_ID);
    $arSite = $rsSites->Fetch();
    $host = $_SERVER['HTTP_HOST'];
    if ($port = strrchr($host, ':')) $host = substr($host, 0, $port);
    $hostname  = 'http://'  . $host;
    $hostname2 = 'https://' . $host;
    if(strpos($_SERVER['HTTP_REFERER'], $hostname)  === 0
    ||  strpos($_SERVER['HTTP_REFERER'], $hostname2) === 0){
		echo '<script>var path = window.location.pathname; if (path.lastIndexOf("auth_form.php") == path.length-13) window.location = "',$arSite['DIR'],'"; else window.location.reload();</script>Login already. Reloading...';
    } else {
    	LocalRedirect($arSite['DIR']);
    }
else :
	
$APPLICATION->IncludeComponent("bitrix:system.auth.authorize", "popup", Array(), false, array("HIDE_ICONS" => "Y"));
?>

<script>
    
    $(document).ready(function(){
       popup = $('#form_auth');
	   popup.validate({
            errorPlacement: function(error, element)
            {
                YSErrorPlacement(error, element, popup);
            },
            success: $.noop,
            rules: {
                'USER_LOGIN': {
                    required: true,
                },
                'USER_PASSWORD': {
                    required: true,
                },
           },
           submitHandler: function(form) {
               
                var loaderObj = $('#login-popup button.button span.notloader');
                YSstartButtonLoader(loaderObj);
                var formData = $(form).serialize();
                var url = $(form).attr('action');
                $.ajax({
                    type: "POST",
                    url: url,
                    data: formData,
                    dataType : "html",
                    success: function(data){
                        html = $.parseHTML( data );
                        
                        var error = $(html).find('span.error');

                        if (error.length > 0)
                        {
                            YSstopButtonLoader(loaderObj);
                            var theme = 'error';
                            
                            $(error).each(function(idx, val){
                                jGrowl($(val).html(), theme);
                            });
                            
                            $('#login-popup').html(data);
                        }
                        else
                            location.reload();
                    }
                });
                
                return false;
           }
        });
        
		popup.valid();
        $('#form_auth input:text:first').focus();
        
    });
</script>

<?
endif;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>
