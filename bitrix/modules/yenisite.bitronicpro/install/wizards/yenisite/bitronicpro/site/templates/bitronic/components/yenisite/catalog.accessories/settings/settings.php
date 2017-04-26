<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_js.php");
// protect
global $USER ;
if(!$USER->IsAdmin())
	return 0;
// includes
$component_path = '/bitrix/components/yenisite/catalog.accessories/' ;
include_once($_SERVER['DOCUMENT_ROOT'].$component_path.'settings/template.php') ;

// initialization params
$arProperties = $_POST['ACCESSORIES_PROPS'] ? unserialize (base64_decode($_POST['ACCESSORIES_PROPS'])) : false; 

if(!($parent_iblock_id = IntVal($_REQUEST['iblock_id']))> 0)
	return 0;

// make window
$obJSPopup = new CJSPopup(GetMessage('YS_ACCESSORIES_TITLE'),
	array(
		//'TITLE' => GetMessage('YS_ACCESSORIES_TITLE'),
		'SUFFIX' => 'yenisite_accessories',
		'ARGS' => ''
	)
);
$obJSPopup->ShowTitlebar();
$obJSPopup->StartDescription('bx-edit-menu');?>
<p><b><?=GetMessage('YS_ACCESSORIES_DESCRIPTION');?></b></p>
<p class="note"><?=GetMessage('YS_ACCESSORIES_DESCRIPTIPON_2');?></p>
<?$obJSPopup->StartContent(); 
// action :) ?>

<table>
	<?if(is_array($arProperties)):?>
		<?// TODO: make table in cicle and function?>
		<?/*
		Array
		(
		    [sessid] => 4f9488042af990067582737377074c8e
		    [PROPERTY] => Array
		        (
		            [0] => Array
		                (
		                    [PARENT_PROPERTY] => LINK_ACCESS
		                    [IBLOCK_TYPE] => catalog_accessories
		                    [IBLOCK_ID] => 269
		                    [PROPERTY_CODE] => SHOW_MAIN
		                )

		        )

		    [action] => GetSerialize
		    [bxsessid] => 4f9488042af990067582737377074c8e
		)
		*/?>

		<?
		$arSelectParentIBlockProperty = false ;
		$arSelectIBlockTypes = false ;
		$arSelectIBlocks = false ;
		$arSelectIBlockProperties = false ;

		foreach($arProperties['PROPERTY'] as $n=>$arProp)
		{
			if(!is_array($arSelectParentIBlockProperty))
				$arSelectParentIBlockProperty = yenisite_GetIBlockProps($parent_iblock_id);		
			if(!is_array($arSelectIBlockTypes))
				$arSelectIBlockTypes = yenisite_GetIBlockTypes() ;
			if(!is_array($arSelectIBlocks[ $arProp['IBLOCK_TYPE'] ]))
				$arSelectIBlocks[ $arProp['IBLOCK_TYPE'] ] = yenisite_GetIBlocks($arProp['IBLOCK_TYPE']) ;
			if(!is_array($arSelectIBlockProperties[ $arProp['IBLOCK_ID'] ]))
				$arSelectIBlockProperties[ $arProp['IBLOCK_ID'] ] = yenisite_GetIBlockProps ( $arProp['IBLOCK_ID'] ) ;

			yenisite_PrintTypesRow ( $n, $arSelectParentIBlockProperty, $arSelectIBlockTypes, $arProp['PARENT_PROPERTY'], $arProp['IBLOCK_TYPE'] ) ;
			yenisite_PrintIBlocksRow ( $n, $arSelectIBlocks[ $arProp['IBLOCK_TYPE'] ], $arProp['IBLOCK_ID'] ) ;
			yenisite_PrintPropertyRow ( $n, $arSelectIBlockProperties[ $arProp['IBLOCK_ID'] ], $arProp['PROPERTY_CODE'] ) ;

		}?>
	<?else:?>
		<?// first property
			$arSelectParentIBlockProperty = yenisite_GetIBlockProps($parent_iblock_id);		
			$arSelectIBlockTypes = yenisite_GetIBlockTypes() ;
			
			yenisite_PrintTypesRow ( 0, $arSelectParentIBlockProperty, $arSelectIBlockTypes) ;
		?>
	<?endif;?>
	<tr id="last_row">
		<td colspan="3" style="text-align:center;">
			<input type="button" id="add_property" value="<?=GetMessage('YS_ACCESSORIES_ADD');?>"/>
			<input type="hidden" id="count_property" value="<?=$n;?>"/>
			<input type="hidden" id="bxsessid" value="<?=bitrix_sessid();?>"/>
			<input type="hidden" id="path2tools" value="<?=$component_path;?>settings/tools.php"/>
			<input type="hidden" id="parent_iblock_id" value="<?=$parent_iblock_id;?>"/>
		</td>
	</tr>
