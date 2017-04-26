<?
/**
 * @author Ilya Faleyev <isfaleev@gmail.com>
 * @copyright 2004-2014 (c) ROMZA
 */
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
echo '<?xml version="1.0" encoding="'. LANG_CHARSET. '"?>';?>
<realty-feed xmlns="http://webmaster.yandex.ru/schemas/feed/realty/2010-06">
	<generation-date><?=$arResult['DATE']?></generation-date>
<?foreach($arResult['OFFER'] as &$arOffer):?>
	<offer internal-id="<?=$arOffer['ID']?>">
		<type><?=$arOffer['PROPERTIES']['TYPE']?></type>
		<property-type><?=$arOffer['PROPERTIES']['PROPERTY_TYPE']?></property-type>
		<category><?=$arOffer['PROPERTIES']['CATEGORY']?></category>
		<url><?=$arOffer['URL']?></url>
		<creation-date><?=$arOffer['DATE_CREATE']?></creation-date><?

		if (isset($arOffer['PROPERTIES']['LAST_UPDATE_DATE']) && $arOffer['PROPERTIES']['LAST_UPDATE_DATE'] != ''):?>

		<last-update-date><?=$arOffer['PROPERTIES']['LAST_UPDATE_DATE']?></last-update-date><?
		endif;

		if (isset($arOffer['PROPERTIES']['EXPIRE_DATE']) && $arOffer['PROPERTIES']['EXPIRE_DATE'] != ''):?>

		<expire-date><?=$arOffer['PROPERTIES']['EXPIRE_DATE']?></expire-date><?
		endif;

		if (isset($arOffer['PROPERTIES']['PAYED_ADV']) && $arOffer['PROPERTIES']['PAYED_ADV'] != ''):?>

		<payed-adv><?=$arOffer['PROPERTIES']['PAYED_ADV']?></payed-adv><?
		endif;

		if (isset($arOffer['PROPERTIES']['MANUALLY_ADDED']) && $arOffer['PROPERTIES']['MANUALLY_ADDED'] != ''):?>

		<manually-added><?=$arOffer['PROPERTIES']['MANUALLY_ADDED']?></manually-added><?
		endif;?>

		<location><?
		foreach ($arResult['LOCATION_PARAMS'] as $param):
			if (isset($arOffer['PROPERTIES'][$param]) && $arOffer['PROPERTIES'][$param] != ''):?>

			<<?=$arResult['TAGS'][$param]?>><?=$arOffer['PROPERTIES'][$param]?></<?=$arResult['TAGS'][$param]?>><?
			endif;
		endforeach;

		if (isset($arOffer['PROPERTIES']['METRO_NAME']) && $arOffer['PROPERTIES']['METRO_NAME'] != ''):?>

			<metro><?
			foreach ($arResult['METRO_PARAMS'] as $param):
				if (isset($arOffer['PROPERTIES'][$param]) && $arOffer['PROPERTIES'][$param] != ''):?>

				<<?=$arResult['TAGS'][$param]?>><?=$arOffer['PROPERTIES'][$param]?></<?=$arResult['TAGS'][$param]?>><?
				endif;
			endforeach?>

			</metro><?
		endif?>

		</location>
		<sales-agent><?
		foreach ($arResult['AGENT_PARAMS'] as $param):
			if (isset($arOffer['PROPERTIES'][$param]) && $arOffer['PROPERTIES'][$param] != ''):?>

			<<?=$arResult['TAGS'][$param]?>><?=$arOffer['PROPERTIES'][$param]?></<?=$arResult['TAGS'][$param]?>><?
			endif;
		endforeach?>

		</sales-agent><?
		if (intval($arOffer["PRICE"]) > 0):?>

		<price>
			<value><?=$arOffer['PRICE']?></value>
			<currency><?=$arOffer['CURRENCY']?></currency><?
			if (isset($arOffer['PROPERTIES']['PRICE_PERIOD']) && $arOffer['PROPERTIES']['PRICE_PERIOD'] != ''):?>

			<period><?=$arOffer['PROPERTIES']['PRICE_PERIOD']?></period><?
			endif;
			if (isset($arOffer['PROPERTIES']['PRICE_UNIT']) && $arOffer['PROPERTIES']['PRICE_UNIT'] != ''):?>

			<unit><?=$arOffer['PROPERTIES']['PRICE_UNIT']?></unit><?
			endif?>

		</price>
		<?
		endif;
		if (!empty($arOffer['PICTURE'])):?>

		<image><?=$arOffer['PICTURE']?></image><?

			foreach ($arOffer['MORE_PHOTO'] as $image):?>

		<image><?=$image?></image><?

			endforeach;
		endif;
		if (!empty($arOffer['DESCRIPTION'])):?>

		<description><?=$arOffer['DESCRIPTION']?></description><?

		endif;

		foreach ($arResult['AREA_PARAMS'] as $param):
			if (isset($arOffer['PROPERTIES'][$param]) && $arOffer['PROPERTIES'][$param] != ''):?>

		<<?=$arResult['TAGS'][$param]?>>
			<value><?=$arOffer['PROPERTIES'][$param]?></value>
			<unit><?=$arParams[$param . '_UNIT']?></unit>
		</<?=$arResult['TAGS'][$param]?>><?
			endif;
		endforeach;

		foreach ($arResult['OTHER_PARAMS'] as $param):
			if (isset($arOffer['PROPERTIES'][$param]) && $arOffer['PROPERTIES'][$param] != ''):?>

		<<?=$arResult['TAGS'][$param]?>><?=$arOffer['PROPERTIES'][$param]?></<?=$arResult['TAGS'][$param]?>><?
			endif;
		endforeach?>
		
	</offer>
<?endforeach?>
</realty-feed>