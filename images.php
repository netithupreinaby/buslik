<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$realUrl = '/upload/uimages/';
$images = $_SERVER['DOCUMENT_ROOT'].$realUrl;
$notFaund = array();
$continueArray = array();
$badSecetArray = array();
$insertArray = array();
$continueCount = 1;
$insertCount = 1;


if ($handle = opendir($images)) {
	
	
	
    while (false !== ($file = readdir($handle))) {
        
		$z++;
		
	
		
        if(strlen($file)>5){


            /*

			$file = 'e7ff1b88-7a59-11e6-96f9-005056977cd8';

            if($u>1){die();}
            */

            $u++;

			$isPNG = file_exists($images.$file.'/main-picture.png');
			$isJPG = file_exists($images.$file.'/main-picture.jpg');

    		//if($isPNG || $isJPG){

			
            $arSelect = Array("ID", "NAME","PREVIEW_PICTURE","DETAIL_PICTURE", "SECTION_CODE", "SECTION_ID", "DATE_ACTIVE_FROM",'DETAIL_PAGE_URL','PROPERTY_*');
            $arFilter = Array('EXTERNAL_ID'=>$file, "IBLOCK_ID"=>58, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
            
            $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize" => 3), $arSelect);
            while($ob = $res->GetNextElement())
            {
             $element = $ob->GetFields();
             $arProps = $ob->GetProperties();
            }


            $Continue = true;
            $clearMorePhoto = false;
            $UpdatePrevAndDetPic = true;

            if($_GET['clearMorePhoto']=='1'){
                $morePhoto = array();
                $arFile = array();
                foreach($arProps['MORE_PHOTO']['VALUE'] as $photo){
                    $arFile = CFile::GetFileArray($photo);
                    if(in_array($arFile['ORIGINAL_NAME'],$morePhoto)) {
                        $Continue = false;
                        $clearMorePhoto = true;
                        break;
                    }
                    $morePhoto[] = $arFile['ORIGINAL_NAME'];
                    unset($arFile);
                }
                unset($morePhoto);
            }


            if($_GET['update_unknown']=='1'){

                file_put_contents('log_update_unknown_loding.txt', $file . " $z   \n\r", FILE_APPEND);


                    $arFilePrev = CFile::GetFileArray($element["PREVIEW_PICTURE"]);
                    $arFileDet = CFile::GetFileArray($element["DETAIL_PICTURE"]);

                if($_GET['clearMorePhoto']=='1'){
                    if($arFilePrev['SRC'] && $arFileDet['SRC'] && file_exists($_SERVER['DOCUMENT_ROOT'] . $arFilePrev['SRC']) != false && file_exists($_SERVER['DOCUMENT_ROOT'] . $arFileDet['SRC']) != false){
                        $UpdatePrevAndDetPic = false;
                    }
                }



                if($Continue){
                    if($arFilePrev['SRC'] && $arFileDet['SRC']){
                        if (file_exists($_SERVER['DOCUMENT_ROOT'] . $arFilePrev['SRC']) != false) {
                            if (file_exists($_SERVER['DOCUMENT_ROOT'] . $arFileDet['SRC']) != false) {
                                $continue = "continue";
                                $continueCount++;
                                //$continueArray[] = $file;
                                file_put_contents('log_update_unknown.txt', $file . " $z $continue {$element['IBLOCK_SECTION_ID']}  {$element['NAME']}  \n\r", FILE_APPEND);
                                continue;
                            }
                        }
                    }
                }
                if($element&&$element['EXTERNAL_ID']!=$file){
                    $badSecetArray[] = array($element['EXTERNAL_ID'],$file);
                }

                $clearMorePhoto = true;

            }





            if($element&&$element['EXTERNAL_ID']==$file){


                $overwriteWithEmptyFolder = false;
                if($_GET['overwriteWithEmptyFolder']=='1'){

                    $overwriteWithEmptyFolder = true;
                }
				
				$detail_picture='';
				
				if(file_exists($images.$file.'/main-picture.jpg')  || file_exists($images.$file.'/main-picture.png') || file_exists($images.$file.'/main-picture.jpeg') || $overwriteWithEmptyFolder){

	                $handleFolder = opendir($images.$file.'/');
                    
                    $currentImages = array();
                        
                    while (false !== ($entry = readdir($handleFolder))){
                      
                        if ($entry != "." && $entry != ".." && $entry!='main-picture.jpg' &&  $entry!='main-picture.png'&&  $entry!='main-picture.jpeg') {
                          
                            $currentIMages[] = $entry;
                          
                        }
                      
                    }  
                    
					if(file_exists($images.$file.'/main-picture.jpg')){
                        
					    $detail_picture = $images.$file.'/main-picture.jpg';
                        
                    }elseif(file_exists($images.$file.'/main-picture.jpeg')){
                        
                        $detail_picture = $images.$file.'/main-picture.jpeg';
                        
                    }elseif(file_exists($images.$file.'/main-picture.png')){

                        $detail_picture = $images.$file.'/main-picture.png';

                    }else{
                        $detail_picture='';
                    }


                    if($overwriteWithEmptyFolder) {
                        $detail_picture = array("del"=>"Y");
                    }else{
                        $detail_picture = CFile::MakeFileArray($detail_picture);
                    }

                    $el = new CIBlockElement;

                   
                    $PROP['IMAGES']=array();
                    
                    foreach($currentIMages as $cImage){
                        
                        $PROP['IMAGES'][] = CFile::MakeFileArray($images.$file.'/'.$cImage);
                        
                    }


                    if($overwriteWithEmptyFolder){
                        $resultMy = CIBlockElement::SetPropertyValuesEx($element['ID'], $element['IBLOCK_ID'], array("843" => Array ("VALUE" => array("del" => "Y"))));
                    }else{

                        if($clearMorePhoto){
                            $resultMy = CIBlockElement::SetPropertyValuesEx($element['ID'], $element['IBLOCK_ID'], array("843" => Array ("VALUE" => array("del" => "Y"))));
                        }

					    $resultMy = CIBlockElement::SetPropertyValues($element['ID'], $element['IBLOCK_ID'],  $PROP['IMAGES'], 'MORE_PHOTO');
                    }


                    $arLoadProductArray = Array(
                        "MODIFIED_BY"    => 1, // элемент изменен текущим пользователем
                        "IBLOCK_SECTION" =>  $element['IBLOCK_SECTION_ID'],
                        //  "PROPERTY_VALUES"=> $PROP,
                        "DETAIL_PICTURE" => $detail_picture,
                        "PREVIEW_PICTURE" => $detail_picture
                    );

                    if($UpdatePrevAndDetPic){
                        $res = $el->Update($element['ID'], $arLoadProductArray);
                    }



                       
                    if($res || $resultMy){

                        if($_GET['update_unknown']=='1') {
                            file_put_contents('log_update_unknown.txt', $file . " $z insert {$element['IBLOCK_SECTION_ID']} {$element['ID']} {$element['NAME']}  \n\r", FILE_APPEND);
                        }else{
                            file_put_contents('log.txt', $file . " $z  {$element['IBLOCK_SECTION_ID']} {$element['ID']} {$element['NAME']}  \n\r", FILE_APPEND);
                        }

                        //$insertArray[] = $file;
                        $insertCount++;

                    }else{
                        $badSecetArray[] = array($element['EXTERNAL_ID'],$file);
                    }
					
				
				
				}else{
                    if($_GET['update_unknown']=='1') {
                        $notFaund[] = $file;
                        file_put_contents('log_not_found_unknown.txt', $file . " $z folder clear \n\r", FILE_APPEND);
                    }
                }

				

            }else{

                if($_GET['update_unknown']=='1') {
                    $notFaund[] = $file;
                    file_put_contents('log_not_found_unknown.txt', $file . " $z \n\r", FILE_APPEND);
                }else{
                    file_put_contents('log_not_found.txt', $file . " $z \n\r", FILE_APPEND);
                }

			}

            unset($element);
            unset($arProps);

		
        }

    }


    if($_GET['update_unknown']=='1') {
        file_put_contents('log_update_unknown.txt', $u. " notFaund=". count($notFaund) ."  continue=".count($continueCount)." insert=".count($insertCount)." \n\r", FILE_APPEND);
    }

    //printr($insertArray);
    //printr($badSecetArray);

    closedir($handle);
}



?>