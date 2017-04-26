<?


global $VOTE_COUNT;
$VOTE_COUNT = 2;

function MyBasketCallbackSur(){}

// служебные функции
class CServices{
    
    static public function vote($val, $element_id, $iblock_id, $property_code, $vote_count = 10000){
        global $USER;
        if($USER->IsAuthorized()){
            
            $type = "";
            
            $counter = CIBlockElement::GetProperty($iblock_id, $element_id, array("sort" => "asc"), Array("CODE" => $property_code."_COUNTER"))->Fetch();
            $values = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$iblock_id, "CODE"=>$property_code));
            $value_exists = false;
            while($value = $values->Fetch()){
                if($value[ID] == $val) 
                    $value_exists = true;
            }
            
            if(!$value_exists){
            
                if(CIBlockElement::GetByID($val)->Fetch()) {$value_exists = true; $type = "E";}
                
            }
            
            if($value_exists)
            {                
                
                if($counter) $counter = unserialize($counter[VALUE]);
                
                if($counter[$val])
                    $counter[$val][VALUE] = $counter[$val][VALUE] + 1;                    
                else
                    $counter[$val][VALUE] = 1;

                if($counter[$val][VALUE] >= $vote_count){
                         $db_props = CIBlockElement::GetProperty($iblock_id, $element_id, array("sort" => "asc"), Array("CODE" => $property_code));
                         $values = array();
                                              
                         while($v = $db_props->GetNext())
                            $values[] = $v[VALUE];                        
                         if(!in_array($val, $values)) {
                            $values[] = $val;   
                            CIBlockElement::SetPropertyValueCode($element_id, $property_code, $values);
                        }
                }
                
                if(in_array($USER->GetID(), $counter[$val][USER]))
                    return "ALREADY_VOTE";
                
                $counter[$val][USER][] = $USER->GetID();
                                    
                $counter = serialize($counter);                
                
                CIBlockElement::SetPropertyValueCode($element_id, $property_code."_COUNTER", $counter);                                                
                return "OK";    
            }            
            return "WRONG_VALUE";
        }    
        return "NO_AUTH";
    }
    
    static public function getVote($element_id, $iblock_id, $property_code){
    
        $prop = CIBlockProperty::GetList(array(), array("IBLOCK_ID" => $iblock_id, "CODE" => $property_code))->Fetch();
        $votes = CIBlockElement::GetProperty($iblock_id, $element_id, array("sort" => "asc"), Array("CODE" => $property_code."_COUNTER"))->Fetch();
        $votes = unserialize($votes[VALUE]);
        
        switch($prop[PROPERTY_TYPE]){
            case "E":
                break;
            case "L":
                echo "LIST";
                break;
            default:
                return "WRONG_PROPERTY_TYPE";
            break;
        }
        
    }

    

    
    static public function getHoliday()
    {
        if(CModule::IncludeModule('iblock')){
            $res = CIBlockElement::GetList(array(), array("PROPERTY_SPECBLOCK_VALUE" => "Y"), false, array("nTopCount" => 1), array("ID"))->Fetch();
            return $res[ID];
        }
    }

    static public function getMostPopularSticker($element_id, $iblock_id, $property_code){            
        $votes = CIBlockElement::GetProperty($iblock_id, $element_id, array("sort" => "asc"), Array("CODE" => $property_code."_COUNTER"))->Fetch();
        $votes = unserialize($votes[VALUE]);
        $id = 0;
        $max = 0;
        foreach($votes as $k => $v)
        {
            if($max < $v[VALUE] || $max == 0)
            {
                $id = $k;
                $max = $v[VALUE];
            }
        }
        return $id;
    }
    
    static public function getStickers($ids, $iblock_id, $spec = false)
    {
        if(CModule::IncludeModule('iblock')){


            $filter = array("ID" => $ids);
            
            if($ids == 0) unset($filter[ID]);
                         
            if($spec)
                $filter["PROPERTY_SPECBLOCK_VALUE"] = "Y";
            

               $result = CIBlockElement::GetList(
                    array(), 
                    $filter,
                    false, 
                    false, 
                    array("ID", "PREVIEW_PICTURE", "DETAIL_PICTURE", "PROPERTY_SPECBLOCK")
                ); 

                

                while($stic = $result->Fetch())
                {
                   $stickers[$stic[ID]][PREVIEW_PICTURE] = CFile::GetPath($stic[PREVIEW_PICTURE]);
                   $stickers[$stic[ID]][DETAIL_PICTURE] = CFile::GetPath($stic[DETAIL_PICTURE]);
                   $stickers[$stic[ID]][PROPERTY_SPECBLOCK_VALUE] = $stic[PROPERTY_SPECBLOCK_VALUE];
                }

            return $stickers;
        }
    }
    
    static public function getBundle($element_id, $iblock_id, $element_property)
    {
        if(CModule::IncludeModule('iblock')){
        
            $res = CIBlockElement::GetList(
                array(), 
                array("IBLOCK_ID" => $iblock_id, "ID" => $element_id), 
                false, 
                array("nTopCount" => 1), 
                array("PROPERTY_{$element_property}")
            )->Fetch();
            $sickers = array();
            
        }
        
        return $res["PROPERTY_{$element_property}_VALUE"];
    }
    
}

global $SURPRIZER;
$SURPRIZER = array();

//Узнаем какой банер к празднику выводить на главной
$SURPRIZER[HOLIDAY_ID] = CServices::getHoliday();




define("PATH_TO_404","/404.php");

AddEventHandler("main", "OnEpilog", "Redirect404");
function Redirect404() {
    if( 
     !defined('ADMIN_SECTION') &&  
     defined("ERROR_404") &&  
     defined("PATH_TO_404") &&  
     file_exists($_SERVER["DOCUMENT_ROOT"].PATH_TO_404) 
   ) {
        global $APPLICATION;
        $APPLICATION->RestartBuffer();
        CHTTP::SetStatus("404 Not Found");
        include($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/header.php");
        include($_SERVER["DOCUMENT_ROOT"].PATH_TO_404);
        include($_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH."/footer.php");
    }
}
?>