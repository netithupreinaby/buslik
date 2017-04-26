<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

CModule::IncludeModule("yenisite.bitronic");
CModule::IncludeModule("yenisite.bitronicpro");
CModule::IncludeModule("yenisite.bitroniclite");

$method = $GLOBALS["USER"]->IsAuthorized()?"options":"cookies";

global $USER;
global $ys_options;

if (!CModule::IncludeModule(CYSBitronicSettings::getModuleId())) {

}

if ($_REQUEST["bitronic_settings_apply"] == "Y") {

	$asDefault = ($_REQUEST["SETTINGS"]["SET_DEFAULT"] == "Y") ? TRUE : FALSE;

    if (!isset($_REQUEST["SETTINGS"]["WINDOW_BORDER"])
        && (in_array("WINDOW",$arParams["EDIT_SETTINGS"])
            || $USER->IsAdmin())) {

        $value = "N";
        $key = "WINDOW_BORDER";

        CYSBitronicSettings::setSetting($key, $value, $asDefault);
    }

    if (!isset($_REQUEST["SETTINGS"]["SHOW_ELEMENT"])
        && (in_array("SHOW_ELEMENT",$arParams["EDIT_SETTINGS"])
            || $USER->IsAdmin())) {

        $value = "N";
        $key = "SHOW_ELEMENT";

        CYSBitronicSettings::setSetting($key, $value, $asDefault);
    }

    if (!isset($_REQUEST["SETTINGS"]["BACKGROUND_REPEAT"])
        && (in_array("BACKGROUND",$arParams["EDIT_SETTINGS"])
            || $USER->IsAdmin())) {

        $value = "N";
        $key = "BACKGROUND_REPEAT";

        CYSBitronicSettings::setSetting($key, $value, $asDefault);
    }

    if(!isset($_REQUEST["SETTINGS"]["NO_SECTION"]) && (in_array("NO_SECTION",$arParams["EDIT_SETTINGS"])  || $USER->IsAdmin()))
    {
        $value="N";
        $key="NO_SECTION";

        CYSBitronicSettings::setSetting($key, $value, $asDefault);
    }

    if(!isset($_REQUEST["SETTINGS"]["SMART_FILTER_AJAX"]) && (in_array("SMART_FILTER_AJAX",$arParams["EDIT_SETTINGS"])  || $USER->IsAdmin()))
    {
        $value="N";
        $key="SMART_FILTER_AJAX";

        CYSBitronicSettings::setSetting($key, $value, $asDefault);
    }
	
    $flag = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "sef_mode", false, SITE_ID);
    $arch = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "architect", false, SITE_ID);

    if (!isset($_REQUEST["SETTINGS"]["SEF"]) && (in_array("SEF", $arParams["EDIT_SETTINGS"]) || $USER->IsAdmin()))
    {
        $value = "N";
        $key = "SEF";

        CYSBitronicSettings::setSetting($key, $value, $asDefault);

        COption::SetOptionString(CYSBitronicSettings::getModuleId(), "sef_mode", "N", false, SITE_ID);
    }

    CModule::IncludeModule("iblock");

    if ($USER->IsAdmin() /*in_array('SEF', $arParams['EDIT_SETTINGS']) ||*/ ) {
        if ($_REQUEST["SETTINGS"]["SEF"] == "Y" && $flag != "Y") {
            $url = array(
                "CONDITION" =>  "#^/hit/sort-(.*[^-])-(.*)/#",
                "RULE"  =>  "order=$1&by=$2",
                "ID"    =>  "",
                "PATH"  =>  "/hit/index.php",
            );
            CUrlRewriter::Add($url);

            $url = array(
                "CONDITION" =>  "#^/new/sort-(.*[^-])-(.*)/#",
                "RULE"  =>  "order=$1&by=$2",
                "ID"    =>  "",
                "PATH"  =>  "/new/index.php",
            );
            CUrlRewriter::Add($url);

            $url = array(
                "CONDITION" =>  "#^/sale/sort-(.*[^-])-(.*)/#",
                "RULE"  =>  "order=$1&by=$2",
                "ID"    =>  "",
                "PATH"  =>  "/sale/index.php",
            );
            CUrlRewriter::Add($url);

            $url = array(
                "CONDITION" =>  "#^/bestseller/sort-(.*[^-])-(.*)/#",
                "RULE"  =>  "order=$1&by=$2",
                "ID"    =>  "",
                "PATH"  =>  "/bestseller/index.php",
            );
            CUrlRewriter::Add($url);

            COption::SetOptionString(CYSBitronicSettings::getModuleId(), "sef_mode", "Y", false, SITE_ID);
        } else if (!isset($_REQUEST["SETTINGS"]["SEF"])) {

            //$fhandl = fopen($_SERVER["DOCUMENT_ROOT"].'/sef_log.txt', 'a+');
            //fwrite($fhandl, 'Start removing sef...' . PHP_EOL);

            if($flag == "Y") {
                $resTypeIb = CIBlockType::GetList();

                while ($arTypeIb = $resTypeIb->Fetch()) {
                    if (strpos($arTypeIb['ID'], 'catalog') === false && strpos($arTypeIb['ID'], SITE_ID) === false) continue;

                    $type       = str_replace("catalog_", "", $arTypeIb['ID']);
                    $type       = str_replace(SITE_ID.'_', "", $type);
                    $typeDir    = str_replace("_", "-", $type);

                    //fwrite($fhandl, 'Start removing ' . $typeDir . PHP_EOL);

                    $resIb = CIBlock::GetList(array(), array('TYPE' => $arTypeIb['ID']));
                    while ( $arIb = $resIb->Fetch() ) {
                        $flag2 = 0;
                        $ibDir = $arIb['CODE'];

                        $path = $_SERVER["DOCUMENT_ROOT"].'/'.$typeDir.'/'.$ibDir.'/index.php';

                        if ($arch != 'multi') {
                            $path = $_SERVER["DOCUMENT_ROOT"].'/catalog/index.php';
                        }

                        //fwrite($fhandl, 'Start removing for ' . $path . PHP_EOL);

                        if (file_exists($path)) {
                            $str = file_get_contents($path);
                            $str = str_replace('"SEF_MODE" => "N"', '"SEF_MODE" => "Y"', $str);

                            if (strpos($str, 'SEF_URL_TEMPLATES__SEF') !== false) {
                                $str = str_replace('SEF_URL_TEMPLATES__SEF', 'SEF_URL_TEMPLATES', $str);
                                $flag2 = 1;
                            }

                            file_put_contents($path, $str);

                            $fh = fopen($path, 'r+');
                            $buffer = '';
                            while (!feof($fh)) {
                                $str = fgets($fh);
                                if (strpos($str, "VARIABLE_ALIASES") === false) {
                                    $buffer .= $str;
                                } else {
                                    if(!$flag2) {
                                        $buffer .= '"SEF_URL_TEMPLATES" => array("sections" => "", "section" => "#SECTION_CODE#/", "element" => "#SECTION_CODE#/#ELEMENT_CODE#.html", "compare" => "compare.php?action=#ACTION_CODE#", ), "VARIABLE_ALIASES" => array("compare" => array("ACTION_CODE" => "action",),)),false);?> <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>';
                                    } else {
                                        $buffer .= '"VARIABLE_ALIASES" => array("compare" => array("ACTION_CODE" => "action",),)),false);?> <?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>';
                                    }

                                    break;
                                }
                            }
                            fclose($fh);

                            $fh = fopen($path, 'w+');
                            fwrite($fh, $buffer);
                            fclose($fh);
                        }
                    }
                }
                COption::SetOptionString(CYSBitronicSettings::getModuleId(), "sef_mode", "N", false, SITE_ID);
            }

            //fclose($fhandl);
        } // else if ($_REQUEST["SETTINGS"]["SEF"] == "Y")
    } // if ( in_array('SEF', $arParams['EDIT_SETTINGS']) || $USER->IsAdmin() )

    foreach($_REQUEST["SETTINGS"] as $key => $value){
        if($value ):
            switch($key){
                case "SHOW_ELEMENT":
                case "ACTION_ADD2B":
                case "ORDER":
                case "NO_SECTION":
                case "SMART_FILTER_TYPE":
                case "SMART_FILTER_AJAX":
                case "VIEW_PHOTO":
                case "SKU_TYPE":
                case "TABS_INDEX":
                case "BLOCK_VIEW_MODE":
                case "BACKGROUND_IMAGE":
                case "BACKGROUND_COLOR":
                case "BACKGROUND_REPEAT":

//                    case "BACKGROUND":
                case "WINDOW_COLOR":
                case "WINDOW_BORDER":
                case "WINDOW_OPACITY":

//                    case "WINDOW": 
                case "MENU_FILTER":
                case "BASKET_POSITION":
                case "MIN_MAX_MIN":
                case "MIN_MAX_MAX":
                case "COLOR_SCHEME":

                    $asDefault = ($_REQUEST["SETTINGS"]["SET_DEFAULT"] == "Y") ? TRUE : FALSE;
                
                    CYSBitronicSettings::setSetting($key, $value, $asDefault);
                    break;
                    
                case "SEF":
                    CYSBitronicSettings::setSetting($key, $value, true);
                default:
                    break;
            }
        endif;
    }
    LocalRedirect($_REQUEST["burl"]);
}

