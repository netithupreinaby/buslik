<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if(!$arResult["FORM_NOTE"]){?>
<script src='https://www.google.com/recaptcha/api.js?hl=ru'></script>

<?=str_replace('<form','<form class="simple-form"',$arResult["FORM_HEADER"]);?>
<?if ($arResult["isFormErrors"] == "Y"):?>
<?$errors = strip_tags($arResult["FORM_ERRORS_TEXT"]);
$errors = explode(':',str_replace('&quot;','',$errors));
$errors = explode('&nbsp;&nbsp;&raquo;&nbsp;',$errors['1']);
$errors = array_filter($errors);
?>
<?endif;?>


		<form class="simple-form">
			<div class="row">
				<div class="col-md-5 col-sm-5 col-xs-12">
						<input type="hidden" name="ELEMENT_ID" value="<?=$_SESSION['review_id']?>">  
						<input type="hidden" name="real_customer_type" value="">

						<?
						foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion)
						{
							if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden')
							{
								echo $arQuestion["HTML_CODE"];
							}
							else
							{
								?>

								<?if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])):?>
								<span class="error-fld" title="<?=$arResult["FORM_ERRORS"][$FIELD_SID]?>"></span>
							<?endif;?>

								<?=$arQuestion["IS_INPUT_CAPTION_IMAGE"] == "Y" ? "<br />".$arQuestion["IMAGE"]["HTML_CODE"] : ""?>

								<?if(strstr($arQuestion["HTML_CODE"],'"inputtext"')){
								$placeholder = $arQuestion["CAPTION"];
                                    $classValidate=$reqSign='';
								if($arQuestion["REQUIRED"] == "Y"){
									$placeholder.= '*';
                                    $reqSign=' class="required-field" ';
                                    $classValidate = ' validate[required';

                                    if($arQuestion["STRUCTURE"][0]['FIELD_TYPE']=='email'){

                                        $classValidate.=',custom[email]';
                                    }

                                    $classValidate.=']';
								}
								$placeholder = 'placeholder="'.$placeholder.'"';
								$currentError = '';
								$errorDiv = '';

								foreach($errors as $error){

									if(substr_count($placeholder,trim($error))>0){

										$currentError = ' class="invalid"';
										$errorDiv = '<p class="notice">Обязательно для заполнения</p>';
										break;

									}

								}

								echo '<div class="form-group"><label'.$reqSign.'>'.$arQuestion['CAPTION'].'</label>'.str_replace('inputtext','form-control'.$classValidate,$arQuestion["HTML_CODE"]).'</div>';

							}elseif(strstr($arQuestion["HTML_CODE"],'select')){

								$placeholder = $arQuestion["CAPTION"];
                                if($arQuestion["REQUIRED"] == "Y"){
                                    $placeholder.= '*';
									$reqSign=' class="required-field" ';
                                    $classValidate = ' validate[required';

                                    if($arQuestion["STRUCTURE"][0]['FIELD_TYPE']=='email'){

                                        $classValidate.=',custom[email]';
                                    }

                                    $classValidate.=']';
                                }
								$arQuestion["HTML_CODE"] =  str_replace('inputselect','chosen-select',$arQuestion["HTML_CODE"]);
								$placeholder = 'data-placeholder="'.$placeholder.'"';
								echo '<div class="form-group"><label'.$reqSign.'>'.$arQuestion['CAPTION'].'</label>'.str_replace('<select','<select '.$placeholder,$arQuestion["HTML_CODE"]).'</div>';

							}elseif(strstr($arQuestion["HTML_CODE"],'textarea')){
								?>
									</div>
								</div>
								<?
								$placeholder = $arQuestion["CAPTION"];
                                $classValidate=$reqSign='';
                                if($arQuestion["REQUIRED"] == "Y"){
                                    $placeholder.= '*';
                                    $reqSign=' class="required-field" ';
                                    $classValidate = ' validate[required';

                                    if($arQuestion["STRUCTURE"][0]['FIELD_TYPE']=='email'){

                                        $classValidate.=',custom[email]';
                                    }

                                    $classValidate.=']';
                                }

								$textarea =  str_replace('<textarea','<textarea '.'class="form-control'.$classValidate.'" ',$arQuestion["HTML_CODE"]);
								$textarea = str_replace(array('rows="5"','cols="40"'),'',$textarea);
								echo '<div class="form-group"><label'.$reqSign.'>'.$arQuestion['CAPTION'].'</label>'.$textarea.'</div>';


							}else{

								echo $arQuestion["HTML_CODE"];

							}
							}
						} //endwhile
						?>
						
				<div class="form-group clearfix">
				<label for="input-3" class="control-label pull-left">Оценка</label>
				<div class="rating-wrapper pull-left">
					<select id="example-css" name="rating" autocomplete="off">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5" selected="selected">5</option>
					</select>
				</div>
			</div>		
			<div class="form-group">
				<div class="g-recaptcha" id="<?=$arResult['arForm']['SID']?>_GCAPTHA" data-sitekey="<?=RE_SITE_KEY?>"></div>
			</div>
			<input <?=(intval($arResult["F_RIGHT"]) < 10 ? "disabled=\"disabled\"" : "");?> type="submit" name="web_form_submit" value="<?=htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?>" class="btn btn-blue btn-white-color" />



	
<script>
    var initValidation = function(){

        $("[name=<?=$arResult['arForm']['SID']?>]").validationEngine();
		 $('#example-css').barrating({
            theme: 'css-stars',
            showSelectedRating: false
        });

		var config = {
			'.chosen-select': {},
			'.chosen-select-deselect': { allow_single_deselect: true },
			'.chosen-select-no-single': { disable_search_threshold: 10 },
			'.chosen-select-no-results': { no_results_text: 'Oops, nothing found!' },
			'.chosen-select-width': { width: "95%" }
		}
		$('[name=real_customer_type]').val($('#form_dropdown_SIMPLE_QUESTION_104 option:selected').html());

        $('#form_dropdown_SIMPLE_QUESTION_104').change(function(){

            $('[name=real_customer_type]').val($('#form_dropdown_SIMPLE_QUESTION_104 option:selected').html());

        })


		for (var selector in config) {
			$(selector).chosen(config[selector]);
		}


    }
    initValidation();

    if(!$("[name=<?=$arResult['arForm']['SID']?>] .rc-anchor").length){

        recaptchaSiteKey = '<?echo RE_SITE_KEY;?>';
        grecaptcha.render('<?=$arResult['arForm']['SID']?>_GCAPTHA', {
            sitekey: recaptchaSiteKey,
            callback: function(response) {
                console.log(response);
            }
        });

    }
</script>
<?=$arResult["FORM_FOOTER"]?>
<?}else{?>

    <?//echo$arResult["FORM_NOTE"]?>
	<p>Спасибо за отзыв! Он будет опубликован в ближайшее время, после проверки модератором.</p>
	
	
	<script>
	var initFormEnd = function(){
				
		$('.comment-form-wrap .wrap-form,.pull-left.add-comment-title').remove();
		
	}
	initFormEnd();
	</script>
<?}?>




