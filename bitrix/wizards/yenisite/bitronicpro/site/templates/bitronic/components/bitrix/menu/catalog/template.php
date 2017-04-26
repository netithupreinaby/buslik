<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if (!empty($arResult)):?>

<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>


<ul id="navigator">

<?
$count = 0;
$count2 = 0;
foreach($arResult as $arItem){
    if($arItem[DEPTH_LEVEL] == 1)
        $count++;
   
}

$count2 = $count2/3 +1;

$previousLevel = 0;
$cnt = 0;
$i = 0;
foreach($arResult as $arItem):?>
<?//if( substr_count($arItem["LINK"], 'catalog_') > 0 ) $arItem["LINK"] = "javascript:void(0);";
$arItem["LINK"] = str_replace("catalog_", "", $arItem["LINK"]);
$arItem["LINK"] = str_replace(SITE_ID."_", "", $arItem["LINK"]);
$arItem["LINK"] = str_replace("_", "-", $arItem["LINK"]);
?>
	<?if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):?>
		<?=str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));?>
	<?endif?>

	<?if ($arItem["IS_PARENT"]):?>

		<?if ($arItem["DEPTH_LEVEL"] == 1): $cnt++;?>
			<li <?if($arItem['SELECTED']):?>class="active"<?endif?> <?if($cnt==$count) echo "class='last'";?> style="width:  <?=100/$count;?>%"><a href="<?=$arItem["LINK"]?>" class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item<?endif?>"><?=$arItem["TEXT"]?></a>
				<div class='subnav'><ul><?$i=0;?>
		<?else:?>
			<li><a href="<?=$arItem["LINK"]?>" class="parent<?if ($arItem["SELECTED"]):?> item-selected<?endif?>"><?=$arItem["TEXT"]?> <sup>123</sup></a>
				<ul>
		<?endif?>

	<?else:?>

		<?if ($arItem["PERMISSION"] > "D"):?>

			<?if ($arItem["DEPTH_LEVEL"] == 1): $cnt++;?>

<?if ($arItem["SELECTED"]):?>
			<li <?if($arItem['SELECTED']):?>class="active"<?endif?> <?if($cnt==$count) echo "class='last'";?> style="width:  <?=100/$count;?>%"><a class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item<?endif?>"><?=$arItem["TEXT"]?></a>
<?else:?>
				<li <?if($cnt==$count) echo "class='last'";?> style="width:  <?=100/$count;?>%"><a href="<?=$arItem["LINK"]?>" class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item<?endif?>"><?=$arItem["TEXT"]?></a></li>
<?endif?>

			<?else: $i++;?>
<?if($i>14): $i=0;?>
    </ul><ul>
<?endif?>
				<li><a href="<?=$arItem["LINK"]?>" <?if ($arItem["SELECTED"]):?> class="item-selected"<?endif?>><?=$arItem["TEXT"]?></a></li>
			<?endif?>

		<?else:?>

			<?if ($arItem["DEPTH_LEVEL"] == 1): $cnt++;?>
<?if ($arItem["SELECTED"]):?>

				<li <?if($cnt==$count) echo "class='last'";?> style="width:  <?=100/$count;?>%"><?=$arItem["TEXT"]?></li>

<?else:?>
				<li <?if($cnt==$count) echo "class='last'";?> style="width:  <?=100/$count;?>%">><a href="" class="<?if ($arItem["SELECTED"]):?>root-item-selected<?else:?>root-item<?endif?>" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>"><?=$arItem["TEXT"]?></a></li>
<?endif?>
			<?else:?>
				<li><a href="" class="denied" title="<?=GetMessage("MENU_ITEM_ACCESS_DENIED")?>"><?=$arItem["TEXT"]?></a></li>
			<?endif?>

		<?endif?>

	<?endif?>

	<?$previousLevel = $arItem["DEPTH_LEVEL"];?>

<?endforeach?>

<?if ($previousLevel > 1)://close last item tags?>
	<?=str_repeat("</ul></li>", ($previousLevel-1) );?>
<?endif?>

</ul>
<?endif?>
