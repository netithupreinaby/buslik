<?
$DB->Query("SET NAMES 'utf8'");
$DB->Query('SET collation_connection = "utf8_unicode_ci"');

if(class_exists(BuslikTimeSlotHelper))
{
	$BuslikTimeSlotHelper=new BuslikTimeSlotHelper();
	$r=$BuslikTimeSlotHelper->getAvailableSlotsToday(2);
	print_r($r);
}

if (!function_exists('printr')) {
        function printr($array) {
                GLOBAL $USER;
                if (!$USER->IsAdmin()) return false;
                $args = func_get_args();
                if (count($args) > 1) {
                        foreach ($args as $values)
                                printr($values);
                } else {
                        if (is_array($array) || is_object($array)) {
                                echo "<pre>";
                                print_r($array);
                                echo "</pre>";
                        } else {
                                echo $array;
                        }
                }
                return true;
        }
}

if (!function_exists('declinationTm')) {
function declinationTm($value=1, $status= array('','а','ов'))
    {

        $values =array(2,0,1,1,1,2);
        return $status[($value%100>4 && $value%100<20)? 2 : $values[($value%10<5)?$value%10:5]];

    }
}





function getSaleFilter($xmlCode){
	global $DB;

	$arDiscountElementID = array();

	$arrFilter = array(
		"ACTIVE" => "Y",
		"!>ACTIVE_FROM" => $DB->FormatDate(date("Y-m-d H:i:s"),
			"YYYY-MM-DD HH:MI:SS",
			CSite::GetDateFormat("FULL")),
		"!<ACTIVE_TO" => $DB->FormatDate(date("Y-m-d H:i:s"),
			"YYYY-MM-DD HH:MI:SS",
			CSite::GetDateFormat("FULL")),
	);

	if($xmlCode){
		$arrFilter["XML_ID"] = $xmlCode;
	}

	$dbProductDiscounts = CCatalogDiscount::GetList(
		array("SORT" => "ASC"),
		$arrFilter,
		false,
		false,
		array(
			"ID", "SITE_ID", "ACTIVE", "ACTIVE_FROM", "ACTIVE_TO",
			"RENEWAL", "NAME", "SORT", "MAX_DISCOUNT", "VALUE_TYPE",
			"VALUE", "CURRENCY", "PRODUCT_ID","UNPACK","CONDITIONS"
		)
	);
	while ($arProductDiscounts = $dbProductDiscounts->Fetch())
	{
		$arSelect = Array("ID", "NAME", "DATE_ACTIVE_FROM");
		$arFilter = eval($arProductDiscounts['UNPACK']);
		$res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
		while($ob = $res->GetNextElement())
		{
			$arFields = $ob->GetFields();
			$arDiscountElementID[] = $arFields["ID"];
		}
/*
		$arFil = ;
		if($res = CCatalogDiscount::GetDiscountProductsList(array(), $arFil, false, false, array())){
			while($ob = $res->GetNext()){
				if(!in_array($ob["PRODUCT_ID"],$arDiscountElementID))
					$arDiscountElementID[] = $ob["PRODUCT_ID"];
			}}*/
	}

	return $arDiscountElementID;

}



class xmlSaleReader{

	function xml2Array(SimpleXMLElement $parent)
	{
		$array = array();

		foreach ($parent as $name => $element) {
			($node = & $array[$name])
				&& (1 === count($node) ? $node = array($node) : 1)
				&& $node = & $node[];

			$node = $element->count() ? xmlSaleReader::xml2Array($element) : trim($element);
		}

		return $array;
	}

	function xmlFile2Array($params){

		$xml = simplexml_load_file($params);

		//$xml   = simplexml_load_string($buffer);
		$array = json_decode(json_encode((array) $xml), 1);
		$array = array($xml->getName() => $array);
		$array = array_shift($array);
		$array = $array['action'];
		/*


		$xml   = simplexml_load_string($buffer);
		$array = xmlSaleReader::xml2Array($xml);
		$array = array($xml->getName() => $array);
		*/
		return $array;
	}






}



//Класс для импорта Акций 
class ImportSale{
	
	
	function dateformat($date,$time=true){
	$d = explode('.',$date);
	$date = '20'.$d[2].'-'.$d[1].'-'.$d[0].' 00:00:00';
	return $date;
	}
	
