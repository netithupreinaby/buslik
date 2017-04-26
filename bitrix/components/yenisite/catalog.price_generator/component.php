<?DeleteDirFilesEx("/yenisite.pricegen");
	if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

	function yen_hierarchy($id){
		CModule::IncludeModule('iblock');
		$sec_id = $id;
		$sec = array();
		$i = 0;
		
		while($id)
		{
			$res = CIBlockSection::GetByID($id)->Fetch();
			$sec[$i]["ID"] = $res["ID"];
			$sec[$i]["NAME"] = $res["NAME"];
			
			$id = $res["IBLOCK_SECTION_ID"];
			$i++;	
		}
		return $sec;
	}
	$arParams["CURRENCY_LIST"]=(!empty($arParams["CURRENCY_LIST"])) ? $arParams["CURRENCY_LIST"] : "RUB";
	$arCurrencyParams = array('CURRENCY_ID' => $arParams["CURRENCY_LIST"]);
	CPageOption::SetOptionString("main", "nav_page_in_session", "N");
	
	/*************************************************************************
		Processing of received parameters
	*************************************************************************/
	
	global $USER;
	
	$arParams["FILE_NAME"]=$arParams["FILE_NAME"].".".$arParams["FILE_TYPE"];
	$filename = $_SERVER["DOCUMENT_ROOT"].$arParams["FILE_NAME"]; 
	
	
	if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;
	
    $arParams["CACHE_TIME_LAST"] = $arParams["CACHE_TIME"];	
    
	$arParams["CACHE_TIME"] = 0;	
	
	if(isset($_REQUEST["PAGEN_1"])){
		$APPLICATION->RestartBuffer();
		if($_REQUEST["IS_GENERATE"] == "Y" && $_REQUEST["PAGEN_1"] == 1)
        unset($_SESSION["YEN_PG"]);
	}
	
	if(!isset($_REQUEST[PROCESS]))
	{
		$generate = false;	
		
		if(file_exists($_SERVER["DOCUMENT_ROOT"].$arParams["FILE_NAME"]))
		{
			$time_sec=time();
			$time_file=filemtime($_SERVER["DOCUMENT_ROOT"].$arParams["FILE_NAME"]);
			$time= intval($arParams["CACHE_TIME_LAST"]) - ($time_sec-$time_file);  
			if($time <= 0) $generate = true;
			
			if(!$generate){
				$string = file_get_contents($filename);
				if(substr_count($string, "<BR name='end'>") == 0)
                $generate = true;
			}
		}
		else $generate = true;
		
		if($_REQUEST["PROCESS"] == "Y") $generate = true;
		
		
		if(!$generate && !$USER->IsAdmin()) 
        LocalRedirect($arParams["FILE_NAME"]);
		elseif($generate && $_REQUEST[IS_GENERATE] != "Y" && !$USER->IsAdmin()){
			//unset($_SESSION["YEN_PG"]);
			$page = $APPLICATION->GetCurPageParam("PAGEN_1=1&PROCESS=Y", array()); 
			LocalRedirect($page);
		}
	}
	
	
	$arParams["INCLUDE_SUBSECTIONS"] = "Y";
	$arParams["SHOW_ALL_WO_SECTION"] = "Y";
	
	
	if(strlen($arParams["FILTER_NAME"])<=0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"]))
	{
		$arrFilter = array();
	}
	else
	{
		global ${$arParams["FILTER_NAME"]};
		$arrFilter = ${$arParams["FILTER_NAME"]};
		if(!is_array($arrFilter))
		$arrFilter = array();
	}
	
	
	$arParams["PAGE_ELEMENT_COUNT"] = CIBlockElement::GetList(
	array(),
	array('IBLOCK_ID' => $arParams["IBLOCK_ID"]),
	false,
	false,
	array('ID'))->SelectedRowsCount();
	
	
	if(!is_array($arParams["PROPERTY_CODE"]))
	$arParams["PROPERTY_CODE"] = array();
	foreach($arParams["PROPERTY_CODE"] as $k=>$v)
	if($v==="")
	unset($arParams["PROPERTY_CODE"][$k]);
	
	if(!is_array($arParams["PRICE_CODE"]))
	$arParams["PRICE_CODE"] = array();
	$arParams["USE_PRICE_COUNT"] = $arParams["USE_PRICE_COUNT"]=="Y";
	$arParams["SHOW_PRICE_COUNT"] = intval($arParams["SHOW_PRICE_COUNT"]);
	if($arParams["SHOW_PRICE_COUNT"]<=0)
	$arParams["SHOW_PRICE_COUNT"]=1;
	$arParams["USE_PRODUCT_QUANTITY"] = $arParams["USE_PRODUCT_QUANTITY"]==="Y";
	$arParams["DISPLAY_TOP_PAGER"] = $arParams["DISPLAY_TOP_PAGER"]=="Y";
	$arParams["DISPLAY_BOTTOM_PAGER"] = $arParams["DISPLAY_BOTTOM_PAGER"]!="N";
	$arParams["PAGER_TITLE"] = trim($arParams["PAGER_TITLE"]);
	$arParams["PAGER_SHOW_ALWAYS"] = $arParams["PAGER_SHOW_ALWAYS"]!="N";
	$arParams["PAGER_TEMPLATE"] = trim($arParams["PAGER_TEMPLATE"]);
	$arParams["PAGER_DESC_NUMBERING"] = $arParams["PAGER_DESC_NUMBERING"]=="Y";
	$arParams["PAGER_DESC_NUMBERING_CACHE_TIME"] = intval($arParams["PAGER_DESC_NUMBERING_CACHE_TIME"]);
	$arParams["PAGER_SHOW_ALL"] = $arParams["PAGER_SHOW_ALL"]!=="N";

	$arNavParams = array(
	"nPageSize" => $arParams["PAGE_ELEMENT_COUNT"],
	"bDescPageNumbering" => $arParams["PAGER_DESC_NUMBERING"],
	"bShowAll" => $arParams["PAGER_SHOW_ALL"],
	);
	$arNavigation = CDBResult::GetNavParams($arNavParams);
	if($arNavigation["PAGEN"]==0 && $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"]>0)
	$arParams["CACHE_TIME"] = $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"];
	
	$arParams["CACHE_FILTER"]=$arParams["CACHE_FILTER"]=="Y";
	if(!$arParams["CACHE_FILTER"] && count($arrFilter)>0)
	$arParams["CACHE_TIME"] = 0;
	
	$arParams["PRICE_VAT_INCLUDE"] = $arParams["PRICE_VAT_INCLUDE"] !== "N";
	
	/*************************************************************************
		Work with cache
	*************************************************************************/
	if($this->StartResultCache(false, array($arrFilter, ($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()), $arNavigation)))
	{
		if(!CModule::IncludeModule("iblock"))
		{
			$this->AbortResultCache();
			ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
			return;
		}
		
		$arSelect = array();
		
		
		if(isset($arParams["SECTION_USER_FIELDS"]) && is_array($arParams["SECTION_USER_FIELDS"]))
		{
			foreach($arParams["SECTION_USER_FIELDS"] as $field)
			if(is_string($field) && preg_match("/^UF_/", $field))
			$arSelect[] = $field;
		}
		if(preg_match("/^UF_/", $arParams["META_KEYWORDS"])) $arSelect[] = $arParams["META_KEYWORDS"];
		if(preg_match("/^UF_/", $arParams["META_DESCRIPTION"])) $arSelect[] = $arParams["META_DESCRIPTION"];
		if(preg_match("/^UF_/", $arParams["BROWSER_TITLE"])) $arSelect[] = $arParams["BROWSER_TITLE"];
		
		$arFilter = array(
		"ACTIVE"=>"Y",
		"GLOBAL_ACTIVE"=>"Y",
		"IBLOCK_ID"=>$arParams["IBLOCK_ID"],
		"IBLOCK_ACTIVE"=>"Y",
		);
		
		$bSectionFound = false;
		
		
		//Hidden triky parameter USED to display linked
		//by default it is not set
		if($arParams["BY_LINK"]==="Y")
		{
			$arResult = array(
			"ID" => 0,
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			);
			$bSectionFound = true;
		}
		elseif(strlen($arParams["SECTION_CODE"]) > 0)
		{
			$arFilter["CODE"]=$arParams["SECTION_CODE"];
			$rsSection = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect);
			$rsSection->SetUrlTemplates("", $arParams["SECTION_URL"]);
			$arResult = $rsSection->GetNext();
			if($arResult)
			$bSectionFound = true;
		}
		elseif($arParams["SECTION_ID"])
		{
			$arFilter["ID"]=$arParams["SECTION_ID"];
			$rsSection = CIBlockSection::GetList(Array(), $arFilter, false, $arSelect);
			$rsSection->SetUrlTemplates("", $arParams["SECTION_URL"]);
			$arResult = $rsSection->GetNext();
			if($arResult)
			$bSectionFound = true;
		}
		else
		{
			//Root section (no section filter)
			$arResult = array(
			"ID" => 0,
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],
			);
			$bSectionFound = true;
		}
		
		if(!$bSectionFound)
		{
			$this->AbortResultCache();
			ShowError(GetMessage("CATALOG_SECTION_NOT_FOUND"));
			@define("ERROR_404", "Y");
			if($arParams["SET_STATUS_404"]==="Y")
			CHTTP::SetStatus("404 Not Found");
			return;
		}
		elseif($arResult["ID"] > 0 && $arParams["ADD_SECTIONS_CHAIN"])
		{
			$arResult["PATH"] = array();
			$rsPath = GetIBlockSectionPath($arResult["IBLOCK_ID"], $arResult["ID"]);
			$rsPath->SetUrlTemplates("", $arParams["SECTION_URL"]);
			while($arPath = $rsPath->GetNext())
			{
				$arResult["PATH"][]=$arPath;
			}
		}
		
		//This function returns array with prices description and access rights
		//in case catalog module n/a prices get values from element properties
		$arResult["PRICES"] = CIBlockPriceTools::GetCatalogPrices($arParams["IBLOCK_ID"], $arParams["PRICE_CODE"]);
		
		$arResult["PICTURE"] = CFile::GetFileArray($arResult["PICTURE"]);
		$arResult["DETAIL_PICTURE"] = CFile::GetFileArray($arResult["DETAIL_PICTURE"]);
		
		//get section with LEFT_MARGIN
		
		$arSectionFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y');
		
		$rsSections = CIBlockSection::GetList(array('LEFT_MARGIN' => 'ASC'), $arSectionFilter);
		
		$arSectionId = array();

		while ($section = $rsSections->Fetch())
		{	
			$arSectionId[]=$section['ID'];
		}
		
		// list of the element fields that will be used in selection
		$arSelect = array(
		"ID",
		"NAME",
		"CODE",
		"DATE_CREATE",
		"ACTIVE_FROM",
		"CREATED_BY",
		"IBLOCK_ID",
		"IBLOCK_SECTION_ID",
		"DETAIL_PAGE_URL",
		"DETAIL_TEXT",
		"DETAIL_TEXT_TYPE",
		"DETAIL_PICTURE",
		"PREVIEW_TEXT",
		"PREVIEW_TEXT_TYPE",
		"PREVIEW_PICTURE",
		);
		
		$arOffersProperties = array();
		
		if($arParams["EXISTENCE_CHECK"]==="Y"){
			$arSelect[]= "PROPERTY_FOR_ORDER";
			$arOffersProperties["CODE"][] = "FOR_ORDER";
		}
		foreach($arParams["PROPERTY_CODE"] as $key){
			$arSelect[]= "PROPERTY_{$key}";
			$arOffersProperties["CODE"][] = $key;
		}
		
		$arFilter = array(
		"IBLOCK_ID" => $arParams["IBLOCK_ID"],
		"IBLOCK_LID" => SITE_ID,
		"IBLOCK_ACTIVE" => "Y",
		"ACTIVE_DATE" => "Y",
		"ACTIVE" => "Y",
		"CHECK_PERMISSIONS" => "Y",
		"INCLUDE_SUBSECTIONS" => $arParams["INCLUDE_SUBSECTIONS"],
		"SECTION_GLOBAL_ACTIVE" => "Y",
		"SECTION_ID" => $arSectionId,
		);
		
		if($arParams["BY_LINK"]!=="Y")
		{
			if($arResult["ID"])
			$arFilter["SECTION_ID"] = $arResult["ID"];
			elseif(!$arParams["SHOW_ALL_WO_SECTION"])
			$arFilter["SECTION_ID"] = 0;
		}
		
		if(is_array($arrFilter["OFFERS"]))
		{
			$arOffersIBlock = CIBlockPriceTools::GetOffersIBlock($arParams["IBLOCK_ID"]);
			if(is_array($arOffersIBlock))
			{
				if(!empty($arrFilter["OFFERS"]))
				{
					$arSubFilter = $arrFilter["OFFERS"];
					$arSubFilter["IBLOCK_ID"] = $arOffersIBlock["OFFERS_IBLOCK_ID"];
					$arSubFilter["ACTIVE_DATE"] = "Y";
					$arSubFilter["ACTIVE"] = "Y";
					$arFilter["=ID"] = CIBlockElement::SubQuery("PROPERTY_".$arOffersIBlock["OFFERS_PROPERTY_ID"], $arSubFilter);
				}
				
				$arPriceFilter = array();
				foreach($arrFilter as $key => $value)
				{
					if(preg_match('/^(>=|<=)CATALOG_PRICE_/', $key))
					{
						$arPriceFilter[$key] = $value;
						unset($arrFilter[$key]);
					}
				}
				
				if(!empty($arPriceFilter))
				{
					$arSubFilter = $arPriceFilter;
					$arSubFilter["IBLOCK_ID"] = $arOffersIBlock["OFFERS_IBLOCK_ID"];
					$arSubFilter["ACTIVE_DATE"] = "Y";
					$arSubFilter["ACTIVE"] = "Y";
					$arFilter[] = array(
					"LOGIC" => "OR",
					array($arPriceFilter),
					"=ID" => CIBlockElement::SubQuery("PROPERTY_".$arOffersIBlock["OFFERS_PROPERTY_ID"], $arSubFilter),
					);
				}
			}
		}
		
		//PRICES
		if(!$arParams["USE_PRICE_COUNT"])
		{
			foreach($arResult["PRICES"] as $key => $value)
			{
				$arSelect[] = $value["SELECT"];
				$arFilter["CATALOG_SHOP_QUANTITY_".$value["ID"]] = $arParams["SHOW_PRICE_COUNT"];
			}
		}
		$arSort = array(
		$arParams["ELEMENT_SORT_FIELD"] => $arParams["ELEMENT_SORT_ORDER"],
		"ID" => "DESC",
		);
		//EXECUTE
		$rsElements = CIBlockElement::GetList(array("IBLOCK_ID" => "asc", "IBLOCK_SECTION_ID" => "asc",  "SORT" => "asc",  "ID" => "asc"), array_merge($arrFilter, $arFilter), false, $arNavParams, $arSelect);
		$rsElements->SetUrlTemplates($arParams["DETAIL_URL"]);
		
		//if($arParams["BY_LINK"]!=="Y" && !$arParams["SHOW_ALL_WO_SECTION"])
		$rsElements->SetSectionContext($arResult);
		$arResult["ITEMS"] = array();
		
		$arElments = array();
		while($obElement = $rsElements->GetNext()) {
			$arElements[$obElement['IBLOCK_SECTION_ID']][] = $obElement;
		}
		unset($obElement);
		
		$arSortElemetns = array();
		
		foreach ($arSectionId as $key => $valSection){
			if (!empty($arElements[$valSection])) {
				
				$arSortElemetns = array_merge($arSortElemetns, $arElements[$valSection]);
				unset($arElements[$valSection]);
			}
		}
		unset($arSectionId,$arElements);
		
		
		foreach($arParams["PROPERTY_CODE"] as $pid)
		{
			$arProperties = CIBlockProperty::GetList(array(), array("CODE" => $pid, ">NAME" => 0))->GetNext();
			if($arProperties["NAME"])
			$arResult["PROPS"][$pid] =  $arProperties["NAME"];
		}
		unset($arProperties);
		
 		foreach($arParams["PRICE_CODE"] as $k=>$pr)
		{
		    if($pr && !in_array($pr, $arResult["PRICE_TITLES"]))
		    {	
				$arResult["PRICE_TITLE"][] = $arResult["PRICES"][$pr]["TITLE"];
				$arResult["PRICE_TITLES"][] = $pr;
			}
		}
		
		foreach($arParams['IBLOCK_ID'] as $idIblock){
			if($idIblock)
			{
				$db_iblock = CIBlock::GetByID($idIblock)->Fetch();
				
				$arIBType = CIBlockType::GetByIDLang($db_iblock["IBLOCK_TYPE_ID"], LANG);
				
				$arHierarchyBlocks["DB_IBLOCK"]["ID"][$idIblock] = $db_iblock["ID"];
				$arHierarchyBlocks["DB_IBLOCK"]["NAME"][$idIblock] = $db_iblock["NAME"];
				$arHierarchyBlocks["IB_TYPE"]["ID"][$idIblock] = $arIBType["ID"];
				$arHierarchyBlocks["IB_TYPE"]["NAME"][$idIblock] = $arIBType["NAME"];
			}
		}
		
		unset($db_iblock,$arIBType);
		
		
		$arIdOffer= array();
		foreach($arSortElemetns as $idOffer){
			$arIdOffer[$idOffer["IBLOCK_ID"]]["ID"][] = $idOffer["ID"];
		}
		
		$arOffersElements = array();
		foreach($arParams['IBLOCK_ID'] as $arIblockOffer){
			$offersFilter = array(
			"IBLOCK_ID" => $arIblockOffer,
			"HIDE_NOT_AVAILABLE" => "N",
			"CHECK_PERMISSIONS" => "N"
			);		
			
 			$arOffers[$arIblockOffer] = CIBlockPriceTools::GetOffersArray(
			$offersFilter,
			$arIdOffer[$arIblockOffer]["ID"],
			array(
			"ID" => "asc",
			),
			$arSelect,
			$arOffersProperties["CODE"],
			0,
			$arResult["PRICES"],
			1,
			$arCurrencyParams
			); 
			
			foreach ($arOffers[$arIblockOffer] as $arSectElemOffer=>$arElemOffer)
			{
				$arOffersElements[$arElemOffer["LINK_ELEMENT_ID"]][] = $arElemOffer;
			}
		}
		
		unset($arOffers, $offersFilter);

		
		foreach($arSortElemetns as $arItem)
		{
			if($_REQUEST["IS_GENERATE"] == "Y"){ 
				
				if(!in_array($arHierarchyBlocks["IB_TYPE"]["ID"][$arItem["IBLOCK_ID"]], $_SESSION["YEN_PG"]["TYPE"]))
				{   
                    $_SESSION["YEN_PG"]["TYPE"][] = $arHierarchyBlocks["IB_TYPE"]["ID"][$arItem["IBLOCK_ID"]];
                    $arItem["HIERARCHY"]["TYPE"] = $arHierarchyBlocks["IB_TYPE"]["ID"][$arItem["IBLOCK_ID"]];
                    $arItem["HIERARCHY"]["TYPE_N"] = $arHierarchyBlocks["IB_TYPE"]["NAME"][$arItem["IBLOCK_ID"]];
					
				}
                if(!in_array($arHierarchyBlocks["DB_IBLOCK"]["ID"][$arItem["IBLOCK_ID"]], $_SESSION["YEN_PG"]["IBLOCK"]))
                {
                    $_SESSION["YEN_PG"]["IBLOCK"][] = $arHierarchyBlocks["DB_IBLOCK"]["ID"][$arItem["IBLOCK_ID"]];
                    $arItem["HIERARCHY"]["IBLOCK"] = $arHierarchyBlocks["DB_IBLOCK"]["ID"][$arItem["IBLOCK_ID"]];
                    $arItem["HIERARCHY"]["IBLOCK_N"] = $arHierarchyBlocks["DB_IBLOCK"]["NAME"][$arItem["IBLOCK_ID"]];
				}
                $sec = yen_hierarchy($arItem["IBLOCK_SECTION_ID"]);
				$sec = array_reverse($sec);
                foreach($sec as $s){
					$arItem["HIERARCHY"]["SECTION"][] = $s["ID"];
					$arItem["HIERARCHY"]["SECTION_N"][] = $s["NAME"];
				}
			}
			
			$arItem["PREVIEW_PICTURE"] = CFile::GetFileArray($arItem["PREVIEW_PICTURE"]);
			$arItem["DETAIL_PICTURE"] = CFile::GetFileArray($arItem["DETAIL_PICTURE"]);
			
			if($arParams["EXISTENCE_CHECK"]==="Y")
			$arItem["FOR_ORDER"] = $arItem["PROPERTY_FOR_ORDER_VALUE"];
			
			foreach($arParams["PROPERTY_CODE"] as $pid)
			{	
				if ($arItem["PROPERTY_" . $pid . "_VALUE"])
				$arItem["DISPLAY_PROPERTIES"][$pid]["VALUE"] = $arItem["PROPERTY_" . $pid . "_VALUE"];
				else $arItem["DISPLAY_PROPERTIES"][$pid]["VALUE"] = " ";
			}
			
			$arItem["PRICES"] = CIBlockPriceTools::GetItemPrices($arParams["IBLOCK_ID"], $arResult["PRICES"], $arItem, $arParams['PRICE_VAT_INCLUDE'], $arCurrencyParams);
			
			
			if(!empty($arOffersElements[$arItem["ID"]])){
				
				foreach($arOffersElements[$arItem["ID"]] as $arOffersElement){

					$arOffersElementItem = $arItem;
					
					if (!empty($arOffersElement["PREVIEW_PICTURE"])) $arOffersElementItem["PREVIEW_PICTURE"] = $arOffersElement["PREVIEW_PICTURE"];
					if (!empty($arOffersElement["DETAIL_PICTURE"])) $arOffersElementItem["DETAIL_PICTURE"] = $arOffersElement["DETAIL_PICTURE"];
					
					
					if($arParams["EXISTENCE_CHECK"]==="Y")
					if(!empty($arOffersElement["PROPERTIES"]["FOR_ORDER"]["VALUE"]))
					$arOffersElementItem["FOR_ORDER"] = $arOffersElement["PROPERTIES"]["FOR_ORDER"]["VALUE"];
					
					foreach($arParams["PROPERTY_CODE"] as $pid)
					{	
						if(!empty($arOffersElement["DISPLAY_PROPERTIES"][$pid]["VALUE"]))
						$arOffersElementItem["DISPLAY_PROPERTIES"][$pid]["VALUE"] = $arOffersElement["DISPLAY_PROPERTIES"][$pid]["VALUE"];
					}
					
					if(!empty($arOffersElement["PRICES"]))
					$arOffersElementItem["PRICES"] = $arOffersElement["PRICES"];
					
					$arOffersElementItem["ID"] = $arOffersElement["ID"];
					$arOffersElementItem["CATALOG_AVAILABLE"] = $arOffersElement["CATALOG_AVAILABLE"];
					$arOffersElementItem["NAME"] = $arOffersElement["NAME"];
					
					$arResult["ITEMS"][]=$arOffersElementItem;
					$arResult["ELEMENTS"][] = $arOffersElementItem["ID"];
				}
			}else{ 
				$arResult["ITEMS"][]=$arItem;
				$arResult["ELEMENTS"][] = $arItem["ID"];
			}
		}
		
		$arResult["NAV_STRING"] = $rsElements->GetPageNavStringEx($navComponentObject, $arParams["PAGER_TITLE"], $arParams["PAGER_TEMPLATE"], $arParams["PAGER_SHOW_ALWAYS"]);
		$arResult["NAV_CACHED_DATA"] = $navComponentObject->GetTemplateCachedData();
		$arResult["NAV_RESULT"] = $rsElements;
		
		$this->SetResultCacheKeys(array(
		"ID",
		"NAV_CACHED_DATA",
		$arParams["META_KEYWORDS"],
		$arParams["META_DESCRIPTION"],
		$arParams["BROWSER_TITLE"],
		"NAME",
		"PATH",
		"IBLOCK_SECTION_ID",
		));
	}
	
	if(CModule::IncludeModule("catalog"))
	{
		$rsPrice=CCatalogGroup::GetList($v1="sort", $v2="asc");
		while($arr=$rsPrice->Fetch()) $arPriceLang[$arr["NAME"]] = $arr["NAME_LANG"];
	}
	foreach($arParams["PRICE_CODE"] as $k=>$pr)
	{
		if($pr && !in_array($arPriceLang[$pr], $arResult["PRICE_TITLES_LANG"]))
		{
			$arResult["PRICE_TITLES_LANG"][] = $arPriceLang[$pr];
			
		}
	}	
	
	$pagecount = $arResult["NAV_RESULT"]->NavPageCount;
	if($arParams["FILE_TYPE"]==""){
		print_r(GetMessage("ERROR_TYPE"));
		$arParams["FILE_TYPE"]="xls";
	}
