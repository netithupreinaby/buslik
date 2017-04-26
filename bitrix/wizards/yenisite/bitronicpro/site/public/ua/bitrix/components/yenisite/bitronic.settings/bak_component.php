<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
CModule::IncludeModule("yenisite.bitronic");
CModule::IncludeModule("yenisite.bitronicpro");
CModule::IncludeModule("yenisite.bitroniclite");

$method = $GLOBALS["USER"]->IsAuthorized()?"options":"cookies";
  
    if($_REQUEST["bitronic_settings_apply"] == "Y"){
    


        foreach($_REQUEST["SETTINGS"] as $key => $value){            
            if($value):
                switch($key){
                    case "BACKGROUND_IMAGE": 
                    case "MENU_FILTER":  
                    case "BASKET_POSITION":                      
                    case "MIN_MAX_MIN":  
                    case "MIN_MAX_MAX":  
                    case "COLOR_SCHEME":                 
                        if($method == "options"){   
                            $k = $key;    
                            $key .= "_UID_".$GLOBALS["USER"]->GetID();
                            COption::SetOptionString(CYSBitronicSettings::getModuleId(),$key, $value);
                            if($_REQUEST["SETTINGS"]["SET_DEFAULT"] == "Y")
                                COption::SetOptionString(CYSBitronicSettings::getModuleId(),$k, $value);
                            
                        }
                        else
                            $GLOBALS["APPLICATION"]->set_cookie($key, $value);
                    break;
                }
            endif;
        }
        LocalRedirect($_REQUEST["burl"]);
    }




if($GLOBALS["USER"]->IsAdmin()){
    $arParams[EDIT_SETTINGS] = array("COLOR_SCHEME", "BASKET_POSITION", "MENU_FILTER", "BACKGROUND_IMAGE", "MIN_MAX");
    $arParams[EDIT_SETTINGS][] = "SET_DEFAULT";
}