	function dateformatKey($date){
	$d = explode('.',$date);
	$d[2] = '20'.$d[2];
	$date = implode('.',$d);
	return $date;
	}
	
	
	function importFileSale($href = '/Sale_new.csv'){
		//echo($href);
		$href = $_SERVER["DOCUMENT_ROOT"].$href;
		$shag = 1000;
		
		$masArray = array(    
			0 => 'Magazin',
            1 => 'GUIDMagazina',
            2 => 'SkidkaNacenka',
            3 => 'Nomenklatura',
            4 => 'GUIDNomenklatury',
            5 => 'HarakteristikaNomenklatury',
            6 => 'GUIDHarakteristikiNomenklatury',
            7 => 'ZnachenieSkidkiNacenki',
            8 => 'DataOkonchanija',
            9 => 'DataNachala');
		$row = 0;
		
		if (($handle = fopen($href, "r")) !== FALSE) {
			
			
			while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
				$num = count($data);
				for ($c=0; $c < $num; $c++) {
					$list[$row][$masArray[$c]] =  $data[$c];
				}
				$row++;
			}
			fclose($handle);
		}
		
		
		//debugbreak();
		
		foreach($list as $k=>$v){
			if($k!=0){
			$listInsert[$shagl][$k] = "(NULL , '".$v['Magazin']."', '".$v['GUIDMagazina']."', '".$v['SkidkaNacenka']."', '".str_replace("'","\'",$v['Nomenklatura'])."', '".$v['GUIDNomenklatury']."', '".$v['HarakteristikaNomenklatury']."', '".$v['GUIDHarakteristikiNomenklatury']."', '".$v['ZnachenieSkidkiNacenki']."', '".ImportSale::dateformat($v['DataOkonchanija'])."', '".ImportSale::dateformat($v['DataNachala'])."')";
			
			if( $v['GUIDNomenklatury']=='e7444133-81fc-11e5-96a8-005056977ec5'){
			//debugbreak();
			}
			//$listInsert[$shagl][$k] = "(NULL , '".$v['Magazin']."', '".$v['GUIDMagazina']."', '".$v['SkidkaNacenka']."', '".$v['Nomenklatura']."', '".$v['GUIDNomenklatury']."', '".$v['HarakteristikaNomenklatury']."', '".$v['GUIDHarakteristikiNomenklatury']."', '".$v['ZnachenieSkidkiNacenki']."', '".ImportSale::dateformat($v['DataOkonchanija'])."', '".ImportSale::dateformat($v['DataNachala'])."')";
			
		
		
			if($v['SkidkaNacenka']=='Процент'){
					
		
				//$listaction[$v['GUIDMagazina']][$v['ZnachenieSkidkiNacenki']][$v['DataNachala'].'-'.$v['DataOkonchanija']][] =array($v['GUIDNomenklatury'],$v['GUIDMagazina']);

				if($v['ZnachenieSkidkiNacenki']){
				$listaction['all'][$v['ZnachenieSkidkiNacenki']][ImportSale::dateformatKey($v['DataNachala']).'-'.ImportSale::dateformatKey($v['DataOkonchanija'])][] =array($v['GUIDNomenklatury'],$v['GUIDHarakteristikiNomenklatury']);
				}
				$listCode[] = $v['GUIDNomenklatury'];
				$listCodead[$v['GUIDNomenklatury'].'-'.$v['GUIDHarakteristikiNomenklatury']] = $v['GUIDNomenklatury'];
			}elseif($v['SkidkaNacenka']=='Новая цена'){
				
				if($v['ZnachenieSkidkiNacenki']){
				$listCodes[] = $v['GUIDNomenklatury'];
					$listActionNewPrice[] = $v;
					$listCodeNewPrice[] = $v['GUIDNomenklatury'];
				}
			}
			}
			if($k%$shag==0){
			$shagl = $shagl+$shag;
			}
		}
		
		
		if(2==3){
			global $DB;
			
			$strSql  = " TRUNCATE TABLE `buslik`.`a_import_action`";
			$res = $DB->Query($strSql, false, $err_mess.__LINE__);
			
			foreach($listInsert as $k=>$insert){
				$strSql  = "INSERT INTO `buslik`.`a_import_action` ( `id` , `Magazin` , `GUIDMagazina` , `SkidkaNacenka` ,  `Nomenklatura` , `GUIDNomenklatury` , `HarakteristikaNomenklatury` ,`GUIDHarakteristikiNomenklatury` ,`ZnachenieSkidkiNacenki` ,`DataOkonchanija` ,`DataNachala`)
				VALUES ".implode(' , ',$insert)."";
				$res = $DB->Query($strSql, false, $err_mess.__LINE__);
				/*echo('<br/>');
				var_dump($res);
				echo('<br/>');*/
			}
		}
		
		
		
