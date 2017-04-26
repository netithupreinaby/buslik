<?
ob_start ();
/**
 * @author Ilya Faleyev <isfaleev@gmail.com>
 * @copyright 2004-2014 (c) ROMZA
 */
echo '<?xml version="1.0"?>';?>

<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
	<channel>
		<title><?=$arResult["SITE"]?></title>
		<link><?="http://".$_SERVER["SERVER_NAME"]?></link>
		<description><?=$arResult["COMPANY"]?></description>
		<?
		foreach($arResult["OFFER"] as $arOffer):?>

		<item>
			<title><?=$arOffer["MODEL"]?></title>
			<link><?=$arOffer["URL"]?></link>
			<description><?=$arOffer["DESCRIPTION"]?></description>
			<g:id><?=$arOffer["ID"]?></g:id>
			<g:condition>new</g:condition>
			<?if (!empty($arOffer["OLD_PRICE"])):?>
				<g:sale_price><?=$arOffer["PRICE"]?> <?=($arOffer["CURRENCY"]?$arOffer["CURRENCY"]:$arParams["CURRENCY"])?></g:sale_price>
				<g:price><?=$arOffer["OLD_PRICE"]?> <?=($arOffer["OLD_CURENCY"]?$arOffer["OLD_CURENCY"]:$arParams["CURRENCY"])?></g:price>
			<?else:?>
				<g:price><?=$arOffer["PRICE"]?> <?=($arOffer["CURRENCY"]?$arOffer["CURRENCY"]:$arParams["CURRENCY"])?></g:price>
			<?endif;?>

			<g:availability><?if($arOffer["AVAIBLE"]=='false'):?>preorder<?else:?>in stock<?endif?></g:availability>
			<g:image_link><?=$arOffer["PICTURE"]?></g:image_link><?
			
			foreach($arOffer["MORE_PHOTO"] as $image):?>

			<g:additional_image_link><?=$image?></g:additional_image_link><?
			
			endforeach;
			
			if($arParams["LOCAL_DELIVERY_COST"]):?>

			<g:shipping>
				<g:country>RU</g:country>
<?//				<g:service>Standard</g:service>?>
				<g:price><?=$arParams["LOCAL_DELIVERY_COST"]?></g:price>
			</g:shipping><?
			endif;

			if ($arOffer["DISPLAY_PROPERTIES"][$arParams["GOOGLE_GTIN"]]["DISPLAY_VALUE"]):?>

			<g:gtin><?=htmlspecialcharsbx($arOffer["DISPLAY_PROPERTIES"][$arParams["GOOGLE_GTIN"]]["DISPLAY_VALUE"])?></g:gtin><?
			
			endif;

//			<g:mpn>M2262D-PC</g:mpn>

			if ($arOffer["DISPLAY_PROPERTIES"][$arParams["DEVELOPER"]]["DISPLAY_VALUE"]):?>

			<g:brand><?=htmlspecialcharsbx($arOffer["DISPLAY_PROPERTIES"][$arParams["DEVELOPER"]]["DISPLAY_VALUE"])?></g:brand><?
			
			endif;
			
			if ($arParams["MARKET_CATEGORY_CHECK"] == 'Y'):?>

			<g:google_product_category><?=str_replace('/', ' &gt; ', $arOffer["MARKET_CATEGORY"])?></g:google_product_category><?
			
			endif?>

			<g:product_type><?=$arOffer["CATEGORY"]?></g:product_type><?
			
			if (!empty($arOffer["GROUP_ID"])):?>

			<g:item_group_id><?=$arOffer["GROUP_ID"]?></g:item_group_id><?
			
			endif;?>

			<?if (!empty($arOffer["SALES_NOTES_OFFER"])):?>
				<g:sales_notes><?=$arOffer["SALES_NOTES_OFFER"]?></g:sales_notes>
			<?endif;?>
		</item><?
		endforeach?>

	</channel>
</rss>

<?$buffer = ob_get_clean();
 if (LANG_CHARSET != $arParams['FORCE_CHARSET']) {
	 $buffer = $APPLICATION->ConvertCharset($buffer, LANG_CHARSET, $arParams['FORCE_CHARSET']);
 }
 echo $buffer;
?>