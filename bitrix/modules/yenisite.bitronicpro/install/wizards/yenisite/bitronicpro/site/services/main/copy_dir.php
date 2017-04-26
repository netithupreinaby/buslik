<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();
	
CopyDirFiles(
	WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/bitrix/components/yenisite/catalog.section.all/",
	$_SERVER['DOCUMENT_ROOT']."/bitrix/components/yenisite/catalog.section.all/",
	$rewrite = true,
	$recursive = true,
	$delete_after_copy = false
);

CopyDirFiles(
	WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/include_areas/",
	WIZARD_SITE_PATH."/include_areas/",
	$rewrite = false,
	$recursive = true,
	$delete_after_copy = false
);

CopyDirFiles(
	WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/admin-forum/",
	WIZARD_SITE_PATH."/admin-forum/",
	$rewrite = false,
	$recursive = true,
	$delete_after_copy = false
);

/* - rewrite validator file - */
$contentValFileNew = file_get_contents(WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/include_areas/validator.php");
$contentValFileOld = file_get_contents(WIZARD_SITE_PATH."/include_areas/validator.php");
if(strcmp($contentValFileNew, $contentValFileOld) != 0)
{
	$new_file = str_replace("//", "/", WIZARD_SITE_PATH.'/include_areas/validator_'.date('Y_m_d_H_i_s').'.back');
	if (copy(WIZARD_SITE_PATH."/include_areas/validator.php", $new_file))
	{
		CopyDirFiles(
			WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/include_areas/validator.php",
			WIZARD_SITE_PATH."/include_areas/validator.php",
			$rewrite = true,
			$recursive = true,
			$delete_after_copy = false
		);
	}
}

/*-------main_spec--------*/
CheckDirPath(WIZARD_SITE_PATH."/include_areas/index/");
if (file_exists(WIZARD_SITE_PATH."/yenisite.main_spec/component.php")) {
	CopyDirFiles(
		WIZARD_SITE_PATH."/yenisite.main_spec/component.php",
		WIZARD_SITE_PATH."/include_areas/index/main_spec.php",
		$rewrite = true,
		$recursive = true,
		$delete_after_copy = false
	);
	$content = file_get_contents(WIZARD_SITE_PATH."/include_areas/index/main_spec.php");
	if(strpos($content, 'global $ys_options') === false)
		$content = '<?global $ys_options;?>'.$content;
	if(strpos($content, 'global $prices') === false)
		$content = '<?global $prices;?>'.$content;
	if(strpos($content, 'global $stores') === false)
		$content = '<?global $stores;?>'.$content;
	file_put_contents ( WIZARD_SITE_PATH."/include_areas/index/main_spec.php" , $content );
	
	CopyDirFiles(
		WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID."/yenisite.main_spec/",
		WIZARD_SITE_PATH."/yenisite.main_spec/",
		$rewrite = true,
		$recursive = true,
		$delete_after_copy = false
	);	
	
	DeleteDirFilesEx(WIZARD_SITE_DIR."/yenisite.main_spec/component.php");
}
/*----end---main_spec--------*/
?>