?>


<form name="stepid" id="stepid" method="get">
    <input type="hidden" name="PAGEN_1" value="<?=isset($_REQUEST["PAGEN_1"])?$_REQUEST["PAGEN_1"]+1:1;?>" />
    <input type="hidden" name="PROCESS" value="Y" />
	<?if($_REQUEST["IS_GENERATE"] == "Y"):?>
    <input type="hidden" name="IS_GENERATE" value="Y" />
	<?endif?>    
	<?if(!$_REQUEST["PROCESS"]):?>
    <input type="submit" name="GO" value="<?=GetMessage("START")?>" /><br/>
    <?=GetMessage("SAVE")?>: <?=$arParams["FILE_NAME"]?>
	<?else:?>
	<?=GetMessage("GENERATE")?><br/>
	<?=GetMessage("PAGES")?> <?=htmlspecialchars($_REQUEST[PAGEN_1])?> <?=GetMessage("FROM")?> <?=$arResult["NAV_RESULT"]->NavPageCount?>    
	
	<?endif?>
</form>
<?
	
	
    if($_REQUEST["PAGEN_1"] > $pagecount){
        if($_REQUEST["IS_GENERATE"] == "Y"){        
            echo GetMessage("FINISH");
            LocalRedirect($arParams["FILE_NAME"]."?".time());        
            return;
		}
		
        global $APPLICATION;
		
        $page = $APPLICATION->GetCurPageParam("PAGEN_1=1&IS_GENERATE=Y", array("PAGEN_1"));                  
        LocalRedirect($page);
        return;
	}
	
	
    global $APPLICATION;
    $this->InitComponentTemplate();
    $template = & $this->GetTemplate();
    $header = $_SERVER["DOCUMENT_ROOT"].$template->GetFolder()."/header.php";
    $footer = $_SERVER["DOCUMENT_ROOT"].$template->GetFolder()."/footer.php";
	
	
    function wif($buffer){
        return $buffer;
	}
    
    
    if($_REQUEST["IS_GENERATE"]):
	
	
	ob_start("wif"); 
	
	
	if(file_exists($header) && $_REQUEST[PAGEN_1] == 1){
        
		$arResult["FIELDS"]["NAME"] = GetMessage("NAME");
		$arResult["FIELDS"]["ID"] = GetMessage("ID");
		$arResult["FIELDS"]["TITLE"] = GetMessage("TITLE");
        
		$fh = fopen($filename, "w"); 
		fclose($fn);
		require_once($header);    
	}
	
	$this->IncludeComponentTemplate();
	
	if(file_exists($footer) && $_REQUEST["PAGEN_1"] >= $pagecount){
		require_once($footer);
		echo "<BR name='end'>";
	}
	
	$bufer = ob_get_contents();
	
	$fh = fopen($filename, "a+"); 
	if($arParams["FILE_TYPE"] == "xls"){
		fwrite($fh, $bufer); 
	}
	//----------Word
	if($arParams["FILE_TYPE"] == "doc"){
		file_put_contents( $filename, $bufer );
	}
	fclose($fn);
	
	
	
	
	ob_clean();    
	ob_end_flush(); 
	
	if($arParams["FILE_TYPE"] == "pdf"){
		include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/yenisite.pricegen/install/pdf/MPDF57/mpdf.php");	
		$mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
		if(LANG_CHARSET=="UTF-8"){
		$mpdf->charset_in = 'utf-8';}
		else{
		$mpdf->charset_in = 'cp1251';}
		
		
		$mpdf->WriteHTML($bufer, 2);
		$mpdf->Output($arParams["FILE_NAME"], 'I');
	}
	
	//2 method pdf
	// require('/fpdf17/html2pdf.php');
	
	// $pdf=new PDF_HTML();
	// $pdf->SetFont('Arial','',12);
	// $pdf->AddPage();
	// $text=$bufer;
	// if(ini_get('magic_quotes_gpc')=='1')
	// $text=stripslashes($text);
	// $pdf->WriteHTML($text);
	// $pdf->Output('example2.pdf','D');
	
    endif;
    
if($_REQUEST["PROCESS"]):?>
<script>
	forma = document.getElementById('stepid');
	forma.submit();
</script>
<?endif;?>
<?
if(($_REQUEST["IS_GENERATE"] == "Y") || ($_REQUEST["PROCESS"]))die;