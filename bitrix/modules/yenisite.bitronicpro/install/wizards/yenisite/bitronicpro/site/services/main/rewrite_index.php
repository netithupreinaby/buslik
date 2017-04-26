<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if (!defined("WIZARD_SITE_ID"))
	return;
	
function getDataArray($content, $template, $key){
	$out = array();
	if(preg_match_all($template, $content, $out, PREG_PATTERN_ORDER))
		return $out[$key];
	else
		return;
}
$q1 = "'";
$q2 = '"';
$q  = "({$q1}|{$q2})";
function getComponentsParam($text, $component, $template, $parameter){
	$q1 = "'";
	$q2 = '"';
	$q  = "({$q1}|{$q2})";
	$temp = '#'.$q.$component.$q.'[^;]*'.$q.$template.$q.'[^;]*'.$q.$parameter.$q.'\s*=>\s*(array\s*\((.*)\))#Usi';
	$arReturn = getDataArray($text, $temp, 7);
	if(empty($arReturn[0]))
	{
		$temp = '#'.$q.$component.$q.'[^;]*'.$q.$template.$q.'[^;]*'.$q.$parameter.$q.'\s*=>\s*(\S+)\s*(\)|,)#U';
		$arReturn = getDataArray($text, $temp, 7);
	}
	//if(empty($arReturn[0]))
	//	$arReturn[0] = '""';
	
	return $arReturn[0];
}
function setComponentsParam($text, $component, $template, $parameter, $value)
{
	if(empty($value) || $value==false || !isset($value))
		return $text;
	$q1 = "'";
	$q2 = '"';
	$q  = "({$q1}|{$q2})";
	$temp = '#IncludeComponent\s*\(\s*'.$q.$component.$q.'[^;]*'.$q.$template.$q.'[^;]*array\s*\(([^;]*);#Usi';
	$arComp = getDataArray($text, $temp, 0);
	foreach($arComp as &$component)
	{
		$component = preg_replace('#'.$q.$parameter.$q.'\s*=>\s*((\S+)\s*,|array\((.*)\)\s*,)#Usi', '"'.$parameter.'" => '.$value.',',$component);
		$text = preg_replace($temp, $component, $text);
	}
	return $text;
}
	
$file_path = WIZARD_SITE_PATH.'/index.php';
$old_index_text = file_get_contents($file_path) ;

//$cat_sect_all_iblock_type = getComponentsParam($old_index_text, 'yenisite:catalog.section.all','top_block','IBLOCK_TYPE');

//if(is_numeric(strpos($cat_sect_all_iblock_type, '%')))	$architect = "multi";
//else	$architect = "one";
	
//if($architect == "multi")
//	$path = str_replace("//", "/", WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/"); 
//else
	$path = str_replace("//", "/", WIZARD_ABSOLUTE_PATH."/site/public_one/".LANGUAGE_ID."/"); 
	
$new_file = str_replace("//", "/", WIZARD_SITE_PATH.'/index_'.date('Y_m_d_H_i_s').'.back');
if (!copy($file_path, $new_file))
	return false ;

$index_text = file_get_contents($path.'index.php') ;
$main_spec_file_path = WIZARD_SITE_PATH.'/include_areas/index/main_spec.php';
$news_file_path = WIZARD_SITE_PATH.'/include_areas/index/news.php';
$campaing_file_path = WIZARD_SITE_PATH.'/include_areas/index/campaigns.php';
$top_block_file_path = WIZARD_SITE_PATH.'/include_areas/index/top_block.php';

$main_spec_text = file_get_contents($main_spec_file_path) ;
$news_text = file_get_contents($news_file_path) ;
$campaing_text = file_get_contents($campaing_file_path) ;
$top_block_text = file_get_contents($top_block_file_path) ;
//if($architect == "one")
//{
	// ### TRANSFER CATALOG_IBLOCK_ID ### //
	$cat_sect_all_iblock_id = getComponentsParam($old_index_text, 'yenisite:catalog.section.all','main_spec','IBLOCK_ID');
	if(empty($cat_sect_all_iblock_id)) $cat_sect_all_iblock_id = '""';
	$top_block_file_path = str_replace('"#CATALOG_IBLOCK_ID#"', $cat_sect_all_iblock_id, $top_block_file_path);
	$main_spec_text = str_replace('"#CATALOG_IBLOCK_ID#"', $cat_sect_all_iblock_id, $main_spec_text);
