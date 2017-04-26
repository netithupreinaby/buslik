<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


<?
use Bitrix\Main\Loader;

if (Loader::includeModule('sale'))
{
    if (CSaleLocation::isLocationProMigrated())
    {
        $template = $arParams['TEMP_FOR_LOCATION'];
    } else{
        $template = '';
    }

}

?>

<?/* <pre><?print_r($arResult);?></pre> */?>
<?if($arResult['STEP'] == 'PERSON_TYPE'):?>
    <a name="tb"></a>
    <a href="<?=$arParams["PATH_TO_LIST"]?>"><?=GetMessage("SPPA_RECORDS_LIST")?></a>
    <br /><br />
    <form action="" method="post">
        <?foreach($arResult['HIDDEN'] as $hidden):?>
            <input type="hidden" name="<?=$hidden['NAME'];?>" value="<?=$hidden['VALUE'];?>"/>
        <?endforeach;?>
        <div class="user_profile_cont">
            
               <div class="user_profile">
			   
			  
                              <h2>  <?=GetMessage("SPPA_PERSON_TYPE");?> </h2>
            <?foreach($arResult[$arResult['STEP']] as $res):?>
                <?switch($res['INPUT']['TYPE']):
                    case 'RADIO':?>					
                       
                                 <!--  <p><div class="radio" id="uniform-<?=$res['INPUT']['VALUE'];?>">
										<span> <input  type="radio" name="<?=$res['INPUT']['NAME'];?>" value="<?=$res['INPUT']['VALUE'];?>"/></span>
									   </div>
									   <label for="<?=$res['INPUT']['VALUE'];?>"><?=$res['NAME'];?></label>
								   </p>-->
								   <p>
							<input type="radio" class="radio" name="<?=$res['INPUT']['NAME']?>" id="<?=$res['INPUT']['NAME']?>_<?=$res["ID"]?>" value="<?=$res['INPUT']["VALUE"]?>"<?if($res['INPUT']["CHECKED"] == "Y") echo " checked";?>> <label for="<?=$res['INPUT']['NAME']?>_<?=$res["ID"]?>"><?=$res["NAME"]?></label><br />
						</p>
						
                    <?break;?>
                <?endswitch;?>
            <?endforeach;?>
			 
        </div>
        </div>
        <input type="submit" value="<?=GetMessage("SPPA_NEXT_STEP");?>" class="button">
    </form>

