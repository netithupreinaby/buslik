<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(function_exists('yenisite_GetCompositeLoader')){global $MESS;$MESS ['COMPOSITE_LOADING'] = yenisite_GetCompositeLoader();}?>

<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>

<h2><?=GetMessage('NEWS_TITLE')?></h2>

<?foreach($arResult["ITEMS"] as $arItem):?>
  <?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING')); ?>
		<div id="<?=$this->GetEditAreaId($arItem['ID']);?>" class="news-item"> <span class="date"><?=$arItem["DATE_CREATE"]?></span>
			<h3><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><?echo $arItem["NAME"]?></a></h3>
		</div>
	<?if(method_exists($this, 'createFrame')) $frame->end();?>
		  <!--.news-item-->
	<?endforeach?>
		  
		  <!--.news-item--> 
		  <a href="/news/" class="all-news"><?=GetMessage('ALL')?></a>