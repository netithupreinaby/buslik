<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
 require($DOCUMENT_ROOT."/bitrix/modules/main/include/prolog_admin_after.php");
?>

<?if(isset($_REQUEST["showshop"])):	 

	//$APPLICATION->set_cookie("RUSSIAN_VISITOR_ID", 156, time()+60*60*24*30*12*2, "/ru/");
	
	if(is_array($_REQUEST["show"])){
		$APPLICATION->set_cookie("TURBO_SET_".htmlspecialcharsEx($_REQUEST["id"]), implode(";", $_REQUEST["show"]));
		LocalRedirect("/bitrix/admin/yci_turbo_report.php?id=".htmlspecialcharsEx($_REQUEST["id"])."&lang=ru");
		
	}
	
endif?>


<?CJSCore::Init(array("jquery")); ?>

<script type="text/javascript">

function get_str(){

        var str = "";
        
        $("tr[title*='green'], tr[title*='cyan'], tr[title*='yellow'], tr[title*='red']").find("td:last").each(
            function(){
            
                id = $(this).parent().find("td:nth-child(2)").html();
                str = str + id + "/";
                
            }
        );
        
        $("#ids").attr("value", str);
        
        str = "";
        
        $("tr[title*='green'], tr[title*='cyan'], tr[title*='yellow'], tr[title*='red']").find("td:last").each(
            function(){
                str = str + $(this).find("input").attr("value") + "/";
            }
        );
       
        $("#prcs").attr("value", str);
}

function get_str_input(){

        var str = "";
        
        $("tr[title*='green'], tr[title*='cyan'], tr[title*='yellow'], tr[title*='red']").find("td:last").each(
            function(){            
                id = $(this).parent().find("td:nth-child(2)").html();
                str = str + id + "/";                
            }
        );
        
        $("#ids").attr("value", str);
        
        str = "";
        
        $("tr[title*='green'], tr[title*='cyan'], tr[title*='yellow'], tr[title*='red']").find("td:last").each(
            function(){
                str = str + $(this).find("input").attr("value") + "/";
            }
        );
       
        $("#prcs").attr("value", str);
}

$(
    function(){
        $("tr[title*='green']").find("td").css("background-color","#99FFA7");
		$("tr[title*='cyan']").find("td").css("background-color","#1faee9");
        $("tr[title*='yellow']").find("td").css("background-color","#FFEB6B");
        $("tr[title*='red']").find("td").css("background-color","#FF6B6B");

       var str = "";
        get_str();
    }
);
     
</script>


<?
IncludeModuleLangFile(__FILE__);
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
CModule::IncludeModule("yenisite.turbo");

global $USER;
if(!$USER->IsAdmin())
	return;

$id = htmlspecialcharsEx($_REQUEST["ID"]);



if(!isset($_REQUEST["by"]))
{
    $by = "ID";
    $sort = "asc";
}
else
{
    $by = $_REQUEST["by"];
    $sort = $_REQUEST["sort"];
}


$sTableID = "turbo_set_list";
//$oSort = new CAdminSorting($sTableID, "id", "asc");
$lAdmin = new CAdminList($sTableID, $oSort);

$arFilterFields = array();
$lAdmin->InitFilter($arFilterFields);
$arFilter = array();


$nPageSize = 10;
$property = "TURBO_YANDEX_LINK";
$page = $_REQUEST['PAGEN_1']?htmlspecialcharsEx($_REQUEST['PAGEN_1']):1;

$set = CTurbineSet::GetByID(htmlspecialcharsEx($_REQUEST["id"]));


$iblock = CIBlock::GetByID($set[IBLOCK_ID])->Fetch();


if($set['SELECT_PROP'] > 0)
{
	$dbResultList = CIBlockProperty::GetByID($set['SELECT_PROP']);
	if($ar_res = $dbResultList->GetNext())
		$filter_poperty = $ar_res['CODE'];
}

/*	FILTER ON PRODUCTS	*/
$arrFilter = CTurbine::getElementFilterForSet($set);
/*	  --------------	*/

$dbResultList = CIBlockElement::GetList(array("ID" => "asc"), $arrFilter, false/*, array("iNumPage" => $page, "nPageSize" => $nPageSize)*/);

