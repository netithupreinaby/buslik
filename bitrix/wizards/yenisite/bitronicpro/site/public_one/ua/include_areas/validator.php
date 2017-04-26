<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$URL = htmlspecialchars($APPLICATION->GetCurPage());?>
<a href="http://validator.w3.org/check?uri=<?=SITE_SERVER_NAME.$URL?>"><img src="<?=SITE_TEMPLATE_PATH."/static/img/html5.png"?>" alt="html5"></a>
<a href="http://www.cssportal.com/css-validator/validate.htm?url=<?=SITE_SERVER_NAME.$URL?>"><img src="<?=SITE_TEMPLATE_PATH."/static/img/css3.png"?>" alt="css3"></a>
