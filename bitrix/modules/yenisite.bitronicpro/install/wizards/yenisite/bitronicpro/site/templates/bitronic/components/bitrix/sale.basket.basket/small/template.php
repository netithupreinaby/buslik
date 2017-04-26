<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>

<?
global $basket_set;
 $basket_set = $arParams[BASKET_PHOTO] ;
?>

<div class="count">
	
<?
if (StrLen($arResult["ERROR_MESSAGE"])<=0)
{
	?>
	<form method="post" id='basket_form' action="<?=POST_FORM_ACTION_URI?>" name="basket_form">
		<?
		if ($arResult["ShowReady"]=="Y")
		{
			include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items.php");
		}

		if ($arResult["ShowDelay"]=="Y")
		{
			//include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_delay.php");
		}

		if ($arResult["ShowNotAvail"]=="Y")
		{
			//include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_notavail.php");
		}
		?>
	</form>
	<?
}

if( count($arResult["ITEMS"]["AnDelCanBuy"]) == 0 ){
	echo "<div class='node'><b>".GetMessage('EMPTY')."</b></div>";
}
?>
	
</div>