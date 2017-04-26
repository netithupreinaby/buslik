<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if (!defined("WIZARD_SITE_ID"))
	return;

if(!function_exists('yenisite_add_bitronic_ini'))
{
	function yenisite_add_bitronic_ini()
	{
		CopyDirFiles(
			WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/bitrix/php_interface/bitronic_ini.php",
			$_SERVER['DOCUMENT_ROOT']."/bitrix/php_interface/bitronic_ini.php",
			$rewrite = true,
			$recursive = true,
			$delete_after_copy = false
		);
		$file_path = $_SERVER['DOCUMENT_ROOT'].'/bitrix/php_interface/init.php';
		$yenisite_add_uf_complete_set = '<?require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/components/yenisite/catalog.sets/userprop.php") ;?>';
		$yenisite_add_bitronic_ini = '<?include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/bitronic_ini.php") ;?>';
		
		if(file_exists($file_path))
		{
			$add_event = false ;
			$new_file = $_SERVER['DOCUMENT_ROOT'].'/bitrix/php_interface/init_'.date('Y_m_d_H_i_s').'.back' ;
			if (!copy($file_path, $new_file))
				return false ;
			$init_text = file_get_contents($file_path) ;
			if(strpos ($init_text , 'catalog.sets/userprop.php') === false)
			{
				$init_text .= $yenisite_add_uf_complete_set ;
				$add_event = true;
			}
			if(strpos ($init_text , 'php_interface/bitronic_ini.php') === false)
			{
				$init_text .= $yenisite_add_bitronic_ini ;
				$add_event = true;
			}
		}
		else
		{
			$init_text = $yenisite_add_bitronic_ini.$yenisite_add_uf_complete_set;
			//$init_text = $yenisite_add_require ;
			$add_event = true;
		}
			
		if($add_event && $init_text)
			if(file_put_contents ( $file_path , $init_text ))
				return true;
		
		return false;
	}
}
yenisite_add_bitronic_ini();
?>