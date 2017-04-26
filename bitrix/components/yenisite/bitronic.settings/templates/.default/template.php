<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(method_exists($this, 'createFrame')) $this->createFrame()->begin('');?>

<!--<link type="text/css" href="/yenisite.resizer2/js/ui/css/ui-lightness/jquery-ui-1.8.14.custom.css?10200" rel="stylesheet" />-->
<?$APPLICATION->SetAdditionalCSS("/yenisite.resizer2/js/colorpicker/css/colorpicker.css");?>
<?$APPLICATION->SetAdditionalCSS("/yenisite.resizer2/js/colorpicker/css/layout.css");?>
<?$APPLICATION->AddHeadScript("/yenisite.resizer2/js/colorpicker/js/colorpicker.js");?>

<? global $ys_options; ?>
<?$APPLICATION->SetAdditionalCSS("/bitrix/components/yenisite/bitronic.settings/templates/.default/".$ys_options["color_scheme"].".css");?>

<?if(count($arResult["SETTINGS"]) == 0) return;?>

<? $sef = COption::GetOptionString(CYSBitronicSettings::getModuleId(), "sef_mode", false, SITE_ID); ?>
<?if ($sef == 'Y'):?>
    <input type="hidden" name='ys-sef-mode' value="Y" />
<?endif?>
<?foreach ($arResult['types'] as $id => $type):?>
    <?foreach ($type as $iblock):?>
        <span class="ys-iblock" data-type="<?=$id?>" data-iblock="<?=$iblock?>" style="display: none;"></span>
    <?endforeach?>
