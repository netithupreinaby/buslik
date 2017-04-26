<?if(function_exists('yenisite_GetCompositeLoader')){global $MESS;$MESS ['COMPOSITE_LOADING'] = yenisite_GetCompositeLoader();}?>

<?include $_SERVER["DOCUMENT_ROOT"].SITE_TEMPLATE_PATH.'/include/cache_time_debug.php';?>

<?if(method_exists($this, 'createFrame')) $this->createFrame()->begin(GetMessage('COMPOSITE_LOADING'));?>
<script type="text/javascript">
reformal_wdg_domain    = "<?=$arParams['DOMEN']?>";
reformal_wdg_title   = "<?=$arParams['HEADER_TEXT']?>";
reformal_wdg_color   = "<?=$arParams["BUTTON_COLOR"]?>";
reformal_wdg_align   = "<?=$arParams["BUTTON_ALIGN"]?>";
reformal_wdg_charset = "<?=SITE_CHARSET?>";
reformal_wdg_waction = <?=$arResult['NEW_WINDOW']?>;
reformal_wdg_bimage = "d3a23dfb2430b39c61dcea7eaa91ee04.png";

<? if (($arParams['PARAMETR'] == 'SETUP_VIDJET')||($arParams['PARAMETR'] == 'NEW_WINDOW' )):?>

reformal_wdg_w    = "<?=$arParams['WIDTH']?>";
reformal_wdg_h    = "<?=$arParams['HEIGHT']?>";
reformal_wdg_mode    = 0;
reformal_wdg_ltitle  = "";
reformal_wdg_lfont   = "";
reformal_wdg_lsize   = "";
reformal_wdg_bcolor  = "<?=$arParams['HEADER_BG_COLOR']?>";
reformal_wdg_tcolor  = "<?=$arParams['HEADER_TEXT_COLOR']?>";
reformal_wdg_vcolor  = "<?=$arParams['VOTE_TEXT_COLOR']?>";
reformal_wdg_cmline  = "<?=$arParams['DOP_COLOR']?>";
reformal_wdg_glcolor  = "<?=$arParams['BODY_LINK_COLOR']?>";
reformal_wdg_tbcolor  = "<?=$arParams["BODY_BG_COLOR"]?>";
reformal_wdg_tcolor_aw4  = "<?=$arParams['BODY_TEXT_COLOR']?>";
 

</script>
<script type="text/javascript" language="JavaScript" src="http://reformal.ru/tabn2v4.js?charset=<?=SITE_CHARSET?>"></script>
<? endif?>

<? if ($arParams['PARAMETR'] == 'CLASSIC_VIDJET' ):?>


reformal_wdg_mode    = 0;
reformal_wdg_ltitle  = "";
reformal_wdg_lfont   = "";
reformal_wdg_lsize   = "";
reformal_wdg_bcolor  = "#516683";
reformal_wdg_tcolor  = "#FFFFFF";
reformal_wdg_vcolor  = "#9FCE54";
reformal_wdg_cmline  = "#E0E0E0";
reformal_wdg_glcolor  = "#105895";
reformal_wdg_tbcolor  = "#FFFFFF";
 


</script>
<script type="text/javascript" language="JavaScript" src="http://reformal.ru/tab6.js?charset=<?=SITE_CHARSET?>"></script>
<? endif?>