if ($GLOBALS["USER"]->IsAdmin()) {

    $arParams['EDIT_SETTINGS'] = array("SHOW_ELEMENT", "ACTION_ADD2B", "SMART_FILTER_AJAX",  "SMART_FILTER_TYPE", "ORDER", "TABS_INDEX", "BLOCK_VIEW_MODE",  "COLOR_SCHEME", 
        "BASKET_POSITION",  "MENU_FILTER", "VIEW_PHOTO", "SKU_TYPE", "BACKGROUND", /*"BACKGROUND_IMAGE", "BACKGROUND_COLOR", "BACKGROUND_REPEAT",*/
        "MIN_MAX", /* "WINDOW_COLOR", "WINDOW_BORDER"*/ "WINDOW",  "SEF",  /* "NO_SECTION", */ );

    $arParams['EDIT_SETTINGS'][] = "SET_DEFAULT";
}

foreach ($arParams['EDIT_SETTINGS'] as $setting) {
    switch($setting) {
        case "SEF":
            $value = $ys_options["sef"];
            $arResult["SETTINGS"][$setting]["INPUT"] = "<input type='checkbox' class='checkbox'".( $value=='Y' ? "CHECKED" : "" )." name='SETTINGS[".$setting."]' value='Y'/>";
            $arResult["SETTINGS"][$setting]["NAME"] = GetMessage($setting);
			$arResult["SETTINGS"][$setting]["MINI"] = "Y";
            break;

        case "ACTION_ADD2B":
            $value = $ys_options["action_add2b"];
            $arResult["SETTINGS"][$setting]["INPUT"] = "<select class='selectBox toggle-list' name='SETTINGS[".$setting."]'>
                                <option ".($value=='popup_basket'?'selected="selected"':'')." value='popup_basket'>".GetMessage("ADD2BASKET_BASKET_OPEN")."</option>
                                <option ".($value=='popup_window'?'selected="selected"':'')." value='popup_window'>".GetMessage("ADD2BASKET_WINDOW_OPEN")."</option>
                            </select>";
            $arResult["SETTINGS"][$setting]["NAME"] = GetMessage($setting);
            break;

        case "SMART_FILTER_AJAX":
            $value = $ys_options["smart_filter_ajax"];
            $arResult["SETTINGS"][$setting]["INPUT"] = "<input type='checkbox' class='checkbox'".( $value=='Y' ? "CHECKED" : "" )." name='SETTINGS[".$setting."]' value='Y'/>";
			$arResult["SETTINGS"][$setting]["NAME"] = GetMessage($setting);
			break;
			
		case "SMART_FILTER_TYPE":
			$value = $ys_options["smart_filter_type"];
            $arResult["SETTINGS"][$setting]["INPUT"] = "<select class='selectBox toggle-list' name='SETTINGS[".$setting."]'>
                                <option ".($value=='DEFAULT'?'selected="selected"':'')." value='DEFAULT'>".GetMessage("SMART_FILTER_TYPE_DEFAULT")."</option>
                                <option ".($value=='KOMBOX'?'selected="selected"':'')." value='KOMBOX'>".GetMessage("SMART_FILTER_TYPE_KOMBOX")."</option>
                            </select>";
            $arResult["SETTINGS"][$setting]["NAME"] = GetMessage($setting);
		   
            break;
			
        case "NO_SECTION":
            $value = $ys_options["no_section"];
            $arResult["SETTINGS"][$setting]["INPUT"] = "<input type='checkbox' class='checkbox'".( $value=='Y' ? "CHECKED" : "" )." name='SETTINGS[".$setting."]' value='Y'/>";
            $arResult["SETTINGS"][$setting]["NAME"] = GetMessage($setting);
            break;

        case "SHOW_ELEMENT":
            $value = $ys_options["show_element"];
            $arResult["SETTINGS"][$setting]["INPUT"] = "<input type='checkbox' class='checkbox'".( $value=='Y' ? "CHECKED" : "" )." name='SETTINGS[".$setting."]' value='Y'/>";
            $arResult["SETTINGS"][$setting]["NAME"] = GetMessage($setting);
            break;

        case "TABS_INDEX":
            $value = $ys_options["tabs_index"];
            $arResult["SETTINGS"][$setting]["INPUT"] = "<select class='selectBox toggle-list' name='SETTINGS[".$setting."]'>
                                <option ".($value=='one_slider'?'selected="selected"':'')." value='one_slider'>".GetMessage("TABS_INDEX_ONE")."</option>
                                <option ".($value=='list'?'selected="selected"':'')." value='list'>".GetMessage("TABS_INDEX_LIST")."</option>
                            </select>";
            $arResult["SETTINGS"][$setting]["NAME"] = GetMessage($setting);

            break;

        case "BLOCK_VIEW_MODE":
            $value = $ys_options["block_view_mode"];
            $arResult["SETTINGS"][$setting]["INPUT"] = "<select class='selectBox toggle-list' name='SETTINGS[".$setting."]'>
                                <option ".($value=='popup'?'selected="selected"':'')." value='popup'>".GetMessage("BLOCK_VIEW_MODE_POPUP")."</option>
                                <option ".($value=='nopopup'?'selected="selected"':'')." value='nopopup'>".GetMessage("BLOCK_VIEW_MODE_NOPOPUP")."</option>
                            </select>";
            $arResult["SETTINGS"][$setting]["NAME"] = GetMessage($setting);
            break;

        case "ORDER":
            $value = $ys_options["order"];
            $arResult["SETTINGS"][$setting]["INPUT"] = "<select class='selectBox toggle-list' name='SETTINGS[".$setting."]'>
                                <option ".($value=='one_step'?'selected="selected"':'')." value='one_step'>".GetMessage("ORDER_ONE_STEP")."</option>
                                <option ".($value=='full'?'selected="selected"':'')." value='full'>".GetMessage("ORDER_FULL")."</option>
                            </select>";
            $arResult["SETTINGS"][$setting]["NAME"] = GetMessage($setting);
            break;

        case "COLOR_SCHEME":
            $value = $ys_options["color_scheme"];
            $arResult["SETTINGS"][$setting]["INPUT"] = "<select class='selectBox toggle-list' name='SETTINGS[".$setting."]'>
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
            $value = $ys_options["basket_position"];
            $arResult["SETTINGS"][$setting]["INPUT"] = "<select class='selectBox toggle-list' name='SETTINGS[".$setting."]'>
                                <option ".($value=='LEFT'?'selected="selected"':'')." value='LEFT'>".GetMessage("BASKET_POSITION_LEFT")."</option>
                                <option ".($value=='RIGHT'?'selected="selected"':'')." value='RIGHT'>".GetMessage("BASKET_POSITION_RIGHT")."</option>
                            </select>";
            $arResult["SETTINGS"][$setting]["NAME"] = GetMessage($setting);
            break;

        case "MENU_FILTER":
            $value = $ys_options["menu_filter"];
            $arResult["SETTINGS"][$setting]["INPUT"] = "<select class='selectBox toggle-list' name='SETTINGS[".$setting."]'>
                                <option ".($value=='top-left'?'selected="selected"':'')." value='top-left'>".GetMessage("MENU_FILTER_TOP_LEFT")."</option>
                                <option ".($value=='left-top'?'selected="selected"':'')." value='left-top'>".GetMessage("MENU_FILTER_LEFT_TOP")."</option>
                            </select>";
            $arResult["SETTINGS"][$setting]["NAME"] = GetMessage($setting);
            break;
			
		case "VIEW_PHOTO":
            $value = $ys_options["view_photo"];
            $arResult["SETTINGS"][$setting]["INPUT"] = "<select class='selectBox toggle-list' name='SETTINGS[".$setting."]'>
                                <option ".($value=='popup'?'selected="selected"':'')." value='popup'>".GetMessage("VIEW_PHOTO_POPUP")."</option>
                                <option ".($value=='zoom'?'selected="selected"':'')." value='zoom'>".GetMessage("VIEW_PHOTO_ZOOM")."</option>
                            </select>";
            $arResult["SETTINGS"][$setting]["NAME"] = GetMessage($setting);
            break;
			
		case "SKU_TYPE":
            $value = $ys_options["sku_type"];
            $arResult["SETTINGS"][$setting]["INPUT"] = "<select class='selectBox toggle-list' name='SETTINGS[".$setting."]'>
                                <option ".($value=='N'?'selected="selected"':'')." value='N'>".GetMessage("SKU_TYPE_N")."</option>
                                <option ".($value=='SB'?'selected="selected"':'')." value='SB'>".GetMessage("SKU_TYPE_SB")."</option>
                            </select>";
            $arResult["SETTINGS"][$setting]["NAME"] = GetMessage($setting);
            break;
			
        case "BACKGROUND":
            $value1 = $ys_options["bg"];
            $value2 = $ys_options["bgrepeat"];
            $value3 = $ys_options["bgcolor"];

            $fl = scandir($_SERVER["DOCUMENT_ROOT"]."/backgrounds/");


            //Р РЋР С—Р С‘РЎРѓР С•Р С” РЎвЂљР ВµР С”РЎРѓРЎвЂљРЎС“РЎР‚
            $textures = "<div id='textures'>";
            foreach($fl as $f)
                if($f != "." && $f != ".." && strpos($f, "texture") === 0)
                    $textures .= "<div class='texture".($value1=="/backgrounds/".$f?' texture_selected':'')."' rel='" . $f . "'><div class='texture_bg' style='background-image: url(/backgrounds/" . $f . ");'></div></div>";
            //$textures .= "<img src='/backgrounds/" . $f . "' rel='" . $f . "' width='30px' class='texture " .($value1 == "/backgrounds/" . $f?'texture_selected':'') ."'>&nbsp;";

            $textures .= "</div>" . /*GetMessage("BACKGROUND_REPEAT"). */" <input type='checkbox' name='SETTINGS[".$setting."_REPEAT]'". ($value2=='Y' ? ' CHECKED' : ''). " value='Y' style='display: none;'/>";

            $arResult['SETTINGS']['BACKGROUND_TEXTURE']['INPUT'] = $textures;
            $arResult['SETTINGS']['BACKGROUND_TEXTURE']['NAME'] = GetMessage('BACKGROUND_TEXTURE');

            $arResult["SETTINGS"][$setting]["INPUT"] = GetMessage("BACKGROUND_IMAGE"). " <select class='selectBox toggle-list' name='SETTINGS[".$setting."_IMAGE]'>
                                <option value='none'>".GetMessage("CHOOSE")."</option>";


            foreach($fl as $f)
                if($f != "." && $f != ".." && strpos($f, "fon") === 0)
                    $arResult["SETTINGS"][$setting]["INPUT"] .= "<option ".($value1=="/backgrounds/".$f?'selected="selected"':'')." value='".$f."'>".$f."</option>";


            $arResult["SETTINGS"][$setting]["INPUT"] .= "</select><br>". GetMessage("BACKGROUND_COLOR"). " <input type='text' class='txt' style='width: 50px !important;' id='bgcolor' name='SETTINGS[".$setting."_COLOR]' value='".$value3."'/>";
            $arResult["SETTINGS"][$setting]["NAME"] = GetMessage($setting);
            break;


        case "MIN_MAX":
            $value1 = $ys_options["min"];
            $value2 = $ys_options["max"];

            //$arResult["SETTINGS"][$setting]["INPUT"] = GetMessage("MIN").": <input type='text' class='txt' size='5' name='SETTINGS[".$setting."_MIN]' value='".$value1."' style='display: none;'/> <span id='".$setting."_MIN' style='font-weight: bold;'>".$value1."</span> ".GetMessage("MAX").": <input type='text' class='txt' size='5' name='SETTINGS[".$setting."_MAX]' value='".$value2."' style='display: none;'/><span id='".$setting."_MAX' style='font-weight: bold;'>".$value2."</span><div id='slider_res_min' style='width: 200px;'></div><div id='slider_res_max' style='width: 200px; margin-top: 10px;'></div>";
            $arResult["SETTINGS"][$setting]["INPUT"] = GetMessage("MIN").": <input type='text' class='txt' size='5' name='SETTINGS[".$setting."_MIN]' value='".$value1."' style='display: none;'/> <span id='".$setting."_MIN' style='font-weight: bold;'>".($value1 + 20)."</span> ".GetMessage("MAX").": <input type='text' class='txt' size='5' name='SETTINGS[".$setting."_MAX]' value='".$value2."' style='display: none;'/><span id='".$setting."_MAX' style='font-weight: bold;'>".($value2 + 20)."</span><br><br><div id='slider-resolution' class='ys_slider'></div>";
            $arResult["SETTINGS"][$setting]["NAME"] = GetMessage($setting);

            break;


        case "WINDOW":
            $value1 = $ys_options["windowcolor"];
            $value2 = $ys_options["windowborder"];
            $value3 = $ys_options["windowopacity"];

            $selectOpacity = "<select class='selectBox toggle-list' style='width: 30px !important;' name='SETTINGS[".$setting."_OPACITY]'>
                                <option value=''>".GetMessage("CHOOSE")."</option>
                                <option ".($value3 == '1'?'selected="selected"':'')." value='1'>1</option>
                                <option ".($value3 == '0.9'?'selected="selected"':'')." value='0.9'>0.9</option>
                                <option ".($value3 == '0.8'?'selected="selected"':'')." value='0.8'>0.8</option>
                                <option ".($value3 == '0.7'?'selected="selected"':'')." value='0.7'>0.7</option>
                                <option ".($value3 == '0.6'?'selected="selected"':'')." value='0.6'>0.6</option>
                                <option ".($value3 == '0.5'?'selected="selected"':'')." value='0.5'>0.5</option>
                                <option ".($value3 == '0.4'?'selected="selected"':'')." value='0.4'>0.4</option>
                                <option ".($value3 == '0.3'?'selected="selected"':'')." value='0.3'>0.3</option>
                                <option ".($value3 == '0.2'?'selected="selected"':'')." value='0.2'>0.2</option>
                                <option ".($value3 == '0.1'?'selected="selected"':'')." value='0.1'>0.1</option>
                            </select>";

            $sliderOpacity = "<input type='text' style='display: none;' name='SETTINGS[".$setting."_OPACITY]' value='" . $value3 . "'><div id='slider-opacity'></div><span id='OPACITY_TEXT'>" . $value3 . "</span>";

            $arResult["SETTINGS"][$setting]["INPUT"] = "<div style='float: left; margin-right: 5px;'>" . GetMessage("WINDOW_COLOR")." <input type='text' class='txt' style='width: 50px !important;' name='SETTINGS[".$setting."_COLOR]' value='".$value1."'/></div> <div><div style='float: left;'>". GetMessage("WINDOW_OPACITY")."</div> " . $sliderOpacity . "</div> <br>". GetMessage("WINDOW_BORDER"). " <input type='checkbox' class='checkbox' name='SETTINGS[".$setting."_BORDER]'". ($value2=='Y' ? ' CHECKED' : ''). " value='Y' /><br>";
//            $arResult["SETTINGS"][$setting]["INPUT"] = GetMessage("WINDOW_COLOR")." <input type='text' class='txt' style='width: 50px !important;' name='SETTINGS[".$setting."_COLOR]' value='".$value1."'/> <div><div style='float: left;'>". GetMessage("WINDOW_OPACITY")."</div> " . $sliderOpacity . "</div> <br>". GetMessage("WINDOW_BORDER"). " <input type='checkbox' class='checkbox' name='SETTINGS[".$setting."_BORDER]'". ($value2=='Y' ? ' CHECKED' : ''). " value='Y' /><br>"; 
            $arResult["SETTINGS"][$setting]["NAME"] = GetMessage($setting);
            break;


        case "SET_DEFAULT":
            if($GLOBALS["USER"]->IsAdmin()){
                $arResult["SETTINGS"][$setting]["INPUT"] = "<input type='checkbox' class='checkbox' name='SETTINGS[".$setting."]' value='Y'/>";
                $arResult["SETTINGS"][$setting]["NAME"] = GetMessage($setting);
				$arResult["SETTINGS"][$setting]["MINI"] = "Y";
            }
            break;
    }
}

