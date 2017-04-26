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
			<offer type="book" id="<?=$arOffer["ID"]?>" available="<?=$arOffer["AVAIBLE"]?>">
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
				
				<?if($arOffer["DISPLAY_PROPERTIES"][$arParams["AUTHOR"]]["DISPLAY_VALUE"]):?><author><?=$arOffer["DISPLAY_PROPERTIES"][$arParams["AUTHOR"]]["DISPLAY_VALUE"]?></author><?endif?>
				
				<name><?=$arOffer["MODEL"]?></name>

				<?if($arOffer["DISPLAY_PROPERTIES"][$arParams["PUBLISHER"]]["DISPLAY_VALUE"]):?><publisher><?=$arOffer["DISPLAY_PROPERTIES"][$arParams["PUBLISHER"]]["DISPLAY_VALUE"]?></publisher><?endif?>								
				<?if($arOffer["DISPLAY_PROPERTIES"][$arParams["SERIES"]]["DISPLAY_VALUE"]):?><series><?=$arOffer["DISPLAY_PROPERTIES"][$arParams["SERIES"]]["DISPLAY_VALUE"]?></series><?endif?>								
				<?if($arOffer["DISPLAY_PROPERTIES"][$arParams["YEAR"]]["DISPLAY_VALUE"]):?><year><?=$arOffer["DISPLAY_PROPERTIES"][$arParams["YEAR"]]["DISPLAY_VALUE"]?></year><?endif?>								
				<?if($arOffer["DISPLAY_PROPERTIES"][$arParams["ISBN"]]["DISPLAY_VALUE"]):?><ISBN><?=$arOffer["DISPLAY_PROPERTIES"][$arParams["ISBN"]]["DISPLAY_VALUE"]?></ISBN><?endif?>								
				<?if($arOffer["DISPLAY_PROPERTIES"][$arParams["VOLUME"]]["DISPLAY_VALUE"]):?><volume><?=$arOffer["DISPLAY_PROPERTIES"][$arParams["VOLUME"]]["DISPLAY_VALUE"]?></volume><?endif?>								
				<?if($arOffer["DISPLAY_PROPERTIES"][$arParams["PART"]]["DISPLAY_VALUE"]):?><part><?=$arOffer["DISPLAY_PROPERTIES"][$arParams["PART"]]["DISPLAY_VALUE"]?></part><?endif?>								
				<?if($arOffer["DISPLAY_PROPERTIES"][$arParams["BINDING"]]["DISPLAY_VALUE"]):?><binding><?=$arOffer["DISPLAY_PROPERTIES"][$arParams["BINDING"]]["DISPLAY_VALUE"]?></binding><?endif?>								
				<?if($arOffer["DISPLAY_PROPERTIES"][$arParams["PAGE_EXTENT"]]["DISPLAY_VALUE"]):?><page_extent><?=$arOffer["DISPLAY_PROPERTIES"][$arParams["PAGE_EXTENT"]]["DISPLAY_VALUE"]?></page_extent><?endif?>												
				<?if($arOffer["DESCRIPTION"]):?><description><?=$arOffer["DESCRIPTION"]?></description><?endif?>
			</offer>
        <?endforeach;?>
        </offers>
    </shop>
</yml_catalog>