$dbResultList = new CAdminResult($dbResultList, $sTableID);
//$dbResultList->NavStart();
//$lAdmin->NavText($dbResultList->GetNavPrint(GetMessage('SET')));


$headers = array(
    array(
        "id"    => "ID",
        "content"  => GetMessage('ID'),
        "sort"     => "id",
        "default"  => true,
    ),
    array(
        "id"    => "NAME",
        "content"  => GetMessage('NAME'),
        "sort"     => "NAME",
        "default"  => true,		
    ),	    
);




$pr = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $set['IBLOCK_ID'], "CODE" => "TURBO_SHOP_YANDEX_%"));

$codes = array();
$allCodes = array();
$allNames = array();
$arBadStatus = array('red','empty');
$arGoodStatus = array('green','cyan','yellow');

///////////////////////////////////////
$showShop = array();

$cooShop = $APPLICATION->get_cookie("TURBO_SET_".htmlspecialcharsEx($_REQUEST["id"]));
if($cooShop) 
	$showShop = explode(";", $cooShop);
/////////////////////////////////////


while($p = $pr->GetNext())
{

	$sn = COption::GetOptionString('yenisite.turbo', 'turbo_shop_name', '');
	if($sn && $sn == $p['NAME']) continue;

$allCodes[] = $p['CODE'];
$allNames[] = $p['NAME'];

//////////////////////
	if($cooShop) {
		if(!in_array($p['CODE'], $showShop)){
			continue;
		}
	}
////////////////////

	
    $headers[] =     array(
        "id"    => $p['CODE'],
        "content"  => $p['NAME'],
        "default"  => true,		
    );
	
    $codes[] = $p['CODE'];
}





$pgr = CCatalogGroup::GetByID($set['PRICE_ID']);

$headers[] = array(
    "id"    => "ZAKUP_PRICE",
    "content"  => $pgr['NAME'],
    "default"  => true,		
);	

$headers[] = array(
    "id"    => "RENT_PRICE",
    "content"  => GetMessage('RENT'),
    "default"  => true,		
);	





