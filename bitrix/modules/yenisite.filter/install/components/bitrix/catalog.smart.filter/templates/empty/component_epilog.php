<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if (CURRENCY_SKIP_CACHE) define("CURRENCY_SKIP_CACHE", false);
// Elements dop filter
if($arParams["CURRENCY_ID"])
{
	global ${$arParams["FILTER_NAME"]};
	$ardopFilter = &${$arParams["FILTER_NAME"]};
	//echo "<pre style='text-align:left;'>";echo htmlspecialcharsbx(var_export($ardopFilter,1));echo "</pre>";
	foreach ($ardopFilter as $key=>$val){
	   $match = array();   
	   preg_match_all('/CATALOG_PRICE_(\S+)/msi',$key,$match);
	   if(count($match[1][0])>0){
		  unset($ardopFilter[$key]);
		  $dopFilter = array(
			 "LOGIC" => "OR",
		  );
		  $obCurrency = CCurrency::GetList();         
		  while($arCurrency = $obCurrency->Fetch()){
			 if($arParams["CURRENCY_ID"] == $arCurrency['CURRENCY']){
				$dopFilter[] = array(
				   $key => $val,
				   'CATALOG_CURRENCY_'.$match[1][0] => $arCurrency['CURRENCY'],
				);
			 }else{
				if(is_array($val))
				$dopFilter[] = array(
				   $key => array(
					  CCurrencyRates::ConvertCurrency($val[0],$arParams["CURRENCY_ID"],$arCurrency['CURRENCY']),
					  CCurrencyRates::ConvertCurrency($val[1],$arParams["CURRENCY_ID"],$arCurrency['CURRENCY']),                  
				   ),
				   'CATALOG_CURRENCY_'.$match[1][0] => $arCurrency['CURRENCY'],
				);
				else 
				$dopFilter[] = array(
				   $key => CCurrencyRates::ConvertCurrency($val,$arParams["CURRENCY_ID"],$arCurrency['CURRENCY']),
				   'CATALOG_CURRENCY_'.$match[1][0] => $arCurrency['CURRENCY'],
				);
			 }
				
		  }
	   }                            
	}
	$ardopFilter[] = $dopFilter;
	//echo "<pre style='text-align:left;'>";echo htmlspecialcharsbx(var_export($ardopFilter,1));echo "</pre>";
}
?>