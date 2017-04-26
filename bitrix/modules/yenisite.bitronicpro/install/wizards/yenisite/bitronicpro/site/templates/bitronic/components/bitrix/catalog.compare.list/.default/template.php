<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>

<?if(function_exists('yenisite_GetCompositeLoader')){global $MESS;$MESS ['COMPOSITE_LOADING'] = yenisite_GetCompositeLoader();}?>

<? CModule::IncludeModule('yenisite.resizer2'); ?>
<? CModule::IncludeModule('iblock'); ?>

<? include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/functions/yenisitegetimage.php'); ?>
<? include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/functions/yenisitecatalog.php'); ?>

<?if($arParams['IT_IS_AJAX_CALL'] != 'Y'):?>
	<?$this->SetViewTarget('COMPARE_LIST');?>
<?endif;?>
<?if(method_exists($this, 'createFrame')) $frame = $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING')); ?>
<?if(count($arResult)>0):?>
	<form action="<?=$arParams["COMPARE_URL"]?>" method="get">
 <div class="compare">
	
		<a href="javascript:void(0);" class="compare_link"><?=GetMessage("IN_COMPARE")?></a> <strong><?=count($arResult);?></strong> <?=GetMessage("ITEMS")?>
	   <div class="basket-popup <?=$arParams['IT_IS_AJAX_REMOVE'] == 'Y' ? 'opened' :'closed';?>" id="compare-popup">
	   <div class="yen-bb-rasp"></div>
	   <a class="yen-bb-close" onclick="yenisite_bb_close()">&#205;</a>
		  <table>
			<tr>
			  <td class="t_photo"><?=GetMessage("PHOTO")?></td>
			  <td class="t_name"><?=GetMessage("NAME")?></td>
			  <!--<td class="t_price"></th>-->
			  <td class="t_delete"><?=GetMessage("CATALOG_DELETE")?></td>
			</tr>
			</table>
	   	<div class="bask_wr">
			<table>
			
			<?foreach($arResult as $arElement):?>
			<tr>
				
				<?
				CModule::IncludeModule('iblock');
				$res1 = CIBlockElement::GetList(array(), array('ID' => $arElement["ID"]), false, false)->Fetch();
				$path = CFile::GetPath(yenisite_GetPicSrc($res1));
				global $basket_set;
				if(!$basket_set) $basket_set = 5;
			  ?>

			  <td class="t_photo"><img class="yen_compare_pic" id="cadded-<?=$arElement['ID'];?>" src="<?=CResizer2Resize::ResizeGD2($path, $basket_set);?>" alt="" /></td>
			  <td class="t_name"><h3><a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><?=$arElement["NAME"]?></a></h3>			
			  <!--<td class="t_price"><span class="price">3 245 <span class="rubl"></span></span></td>-->
			  <?$arElement['DELETE_URL'] = substr($arElement['DELETE_URL'], strpos($arElement['DELETE_URL'], '?'));?>
			  <td class="t_delete"><a class="button6 sym ajax_remove_compare" title="<?=GetMessage("CATALOG_DELETE"); ?>" href="<?=$arElement["DELETE_URL"]?>" rel="nofollow">&#206;</a></td>
			</tr>
		<?endforeach?>	
		  </table>
		  </div><!--.bask_wr-->

		   <?if (count($arResult) >=2 ):?>
			   <input type="hidden" name="action" value="COMPARE" />
			   <input type="hidden" name="IBLOCK_ID" value="<?=$arParams["IBLOCK_ID"]?>" />
			   <?
			   $sef = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "sef_mode", false, SITE_ID);
			   $tmp = array_slice($arResult, 0, 1);
			   $tmp = $tmp[0]["COMP_URL"];
			   ?>

			   <div class="make_order">
				   <?if ($sef == "Y"):?>
					   <a href="<?=$tmp?>" class="button3"><?=GetMessage("CATALOG_COMPARE")?></a>
				   <?else:?>
					   <button class="button3"><?=GetMessage("CATALOG_COMPARE")?></button>
				   <?endif;?>
			   </div>
		   <?endif;?>
		  
		  
		  <!--.make_order-->
		  <div class="pbot"></div>
		</div>
		<!--.basket-popup--> 
	  </div><!--.compare-->
	</form>

	
<?endif;?>

<?if(method_exists($this, 'createFrame')) $frame->end();?>
<?if ($arParams['IT_IS_AJAX_CALL'] != 'Y'):?>
	<?$this->EndViewTarget();?>
<?endif;?>
