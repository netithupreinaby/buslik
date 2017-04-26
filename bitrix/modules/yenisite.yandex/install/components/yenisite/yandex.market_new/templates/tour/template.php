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
			<offer type="tour" id="<?=$arOffer["ID"]?>" available="<?=$arOffer["AVAIBLE"]?>">
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
				<name><?=$arOffer["MODEL"]?></name>
				<?if($arOffer["DISPLAY_PROPERTIES"][$arParams["WORLDREGION"]]["DISPLAY_VALUE"]):?><worldRegion><?=$arOffer["DISPLAY_PROPERTIES"][$arParams["WORLDREGION"]]["DISPLAY_VALUE"]?></worldRegion><?endif?>								
				<?if($arOffer["DISPLAY_PROPERTIES"][$arParams["COUNTRY"]]["DISPLAY_VALUE"]):?><country><?=$arOffer["DISPLAY_PROPERTIES"][$arParams["COUNTRY"]]["DISPLAY_VALUE"]?></country><?endif?>								
				<?if($arOffer["DISPLAY_PROPERTIES"][$arParams["REGION"]]["DISPLAY_VALUE"]):?><region><?=$arOffer["DISPLAY_PROPERTIES"][$arParams["REGION"]]["DISPLAY_VALUE"]?></region><?endif?>								
				<?if($arOffer["DISPLAY_PROPERTIES"][$arParams["DAYS"]]["DISPLAY_VALUE"]):?><days><?=$arOffer["DISPLAY_PROPERTIES"][$arParams["DAYS"]]["DISPLAY_VALUE"]?></days><?endif?>				
				<?if($arOffer["DISPLAY_PROPERTIES"][$arParams["DATATOUR"]]["DISPLAY_VALUE"]):?><dataTour><?=$arOffer["DISPLAY_PROPERTIES"][$arParams["DATATOUR"]]["DISPLAY_VALUE"]?></dataTour><?endif?>				
				<?if($arOffer["DISPLAY_PROPERTIES"][$arParams["HOTEL_STARS"]]["DISPLAY_VALUE"]):?><hotel_stars><?=$arOffer["DISPLAY_PROPERTIES"][$arParams["HOTELSTARS"]]["DISPLAY_VALUE"]?></hotel_stars><?endif?>								
				<?if($arOffer["DISPLAY_PROPERTIES"][$arParams["ROOM"]]["DISPLAY_VALUE"]):?><room><?=$arOffer["DISPLAY_PROPERTIES"][$arParams["ROOM"]]["DISPLAY_VALUE"]?></room><?endif?>								
				<?if($arOffer["DISPLAY_PROPERTIES"][$arParams["MEAL"]]["DISPLAY_VALUE"]):?><meal><?=$arOffer["DISPLAY_PROPERTIES"][$arParams["MEAL"]]["DISPLAY_VALUE"]?></meal><?endif?>		
				<?if($arOffer["DISPLAY_PROPERTIES"][$arParams["INCLUDED"]]["DISPLAY_VALUE"]):?><included><?=$arOffer["DISPLAY_PROPERTIES"][$arParams["INCLUDED"]]["DISPLAY_VALUE"]?></included><?endif?>								
				<?if($arOffer["DISPLAY_PROPERTIES"][$arParams["TRANSPORT"]]["DISPLAY_VALUE"]):?><transport><?=$arOffer["DISPLAY_PROPERTIES"][$arParams["TRANSPORT"]]["DISPLAY_VALUE"]?></transport><?endif?>												
				<?if($arOffer["DESCRIPTION"]):?><description><?=$arOffer["DESCRIPTION"]?></description><?endif?>
			</offer>
        <?endforeach;?>
        </offers>
    </shop>
</yml_catalog>