while($prof = $dbResultList->GetNext())
{
    $values = array(
	    "ID"=>$prof["ID"],
	    "NAME"=>$prof["NAME"],				   
	);

    $arActions = Array();
    $arActions[] = array("SEPARATOR" => true);
    $arActions[] = array("ICON"=>"edit", "TEXT"=>GetMessage('EDIT'),  "ACTION"=>$lAdmin->ActionRedirect("/bitrix/admin/iblock_element_edit.php?ID={$prof['ID']}&type={$iblock['IBLOCK_TYPE_ID']}&lang=ru&IBLOCK_ID={$set['IBLOCK_ID']}"));    

    $vals = array();
    foreach($codes as $code){
        $val = CIBlockElement::GetProperty($set['IBLOCK_ID'], $prof['ID'], array("sort" => "asc"), Array("CODE" => $code))->Fetch();
        $values[$code] = $val['VALUE']?$val['VALUE']:"";
        if($values[$code]) $vals[] = $values[$code];
    }
    
	sort($vals); 
	
	if($set["PLACE"] > 0){
		$min = $vals[$set["PLACE"] - 1];
		if(!$min)
		{			
			for($i = $set["PLACE"]-1; $i>=0; $i--){
				$min = $vals[$i];
				if($min > 0)
					break;
			}
		}
	}
	else
		$min = $vals[0];

	
	$max = end($vals);
	//print_r("Место: ".$set["PLACE"]."<br/>");	
	//print_r($vals); echo "---".$min."<br/><br/>";
	
    //$min = min($vals);
    
	

    $db_res = CPrice::GetList(
        array(),
        array(
                "PRODUCT_ID" => $prof[ID],
                "CATALOG_GROUP_ID" => $set['PRICE_ID']
            )
    )->Fetch();
    
    $values['ZAKUP_PRICE'] = $db_res['PRICE'];
    $values['RENT_PRICE'] = floatval($values['ZAKUP_PRICE'] + $values['ZAKUP_PRICE'] * $set["RENT"]/100);
    
	$values['RENT_PRICE'] =  round($values['RENT_PRICE']);
    
	// SET STATUS
	if(count($vals)<=0 || $max<=0)
		$idkey = "empty";  
    elseif($max <= $values['RENT_PRICE'])
        $idkey = "red";        
    elseif($values['RENT_PRICE'] >= $min)
        $idkey = "yellow";
	elseif($values['RENT_PRICE'] < $min && $values['RENT_PRICE'] > $min-$min*$set['DISCOUNT']/100)
		$idkey = "cyan";
	else
	{
		// consider DISCOUNT
		$values['RENT_PRICE'] = round(($min-$min*$set['DISCOUNT']/100));
		$idkey = "green";
	}
	
	
    if($_REQUEST["apply_report"] && !in_array( $idkey , $arBadStatus)){
        $ids = explode("/", $_REQUEST['ids']);
        $prcs = explode("/", $_REQUEST['prcs']);
        $els = array();
        foreach($ids as $k=>$v){
            $els[$v] = $prcs[$k];
        }
         
        //print_r($els);
        if($els[$prof['ID']] && floatval($els[$prof['ID']])>0) 
		{
			$values['RENT_PRICE'] = floatval($els[$prof['ID']]);
			 
			$PriceType = CCatalogGroup::GetList( array(), array("NAME" => "TurboYandex"))->Fetch();
			if(!$PriceType['ID']){
				$arFields = array(
					"NAME" => "TurboYandex",
					"SORT" => 1111,
					"USER_GROUP" => array(1),   
					"USER_GROUP_BUY" => array(1),  
					"ACTIVE" => "N",                                             
					"USER_LANG" => array(
						"ru" => "TurboYandex",
						"en" => "TurboYandex"
					)
				);
				$PriceType[ID] = CCatalogGroup::Add($arFields);
			}
			   
			$arFields = Array(
				"PRODUCT_ID" => $prof['ID'],
				"CATALOG_GROUP_ID" => $PriceType['ID'],
				"PRICE" => $values['RENT_PRICE'],
				"CURRENCY" => "RUB",                   
			);
			$r = CPrice::GetList(
				array(),
				array(
					"PRODUCT_ID" => $prof['ID'],
					"CATALOG_GROUP_ID" => $PriceType['ID']
				)
			);
			if ($arr = $r->Fetch())
				CPrice::Update($arr["ID"], $arFields);
			else
				CPrice::Add($arFields);
        }
	}
       
    $row =& $lAdmin->AddRow($idkey, $values, "/bitrix/admin/iblock_element_edit.php?ID={$prof[ID]}&type={$iblock[IBLOCK_TYPE_ID]}&lang=ru&IBLOCK_ID={$set[IBLOCK_ID]}", GetMessage('EDIT_ELEMENT').'('.$idkey.')');
	
	if(!in_array( $idkey , $arBadStatus))
		$row->AddViewField("RENT_PRICE", "<input onkeyup='get_str_input();' type='text' value='".$values['RENT_PRICE']."'>");
	else
		$row->AddViewField("RENT_PRICE", "<input onkeyup='get_str_input();' type='text' placeholder='".$values['RENT_PRICE']."'>");
		
		
    $row->AddActions($arActions);
    
	
}
    $lAdmin->CheckListMode();
	$lAdmin->AddHeaders($headers);
  
