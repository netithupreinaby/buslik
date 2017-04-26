<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
<div class="subscribe-edit">
<?

foreach($arResult["MESSAGE"] as $itemID=>$itemValue)
	//echo ShowMessage(array("MESSAGE"=>$itemValue, "TYPE"=>"OK"));		OLD ERROR MESSAGE (04.03.2013)
	{	/*	NEW ERROR MESSAGE	*/	
	$ERROR = preg_split("/<br>/",$itemValue);
	foreach($ERROR as $k=>$v)
		if(!empty($v))
			print_r( "<script>jGrowl('".$v."','ok');</script>");
}	/*		--------		*/
foreach($arResult["ERROR"] as $itemID=>$itemValue)
	//echo ShowMessage(array("MESSAGE"=>$itemValue, "TYPE"=>"ERROR"));	OLD ERROR MESSAGE (04.03.2013)
{	/*	NEW ERROR MESSAGE	*/	
	$ERROR = preg_split("/<br>/",$itemValue);
	foreach($ERROR as $k=>$v)
		if(!empty($v))
			print_r( "<script>jGrowl('".$v."','error');</script>");
}	/*		--------		*/

//whether to show the forms
if($arResult["ID"] == 0 && empty($_REQUEST["action"]) || CSubscription::IsAuthorized($arResult["ID"]))
{
	//show confirmation form
	if($arResult["ID"]>0 && $arResult["SUBSCRIPTION"]["CONFIRMED"] <> "Y")
	{
		include("confirmation.php");
	}
	//show current authorization section
	if($USER->IsAuthorized() && ($arResult["ID"] == 0 || $arResult["SUBSCRIPTION"]["USER_ID"] == 0))
	{
		include("authorization.php");
	}
	//show authorization section for new subscription
	if($arResult["ID"]==0 && !$USER->IsAuthorized())
	{
		if($arResult["ALLOW_ANONYMOUS"]=="N" || ($arResult["ALLOW_ANONYMOUS"]=="Y" && $arResult["SHOW_AUTH_LINKS"]=="Y"))
		{
			include("authorization_new.php");
		}
	}
	//setting section
	include("setting.php");
	//status and unsubscription/activation section
	if($arResult["ID"]>0)
	{
		include("status.php");
	}
	?>
	<p><span class="starrequired">*</span><?echo GetMessage("subscr_req")?></p>
	<?
}
else
{
	 //subscription authorization form
	include("authorization_full.php");
}
?>
</div>