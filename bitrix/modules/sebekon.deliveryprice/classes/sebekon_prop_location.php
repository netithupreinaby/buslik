<?
class CSebekonPropertyLocation {

	function GetUserTypeDescription()
	{
		return array(
			"PROPERTY_TYPE"	=>"E",
			"USER_TYPE"		=>"Locations",
			"DESCRIPTION" => GetMessage('SEBEKON_DELIVERYPRICE_LOCATION'),
			"CLASS_NAME" => "CSebekonPropertyLocation",
			"GetPropertyFieldHtml"	=>array("CSebekonPropertyLocation","GetPropertyFieldHtml"),
			"ConvertToDB"		=>array("CSebekonPropertyLocation","ConvertToDB"),
			"ConvertFromDB"		=>array("CSebekonPropertyLocation","ConvertFromDB"),
		);
	}

	function PrepareSettings($arProperty) {
		return array(
			"size" =>  20,
			"width" => 0
		);
	}

	function GetSettingsHTML($arProperty, $strHTMLControlName, &$arPropertyFields) {
		$settings = CSebekonPropertyLocation::PrepareSettings($arProperty);

		$arPropertyFields = array(
			"HIDE" => array("COL_COUNT"),
		);

		return '';
	}

	//PARAMETERS:
	//$arProperty - b_iblock_property.*
	//$value - array("VALUE","DESCRIPTION") -- here comes HTML form value
	//strHTMLControlName - array("VALUE","DESCRIPTION")
	//return:
	//safe html
	function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName) {
		$settings = CIBlockPropertyElementList::PrepareSettings($arProperty);

		if($settings["width"] > 0)
			$width = ' style="width:'.$settings["width"].'px"';
		else
			$width = '';
			
		if (!is_array($value["VALUE"]) && strlen($value["VALUE"])>0) {
				$value["VALUE"] = unserialize($value["VALUE"]);
		}
		if (!is_array($value["VALUE"])) $value["VALUE"] = array();

		$html = '<select multiple="multiple" name="'.htmlspecialcharsEx($strHTMLControlName["VALUE"]).'[]" size="10">';
		if (CModule::IncludeModule('sale')) {
			$db_vars = CSaleLocation::GetList(Array("COUNTRY_NAME_LANG"=>"ASC", "REGION_NAME_LANG"=>"ASC", "CITY_NAME_LANG"=>"ASC"), array(), LANG);
			while ($vars = $db_vars->Fetch()) {
				$locationName = $vars["COUNTRY_NAME"];

				if (strlen($vars["REGION_NAME"]) > 0)
				{
					if (strlen($locationName) > 0)
						$locationName .= " - ";
					$locationName .= $vars["REGION_NAME"];
				}
				if (strlen($vars["CITY_NAME"]) > 0)
				{
					if (strlen($locationName) > 0)
						$locationName .= " - ";
					$locationName .= $vars["CITY_NAME"];
				}
				$html .= '<option value="'.htmlspecialcharsEx($vars["ID"]).'" '.((in_array($vars["ID"], $value["VALUE"]))?'selected="selected"':'').'>'.htmlspecialcharsbx($locationName).'</option>';
			}
		}
		$html .= '</select>';
		
		return  $html;
	}	
	
	
	function ConvertToDB($arProperty, $value) {
		$result = array();
		$return = array();

		if(is_array($value["VALUE"]) && is_array($value["VALUE"]["VALUE"]))
		{
			$result["VALUE"] = $value["VALUE"]["VALUE"];
		}
		else
		{
			$result["VALUE"] = $value["VALUE"];
		}
		if (!is_array($result["VALUE"])) $result["VALUE"] = array();
		$return["VALUE"] = serialize($result["VALUE"]);		
		return $return;
	}

	function ConvertFromDB($arProperty, $value) {
		$return = array();
		if (strLen(trim($value["VALUE"])) > 0)
			$return["VALUE"] = unserialize($value["VALUE"]);
			
		if (!is_array($return["VALUE"])) $return["VALUE"] = array();
		return $return;
	}
}

AddEventHandler("iblock", "OnIBlockPropertyBuildList", array("CSebekonPropertyLocation", "GetUserTypeDescription"));
?>
