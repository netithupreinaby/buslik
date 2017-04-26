<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?echo '<?xml version="1.0" encoding="'. LANG_CHARSET. '"?>';?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="<?=$arResult["DATE"]?>">
    <shop>
        <name><?=$arResult["SITE"]?></name>
        <company><?=$arResult["COMPANY"]?></company>
        <url><?="http://".$_SERVER["SERVER_NAME"]?></url>
		
        <currencies>
			<?if ( !empty($arResult["CURRENCIES"]) ):?>
				<?foreach($arResult["CURRENCIES"] as $k=>$cur):?>
					<?if(!empty($cur) && $cur != 'RUR'):?><currency id="<?=$cur?>"<?if ( $cur == 'RUB' ):?> rate="1"<?endif;?>/><?endif;?>
				<?endforeach;?>
			<?else:?>
				<currency id="<?=$arParams["CURRENCY"]?>" rate="1"/>
			<?endif;?>
        </currencies>
		
	<categories>
<?foreach($arResult["CATEGORIES"] as $arCategory):?>
		<category id="<?=$arCategory["ID"]?>"<?
if($arCategory["PARENT"])
	echo ' parentId="'. $arCategory['PARENT']. '"';
?>><?=$arCategory["NAME"]?></category>
<?endforeach;?>
	</categories>	
	<?if($arParams["LOCAL_DELIVERY_COST"]):?>
	<local_delivery_cost><?=$arParams["LOCAL_DELIVERY_COST"]?></local_delivery_cost>
	<?endif?>
        <offers>
        <?foreach($arResult["OFFER"] as $arOffer):?>
			<offer type="artist.title" id="<?=$arOffer["ID"]?>" available="<?=$arOffer["AVAIBLE"]?>">
				<url><?=$arOffer["URL"]?></url>
				<price><?=$arOffer["PRICE"]?></price>
				<?if (!empty($arOffer["OLD_PRICE"])):?>
					<oldprice><?=$arOffer["OLD_PRICE"]?></oldprice>
				<?endif;?>
				
				<currencyId>
					<?if ( !empty($arOffer["CURRENCY"]) ):?>
						<?=$arOffer["CURRENCY"]?>
					<?else:?>
						<?=$arParams["CURRENCY"]?>
					<?endif;?>
				</currencyId>
				
				<categoryId><?=$arOffer["CATEGORY"]?></categoryId>
				<?if($arOffer["PICTURE"]):?><picture><?=$arOffer["PICTURE"]?></picture><?endif?>
				<?if($arParams["LOCAL_DELIVERY_COST"]):?><delivery>true</delivery><?endif?>
				<title><?=$arOffer["MODEL"]?></title>
				<?if($arOffer["DISPLAY_PROPERTIES"][$arParams["YEAR"]]["DISPLAY_VALUE"]):?><year><?=$arOffer["DISPLAY_PROPERTIES"][$arParams["YEAR"]]["DISPLAY_VALUE"]?></year><?endif?>								
				<?if($arOffer["DISPLAY_PROPERTIES"][$arParams["MEDIA"]]["DISPLAY_VALUE"]):?><media><?=$arOffer["DISPLAY_PROPERTIES"][$arParams["MEDIA"]]["DISPLAY_VALUE"]?></media><?endif?>								
				<?if($arOffer["DISPLAY_PROPERTIES"][$arParams["STARRING"]]["DISPLAY_VALUE"]):?><starring><?=$arOffer["DISPLAY_PROPERTIES"][$arParams["STARRING"]]["DISPLAY_VALUE"]?></starring><?endif?>				
				<?if($arOffer["DISPLAY_PROPERTIES"][$arParams["DIRECTOR"]]["DISPLAY_VALUE"]):?><director><?=$arOffer["DISPLAY_PROPERTIES"][$arParams["DIRECTOR"]]["DISPLAY_VALUE"]?></director><?endif?>				
				<?if($arOffer["DISPLAY_PROPERTIES"][$arParams["ORIGINALNAME"]]["DISPLAY_VALUE"]):?><originalName><?=$arOffer["DISPLAY_PROPERTIES"][$arParams["ORIGINALNAME"]]["DISPLAY_VALUE"]?></originalName><?endif?>				
				<?if($arOffer["DISPLAY_PROPERTIES"][$arParams["COUNTRY"]]["DISPLAY_VALUE"]):?><country><?=$arOffer["DISPLAY_PROPERTIES"][$arParams["COUNTRY"]]["DISPLAY_VALUE"]?></country><?endif?>								
				<?if($arOffer["DISPLAY_PROPERTIES"][$arParams["BARCODE"]]["DISPLAY_VALUE"]):?><barcode><?=$arOffer["DISPLAY_PROPERTIES"][$arParams["PERFORMED_BY"]]["DISPLAY_VALUE"]?></barcode><?endif?>								
				<?if($arOffer["DESCRIPTION"]):?><description><?=$arOffer["DESCRIPTION"]?></description><?endif?>
			</offer>
        <?endforeach;?>
        </offers>
    </shop>
</yml_catalog>