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
					<?if(!empty($cur) && $cur != 'RUR'):?><currency id="<?=$cur?>"<?if ( $k == 0 ):?> rate="1"<?endif;?>/><?endif;?>
				<?endforeach;?>
			<?else:?>
				<currency id="<?=$arParams["CURRENCY"]?>" rate="1"/>
			<?endif;?>
        </currencies>
		
	<categories>
<?foreach($arResult["CATEGORIES"] as $id => $arCategory):?>
		<category id="<?=$id?>"<?
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
			<offer id="<?=$arOffer["ID"]?>" type="vendor.model" available="<?=$arOffer["AVAIBLE"]?>" <?if (!empty($arOffer["GROUP_ID"])):?>group_id="<?=$arOffer["GROUP_ID"]?>"<?endif;?>>
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
				
				<?if ($arParams['MARKET_CATEGORY_CHECK'] == "Y"):?>
					<market_category><?=$arOffer["MARKET_CATEGORY"]?></market_category>
				<?endif;?>
				
				<?if ( !empty( $arOffer["PICTURE"] ) ):?>
					<picture><?=$arOffer["PICTURE"]?></picture>
				<?endif;?>
				
				<?foreach ($arOffer["MORE_PHOTO"] as $pic):?>
					<picture><?=$pic?></picture>
				<?endforeach;?>
				
				<?if ( $arOffer["STORE"] ):?>
					<store><?=$arOffer["STORE"]?></store>
				<?endif;?>
				
				<?if ( $arOffer["PICKUP"] ):?>
					<pickup><?=$arOffer["PICKUP"]?></pickup>
				<?endif;?>
					
				<?if($arParams["LOCAL_DELIVERY_COST"]):?>
					<delivery>true</delivery>
				<?endif?>
				
				<vendor>
					<?if ( !empty($arOffer["DEVELOPER"]) ):?>
						<?=$arOffer["DEVELOPER"]?>
					<?else:?>
						<?=$arOffer["DISPLAY_PROPERTIES"][$arParams["DEVELOPER"]]["DISPLAY_VALUE"]?>
					<?endif;?>
				</vendor>
				
				<?if ( $arOffer["DISPLAY_PROPERTIES"][$arParams["VENDOR_CODE"]]["DISPLAY_VALUE"] ):?>
					<vendorCode>
						<?=$arOffer["DISPLAY_PROPERTIES"][$arParams["VENDOR_CODE"]]["DISPLAY_VALUE"]?>
					</vendorCode>
				<?endif;?>
					
				<?if ( !empty($arOffer["DISPLAY_PROPERTIES"][$arParams["MODEL"]]["DISPLAY_VALUE"]) && !empty($arOffer["DISPLAY_PROPERTIES"][$arParams["SERIES"]]["DISPLAY_VALUE"]) ):?>
					<model><?=$arOffer["DISPLAY_PROPERTIES"][$arParams["SERIES"]]["DISPLAY_VALUE"]?> <?=$arOffer["DISPLAY_PROPERTIES"][$arParams["MODEL"]]["DISPLAY_VALUE"]?></model>
					
				<?elseif ( !empty($arOffer["DISPLAY_PROPERTIES"][$arParams["MODEL"]]["DISPLAY_VALUE"]) ):?>
					<model><?=$arOffer["DISPLAY_PROPERTIES"][$arParams["MODEL"]]["DISPLAY_VALUE"]?></model>	
				<?else:?>
					<model><?=$arOffer["MODEL"]?></model>
				<?endif;?>
				
				<?if ( !empty( $arOffer["DESCRIPTION"] ) ):?>
					<description><?=$arOffer["DESCRIPTION"]?></description>
				<?endif;?>

				<?if ( !empty( $arOffer["SALES_NOTES_OFFER"] ) ):?>
					<sales_notes><?=$arOffer["SALES_NOTES_OFFER"]?></sales_notes>
				<?endif;?>
				
				<?if ( $arOffer["DISPLAY_PROPERTIES"][$arParams["MANUFACTURER_WARRANTY"]]["DISPLAY_VALUE"] ):?>
					<manufacturer_warranty>
						<?=$arOffer["DISPLAY_PROPERTIES"][$arParams["MANUFACTURER_WARRANTY"]]["DISPLAY_VALUE"]?>
					</manufacturer_warranty>
				<?endif;?>
				
				<?if ( !empty($arOffer["DISPLAY_PROPERTIES"][$arParams["COUNTRY"]]["DISPLAY_VALUE"]) ):?>
					<country_of_origin>
						<?=$arOffer["DISPLAY_PROPERTIES"][$arParams["COUNTRY"]]["DISPLAY_VALUE"]?>
					</country_of_origin>
				<?elseif ( !empty($arOffer["COUNTRY"]) ):?>
					<country_of_origin>
						<?=$arOffer["COUNTRY"]?>
					</country_of_origin>
				<?endif;?>
				
				<?foreach($arParams as $k=>$v):?>
				<?if(is_array($arOffer['LIST_PROPERTIES']) && !is_array($v)):?>
					<?foreach($arOffer["LIST_PROPERTIES"] as $key=>$val):?>
						<?if( $key == $k && $k != "DEVELOPER" && $k != "MODEL" && $k != "VENDOR_CODE" && $k != "COUNTRY" && $k != "MANUFACTURER_WARRANTY" ):?>
						<?if(!empty($arOffer["DISPLAY_PROPERTIES"][$v]["DISPLAY_VALUE"])):?>