// Hack for additional SEF mode
CModule::IncludeModule('iblock');
if($this->StartResultCache(86400, SITE_ID."_".$USER->GetID()."_".serialize($ys_options)))
{
	$arResult['ibCount'] = 0;
	$arResult['types'] = array();
	$resTypeIb = CIBlockType::GetList(array('ID' => 'asc'));
	while ($arTypeIb = $resTypeIb->Fetch()) {
		if (strpos($arTypeIb['ID'], 'catalog') !== 0 && strpos($arTypeIb['ID'], SITE_ID) !== 0) continue;

		$resIblocks = CIBlock::GetList(array('ID' => 'asc'), array('ACTIVE' => 'Y', 'SITE_ID' => SITE_ID, 'TYPE' => $arTypeIb['ID']));

		while ($arIblock = $resIblocks->Fetch()) {
			$arResult['types'][$arTypeIb['ID']][] = $arIblock['CODE'];
			$arResult['ibCount']++;
		}
	}
	
	$this->EndResultCache();
}

if (count($arResult['types']) > 1 || $arResult['ibCount'] > 1) {
	COption::SetOptionString(CYSBitronicSettings::getModuleId(), "architect", 'multi', false, SITE_ID);
}
else{
	COption::SetOptionString(CYSBitronicSettings::getModuleId(), "architect", '', false, SITE_ID);
}
$this->IncludeComponentTemplate();
?>