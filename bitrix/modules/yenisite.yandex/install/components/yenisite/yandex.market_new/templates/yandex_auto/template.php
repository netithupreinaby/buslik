<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?echo '<?xml version="1.0" encoding="'. LANG_CHARSET. '"?>';?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<auto-catalog>
	 <creation-date><?=$arResult["DATE"]?></creation-date>
	  <host><?=$arResult["SITE"]?> </host>
	    <offers>
	        <?foreach($arResult["OFFER"] as $arOffer):?>
				<offer type ="<?=$arOffer["TYPE_OFFER"]?>">
					<url><?=$arOffer["URL"]?></url>

					<date><?=$arOffer["DATE_CREATE"]?></date>

					<?if(!empty($arOffer["TIMESTAMP_X"])):?>
						<update-date><?=$arOffer["TIMESTAMP_X"]?></update-date>
					<?endif;?>

					<?if($arParams["LOCAL_DELIVERY_COST"]):?>
						<?if ( !empty($arOffer["DISPLAY_PROPERTIES"][$arParams["COUNTRY"]]["DISPLAY_VALUE"]) ):?>
							<car-location><?=$arOffer["DISPLAY_PROPERTIES"][$arParams["COUNTRY"]]["DISPLAY_VALUE"]?></car-location>
						<?elseif ( !empty($arOffer["COUNTRY"]) ):?>
							<car-location>
								<?=$arOffer["COUNTRY"]?>
							</car-location>
						<?endif;?>
					<?endif?>
					
					<?foreach ($arOffer["PROPERTIES"]["MARK"]["PRODUCT_PROPERTIES_VALUE"] as $key => $value):?>
						<?if(!empty($value)):?>
							<mark><?=$value?></mark>
						<?endif;?>
					<?endforeach;?>

					<?foreach ($arOffer["PROPERTIES"]["MODEL"]["PRODUCT_PROPERTIES_VALUE"] as $key => $value):?>
						<?if(!empty($value)):?>
							<model><?=$value?></model>
						<?endif;?>
					<?endforeach;?>

					<?foreach ($arOffer["PROPERTIES"]["YEAR"]["PRODUCT_PROPERTIES_VALUE"] as $key => $value):?>
							<?if(!empty($value)):?>
								<year><?=$value?></year>
							<?endif;?>
					<?endforeach;?>

					<price><?=$arOffer["PRICE"]?></price>

					<currency-type>
						<?if ( !empty($arOffer["CURRENCY"]) ):?>
							<?=$arOffer["CURRENCY"]?>
						<?else:?>
							<?=$arParams["CURRENCY"]?>
						<?endif;?>
					</currency-type>

					<?foreach ($arOffer["PROPERTIES"]["STATE"]["PRODUCT_PROPERTIES_VALUE"] as $key => $value):?>
						<?if(!empty($value)):?>
							<state><?=$value?></state>
						<?endif;?>
					<?endforeach;?>


					<?foreach ($arOffer["PROPERTIES"]["COLOR"]["PRODUCT_PROPERTIES_VALUE"] as $key => $value):?>
							<?if(!empty($value)):?>
								<color><?=$value?></color>
							<?endif;?>
					<?endforeach;?>

					<?foreach ($arOffer["PROPERTIES"]["BODY_TYPE"]["PRODUCT_PROPERTIES_VALUE"] as $key => $value):?>
							<?if(!empty($value)):?>
								<body-type><?=$value?></body-type>
							<?endif;?>
					<?endforeach;?>

					<?foreach ($arOffer["PROPERTIES"]["ENGINE_TYPE"]["PRODUCT_PROPERTIES_VALUE"] as $key => $value):?>
						<?if(!empty($value)):?>
							<engine-type><?=$value?></engine-type>
						<?endif;?>
					<?endforeach;?>	

					<?foreach ($arOffer["PROPERTIES"]["GEAR_TYPE"]["PRODUCT_PROPERTIES_VALUE"] as $key => $value):?>
						<?if(!empty($value)):?>
							<gear-type><?=$value?></gear-type>
						<?endif;?>
					<?endforeach;?>	

					<?foreach ($arOffer["PROPERTIES"]["TRANSMITION"]["PRODUCT_PROPERTIES_VALUE"] as $key => $value):?>
						<?if(!empty($value)):?>
							<transmission><?=$value?></transmission>
						<?endif;?>
					<?endforeach;?>	

					<?foreach ($arOffer["PROPERTIES"]["HAGGLE"]["PRODUCT_PROPERTIES_VALUE"] as $key => $value):?>
						<?if(!empty($value)):?>
							<haggle><?=$value?></haggle>
						<?endif;?>
					<?endforeach;?>	

					<?foreach ($arOffer["PROPERTIES"]["COUSTOM_HOUSE_STATE"]["PRODUCT_PROPERTIES_VALUE"] as $key => $value):?>
						<?if(!empty($value)):?>
							<custom-house-state><?=$value?></custom-house-state>
						<?endif;?>
					<?endforeach;?>	

					<?foreach ($arOffer["PROPERTIES"]["SELLER"]["PRODUCT_PROPERTIES_VALUE"] as $key => $value):?>
						<?if((!empty($value)) && ($value != " " )):?>
							<seller><?=$value?></seller>
						<?endif;?>	
					<?endforeach;?>

					<?foreach ($arOffer["PROPERTIES"]["SELLER_CITY"]["PRODUCT_PROPERTIES_VALUE"] as $key => $value):?>
						<?if(!empty($value)):?>
							<seller-city><?=$value?></seller-city>
						<?endif;?>	
					<?endforeach;?>	

					<?foreach ($arOffer["PROPERTIES"]["SELLER_PHONE"]["PRODUCT_PROPERTIES_VALUE"] as $key => $value):?>
						<?if(!empty($value)):?>
							<seller-phone><?=$value?></seller-phone>
						<?endif;?>	
					<?endforeach;?>		

					<?foreach ($arOffer["PROPERTIES"]["EQUIPMENT"]["PRODUCT_PROPERTIES_VALUE"] as $key => $value):?>
						<?if(!empty($value)):?>
							<equipment><?=$value?></equipment>
						<?endif;?>
					<?endforeach;?>		

					<?if ( !empty( $arOffer["PICTURE"] ) ):?>
						<image><?=$arOffer["PICTURE"]?></image>
					<?endif;?>

					<?foreach ($arOffer["MORE_PHOTO"] as $pic):?>
						<image><?=$pic?></image>
					<?endforeach;?>

					<?if (!empty($arOffer["DATE_ACTIVE_TO"])):?>
						<valid-thru-date><?=$arOffer["DATE_ACTIVE_TO"]?></valid-thru-date>
					<?endif;?>

					<?foreach ($arOffer["PROPERTIES"]["VIN"]["PRODUCT_PROPERTIES_VALUE"] as $key => $value):?>
						<?if ( !empty($value)):?>
							<vin><?=$value?></vin>
						<?endif;?>
					<?endforeach;?>

					<?foreach ($arOffer["PROPERTIES"]["ADDITIONAL_INFO"]["PRODUCT_PROPERTIES_VALUE"] as $key => $value):?>
						<?if (!empty($value)):?>
							<additional-info><?=$value?></additional-info>
						<?endif;?>
					<?endforeach;?>

					<?foreach ($arOffer["PROPERTIES"]["DOORS_COUNT"]["PRODUCT_PROPERTIES_VALUE"] as $key => $value):?>
						<?if (!empty($value)):?>
							<doors-count><?=$value?></doors-count>
						<?endif;?>
					<?endforeach;?>

					<?foreach ($arOffer["PROPERTIES"]["GENERATION"]["PRODUCT_PROPERTIES_VALUE"] as $key => $value):?>
						<?if (!empty($value)):?>
							<generation><?=$value?></generation>
						<?endif;?>
					<?endforeach;?>

					<?foreach ($arOffer["PROPERTIES"]["MODIFICATION"]["PRODUCT_PROPERTIES_VALUE"] as $key => $value):?>
						<?if (!empty($value)):?>
							<modification><?=$value?></modification>
						<?endif;?>
					<?endforeach;?>

					<?foreach ($arOffer["PROPERTIES"]["HORSE_POWER"]["PRODUCT_PROPERTIES_VALUE"] as $key => $value):?>
						<?if (!empty($value)):?>
							<horse-power><?=$value?></horse-power>
						<?endif;?>
					<?endforeach;?>

					<?foreach ($arOffer["PROPERTIES"]["DISPLACEMENT"]["PRODUCT_PROPERTIES_VALUE"] as $key => $value):?>
						<?if (!empty($value)):?>
							<displacement><?=$value?></displacement>
						<?endif;?>
					<?endforeach;?>

					<?foreach ($arOffer["PROPERTIES"]["RUN"]["PRODUCT_PROPERTIES_VALUE"] as $key => $value):?>
						<?if (!empty($value)):?>
							<run><?=$value?></run>
						<?endif;?>
					<?endforeach;?>

					<?foreach ($arOffer["PROPERTIES"]["RUN_METRIC"]["PRODUCT_PROPERTIES_VALUE"] as $key => $value):?>
						<?if (!empty($value)):?>
							<run-metric><?=$value?></run-metric>
						<?endif;?>
					<?endforeach;?>

					<?foreach ($arOffer["PROPERTIES"]["STEERING_WHEL"]["PRODUCT_PROPERTIES_VALUE"] as $key => $value):?>
						<?if (!empty($value)):?>
							<steering-wheel><?=$value?></steering-wheel>
						<?endif;?>
					<?endforeach;?>

					<?foreach ($arOffer["PROPERTIES"]["STOCK"]["PRODUCT_PROPERTIES_VALUE"] as $key => $value):?>
						<?if (!empty($value)):?>
							<stock><?=$value?></stock>
						<?endif;?>
					<?endforeach;?>
						
				</offer>
        <?endforeach?>
        </offers>
</auto-catalog>