<?
$dispName = &$arOffer["DISPLAY_PROPERTIES"][$v]['DISPLAY_NAME'];
switch ($k) {
	case 'PRODUCT_LINE': $dispName = GetMessage('NAME_PRODUCT_LINE'); break;
	case 'COLOR':        $dispName = GetMessage('NAME_COLOR'); break;
	case 'VOLUME':       $dispName = GetMessage('NAME_VOLUME'); break;
	case 'COLOR_RGB':    $dispName = GetMessage('NAME_COLOR_RGB'); break;
	case 'GENDER':       $dispName = GetMessage('NAME_GENDER'); break;
	default: break;
}
?>
							<param name="<?=$arOffer["DISPLAY_PROPERTIES"][$v]["DISPLAY_NAME"]?>" <?if( !empty($arOffer["UNIT"][$k]) ):?> unit="<?=$arOffer["UNIT"][$k]?>"<?endif;?>>
							<?=$arOffer["DISPLAY_PROPERTIES"][$v]["DISPLAY_VALUE"]?>
							</param>
						<?endif?>
						<?endif?>
					<?endforeach?>
				<?endif?>
				<?endforeach?>
				
				<?foreach($arParams["PARAMS"] as $k=>$v):?>
					<?foreach($arOffer["LIST_PROPERTIES"]["PARAMS"] as $key=>$val):?>
						<?if( $key == $v && $v != "DEVELOPER" && $v != "MODEL" && $v != "VENDOR_CODE" && $v != "MANUFACTURER_WARRANTY" ):?>
							<?if(!empty($arOffer["DISPLAY_PROPERTIES"][$v]["DISPLAY_VALUE"])):?>
								<param name="<?=$arOffer["DISPLAY_PROPERTIES"][$v]["DISPLAY_NAME"]?>"<?if( !empty($arOffer["UNIT"][$v]) ):?> unit="<?=$arOffer["UNIT"][$v]?>"<?endif;?>><?=$arOffer["DISPLAY_PROPERTIES"][$v]["DISPLAY_VALUE"]?></param>
							 <?endif?>
						 <?endif?>
					 <?endforeach?>
				<?endforeach?>
				
			<?if(is_array($arOffer['DISPLAY_CHARACTERISTICS'])):?>
				<?foreach($arOffer["DISPLAY_CHARACTERISTICS"] as $code=>$value):?>
					<?if(!empty($value["DISPLAY_VALUE"])):?>
						<param name="<?=$value["DISPLAY_NAME"]?>"><?=$value["DISPLAY_VALUE"]?></param>
					<?endif?>
				<?endforeach?>
			<?endif?>
				
			</offer>
        <?endforeach?>
        </offers>
    </shop>
</yml_catalog>
