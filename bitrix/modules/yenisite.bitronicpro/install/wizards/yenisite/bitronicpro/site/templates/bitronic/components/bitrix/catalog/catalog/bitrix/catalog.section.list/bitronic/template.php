<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);
$strDepthSym = '>';

include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';

?><div class="ys_catalog_text"><?

if (0 < $arResult["SECTIONS_COUNT"]):
	?><ul class="ys_catalog_text_ul"><?
	foreach ($arResult['SECTIONS'] as &$arSection)
	{
		$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
		$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')));
		?><li><span><? echo str_repeat($strDepthSym, $arSection['RELATIVE_DEPTH']);
		?><a href="<? echo $arSection['SECTION_PAGE_URL']; ?>"><? echo $arSection['NAME']; ?></a><?
		
		?> <sup><?if ($arParams["COUNT_ELEMENTS"] && intval($arSection['ELEMENT_CNT'])>0){ echo $arSection['ELEMENT_CNT']; }?></sup><?
		
		?></span></li><?
	}
	unset($arSection);
	?></ul><div style="clear: both;"></div>
<?endif?>
</div>