		//debugbreak();
		//ImportSale::getIDbyCode($listCode);
		//ImportSale::getIDbyCode($listCodes);
		ImportSale::addImportSaleBitrix($listaction,$listCode);
		ImportSale::addImportSaleBitrix($listActionNewPrice,$listCode,true);
	}
	
	function addImportSaleBitrix($Sales, $CodeSales, $importNewPriceValue=false){
		$arFieldsM = Array("SITE_ID" => "s1",
					"ACTIVE" => "Y",
					"XML_ID" => "",
					"ACTIVE_FROM" => "",
					"ACTIVE_TO" => "",
					"RENEWAL" => "N",
					"NAME" => "export ",
					"SORT" => "100",
					"MAX_DISCOUNT" => "0",
					"VALUE_TYPE" => "P",
					"VALUE" => "0",
					"CURRENCY" => "BYN",
					"NOTES" => "",
					"PRIORITY" => "1",
					"LAST_DISCOUNT" => "Y",
					"GROUP_IDS" => Array
						(
						),
					"CATALOG_GROUP_IDS" => Array
						(
						),
					"CONDITIONS" => Array
						(
							"CLASS_ID" => "CondGroup",
							"DATA" => Array
								(
									"All" => "OR",
									"True" => "True",
								),
							"CHILDREN" => Array(),
						)
					);
	
		$price = array('534cd330-563f-11e4-a5b8-005056b60002'=>2,'ead639da-c40d-11e5-94f2-005056b60002'=>3);
		
		
		
		
		if($importNewPriceValue){
		
		$p = 0;
	
		
		
			$t_s = microtime(true);
			$arr = array();
				
			foreach($Sales as $value){
								$arFields[$p] = $arFieldsM; 
								$arFields[$p]['VALUE_TYPE'] = 'S'; 
								$arFields[$p]['VALUE'] = $value['ZnachenieSkidkiNacenki'];
								$arFields[$p]['NAME'] = $arFields[$p]['NAME'].'new price';
						
								//$arFields[$p]["ACTIVE_FROM"]= ImportSale::dateformatKey($value['DataNachala']);
								//$arFields[$p]["ACTIVE_TO"]= ImportSale::dateformatKey($value['DataOkonchanija']);
								$arFields[$p]["PRIORITY"]= "105";
								
			
								$arFields[$p]["CONDITIONS"]["CLASS_ID"] = "CondGroup";
								$arFields[$p]["CONDITIONS"]["DATA"] = array("All"=>"AND","True"=>"True");
								$arFields[$p]["CONDITIONS"]["CHILDREN"] = Array("0" => Array
																	(
																		"CLASS_ID" => "CondIBXmlID",
																		"DATA" => Array
																			(
																				"logic" => "Equal",
																				"value" => $value['GUIDNomenklatury'],
																			),

																	));
								/*if(isset($value[1])){
								$arFields[$p]["CONDITIONS"]["CHILDREN"][1] = Array(
																		"CLASS_ID" => "CondIBProp:57:997",
																		"DATA" => Array
																			(
																				"logic" => "Equal",
																				"value" => $value[0].'#'.$value[1]
																			)
																	);
								}*/
									
			$p++;
			}
			
		
		
		if (CModule::IncludeModule("catalog")){		
			//debugbreak();
			foreach($arFields as $k=>$Sale){		
				//if(count($Sale['CONDITIONS']['CHILDREN'])<1000){
				if(true){
					if(true){
						$time_start = microtime(true);
						
						//debugbreak();
						
						$ID = CCatalogDiscount::Add($Sale);
						$res = $ID>0;
						if (!$res) { 
						echo('</br>');
						echo('error k '.$k.'-% '.$Sale['value'].' -'.$Sale['ACTIVE_FROM'].'-to - '.$Sale['ACTIVE_TO'].' count ='.count($Sale['CONDITIONS']['CHILDREN']).'  name =  '.$Sale['name'].'  time add'.$time);			
						echo('</br>');
						
						$j++;
						}
						
						
						$time_end = microtime(true);
						$time = $time_end - $time_start;
						
						echo(' k '.$k.'-- count ='.count($Sale['CONDITIONS']['CHILDREN']).'  name =  '.$Sale['name'].'  time add'.$time);
						$arr[] = ' k '.$k.'-- count ='.count($Sale['CONDITIONS']['CHILDREN']).'  name =  '.$Sale['name'].'  time add'.$time;
						echo('</br>');
						echo('</br>');
						echo('</br>');
					}
					$i++;
				}
				
			}
			
			$t_e = microtime(true);
			$t = $t_e - $t_s;
			echo('all time add'.$t);
		
			
		
		}
		
			
		//

			
		
		}else{
		

		
			$i = 0;
			$j = 0;
			$x = 0;
			$arFields = array();
			//debugbreak();
			foreach($Sales as $shop=>$sizeSale){
					
					/*
					$arFields[$i.'-'.$j.'-'.$x] = $arFieldsM; 
					$arFields[$i.'-'.$j.'-'.$x]['NAME'] = $arFieldsM['NAME'].' type_rate ';
					if($shop=='all'){
						$arFields[$i.'-'.$j.'-'.$x]['CATALOG_GROUP_IDS'] = array();
					}elseif(2==3){
						$arFields[$i.'-'.$j.'-'.$x]['CATALOG_GROUP_IDS'] = array(0=>$price[$kshop]);
					}
					*/
					
					foreach($sizeSale as $size=>$dateList){
							/*
							$arFields[$i.'-'.$j.'-'.$x]['VALUE'] = $size;
							$arFields[$i.'-'.$j.'-'.$x]['NAME'] = $arFields[$i.'-'.$j.'-'.$x]['NAME'].$size;
							*/
								
							foreach($dateList as $date=>$child){
								if($i.'-'.$j.'-'.$x=='0-0-0'){
								//debugbreak();
								}
								$arFields[$i.'-'.$j.'-'.$x] = $arFieldsM; 
								$arFields[$i.'-'.$j.'-'.$x]['NAME'] = $arFieldsM['NAME'].' type_rate ';
								if($shop=='all'){
									$arFields[$i.'-'.$j.'-'.$x]['CATALOG_GROUP_IDS'] = array();
								}elseif(2==3){
									$arFields[$i.'-'.$j.'-'.$x]['CATALOG_GROUP_IDS'] = array(0=>$price[$kshop]);
								}
								//
								$arFields[$i.'-'.$j.'-'.$x]['VALUE'] = $size;
								$arFields[$i.'-'.$j.'-'.$x]['NAME'] = $arFields[$i.'-'.$j.'-'.$x]['NAME'].$size;
								//
								$list = array();
								
								$active = explode("-",$date);
								$arFields[$i.'-'.$j.'-'.$x]["ACTIVE_FROM"]= $active[0];
								$arFields[$i.'-'.$j.'-'.$x]["ACTIVE_TO"]= $active[1];
								$arFields[$i.'-'.$j.'-'.$x]["PRIORITY"]= 80;
							/**/
								$zz = 0;
								foreach($child as $item=>$value){
									
										if(!empty($value[1])){
									
											$list[$zz] = array("CLASS_ID" => "CondGroup",
													"DATA" => Array
														(
															"All" => "AND",
															"True" => "True"
														),
													"CHILDREN" => Array
														(
															"0" => Array
																(
																	"CLASS_ID" => "CondIBProp:57:997",
																	"DATA" => Array
																		(
																			"logic" => "Equal",
																			"value" => $value[0].'#'.$value[1]
																		)

																),
															
															
														)
											);
											
											
										}else{
										
											$list[$zz] = array("CLASS_ID" => "CondGroup",
													"DATA" => Array
														(
															"All" => "AND",
															"True" => "True"
														),
													"CHILDREN" => Array
														(
															"0" => Array
																(
																	"CLASS_ID" => "CondIBXmlID",
																	"DATA" => Array
																		(
																			"logic" => "Equal",
																			"value" => $value[0]
																		)

																),
															
															
														)
											);
										}						
								$zz++;		
								}
								
							//debugbreak();
							$arFields[$i.'-'.$j.'-'.$x]['NAME'] = $arFields[$i.'-'.$j.'-'.$x]['NAME'].' countItem='.count($list);
							$arFields[$i.'-'.$j.'-'.$x]['CONDITIONS']['CHILDREN'] = $list;
							
							
							$x++;
							}
					$j++;
					}
					
			$i++;		
			}


			
			//debugbreak();

			$chunk_size = 100; 
		
			$i = 0;
			$j = 0;
			if (CModule::IncludeModule("catalog")){
			$t_s = microtime(true);
			$arr = array();
			
			foreach($arFields as $k=>$Sale){		
				if(count($Sale['CONDITIONS']['CHILDREN'])>$chunk_size){
				//debugbreak();
						$CHILDREN = array_chunk($Sale['CONDITIONS']['CHILDREN'],$chunk_size,true);
						
						$o = 1;
						$count = count($CHILDREN);
						foreach($CHILDREN as $child){							
							$Insert = $Sale;
							$Insert["NAME"] = $Insert["NAME"]." chunk ".$o." of ".$count;
							$Insert['CONDITIONS']['CHILDREN'] = $child;
							
							$ID = CCatalogDiscount::Add($Insert);
							$res = $ID>0;
							if (!$res) { 
							echo('</br>');
							echo('error k '.$k.'-'.$o.'-'.$Sale['ACTIVE_FROM'].'-to - '.$Sale['ACTIVE_TO'].' count ='.count($Sale['CONDITIONS']['CHILDREN']).'  name =  '.$Sale['name'].'  time add'.$time);			
							echo('</br>');
							}
							$o++;
							
							$arr[] = ' k '.$k.'-- chunk count ='.count($Insert['CONDITIONS']['CHILDREN']).'  name =  '.$Insert['name'].'  time add'.$time;
						}
						
				}else{
					if(false){
						$time_start = microtime(true);
						
						//debugbreak();
						
						$ID = CCatalogDiscount::Add($Sale);
						$res = $ID>0;
						if (!$res) { 
						echo('</br>');
						echo('error k '.$k.'--'.$Sale['ACTIVE_FROM'].'-to - '.$Sale['ACTIVE_TO'].' count ='.count($Sale['CONDITIONS']['CHILDREN']).'  name =  '.$Sale['name'].'  time add'.$time);			
						echo('</br>');
						
						$j++;
						}
						
						
						$time_end = microtime(true);
						$time = $time_end - $time_start;
						
						echo(' k '.$k.'-- count ='.count($Sale['CONDITIONS']['CHILDREN']).'  name =  '.$Sale['name'].'  time add'.$time);
						$arr[] = ' k '.$k.'-- count ='.count($Sale['CONDITIONS']['CHILDREN']).'  name =  '.$Sale['name'].'  time add'.$time;
						echo('</br>');
						echo('</br>');
						echo('</br>');
					}
					$i++;
				}
				
			}
			
			$t_e = microtime(true);
				$t = $t_e - $t_s;
				echo('all time add'.$t);
				echo('</br>');
				echo('</br>');
				echo('</br>');
				printr($arr);
				echo('</br>');
				echo(count($arFields).'--- insert--'.$i.'---error'.$j);
				echo('</br>');
		
			}
		
		}
	}
	
	
	function getIDbyCode($listCode,$idblock){	
		$idblock = $idblock ? $idblock : 58;
		$elements =array();
		//$listCode =  array('e2168e32-f0fb-11e5-b84b-005056977cd8','c87a3154-7988-11e5-91ba-005056977cd8','c87a314e-7988-11e5-91ba-005056977cd8','c87a3144-7988-11e5-91ba-005056977cd8','75e15ef2-bbf6-11e4-9011-005056977cd8','75e15eef-bbf6-11e4-9011-005056977cd8','75e15eec-bbf6-11e4-9011-005056977cd8','75e15ee9-bbf6-11e4-9011-005056977cd8','77d01332-23f9-11e6-b3c3-005056977ec5','9684ac2b-233b-11e6-b3c3-005056977ec5','d09cedfc-9104-11e0-b6ca-0015171f7e19','398ddcd4-ce32-11e3-a342-0050569740d4');	
		//$listCode =  array('d09cedfc-9104-11e0-b6ca-0015171f7e19','398ddcd4-ce32-11e3-a342-0050569740d4','d09cedfc-9104-11e0-b6ca-0015171f7e19');
		
		//$arFilter = array('IBLOCK_ID' => IntVal($idblock),'EXTERNAL_ID'=>$listCode,'ACTIVE'=>'Y'); 
		//$arFilter = array('IBLOCK_ID' => IntVal($idblock),'ID'=>'100280','ACTIVE'=>'Y'); 
		//$arFilter = array('IBLOCK_ID' => IntVal($idblock),'EXTERNAL_ID'=>$listCode,'ACTIVE'=>'Y'); 
		
		//$arFilter = array('IBLOCK_ID' => IntVal($idblock),'EXTERNAL_ID'=>array('d09cedfc-9104-11e0-b6ca-0015171f7e19','398ddcd4-ce32-11e3-a342-0050569740d4'),'ACTIVE'=>'Y'); 
		
			echo(count($listCode));
			echo('</br>');
		
		$list = array_chunk($listCode, 1);
		
		if (CModule::IncludeModule('iblock')){

			$i = 0;		
			//debugbreak();
			foreach($list as $value){
			$arFilter = array('IBLOCK_ID' => IntVal($idblock),'XML_ID'=>$value,'ACTIVE'=>'Y'); 
			$rsSect = CIBlockElement::GetList(array('IBLOCK_ID','NAME','SECTION_CODE','SECTION_ID','ID','EXTERNAL_ID'),$arFilter, false, Array(), array());
				while($elements = $rsSect->GetNext()){
					$item[$i] = $elements['ID'];
					$itemm[$i]['NAME'] = $elements['NAME'];
					$itemm[$i]['ID'] = $elements['ID'];
					$itemm[$i]['XML_ID'] = $elements['XML_ID'];
					$itemm[$i]['DETAIL_PAGE_URL'] = $elements['DETAIL_PAGE_URL'];
					$itemm[$i]['IBLOCK_SECTION_ID'] = $elements['IBLOCK_SECTION_ID'];
					//$itemm[$i] = $elements;
					$i++;
				};
			}
			
			echo(count($itemm));
			echo('</br>');
			printr($itemm);
			echo('</br>');
			//echo(count($item));
		}
		
		/*
		if (CModule::IncludeModule('iblock')){
		
			$i = 0;
			
			$arFilter = array('IBLOCK_ID' => IntVal($idblock),'XML_ID'=>$listCode,'ACTIVE'=>'Y'); 
			$rsSect = CIBlockElement::GetList(array('IBLOCK_ID','NAME','SECTION_ID','ID','EXTERNAL_ID'),$arFilter, false, Array(), array());
			
			//debugbreak();
			while($elements = $rsSect->GetNext()){
					//$item[$elements['ID']] = array('ID'=>$elements['ID'], 'NAME'=>$elements['NAME'],'EXTERNAL_ID'=>$elements['EXTERNAL_ID']);
					if(in_array($elements['EXTERNAL_ID'], $listCode)){
					echo('</br>');
					echo($elements['EXTERNAL_ID'].'   '.$elements['ID']);
					echo('</br>');
					}
					//$item[$i] = array('ID'=>$elements['ID'], 'NAME'=>$elements['NAME'],'EXTERNAL_ID'=>$elements['EXTERNAL_ID']);				
			//$item[$elements['EXTERNAL_ID']] = $elements['ID'];
			$item[$i] = $elements['ID'];
			$itemm[$i] = $elements;
			$i++;
			};
			
			printr($itemm);
			//echo(count($item));
		}
		
		*/
		
	}
	
	
	function importBasketRule($sale){
	$arFields = Array(
			"LID" => "s1",
			"NAME" => " import",
			"ACTIVE_FROM" => "",
			"ACTIVE_TO" => "",
			"ACTIVE" => "Y",
			"SORT" => "100",
			"PRIORITY" => "1",
			"LAST_DISCOUNT" => "Y",
			"XML_ID" => "",
			"CONDITIONS" => Array
				(
					"CLASS_ID" => "CondGroup",
					"DATA" => Array
						(
							"All" => "AND",
							"True" => "True",
						),

					"CHILDREN" => Array
						(
							"0" => Array
								(
									"CLASS_ID" => "CondBsktProductGroup",
									"DATA" => Array
										(
											"Found" => "Found",
											"All" => "AND",
										),

									"CHILDREN" => Array
										(
											"0" => Array
												(
													"CLASS_ID" => "CondIBProp:58:906",
													"DATA" => Array
														(
															"logic" => "Equal",
															//"value" => "dcd1b3a4-2d0e-11e0-9ad2-005056882284",
															"value" => "2e0ea00b-c2ff-11e4-9011-005056977cd8",
														),

												),

											"1" => Array
												(
													"CLASS_ID" => "CondBsktFldQuantity",
													"DATA" => Array
														(
															//"logic" => "Equal",
															"logic" => "EqGr",
															"value" => "2",
														),

												),

										),

								),

						),

				),

			"ACTIONS" => Array
				(
					"CLASS_ID" => "CondGroup",
					"DATA" => Array
						(
							"All" => "AND",
						),

					"CHILDREN" => Array
						(
							"0" => Array
								(
									"CLASS_ID" => "GiftCondGroup",
									"DATA" => Array
										(
											"All" => "AND",
										),

									"CHILDREN" => Array
										(
											"0" => Array
												(
													"CLASS_ID" => "GifterCondIBElement",
													"DATA" => Array
														(
															"Type" => "one",
															"Value" => Array
																(
																	"0" => "104761",
																	"1" => "104762",
																),

														),

												),
											/*"0" => Array
												(
													"CLASS_ID" => "CondIBXmlID",
													"DATA" => Array
														(
															"Type" => "one",
															"Value" => Array
																(
																	"0" => "145c6486-06c8-11e6-b3c3-005056977ec5",
																	"1" => "79d34a91-cec8-11e4-96e3-0050569740d4",
																),

														),

												),*/

										),

								),

						),

				),

			"USER_GROUPS" => Array
				(
					"0" => "1",
					"1" => "2",
					"2" => "6",
				),
		);
	//debugbreak();
	if (CModule::IncludeModule('Sale')){
			$discountID = (int)CSaleDiscount::Add($arFields);
			//debugbreak();
			if ($discountID <= 0)
			{
			echo('error');
			}
			else
			{
			echo('ok');
			}
	}
	
	
	}
	
	
	
	function getSaleById($ID,$price_type=false,$ID_SKU=false){
	
	CModule::IncludeModule("iblock"); 
	CModule::IncludeModule("catalog");
	CModule::IncludeModule("sale");   
	global $USER;
	
	$arDiscounts = CCatalogDiscount::GetDiscountByProduct($item_id, $USER->GetUserGroupArray(), "N", 2);
	
	//debugbreak();
	/*
	
	global $DB; 
	$PRODUCT_ID = $ID;
	
	if (CModule::IncludeModule("catalog")){
	
		
		$dbProductDiscounts = CCatalogDiscount::GetList(
			array("SORT" => "ASC"),
			array(
					//"+PRODUCT_ID" => $PRODUCT_ID,
					"ACTIVE" => "Y",
					"!>ACTIVE_FROM" => $DB->FormatDate(date("Y-m-d H:i:s"), 
													   "YYYY-MM-DD HH:MI:SS",
													   CSite::GetDateFormat("FULL")),
					"!<ACTIVE_TO" => $DB->FormatDate(date("Y-m-d H:i:s"), 
													 "YYYY-MM-DD HH:MI:SS", 
													 CSite::GetDateFormat("FULL")),
					"COUPON" => ""
				),
			false,
			false,
			array(
					"ID", "SITE_ID", "ACTIVE", "ACTIVE_FROM", "ACTIVE_TO", 
					"RENEWAL", "NAME", "SORT", "MAX_DISCOUNT", "VALUE_TYPE", 
			"VALUE", "CURRENCY", "PRODUCT_ID"
				)
			);
			debugbreak();
		while ($arProductDiscounts = $dbProductDiscounts->Fetch())
		{
		 
		}
		
		if($price){
		
		}else{
		return false;
		}
	}
	*/
	
	
	}
	
	
	

}
//printr($arr1,$arr2, $str, "string");

?>