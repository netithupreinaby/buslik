<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();
?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
<?if($arParams["POPUP"]):?>
<div style="display:none">
<div id="bx_auth_float" class="bx-auth-float">
<?endif?>

<?if($arParams["~CURRENT_SERVICE"] <> ''):?>
<script type="text/javascript">
BX.ready(function(){BxShowAuthService('<?=CUtil::JSEscape($arParams["~CURRENT_SERVICE"])?>', '<?=$arParams["~SUFFIX"]?>')});
</script>
<?endif?>
<div class="bx-auth">
	<form method="post" name="bx_auth_services<?=$arParams["SUFFIX"]?>" target="_top" action="<?=$arParams["AUTH_URL"]?>">		
		<div class="bx-auth-services">
<?foreach($arParams["~AUTH_SERVICES"] as $service):?>
			<a href="javascript:void(0)" onclick="BxShowAuthService('<?=$service["ID"]?>', '<?=$arParams["SUFFIX"]?>')" id="bx_auth_href_<?=$arParams["SUFFIX"]?><?=$service["ID"]?>"><i class="bx-ss-icon <?=htmlspecialchars($service["ICON"])?>"></i></a>
<?endforeach?>
		</div>		
		<div class="bx-auth-service-form" id="bx_auth_serv<?=$arParams["SUFFIX"]?>" style="display:none">
<?foreach($arParams["~AUTH_SERVICES"] as $service):?>
			<div id="bx_auth_serv_<?=$arParams["SUFFIX"]?><?=$service["ID"]?>" style="display:none"><?=$service["FORM_HTML"]?></div>
<?endforeach?>
		</div>
<?foreach($arParams["~POST"] as $key => $value):?>
		<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
<?endforeach?>
		<input type="hidden" name="auth_service_id" value="" />
	</form>
</div>

<?if($arParams["POPUP"]):?>
</div>
</div>
<?endif?>

