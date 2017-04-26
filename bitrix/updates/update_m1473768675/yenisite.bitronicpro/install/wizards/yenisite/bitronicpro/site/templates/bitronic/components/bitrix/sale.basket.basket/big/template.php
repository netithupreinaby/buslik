<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?/*?>
		<div id="container">
			<article class="no_border">
            	<div class="crumbs">
                	<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "", array(),	false );?>
                </div><!--.crumbs-->
                <h1><?=$APPLICATION->GetTitle();?></h1>
<?*/?>
				<div class="user_basket">
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
else
	echo "<b>".GetMessage('EMPTY')."</b>";
?>

                 
                </div><!--.user_basket-->
<?/*?>
	  </article><!-- #content-->
  </div><!-- #container-->
<?*/?>