<?elseif($arResult['STEP'] == 'ORDER_PROPS'):?>
    <a name="tb"></a>
    <a href="<?=$arParams["PATH_TO_LIST"]?>"><?=GetMessage("SPPA_RECORDS_LIST")?></a>
    <br /><br />
        <?=ShowError($arResult["ERROR_MESSAGE"])?>
        <form method="post" action="<?=POST_FORM_ACTION_URI?>">
        <?foreach($arResult['HIDDEN'] as $hidden):?>
            <input type="hidden" name="<?=$hidden['NAME'];?>" value="<?=$hidden['VALUE'];?>"/>
        <?endforeach;?>
        <?=bitrix_sessid_post()?>
        <div class="profile_item_cont">
		<div class="profile_item">
           <!-- <tr>
                <th colspan="2">
                    <b><?=GetMessage("SPPA_CREATE_PROFILE");?></b>
                </th>
            </tr>-->
            <div class="form-item">
                <label><?echo GetMessage("SALE_PERS_TYPE")?>:</label>
               <?=$arResult["PERSON_TYPE"]["NAME"]?>
                <input type="hidden" name="PT" value="<?=$arResult["PERSON_TYPE"]['ID'];?>"/>
                <?/*<pre><?print_r($arResult["PERSON_TYPE"]);?></pre>*/?>
            </div>
            <div class="form-item">
                <label ><?echo GetMessage("SALE_PNAME")?>:<span class="req">*</span></label>
                <input type="text" class="txt" name="NAME" value="<?echo $arResult["NAME"];?>" size="40">
            </div>
            
            <?
            foreach($arResult["ORDER_PROPS"] as $val)
            {
                if(!empty($val["PROPS"]))
                {
                    ?>
                    <!--<tr>
                        <th colspan="2"><b><?=$val["NAME"];?></b></th>
    
                       
                    </tr>-->
                    <?
                    foreach($val["PROPS"] as $vval)
                    {
                        $currentValue = $arResult["ORDER_PROPS_VALUES"]["ORDER_PROP_".$vval["ID"]];
                        $name = "ORDER_PROP_".$vval["ID"];
                        ?>
                        <div class="form-item">
                            <label <?if ($vval["TYPE"]=="LOCATION"):?> class = "label-location" <?endif?>>
                            <?=$vval["NAME"] ?>:
                                <?if ($vval["REQUIED"]=="Y")
                                {
                                    ?><span class="req">*</span><?
                                }
                                ?></label>
                            
                                <?if ($vval["TYPE"]=="CHECKBOX"):?>
                                    <input type="hidden" name="<?=$name?>" value="">
                                    <input type="checkbox" name="<?=$name?>" value="Y"<?if ($currentValue=="Y" || !isset($currentValue) && $vval["DEFAULT_VALUE"]=="Y") echo " checked";?>>
                                <?elseif ($vval["TYPE"]=="TEXT"):?>
                                    <input type="text" class="txt" size="<?echo (IntVal($vval["SIZE1"])>0)?$vval["SIZE1"]:30; ?>" maxlength="250" value="<?echo (isset($currentValue)) ? $currentValue : $vval["DEFAULT_VALUE"];?>" name="<?=$name?>">
                                <?elseif ($vval["TYPE"]=="SELECT"):?>
                                    <select name="<?=$name?>">
                                        <?foreach($vval["VALUES"] as $vvval):?>
                                         			<option value="<?echo $vvval["ID"]?>"<?if (IntVal($vvval["ID"])==IntVal($currentValue) || !isset($currentValue) && IntVal($vvval["ID"])==IntVal($vval["DEFAULT_VALUE"])) echo " selected"?>><?echo $vvval["COUNTRY_NAME"]." - ".$vvval["CITY_NAME"]?></option>
							 <?endforeach;?>
                                    </select>
                                <?elseif ($vval["TYPE"]=="MULTISELECT"):?>
                                    <select multiple name="<?=$name?>[]" size="<?echo (IntVal($vval["SIZE1"])>0)?$vval["SIZE1"]:5; ?>">
                                        <?
                                        $arCurVal = array();
                                        $arCurVal = explode(",", $currentValue);
                                        for ($i = 0; $i<count($arCurVal); $i++)
                                            $arCurVal[$i] = Trim($arCurVal[$i]);
                                        $arDefVal = explode(",", $vval["DEFAULT_VALUE"]);
                                        for ($i = 0; $i<count($arDefVal); $i++)
                                            $arDefVal[$i] = Trim($arDefVal[$i]);
                                        foreach($vval["VALUES"] as $vvval):?>
                                            <option value="<?echo $vvval["VALUE"]?>"<?if (in_array($vvval["VALUE"], $arCurVal) || !isset($currentValue) && in_array($vvval["VALUE"], $arDefVal)) echo" selected"?>><?echo $vvval["NAME"]?></option>
                                        <?endforeach;?>
                                    </select>
                                <?elseif ($vval["TYPE"]=="TEXTAREA"):?>
                                    <textarea class="txt" rows="<?echo (IntVal($vval["SIZE2"])>0)?$vval["SIZE2"]:4; ?>" cols="<?echo (IntVal($vval["SIZE1"])>0)?$vval["SIZE1"]:40; ?>" name="<?=$name?>"><?echo (isset($currentValue)) ? $currentValue : $vval["DEFAULT_VALUE"];?></textarea>
                                <?elseif ($vval["TYPE"]=="LOCATION"):?>
                                   
								<?if ($arParams['USE_AJAX_LOCATIONS'] == 'Y'):?>
                                    <div class="location-selector">
                                                 <?
                                                    CSaleLocation::proxySaleAjaxLocationsComponent(
                                                        array(
                                                            "AJAX_CALL" => "N",
                                                            'CITY_OUT_LOCATION' => 'Y',
                                                            'COUNTRY_INPUT_NAME' => "COUNTRY_".$name,
                                                            'CITY_INPUT_NAME' => $name,
                                                            'LOCATION_VALUE' => isset($currentValue) ? $currentValue : $vval["DEFAULT_VALUE"],
                                                            'SHOW_DEFAULT_LOCATIONS' => 'Y',
                                                        ),
                                                        array(
                                                            "CODE" => "",
                                                            "PROVIDE_LINK_BY" => "id",
                                                        ),
                                                        $template, true, false
                                                    );?>
                                     </div>
                                 <?
								else:
								?>
								<select class="select" name="<?=$name?>" <?/*size="<?echo (IntVal($vval["SIZE1"])>0)?$vval["SIZE1"]:1; ?>"*/?>>
									<?foreach($vval["VALUES"] as $vvval):?>
										<option value="<?echo $vvval["ID"]?>"<?if (IntVal($vvval["ID"])==IntVal($currentValue) || !isset($currentValue) && IntVal($vvval["ID"])==IntVal($vval["DEFAULT_VALUE"])) echo " selected"?>><?echo $vvval["COUNTRY_NAME"]." - ".$vvval["CITY_NAME"]?></option>
									<?endforeach;?>
								</select>
								<?
								endif;
								?>
                                <?elseif ($vval["TYPE"]=="RADIO"):?>
                                    <?foreach($vval["VALUES"] as $vvval):?>
                                        <input type="radio" name="<?=$name?>" value="<?echo $vvval["VALUE"]?>"<?if ($vvval["VALUE"]==$currentValue || !isset($currentValue) && $vvval["VALUE"]==$vval["DEFAULT_VALUE"]) echo " checked"?>><?echo $vvval["NAME"]?><br />
                                    <?endforeach;?>
                                <?endif?>

                                <?if (strlen($vval["DESCRIPTION"])>0):?>
                                    <br /><small><?echo $vval["DESCRIPTION"] ?></small>
                                <?endif?>
                            
                        </div>
                        <?
                    }
                }
            }
            ?>

        </div>

        <br />
            <input type="submit" name="save" value="<?echo GetMessage("SALE_SAVE") ?>" class="button1">
        <?if($arParams["PATH_TO_DETAIL"]):?>
            &nbsp;
            <input type="submit" name="apply" value="<?=GetMessage("SALE_APPLY")?>" class="button1">
        <?endif;?>
            &nbsp;
            <input type="submit" name="reset" value="<?echo GetMessage("SALE_RESET")?>" class="button1">
        </div>
        </form>
<?elseif($arResult['STEP'] == 'FINISH'):?>
    <br/><?=GetMessage("SPPA_FINISH", array("#ID#"=>$arResult["ID"]));?><br/>
<?endif;?>