<style>
    .bx-ss-icon {background-image:url('/bitrix/js/socialservices/css/icons.png'); width:16px; height:16px; background-repeat:no-repeat;}
    .openid {background-position:0px -192px;}
    .yandex {background-position:0px 0px;}
    .openid-mail-ru {background-position:0px -48px;}
    .livejournal {background-position:0px -16px;}
    .liveinternet {background-position:0px -128px;}
    .blogger {background-position:0px -144px;}
    .rambler {background-position:0px -160px;}
    .liveid {background-position:0px -176px;}
    .facebook {background-position:0px -32px;}
    .twitter {background-position:0px -96px;}
    .vkontakte {background-position:0px -80px;}
    .mymailru {background-position:0px -64px;}
    .google {background-position:0px -112px;}
    .odnoklassniki {background-position:0px -208px;}

    .bx-ss-button {display:inline-block; background-image:url('/bitrix/js/socialservices/css/icons.png') !important; width:87px !important; height:21px !important; background-repeat:no-repeat; vertical-align:middle !important;}
    .liveid-button {background-position:0px -334px !important;}
    .facebook-button {background-position:0px -224px !important;}
    .twitter-button {background-position:0px -290px !important;}
    .vkontakte-button {background-position:0px -268px !important;}
    .mymailru-button {background-position:0px -246px !important;}
    .google-button {background-position:0px -312px !important;}
    .odnoklassniki-button {background-position:0px -356px !important;}

    td.to_twitter {text-align:center !important;}
    div.bx-auth {max-width:600px; margin-top:12px; margin-bottom:10px;}
    div.bx-auth form {padding:0; margin:0;}
    div.bx-auth-line {border-bottom:1px solid #E3E3E3; padding-bottom:10px;}
    div.bx-auth-title {font-size:140%; border-bottom:solid 2px #E3E3E3; padding-bottom:12px; }
    div.bx-auth-note{margin:18px 0px 6px 0px;}

    div.bx-auth-services  {padding:0px; margin:0px; overflow:hidden; zoom:1;}
    div.bx-auth-services div {display:block; float:left; margin:4px 6px 0px 0px; width:128px;}
    div.bx-auth-services a {display:inline-block; margin:1px; padding:4px 4px 4px 4px; text-decoration:none; color:#646464; outline:none;}
    div.bx-auth-services a:hover {margin:0px; border:1px solid #D9D9D9; text-decoration: none !important;}
    div.bx-auth-services a.bx-ss-selected {margin:0px; border:1px solid #D9D9D9; background-color:#EBEBEB;}
    div.bx-auth-services i {display:inline-block; margin:0px; margin-right:4px; vertical-align:middle; cursor:pointer;}
    div.bx-auth-services b {vertical-align:middle; font-weight:bold; font-size:12px; font-family:Tahoma,Verdana,Arial,sans-serif;}

    div.bx-auth-service-form {color:black; background-color:#EEEEEE; border-bottom:solid 1px #D6D6D6; margin-top:1px; padding:8px; vertical-align:middle; font-size:12px;}
    div.bx-auth-service-form span, div.bx-auth-service-form input { vertical-align:middle;}
    div.bx-auth-service-form input.button {position:relative; left:1px;}
    div.bx-auth-service-form span.openid {display:inline-block; margin-right:6px;}

    span.bx-spacer {display:inline-block; width:8px;}
    span.bx-spacer-vert {display:inline-block; height:10px;}
    span.bx-spacer-vert25 {display:inline-block; height:25px;}

    div.bx-auth-serv-icons {}
    div.bx-auth-serv-icons a {display:inline-block; margin:1px; text-decoration:none; color:#646464; outline:none;}
    div.bx-auth-serv-icons a:hover {margin:0px; border:1px solid #D9D9D9;}
    div.bx-auth-serv-icons i {margin:3px; display:inline-block; vertical-align:middle;  cursor:pointer;}
    div.bx-auth-lbl {margin-top:8px; margin-bottom:4px;}

    div.bx-auth-float {padding-left:15px; padding-right:15px; font-size:100%;}

    div.bx-sonet-profile-field-socserv {line-height: 15px !important;}

    div.bx-auth-form {width: 510px !important;}

    textarea.ss-text-for-message-default {width: 300px; height: 130px; color:grey;  font-weight:lighter;}
    td.bx-ss-soc-serv { font: bold 12px/15px "Helvetica Neue",Helvetica,Arial,sans-serif; }
    td.bx-ss-soc-serv i{ vertical-align:-35%;}

    div.bx-taimen-socserv-div {padding-bottom: 9px; padding-left: 50px; }
    a.ss-socserv-setup-link {padding-left: 10px; color: grey !important; text-decoration:underline; }
    .ss-text-without-border {padding-left:5px; border: none; background: #F8FAFB; color: #6286bb; font-style:italic; }

    td.bx-ss-soc-serv-setup {white-space: pre-line; width: 300px; font-size: 75%;}
    a.bx-ss-soc-serv-setup-link{text-decoration:underline !important;}
</style>

<script>
function BxShowAuthService(id, suffix)
{
	var bxCurrentAuthId = ''; 
	if(window['bxCurrentAuthId'+suffix])
		bxCurrentAuthId = window['bxCurrentAuthId'+suffix];

	BX('bx_auth_serv'+suffix).style.display = '';
	if(bxCurrentAuthId != '' && bxCurrentAuthId != id)
	{
		BX('bx_auth_href_'+suffix+bxCurrentAuthId).className = '';
		BX('bx_auth_serv_'+suffix+bxCurrentAuthId).style.display = 'none';
	}
	BX('bx_auth_href_'+suffix+id).className = 'bx-ss-selected';
	BX('bx_auth_href_'+suffix+id).blur();
	BX('bx_auth_serv_'+suffix+id).style.display = '';
	var el = BX.findChild(BX('bx_auth_serv_'+suffix+id), {'tag':'input', 'attribute':{'type':'text'}}, true);
	if(el)
		try{el.focus();}catch(e){}
	window['bxCurrentAuthId'+suffix] = id;
    if(document.forms['bx_auth_services'+suffix])
        document.forms['bx_auth_services'+suffix].auth_service_id.value = id;
    else if(document.forms['bx_user_profile_form'+suffix])
        document.forms['bx_user_profile_form'+suffix].auth_service_id.value = id;
}

var bxAuthWnd = false;
function BxShowAuthFloat(id, suffix)
{
	var bCreated = false;
	if(!bxAuthWnd)
	{
		bxAuthWnd = new BX.CDialog({
			'content':'<div id="bx_auth_float_container"><\/div>', 
			'width': 640,
			'height': 400,
			'resizable': false
		});
		bCreated = true;
	}
	bxAuthWnd.Show();

	if(bCreated)
		BX('bx_auth_float_container').appendChild(BX('bx_auth_float'));
			
	BxShowAuthService(id, suffix);
}
</script>