foreach($arParams[EDIT_SETTINGS] as $setting){
    switch($setting){
        case "COLOR_SCHEME":   
            if($method == "options"){
                $key = $setting."_UID_".$GLOBALS["USER"]->GetID();
                $value = COption::GetOptionString(CYSBitronicSettings::getModuleId(), $key, "");
            }
            else
                $value = $GLOBALS["APPLICATION"]->get_cookie($setting);     
                
                
            $arResult["SETTINGS"][$setting]["INPUT"] = "<select class='selectBox toggle-list' name='SETTINGS[".$setting."]'>
                <option value=''>".GetMessage("CHOOSE")."</option>
                <option ".($value=='red'?'selected="selected"':'')." value='red'>".GetMessage("COLOR_SCHEME_RED")."</option>
                <option ".($value=='ice'?'selected="selected"':'')." value='ice'>".GetMessage("COLOR_SCHEME_ICE")."</option>
                <option ".($value=='green'?'selected="selected"':'')." value='green'>".GetMessage("COLOR_SCHEME_GREEN")."</option>                
                <option ".($value=='yellow'?'selected="selected"':'')." value='yellow'>".GetMessage("COLOR_SCHEME_YELLOW")."</option>   
                <option ".($value=='pink'?'selected="selected"':'')." value='pink'>".GetMessage("COLOR_SCHEME_PINK")."</option>   
                <option ".($value=='metal'?'selected="selected"':'')." value='metal'>".GetMessage("COLOR_SCHEME_METAL")."</option>   
            </select>"; 
            $arResult["SETTINGS"][$setting]["NAME"] = GetMessage($setting);
                       
        break;
        
        case "BASKET_POSITION":   
            if($method == "options"){
                $key = $setting."_UID_".$GLOBALS["USER"]->GetID();
                $value = COption::GetOptionString(CYSBitronicSettings::getModuleId(), $key, "");
            }
            else
                $value = $GLOBALS["APPLICATION"]->get_cookie($setting);  
                   
            $arResult["SETTINGS"][$setting]["INPUT"] = "<select class='selectBox toggle-list' name='SETTINGS[".$setting."]'>
                <option value=''>".GetMessage("CHOOSE")."</option>
                <option ".($value=='left'?'selected="selected"':'')." value='left'>".GetMessage("BASKET_POSITION_LEFT")."</option>
                <option ".($value=='right'?'selected="selected"':'')." value='right'>".GetMessage("BASKET_POSITION_RIGHT")."</option>
            </select>"; 
            $arResult["SETTINGS"][$setting]["NAME"] = GetMessage($setting);
                       
        break;
        
        case "MENU_FILTER":   
            if($method == "options"){
                $key = $setting."_UID_".$GLOBALS["USER"]->GetID();
                $value = COption::GetOptionString(CYSBitronicSettings::getModuleId(), $key, "");
            }
            else
                $value = $GLOBALS["APPLICATION"]->get_cookie($setting);  
                   
            $arResult["SETTINGS"][$setting]["INPUT"] = "<select class='selectBox toggle-list' name='SETTINGS[".$setting."]'>
                <option value=''>".GetMessage("CHOOSE")."</option>
                <option ".($value=='top-left'?'selected="selected"':'')." value='top-left'>".GetMessage("MENU_FILTER_TOP_LEFT")."</option>
                <option ".($value=='left-top'?'selected="selected"':'')." value='left-top'>".GetMessage("MENU_FILTER_LEFT_TOP")."</option>
            </select>"; 
            $arResult["SETTINGS"][$setting]["NAME"] = GetMessage($setting);
        break;
        
        case "BACKGROUND_IMAGE":
            if($method == "options"){
                $key = $setting."_UID_".$GLOBALS["USER"]->GetID();
                $value = COption::GetOptionString(CYSBitronicSettings::getModuleId(), $key, "");
            }
            else
                $value = $GLOBALS["APPLICATION"]->get_cookie($setting);  
                   
            $arResult["SETTINGS"][$setting]["INPUT"] = "<select class='selectBox toggle-list' name='SETTINGS[".$setting."]'>
                <option value='none'>".GetMessage("CHOOSE")."</option>";               
                
            $fl = scandir($_SERVER["DOCUMENT_ROOT"]."/backgrounds/");               
            foreach($fl as $f) 
                if($f != "." && $f != "..")           
                    $arResult["SETTINGS"][$setting]["INPUT"] .= "<option ".($value==$f?'selected="selected"':'')." value='".$f."'>".$f."</option>";
                
            $arResult["SETTINGS"][$setting]["INPUT"] .= "</select>"; 
            $arResult["SETTINGS"][$setting]["NAME"] = GetMessage($setting);
                       
        break;
        
        case "MIN_MAX":
            if($method == "options"){
                $key = $setting."_MIN_UID_".$GLOBALS["USER"]->GetID();
                $value1 = COption::GetOptionString(CYSBitronicSettings::getModuleId(), $key, "");
                $key = $setting."_MAX_UID_".$GLOBALS["USER"]->GetID();
                $value2 = COption::GetOptionString(CYSBitronicSettings::getModuleId(), $key, "");
            }
            else{
                $value1 = $GLOBALS["APPLICATION"]->get_cookie($setting."_MIN");
                $value2 = $GLOBALS["APPLICATION"]->get_cookie($setting."_MAX");
            }
            
            $arResult["SETTINGS"][$setting]["INPUT"] = GetMessage("MIN").": <input type='text' class='txt' size='5' name='SETTINGS[".$setting."_MIN]' value='".$value1."'/> ".GetMessage("MAX")."<input type='text' class='txt' size='5' name='SETTINGS[".$setting."_MAX]' value='".$value2."'/>"; 
            $arResult["SETTINGS"][$setting]["NAME"] = GetMessage($setting);
                       
        break;
        
        case "SET_DEFAULT":
            if($GLOBALS["USER"]->IsAdmin()){
                $arResult["SETTINGS"][$setting]["INPUT"] = "<input type='checkbox' class='checkbox' name='SETTINGS[".$setting."]' value='Y'/>"; 
                $arResult["SETTINGS"][$setting]["NAME"] = GetMessage($setting);
            }
                       
        break;

    }
}
$this->IncludeComponentTemplate();
?>
