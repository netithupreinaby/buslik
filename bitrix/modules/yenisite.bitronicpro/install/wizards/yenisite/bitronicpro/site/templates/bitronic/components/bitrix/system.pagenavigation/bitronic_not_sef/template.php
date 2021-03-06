<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);

$arDelParams = array();

if(strpos($arResult['sUrlPath'], '/filter/') !== false) {
//|| strpos($arResult['NavQueryString'], 'set_filter') !== false) {
	
	$arDelParams[] = 'arrFilter_[\d_]+';
	$arDelParams[] = 'arrFilter_AVAILABLE_\d+';
	$arDelParams[] = 'available%5Bno%5D';
	$arDelParams[] = 'available%5Byes%5D';
	$arDelParams[] = 'set_filter';
}

foreach($arDelParams as $param)
{
	$arResult["NavQueryString"] = preg_replace( '/&?'.$param.'=\S*(?:&)?/', '', htmlspecialchars_decode($arResult["NavQueryString"]));
}

if(!$arResult["NavShowAlways"])
{
	if ($arResult["NavRecordCount"] == 0 || ($arResult["NavPageCount"] == 1 && $arResult["NavShowAll"] == false))
		return;
}

$strNavQueryString = ($arResult["NavQueryString"] != "" ? $arResult["NavQueryString"]."&amp;" : "");
$strNavQueryStringFull = ($arResult["NavQueryString"] != "" ? "?".$arResult["NavQueryString"] : "");


if($arResult["bDescPageNumbering"] === true)
	$prev =  ($arResult["NavPageNomer"] < $arResult["NavPageCount"]) ? $arResult["NavPageNomer"] + 1 : $arResult["NavPageNomer"];
elseif($arResult["NavPageNomer"] == 1)
	$prev = 1;
elseif($arResult["NavPageNomer"] == 0)
	$prev = 1;
else $prev = $arResult["NavPageNomer"] - 1;

if($arResult["bDescPageNumbering"] === true)
	$next =  ($arResult["NavPageNomer"] - 1 > 0)? $arResult["NavPageNomer"] - 1 : 1;
elseif($arResult["NavPageNomer"] == $arResult["NavPageCount"])
	$next = $arResult["NavPageCount"];
elseif($arResult["NavPageNomer"] > $arResult["NavPageCount"])
	$next = $arResult["NavPageCount"];
else 
	$next = $arResult["NavPageNomer"]+1;


?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
					<div class="one-step">						
						<?$a_class = $arResult['NavPageNomer'] == $arResult['nStartPage'] ? 'class="nav-hidden"' : '';?>
						<a <?=$a_class;?> href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$prev?>" rel="canonical" data-page="<?=$prev?>">&larr; <?=GetMessage("nav_prev")?></a>
						<?$a_class = $arResult['NavPageNomer'] == $arResult['nEndPage'] ? 'class="nav-hidden"' : '';?>
						<a <?=$a_class;?> href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$next?>" rel="canonical" data-page="<?=$next?>"><?=GetMessage("nav_next")?> &rarr;</a>
					</div><!--.one-step-->

<?if($arResult["bDescPageNumbering"] === true):?>

	<div class="pager">

	<?if ($arResult["NavPageNomer"] < $arResult["NavPageCount"]):?>
		<?if($arResult["bSavePage"]):?>
			<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>" rel="canonical" data-page="<?=$arResult["NavPageCount"]?>"><?=GetMessage("nav_begin")?></a>
		<?else:?>
			<a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>" rel="canonical" ><?=GetMessage("nav_begin")?></a>
			<?/*if ($arResult["NavPageCount"] == ($arResult["NavPageNomer"]+1) ):?>
			<?else:?>
			<?endif*/?>
		<?endif?>
	<?else:?>
		<a class="nav-hidden" href="#"><?=GetMessage("nav_begin")?></a>
	<?endif?>

	<?while($arResult["nStartPage"] >= $arResult["nEndPage"]):?>
		<?$NavRecordGroupPrint = $arResult["NavPageCount"] - $arResult["nStartPage"] + 1;?>

		<?if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
			<a href="#" class='active'><?=$NavRecordGroupPrint?></a>
		<?elseif($arResult["nStartPage"] == $arResult["NavPageCount"] && $arResult["bSavePage"] == false):?>
			<a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>" rel="canonical" ><?=$NavRecordGroupPrint?></a>
		<?else:?>
			<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>" rel="canonical" data-page="<?=$arResult["nStartPage"]?>"><?=$NavRecordGroupPrint?></a>
		<?endif?>

		<?$arResult["nStartPage"]--?>
	<?endwhile?>



	<?if ($arResult["NavPageNomer"] > 1):?>
		
	
		<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1" rel="canonical" data-page="1"><?=GetMessage("nav_end")?></a>
	<?else:?>
		<a class="nav-hidden" href="#"><?=GetMessage("nav_end")?></a>
	<?endif?>

<?else:?>
	<div class="pager">

	<?if ($arResult["NavPageNomer"] > 1):?>

		<?if($arResult["bSavePage"]):?>
			<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=1" rel="canonical" data-page="1"><?=GetMessage("nav_begin")?></a>
		
		
		
		<?else:?>
			<a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>" rel="canonical" ><?=GetMessage("nav_begin")?></a>
			
			<?if ($arResult["NavPageNomer"] > 2):?>
				
			<?else:?>
				
			<?endif?>
			
		<?endif?>

	<?else:?>
		<a class="nav-hidden" href="#"><?=GetMessage("nav_begin")?></a>
	<?endif?>

	<?while($arResult["nStartPage"] <= $arResult["nEndPage"]):?>

		<?if ($arResult["nStartPage"] == $arResult["NavPageNomer"]):?>
			<a href="#" class='active'><?=$arResult["nStartPage"]?></a>
		<?elseif($arResult["nStartPage"] == 1 && $arResult["bSavePage"] == false):?>
			<a href="<?=$arResult["sUrlPath"]?><?=$strNavQueryStringFull?>" rel="canonical" data-page="<?=$arResult["nStartPage"]?>" ><?=$arResult["nStartPage"]?></a>
		<?else:?>
			<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["nStartPage"]?>" rel="canonical" data-page="<?=$arResult["nStartPage"]?>"><?=$arResult["nStartPage"]?></a>
		<?endif?>
		<?$arResult["nStartPage"]++?>
	<?endwhile?>
	

	<?if($arResult["NavPageNomer"] < $arResult["NavPageCount"]):?>
		
		<a href="<?=$arResult["sUrlPath"]?>?<?=$strNavQueryString?>PAGEN_<?=$arResult["NavNum"]?>=<?=$arResult["NavPageCount"]?>" rel="canonical" data-page="<?=$arResult["NavPageCount"]?>"><?=GetMessage("nav_end")?></a>
	<?else:?>
		<a class="nav-hidden" href="#"><?=GetMessage("nav_end")?></a>
	<?endif?>

<?endif?>

</div>