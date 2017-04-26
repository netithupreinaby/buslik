<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if ($arParams["AJAX_CALL"] != "Y"):?><div id="LOCATION_<?=$arParams["CITY_INPUT_NAME"];?>" class="form-item"><?endif?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
<select class="select" name="<?=$arParams["COUNTRY_INPUT_NAME"]?>" onChange="loadCitiesList(this.value, <?=htmlspecialchars($arResult["JS_PARAMS"])?>, '<?=SITE_ID?>')">
<?if (count($arResult["COUNTRY_LIST"]) > 0):?>
    <option><?echo GetMessage('SAL_CHOOSE_COUNTRY')?></option>
    <?foreach ($arResult["COUNTRY_LIST"] as $arCountry):?>
    <option value="<?=$arCountry["ID"]?>"<?if ($arCountry["ID"] == $arParams["COUNTRY"]):?> selected="selected"<?endif;?>><?=$arCountry["NAME_LANG"]?></option>
    <?endforeach;?>
<?endif;?>
</select>

<?if (count($arResult["REGION_LIST"]) > 0):?>
    <?
    $id = "";
    if (count($arResult["COUNTRY_LIST"]) <= 0):
        $id = "id=\"".$arParams["COUNTRY_INPUT_NAME"].$arParams["CITY_INPUT_NAME"]."\"";
    endif;?>

    <?
    if ($arResult["EMPTY_CITY"] == "Y")
        $change = $arParams["ONCITYCHANGE"];
    else
        $change = "loadCitiesList(".$arParams["COUNTRY"].", ".$arResult["JS_PARAMS"].", '".CUtil::JSEscape($arParams["SITE_ID"])."', this.value)";
    ?>

    <select <?=$id?> <?if($disabled) echo "disabled";?> name="<?=$arParams["REGION_INPUT_NAME"].$arParams["CITY_INPUT_NAME"]?>" onChange="<?=$change?>" type="location">
        <option><?echo GetMessage('SAL_CHOOSE_REGION')?></option>
        <?foreach ($arResult["REGION_LIST"] as $arRegion):?>
            <option value="<?=$arRegion["ID"]?>"<?if ($arRegion["ID"] == $arParams["REGION"]):?> selected="selected"<?endif;?>><?=$arRegion["NAME_LANG"]?></option>
        <?endforeach;?>
    </select>
<?endif;?>

<?if (count($arResult["CITY_LIST"]) > 0):?>
<select class="select" name="<?=$arParams["CITY_INPUT_NAME"]?>"<?if (strlen($arParams["ONCITYCHANGE"]) > 0):?> onchange="<?=$arParams["ONCITYCHANGE"]?>"<?endif;?>>
    <option><?echo GetMessage('SAL_CHOOSE_CITY')?></option>
    <?foreach ($arResult["CITY_LIST"] as $arCity):?>
    <option value="<?=$arCity["ID"]?>"<?
        if ($arCity["ID"] == $arParams["CITY"]):
            ?> selected="selected"<?
        endif;
        if ($arCity['CITY_ID'] == 0):
            ?> class="no-city"<?
        endif;
        ?>><?=($arCity['CITY_ID'] > 0 ? $arCity["CITY_NAME"] : GetMessage('SAL_CHOOSE_CITY_OTHER'))
    ?></option>
    <?endforeach;?>
</select>
<?endif;?>
<?if ($arParams["AJAX_CALL"] != "Y"):?></div><div id="wait_container_<?=$arParams["CITY_INPUT_NAME"]?>" style="display: none;"></div><?endif;?>