</table>
<?// $obJSPopup->EndContent();   // ----> ни на что не влияет? Посмотреть в библиотеке, что делает.?>
<?$obJSPopup->StartButtons();?>
<input type="submit" class="adm-btn-save" value="<?=GetMessage('YS_ACCESSORIES_SAVE');?>" onclick="return window.jsYenisiteAccessoriesOpener.__saveData();"/>
<?$obJSPopup->ShowStandardButtons(array('cancel'));
$obJSPopup->EndButtons();?>
<script>
	$(function(){
		var url = $('#path2tools').val() ;
		var bxsessid = $('#bxsessid').val() ;
		
		function GetIBlocks(){
			row = parseInt( $(this).attr('id').replace('PROPERTY[', '').replace('][IBLOCK_TYPE]', '') ) ;			
			$.post(url, {bxsessid: bxsessid, IBLOCK_TYPE: $(this).val(), action: 'GetIBlocks', row: row},
			function(data) {
				$('.iblock_id_select').unbind('change',GetProp);
				$('#iblock_id_row'+row).remove() ;
				$('#iblock_prop_row'+row).remove() ;
				$(data).insertAfter('#iblock_type_row'+row);
				$('.iblock_id_select').change(GetProp);
			});
		}
	
		function GetNewProp(){
			row = parseInt( $('#last_row').prev().attr('class').replace('row', '') ) + 1 ;
			$.post(url, {bxsessid: bxsessid, PARENT_IBLOCK_ID: $('#parent_iblock_id').val(), action: 'GetNewProp', row: row},
			function(data) {
			//	$('.iblock_id_select').unbind('change', GetProp);

				$('.ys_access_del_button').unbind('click', RemoveRow) ;
				$('.iblock_type_select').unbind('change', GetIBlocks);
				$('.iblock_id_select').unbind('change', GetProp);

				$(data).insertBefore('#last_row');

				$('.ys_access_del_button').click(RemoveRow) ;
				$('.iblock_type_select').change(GetIBlocks);
				$('.iblock_id_select').change(GetProp);
			});
		}
		
		function GetProp(){
			row = parseInt( $(this).attr('id').replace('PROPERTY[', '').replace('][IBLOCK_ID]', '') ) ;
			$.post(url, {bxsessid: bxsessid, IBLOCK_ID: $(this).val(), action: 'GetProps', row: row},
			function(data) {
				$('.iblock_id_select').unbind('change', GetProp);
				$('#iblock_prop_row'+row).remove() ;
				$(data).insertAfter('#iblock_id_row'+row);
				$('.iblock_id_select').change(GetProp);
			});
		}
		
		function RemoveRow(){
			row = parseInt( $(this).attr('id').replace('del_property', '') );
			$('.row'+row).remove() ;
		}
		$('#add_property').click(GetNewProp) ;
		$('.ys_access_del_button').click(RemoveRow) ;
		$('.iblock_type_select').change(GetIBlocks);
		$('.iblock_id_select').change(GetProp);

	});
</script>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin_js.php");