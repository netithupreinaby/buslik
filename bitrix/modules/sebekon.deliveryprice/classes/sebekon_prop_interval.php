<?
class CSebekonPropertyInterval {

	function GetUserTypeDescription() {
		return array(
			"PROPERTY_TYPE" => "S",
			"USER_TYPE" => "SebekonPropertyInterval",
			"DESCRIPTION" => GetMessage('SEBEKON_DELIVERYPRICE_INTERVAL'),
			//optional handlers
			"GetPropertyFieldHtml" => array("CSebekonPropertyInterval","GetPropertyFieldHtml"),
			"ConvertToDB" => array("CSebekonPropertyInterval","ConvertToDB"),
		);
	}
	
	function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
	{
	
		if(is_array($value["VALUE"]) && isset($value["VALUE"]["VALUE"])) {
			$value["VALUE"] = $value["VALUE"]["VALUE"];
		}
		
		if(is_array($value["DESCRIPTION"]) && isset($value["DESCRIPTION"]["VALUE"])) {
			$value["DESCRIPTION"] = $value["DESCRIPTION"]["VALUE"];
		}
	
		if (!is_array($value["DESCRIPTION"])) {
			$interval  = explode('-', $value["DESCRIPTION"]);
		}
		if (!is_array($value["VALUE"])) {
			$value  = explode('-', $value["VALUE"]);
		}
		$result = GetMessage('SEBEKON_DELIVERYPRICE_INTERVAL_FROM').'<input type="text" size="4" name="'.$strHTMLControlName["DESCRIPTION"].'[0]" value="'.htmlspecialcharsbx($interval[0]).'">&mdash;<input type="text" size="4" name="'.$strHTMLControlName["DESCRIPTION"].'[1]" value="'.htmlspecialcharsbx($interval[1]).'">';
		$result .= GetMessage('SEBEKON_DELIVERYPRICE_INTERVAL_PRICE').'<input type="text" size="20" name="'.$strHTMLControlName["VALUE"].'[0]" value="'.htmlspecialcharsbx($value[0]).'">';
		$result .= GetMessage('SEBEKON_DELIVERYPRICE_INTERVAL_PRICE_KM').'<input type="text" size="20" name="'.$strHTMLControlName["VALUE"].'[1]" value="'.htmlspecialcharsbx($value[1]).'">';
	
		return  $result;
	}
	
	function ConvertToDB($arProperty, $value)
	{
		$result = array();
		$return = array();
		
		if(is_array($value["VALUE"]) && isset($value["VALUE"]["VALUE"]))
		{
			$result["VALUE"] = $value["VALUE"]["VALUE"];
			$result["DESCRIPTION"] = $value["DESCRIPTION"]["VALUE"];
		}
		else
		{
			$result["VALUE"] = $value["VALUE"];
			$result["DESCRIPTION"] = $value["DESCRIPTION"];
		}
		
		if (!is_array($result["DESCRIPTION"])) $result["DESCRIPTION"] = array($result["DESCRIPTION"]);
		if (!is_array($result["VALUE"])) $result["VALUE"] = array($result["VALUE"]);
		
		$return["DESCRIPTION"] = implode('-',$result["DESCRIPTION"]);
		$return["VALUE"] = implode('-',$result["VALUE"]);
		if ($return["VALUE"] == '-') return false;
				
		return $return;
	}
	
}
?>
