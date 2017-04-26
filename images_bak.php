<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$realUrl = '/upload/uimages/';
$images = $_SERVER['DOCUMENT_ROOT'].$realUrl;
	
if ($handle = opendir($images)) {
	
	
	
    while (false !== ($file = readdir($handle))) {
        
		$z++;
		
	
		
        if(strlen($file)>5){
            
            $arSelect = Array("ID", "NAME","PREVIEW_PICTURE", "SECTION_CODE", "SECTION_ID", "DATE_ACTIVE_FROM",'DETAIL_PAGE_URL','PROPERTY_*');
            $arFilter = Array('EXTERNAL_ID'=>$file, "IBLOCK_ID"=>58, "ACTIVE_DATE" => "Y", "ACTIVE" => "Y");
            
            $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize" => 3), $arSelect);
            while($ob = $res->GetNextElement())
            {
             $element = $ob->GetFields();
             $arProps = $ob->GetProperties();
            }
			
		
		
			
			
            if($element&&$element['EXTERNAL_ID']==$file){
		
			
				
				
				$detail_picture='';
				
				if(file_exists($images.$file.'/main-picture.jpg')){

	                $handleFolder = opendir($images.$file.'/');
                    
                    $currentImages = array();
                        
                    while (false !== ($entry = readdir($handleFolder))){
                      
                        if ($entry != "." && $entry != ".." && $entry!='main-picture.jpg') {
                          
                            $currentIMages[] = $entry;
                          
                        }
                      
                    }  
                    
					$detail_picture = $images.$file.'/main-picture.jpg';
                        
                    $detail_picture =  CFile::MakeFileArray($detail_picture);

                    $el = new CIBlockElement;

                   
                    $PROP['IMAGES']=array();
                    
                    foreach($currentIMages as $cImage){
                        
                        $PROP['IMAGES'][] = CFile::MakeFileArray($images.$file.'/'.$cImage);
                        
                    }
                    
					
					
                    $arLoadProductArray = Array(
                        "MODIFIED_BY"    => 1, // элемент изменен текущим пользователем
                        "IBLOCK_SECTION" =>  $element['IBLOCK_SECTION_ID'],
                      //  "PROPERTY_VALUES"=> $PROP,
                        "DETAIL_PICTURE" => $detail_picture,
                        "PREVIEW_PICTURE" => $detail_picture
                    );
					
					$resultMy = CIBlockElement::SetPropertyValues($element['ID'], $element['IBLOCK_ID'],  $PROP['IMAGES'], 'MORE_PHOTO');

                    $res = $el->Update($element['ID'], $arLoadProductArray);
                       
                    if($res){
                        
                        file_put_contents('log.txt',$file." $z  {$element['IBLOCK_SECTION_ID']}  {$element['NAME']}   \n\r", FILE_APPEND);
                        
                    }
					
				
				
				}
				

            }

        }

    }

    closedir($handle);
}



?>