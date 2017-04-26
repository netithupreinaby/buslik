<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'setFrameMode')) $this->setFrameMode(true);?>
<?
if (!isset($arParams['SHOW_DELAY_DETAIL'])) $arParams['SHOW_DELAY_DETAIL'] = '300';
if (!isset($arParams['HIDE_DELAY_DETAIL'])) $arParams['HIDE_DELAY_DETAIL'] = '600';
if (!isset($arParams['OUTLINE_TYPE'])) $arParams['OUTLINE_TYPE'] = '';
if (!isset($arParams['DIMMING_OPACITY'])) $arParams['DIMMING_OPACITY'] = '0.5';
if (empty($arParams['TRANSITIONS_APPEARANCE'])) $arParams['TRANSITIONS_APPEARANCE'] = 'expand';
if (empty($arParams['TRANSITIONS_CHANGE'])) $arParams['TRANSITIONS_CHANGE'] = 'expand';
if (empty($arParams['NUMBER_POSITION'])) $arParams['NUMBER_POSITION'] = 'caption';
if (!isset($arParams['SLIDE_INTERVAL'])) $arParams['SLIDE_INTERVAL'] = '5000';
if (empty($arParams['SLIDE_REPEAT'])) $arParams['SLIDE_REPEAT'] = 'false';
if (!isset($arParams['CONTROL_OPACITY'])) $arParams['CONTROL_OPACITY'] = '1';
if (empty($arParams['CONTROL_POSITION'])) $arParams['CONTROL_POSITION'] = 'bottom center';
if (empty($arParams['CONTROL_MOUSE'])) $arParams['CONTROL_MOUSE'] = 'false';
if (empty($arParams['WRAPPER_CLASS_NAME'])) $arParams['WRAPPER_CLASS_NAME'] = 'dark';
?>

<script type="text/javascript">
    var galleryOptions = {

	slideshowGroup: 'gallery',
	wrapperClassName: '<?=$arParams["WRAPPER_CLASS_NAME"];?>',
	outlineType: '<?=$arParams["OUTLINE_TYPE"];?>',
	dimmingOpacity: <?=$arParams["DIMMING_OPACITY"];?>,
	align: 'center',
	transitions: ['<?=$arParams["TRANSITIONS_APPEARANCE"];?>', '<?=$arParams["TRANSITIONS_CHANGE"];?>'],
	fadeInOut: true,
	
	//captionEval: "<?=$arParams["CAPTION_EVAL"];?>"
	numberPosition: '<?=$arParams["NUMBER_POSITION"];?>'

};
// ��������� ���� ��������� (�����������)

if (hs.addSlideshow) hs.addSlideshow({
		interval: <?=$arParams["SLIDE_INTERVAL"];?>,         // �������� ���� � ���������
        repeat: <?=$arParams["SLIDE_REPEAT"];?>,                        // ���������/�� ���������
        useControls: true,                    // ������������ �� ������ ���������?
		overlayOptions: {
            opacity: <?=$arParams["CONTROL_OPACITY"];?>,     // ������������ ������ ���������
            position: '<?=$arParams["CONTROL_POSITION"];?>', // ������� ������ ���������
            hideOnMouseOut: <?=$arParams["CONTROL_MOUSE"];?> // ������ ���������, ���� ������ ������ ���� �� ������
		}

});

    function loader()
    {
        if($(".yedetail").attr("src") != $(".yebuff").attr("src"))
        {          
            $(".yedetail").animate(
                {opacity: "hide"},
                <?=$arParams["HIDE_DELAY_DETAIL"];?>,
                function(){
                    $(".yedetail").attr("src", $(".yebuff").attr("src"));
                    $(".yedetail").animate(
                        {opacity: "show"},
                    <?=$arParams["SHOW_DELAY_DETAIL"];?>);
                }
            );
            
        }

    }
</script>


<ul id="gallery">
<?
//if(empty($arParams['SET_DETAIL'])) $arParams['SET_DETAIL'] = 2;
if(empty($arParams['SET_BIG_DETAIL'])) $arParams['SET_BIG_DETAIL'] = 1;
if(empty($arParams['SET_SMALL_DETAIL'])) $arParams['SET_SMALL_DETAIL'] = 6;
CModule::IncludeModule('yenisite.resizer2');
	$i=0;
	if(count($arResult["PATH"]) >0):
		foreach($arResult["PATH"] as $value):
			$path = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_SMALL_DETAIL']);
			//$pathb = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_DETAIL']);
			$pathbb = CResizer2Resize::ResizeGD2($arResult['PATH'][$i], $arParams['SET_BIG_DETAIL']);
			$i++;							
?>
	<li><a class="yenisite-zoom" rel="tour" onclick="return hs.expand(this, galleryOptions)" href="<?=CFile::GetPath($pathbb)?>" title="<?=$arResult["DESCRIPTION"][$i-1]?>"><img src="<?=CFile::GetPath($path)?>" alt="<?=$arResult["DESCRIPTION"][$i-1]?>" /></a></li>
<?
		endforeach;
	endif;		
?>
</ul>