?>
    <h1><?=GetMessage('H1').' "'.$set[NAME].'"';?></h1>
	

	
    <div style="width: 100%; border: 1px solid #cccccc; padding: 10px 0 10px 0; margin-bottom: 20px; margin-top: 10px; text-align: center; font-size: 12px;">	
	
        <form action="/bitrix/admin/yci_turbo_set_edit.php?id=<?=$set[ID];?>&action=edit&lang=ru" method="post">    
            <?foreach($set as $k=>$v): if(substr_count($k,'~')) continue; $k = strtolower($k); ?>
                <?if($k == "rent" || $k == "discount"):?>
                    <?=GetMessage($k)?>: <input type="text" name="<?=$k?>" value="<?=$v?>" />&nbsp;&nbsp;&nbsp;&nbsp;
				<?elseif($k=="place"):?>
					<?=GetMessage(strtoupper($k))?>: <select name="place">									
						<option <?=$set["PLACE"]==1?"selected='selected'":"";?> value="1">1</option>
						<option <?=$set["PLACE"]==2?"selected='selected'":"";?> value="2">2</option>
						<option <?=$set["PLACE"]==3?"selected='selected'":"";?> value="3">3</option>				
					</select>
                 <?else:?>   
                    <? if($k == "id") continue; ?>
                    <input type="hidden" name="<?=$k?>" value="<?=$v?>" />
                <?endif;?>
            <?endforeach;?>
            <!--<input type="hidden" name="action" value="edit" />-->
            <!--<input type="hidden" name="apply" value="Y" />-->
            <input type="hidden" name="report" value="Y" />
            &nbsp;&nbsp;<input type="submit" name="apply" value="<?=GetMessage('SAVE')?>" />
        </form>
     </div>   
    
    <?if($_REQUEST[apply_report]):?>
    <h3><?=GetMessage('APPLIED')?></h3>
    <br/>
    <?endif?>


	
	
 <h1><?=GetMessage('SHOW_SHOP')?></h1>	
<div style="width: 100%; border: 1px solid #cccccc; padding: 10px 10px 0px 10px; margin-bottom: 20px; margin-top: 10px;font-size: 12px;">	
	<form>
	<input type="hidden" name="id" value="<?=htmlspecialcharsEx($_REQUEST["id"]);?>">
	
<?if($cooShop):?>
	
	<?foreach($allCodes as $k=>$cod):
	
		if(in_array($cod, $showShop)) $c = 'checked="checked"'; else $c = '';
	?>
		
		<div style="float: left; margin-right: 10px;"><input <?=$c?> type="checkbox" name="show[]" value="<?=$cod?>" /><?=$allNames[$k]?></div>
	<?endforeach;?>

<?else:?>	
	<?foreach($allCodes as $k=>$cod):?>
		<div style="float: left; margin-right: 10px;"><input checked="checked" type="checkbox" name="show[]" value="<?=$cod?>" /><?=$allNames[$k]?></div>
	<?endforeach;?>
<?endif?>
	
	
		<br style="clear: both;" /><br/><input type="submit" name="showshop" value="<?=GetMessage('SAVE')?>" />
	</form>
	<br style="clear: both; "/>
</div>

	
<?$lAdmin->DisplayList();?>

<form method="post">
<?foreach($_GET as $key => $val): if($key == "apply_report") continue;?>
<input type="hidden" name="<?=$key?>" value="<?=$val?>">
<?endforeach?>

<br/>
<?echo BeginNote();?>
<div style="width: 100%; float: left;"><div style="float: left; width: 16px; height: 16px; background: #99FFA7; border: 1px solid #000;"></div><div style="float: left;"> &nbsp;- <?=GetMessage('F1_1')?></div></div>
<div style="width: 100%; float: left;"><div style="float: left; width: 16px; height: 16px; background: #1faee9; border: 1px solid #000;"></div><div style="float: left;"> &nbsp;- <?=GetMessage('F1_4')?></div></div>
<div style="width: 100%; float: left;"><div style="float: left; width: 16px; height: 16px; background: #FFEB6B; border: 1px solid #000;"></div><div style="float: left;"> &nbsp;- <?=GetMessage('F1_2')?></div></div>
<div style="width: 100%; float: left;"><div style="float: left; width: 16px; height: 16px; background: #FF6B6B; border: 1px solid #000;"></div><div style="float: left;"> &nbsp;- <?=GetMessage('F1_3')?></div></div>
<div style="width: 100%; float: left;"><div style="float: left; width: 16px; height: 16px; background: #FFFFFF; border: 1px solid #000;"></div><div style="float: left;"> &nbsp;- <?=GetMessage('F1_5')?></div></div>

<?echo EndNote();?>

<input id="ids" type="hidden" name="ids" value=""/>
<input id="prcs" type="hidden" name="prcs" value=""/>
<input type="submit" name="apply_report"  value="<?=GetMessage('APPLY');?>"/>
</form>

<? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>