<?endforeach?>
<script type="text/javascript">

    function getOptionByName(name)
    {
        var re =  /\[(\w+)\]/g
        var result = name.match(re);
        var option = result[0];
        option = option.substr(1, option.length - 2);

        return option;
    }

    $(document).ready(
        function(){
//ColorPicker
            $('input[name="SETTINGS[BACKGROUND_COLOR]"]').ColorPicker({
                onSubmit: function(hsb, hex, rgb, el) {
                    $(el).val('#' + hex);
                    $(el).ColorPickerHide();
                },
                onBeforeShow: function () {
                    $(this).ColorPickerSetColor(this.value);
                },
                onChange: function(hsb, hex, rgb) {
                    $('input[name="SETTINGS[BACKGROUND_COLOR]"]').val('#' + hex);
                    $('select[name="SETTINGS[BACKGROUND_IMAGE]"]').selectBox('value', '');
                    $('div.texture_selected').removeClass('texture_selected');
                }
            })
                .bind('keyup', function(){
                    $(this).ColorPickerSetColor(this.value);
                });
            //ColorPicker
            $('input[name="SETTINGS[WINDOW_COLOR]"]').ColorPicker({
                onSubmit: function(hsb, hex, rgb, el) {
                    $(el).val('#' + hex);
                    $(el).ColorPickerHide();
                },
                onBeforeShow: function () {
                    $(this).ColorPickerSetColor(this.value);
                },
                onChange: function(hsb, hex, rgb) {
                    $('input[name="SETTINGS[WINDOW_COLOR]"]').val('#' + hex);
                }
            })
                .bind('keyup', function(){
                    $(this).ColorPickerSetColor(this.value);
                });
				
		$(".selectBox").selectBox();
		$("body").append($("#ys-bitronic-settings"));
		$("#ys-a-settings").click(function(){
			if($("#ys-bitronic-settings").css("bottom") != "-10px")
				$("#ys-bitronic-settings").animate({bottom: '-10px'});
			else
				$("#ys-bitronic-settings").animate({bottom: '-780px'});
		});

            $('#bitronic_reset').click(function(){
                var defaultSettings = <?=CYSBitronicSettings::getAllDefaultSettingsOptionsAsJSON();?>;
                
				$('div#ys-bitronic-settings-body select.selectBox').each(function(index, value){
                    var name = $(value).attr('name');
                    //var child = 2;
                    if (name !== undefined)
                    {
                        var option = getOptionByName(name);
                        $(value).selectBox('value', defaultSettings[option]);

                        //OLD STUFF!
                        //$('select[name="SETTINGS['+option+']"]').val(defaultSettings[option]).attr('selected', 'selected');
                        //var text = $('select[name="SETTINGS['+option+']"]').find(":selected").text();
                        //$('select[name="' + name + '"]').parent().find('a span.selectBox-label').html(text);
                    }
                });

                $('div#ys-bitronic-settings-body input[type=checkbox]').attr('checked', false).parent().attr('class', '');

                
                $('div#ys-bitronic-settings-body input[type=text]').each(function(index, value){
                    var name = $(value).attr('name');
                    var option = getOptionByName(name);
                    $(value).val(defaultSettings[option]);
                });

                var min_max_min = $('input[name="SETTINGS[MIN_MAX_MIN]"]').val();
                var min_max_max = $('input[name="SETTINGS[MIN_MAX_MAX]"]').val();
                var opacity = $('input[name="SETTINGS[WINDOW_OPACITY]"]').val();

                $('span#MIN_MAX_MIN').html(parseInt(min_max_min) + 20);
                $('span#MIN_MAX_MAX').html(parseInt(min_max_max) + 20);
                $('span#OPACITY_TEXT').html(parseInt(opacity));

                $('#slider-resolution').slider("values", [resolutions.indexOf(parseInt(min_max_min)), resolutions.indexOf(parseInt(min_max_max))]);
                $('#slider-opacity').slider("value", parseInt(opacity));

                $('div.texture_selected').removeClass('texture_selected');
            });

            var min_max_min = $('input[name="SETTINGS[MIN_MAX_MIN]"]').val();
            var min_max_max = $('input[name="SETTINGS[MIN_MAX_MAX]"]').val();
            var resolutions = [780, 1004, 1260, 1346, 1420, 1660];
            $('#slider-resolution').slider({
                range: true,
                min: 0,
                max: resolutions.length - 1,
                values: [resolutions.indexOf(parseInt(min_max_min)), resolutions.indexOf(parseInt(min_max_max))],
                slide: function(event, ui) {
                    $('input[name="SETTINGS[MIN_MAX_MIN]"]').val(resolutions[ui.values[0]]);
                    $('input[name="SETTINGS[MIN_MAX_MAX]"]').val(resolutions[ui.values[1]]);

                    $('span#MIN_MAX_MIN').html(resolutions[ui.values[0]] + 20);
                    $('span#MIN_MAX_MAX').html(resolutions[ui.values[1]] + 20);
                }
            });

            $('#slider-opacity').slider({
                range: "min",
                min: 0.1,
                max: 1,
                step: 0.1,
                value: $('input[name="SETTINGS[WINDOW_OPACITY]"]').val(),
                slide: function(event, ui) {
                    $('span#OPACITY_TEXT').html(ui.value);
                    $('input[name="SETTINGS[WINDOW_OPACITY]"]').val(ui.value);
                }
            });

            $('select[name="SETTINGS[BACKGROUND_IMAGE]"]').change(function(){
                $('div.texture').removeClass('texture_selected');
                $('input[name="SETTINGS[BACKGROUND_REPEAT]"]').attr('checked', false);
            });
            
			$('div.texture').click(function(){
                var texture = $(this).attr('rel');
                $('select[name="SETTINGS[BACKGROUND_IMAGE]"]').selectBox('value', '');
                $('select[name="SETTINGS[BACKGROUND_IMAGE]"]').append("<option value=" + texture + ">" + texture + "</option>");
                $('select[name="SETTINGS[BACKGROUND_IMAGE]"] option:last').attr('selected', 'selected');

                $('div.texture').removeClass('texture_selected');
                $(this).addClass('texture_selected');
                $('input[name="SETTINGS[BACKGROUND_REPEAT]"]').attr('checked', true);
            });

           var tex = $('div.texture_selected').attr('rel');
            if (tex !== undefined)
            {
                $('select[name="SETTINGS[BACKGROUND_IMAGE]"]').append("<option value=" + tex + ">" + tex + "</option>");
                $('select[name="SETTINGS[BACKGROUND_IMAGE]"] option:last').attr('selected', 'selected');

                $('input[name="SETTINGS[BACKGROUND_REPEAT]"]').attr('checked', true);
            }
        }
    );
</script>

<div id="ys-bitronic-settings">
    <div id="ys-bitronic-settings-body">
        <a class="yen-settings-close" onclick="yenisite_settings_close()">&#205;</a>
        <form method="POST" action="/">
            <input type="hidden" value="Y" name="bitronic_settings_apply" />
			<input type="hidden" value="<?=$GLOBALS['APPLICATION']->GetCurPageParam('',array('bxrand'));?>" name="burl" />
			
			<div class="margerine">
                <?foreach($arResult["SETTINGS"] as $k => $v):?>
                    <div class="set-item <?if($v["MINI"] == "Y"):?>mini-item<?endif;?>">
                        <?if($k == "SET_DEFAULT"):?>
                            <br/><label><?=$v["INPUT"]?> <?=$v["NAME"]?></label>
                        <?else:?>
                            <b><?=$v["NAME"]?></b>:<br/><?=$v["INPUT"]?>
                        <?endif?>
                    </div>
                <?endforeach?>

            </div>
            <br/>
			<div class="set-but">
                <input class="button" type="button" value="<?=GetMessage("RESET")?>" name="bitronic_reset" id="bitronic_reset"><input class="button" type="button" value="<?=GetMessage("APPLY")?>" name="bitronic_ok"></div>
        </form>
    </div>
    <div id="ys-bitronic-settings-head"><a id="ys-a-settings" href="javascript:void(0);"><span><?=getMessage('YS_SETTINGS');?></span></a></div>
    <div id="ys-bitronic-settings-white">
    </div>

</div>