//}
// ####################################### //
// ### START TRANSFER COMPONENT PARAMS ### //
// ####################################### //
$param = getComponentsParam($old_index_text, 'yenisite:catalog.section.all','top_block','IBLOCK_TYPE');
$top_block_file_path = setComponentsParam($top_block_file_path, 'yenisite:catalog.section.all','top_block','IBLOCK_TYPE',$param);
$param = getComponentsParam($old_index_text, 'yenisite:catalog.section.all','top_block','IBLOCK_ID');
$top_block_file_path = setComponentsParam($top_block_file_path, 'yenisite:catalog.section.all','top_block','IBLOCK_ID',$param);
$param = getComponentsParam($old_index_text, 'yenisite:catalog.section.all','top_block','PRICE_CODE');
$top_block_file_path = setComponentsParam($top_block_file_path, 'yenisite:catalog.section.all','top_block','PRICE_CODE',$param);

$param = getComponentsParam($old_index_text, 'yenisite:catalog.section.all','main_spec','IBLOCK_TYPE');
$main_spec_text = setComponentsParam($main_spec_text, 'yenisite:main_spec','.default','IBLOCK_TYPE',$param);
$param = getComponentsParam($old_index_text, 'yenisite:catalog.section.all','main_spec','IBLOCK_ID');
$main_spec_text = setComponentsParam($main_spec_text, 'yenisite:main_spec','.default','IBLOCK_ID',$param);
$param = getComponentsParam($old_index_text, 'yenisite:catalog.section.all','main_spec','PRICE_CODE');
$main_spec_text = setComponentsParam($main_spec_text, 'yenisite:main_spec','.default','PRICE_CODE',$param);
// ### END TRANSFER COMPONENT PARAMS   ### //
// ####################################### //

// ### TRANSFER TITLE ### //
$title = getDataArray($old_index_text, '#SetPageProperty\s*\(\s*'.$q.'title'.$q.'\s*,\s*'.$q.'(.+)'.$q.'#Us', 4); 
$index_text = preg_replace('#SetPageProperty\s*\(\s*'.$q.'title'.$q.'.*\);#Us', 'SetPageProperty("title", "'.$title[0].'");',$index_text);

// ### TRANSFER CAMPAING PARAMS ### //
$campaing_iblock_id = getComponentsParam($old_index_text, 'bitrix:news.list','campaigns','IBLOCK_ID'); 
if(empty($campaing_iblock_id)) $campaing_iblock_id = '""';
$campaing_text = str_replace('"#CAMPAIGNS_IBLOCK_ID#"', $campaing_iblock_id, $campaing_text);

$campaing_camponent_active = getComponentsParam($old_index_text, 'bitrix:news.list','campaigns','ACTIVE_COMPONENT'); 
if(empty($campaing_camponent_active)) $campaing_camponent_active = '""';
$campaing_text = str_replace('"#CAMPAINGS_COMPONENT_ACTIVE#"', $campaing_camponent_active, $campaing_text);

// ### TRANSFER NEWS PARAMS ### //
$news_iblock_id = getComponentsParam($old_index_text, 'bitrix:news.list','main_list','IBLOCK_ID'); 
if(empty($news_iblock_id)) $news_iblock_id = '""';
$news_text = str_replace('"#NEWS_IBLOCK_ID#"', $news_iblock_id, $news_text);

$news_camponent_active = getComponentsParam($old_index_text, 'bitrix:news.list','main_list','ACTIVE_COMPONENT'); 
if(empty($news_camponent_active)) $news_camponent_active = '""';
$news_text = str_replace('"#NEWS_COMPONENT_ACTIVE#"', $news_camponent_active, $news_text);

// ### WRITE NEW INDEX.PHP ### //
file_put_contents ( $file_path , $index_text );
file_put_contents ( $main_spec_file_path , $main_spec_text );
file_put_contents ( $news_file_path , $news_text );
file_put_contents ( $campaing_file_path , $campaing_text );
file_put_contents ( $top_block_file_path , $top_block_text );
?>