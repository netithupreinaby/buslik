<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? CModule::IncludeModule('yenisite.resizer2'); ?>
<? CModule::IncludeModule('iblock'); ?>
<?$this->SetViewTarget('COMPARE_LIST');?>
<?if(count($arResult)>0):?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>
	<form action="<?=$arParams["COMPARE_URL"]?>" method="get">
 <div class="compare">
		<a href="#" class="compare_link"><?=GetMessage("IN_COMPARE")?></a> <strong><?=count($arResult);?></strong> <?=GetMessage("ITEMS")?>
	   <div class="basket-popup closed" id="compare-popup">
	   <div class="yen-bb-rasp"></div>
	   <a class="yen-bb-close" onclick="yenisite_bb_close()">&#205;</a>
		  <table>
			<tr>
			  <td class="t_photo"><?=GetMessage("PHOTO")?></th>
			  <td class="t_name"><?=GetMessage("NAME")?></th>
			  <!--<td class="t_price"></th>-->
			  <td class="t_delete"><?=GetMessage("CATALOG_DELETE")?></th>
			</tr>
			</table>
	   	<div class="bask_wr">
			<table>
			
			<?foreach($arResult as $arElement):?>
			<tr>
			
				<?
				CModule::IncludeModule('iblock');
				$res1 = CIBlockElement::GetList(array(), array('ID' => $arElement["ID"]), false, false)->Fetch();
				$res = CIBlockElement::GetProperty($res1[IBLOCK_ID], $arElement["ID"], array("value_id" => "asc"), Array("CODE"=>"MORE_PHOTO"))->Fetch();
				$path = CFile::GetPath($res[VALUE]);		
				if(!$path) $path = CFile::GetPath($res1[DETAIL_PICTURE]);		
				global $basket_set;
				if(!$basket_set) $basket_set = 5;
			  ?>
			  
			  
			  
			  <td class="t_photo"><img src="<?=CResizer2Resize::ResizeGD2($path, $basket_set);?>" alt="" /></td>
			  <td class="t_name"><h3><a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><?=$arElement["NAME"]?></a></h3>			
			  <!--<td class="t_price"><span class="price">3 245 <span class="rubl"></span></span></td>-->
			  <td class="t_delete"><a class="button6 sym" href="<?=$arElement["DELETE_URL"]?>" rel="nofollow">&#206;</a></td>
			</tr>
		<?endforeach?>	
		  </table>
		  </div><!--.bask_wr-->
		  
			<?if(count($arResult)>=2):?>
			<input type="hidden" name="action" value="COMPARE" />
			<input type="hidden" name="IBLOCK_ID" value="<?=$arParams["IBLOCK_ID"]?>" />
			<div class="make_order"><button class="button3"><?=GetMessage("CATALOG_COMPARE")?></button></div>
			<?endif;?>
		  
		  
		  <!--.make_order-->
		  <div class="pbot"></div>
		</div>
		<!--.basket-popup--> 
	  </div><!--.compare-->

	</form>
<?endif;?>	  
	  
<?$this->EndViewTarget();?>	  
	  <?return;?>

<div class="catalog-compare-list">
<a name="compare_list"></a>
<?if(count($arResult)>0):?>
	<form action="<?=$arParams["COMPARE_URL"]?>" method="get">
	<table class="data-table" cellspacing="0" cellpadding="0" border="0">
		<thead>
		<tr>
			<td align="center" colspan="2"><?=GetMessage("CATALOG_COMPARE_ELEMENTS")?></td>
		</tr>
		</thead>
		<?foreach($arResult as $arElement):?>
		<tr>
			<td><input type="hidden" name="ID[]" value="<?=$arElement["ID"]?>" /><a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><?=$arElement["NAME"]?></a></td>
			<td><noindex><a href="<?=$arElement["DELETE_URL"]?>" rel="nofollow"><?=GetMessage("CATALOG_DELETE")?></a></noindex></td>
		</tr>
		<?endforeach?>
	</table>
	<?if(count($arResult)>=2):?>
		<br /><input type="submit"  value="<?=GetMessage("CATALOG_COMPARE")?>" />
		<input type="hidden" name="action" value="COMPARE" />
		<input type="hidden" name="IBLOCK_ID" value="<?=$arParams["IBLOCK_ID"]?>" />
	<?endif;?>
	</form>
<?endif;?>
</div>
