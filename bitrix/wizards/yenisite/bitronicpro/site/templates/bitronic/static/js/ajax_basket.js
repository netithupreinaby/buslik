function yenisite_add2b_close(){
	$('#mask').fadeOut(300);
	$('#add_2b_popup').fadeOut(300);
}
$(document).ready(function(){
	$(document).keydown(function(eventObject){
		//27 - ASCII code of button 'ESC'
		if(eventObject.which==27)
		{
			yenisite_add2b_close();
		}
	});
	//ON CLICK OUTSIDE SETTINGS WINDOW
	var onADD2BPopup;
	$('#add_2b_popup').mouseover(function(){onADD2BPopup=true;});
	$('#add_2b_popup').mouseout(function(){onADD2BPopup=false;});

	$(document).on('click',function(){
		if(onADD2BPopup==false)
			yenisite_add2b_close();
	});
});

function yenisite_bs_flyObjectTo(what, to)
{
	//to=".cart_icon";
	if($(what).size()<1)
		what = 'img.yenisite-detail';
	if($(what).size()<1)
		what = '.ico_0 img';
	var pic = $(what).clone();
	//if(!pic.lenght) return;
	$('body').after(pic);
	
	pic.css({'z-index':'1000', 'position':'absolute', 'left': $(what).offset().left + 'px', 'top': $(what).offset().top + 'px'});
	
	to_left =  $(to).offset().left;
	to_top =  $(to).offset().top;
	
	pic.animate({
		width: "0",
		height: "0",
		//opacity: 0.4,
		left: to_left,
		top: to_top,
	}, 1000, function(){ pic.remove() ; } );
}





function ys_ajax_cb_buttons(){
	$('.compare_list a').unbind('click')
	$('.compare_list a').click(function(){
		if(!$(this).hasClass('button_in_compare'))
		{
			
			var iblock_id = $('#ajax_iblock_id').attr('value');
			var href = $(this).attr('href') ;
			var ob_post_params = JSON.parse('{"'+href.substr(href.indexOf('?')+1).split('&').join('","').split('=').join('":"')+'", "iblock_id":"'+iblock_id+'","sessid":"'+BX.message.bitrix_sessid+'"}');
			var element_id = ob_post_params.id;
			ob_post_params['SITE_ID'] = SITE_ID;
			
			var url = SITE_TEMPLATE_PATH+'/ajax/add2compare.php';
			$.post(url, ob_post_params, function(data) {
				//$('.result').html(data);
					yenisite_bs_flyObjectTo('#product_photo_'+element_id, '.basket-box');
				//yenisite_bs_flyObjectTo('#product_photo_'+element_id, '.basket-box');
				$('.basket-box').html(data);
				//$('
			});
		}
		return false;
	});
	//$(".basket-box").on("click", "form .compare a.compare_link", function () {
	$('.basket-box').off('click', 'a.ajax_remove_compare');
	$('.basket-box').on('click', 'a.ajax_remove_compare', function(){
		if(!$(this).hasClass('button_in_compare'))
		{
			var remove = $(this).hasClass('ajax_remove_compare');
			var iblock_id = $('#ajax_iblock_id').attr('value');
			var href = $(this).attr('href') ;
			var ob_post_params = JSON.parse('{"'+href.substr(href.indexOf('?')+1).split('&').join('","').split('=').join('":"')+'", "iblock_id":"'+iblock_id+'","sessid":"'+BX.message.bitrix_sessid+'","remove":"Y"}');
			var element_id = ob_post_params.id;
			ob_post_params['SITE_ID'] = SITE_ID;
			
			var url = SITE_TEMPLATE_PATH+'/ajax/add2compare.php';
			$.post(url, ob_post_params, function(data) {
				//$('.result').html(data);
				if(!remove)
					yenisite_bs_flyObjectTo('#product_photo_'+element_id, '.basket-box');
				//yenisite_bs_flyObjectTo('#product_photo_'+element_id, '.basket-box');
				$('.basket-box').html(data);
				//$('
			});
		}
		return false;
	});
	//var SITE_TEMPLATE_PATH;
	$('.ajax_add2basket').unbind('click')
	$('.ajax_add2basket').click(function(){
		if(!$(this).hasClass('button_in_basket'))
		{
			var element_id = $(this).attr('id').replace('b-','');
			var sku_id = $(this).attr('data-sku-id');
			var href = $(this).attr('href') ;
			var iblock_id = $('#ajax_iblock_id').attr('value');
			//var get_params = href.substr(href.indexOf('?')+1).split('&');
			var action_add2b = $('#action_add2b').attr('value') ;
			
			var ob_post_params = JSON.parse('{"'+href.substr(href.indexOf('?')+1).split('&').join('","').split('=').join('":"')+'", "iblock_id":"'+iblock_id+'","sessid":"'+BX.message.bitrix_sessid+'", "action_add2b":"'+action_add2b+'"}');
			
			// SERVICES
			if($('#options-form input[type="checkbox"]:checked').size()>0)
				$('#options-form input[type="checkbox"]:checked').each(function(){
					ob_post_params['SERVICE['+$(this).val()+']'] = $('input[name="servicePrice_'+$(this).val()+'"]').val();
				});
			if(sku_id > 0)
			{
				if('string' == typeof ob_post_params['id[0]'])
					ob_post_params['id[0]'] = sku_id;	// for sets buy
				else	
					ob_post_params.id = sku_id;
			}

			if ($('#ajax_iblock_id_sku').length && sku_id > 0)
			{
				ob_post_params['iblock_id_sku'] = $('#ajax_iblock_id_sku').attr('value');
			}
			ob_post_params['SITE_ID'] = SITE_ID;

			//JSON.parse(get_params)
			//replace('&', ',').replace('=',':')+'}' ;
			var url = SITE_TEMPLATE_PATH+'/ajax/add2basket.php';
			$.post(url, ob_post_params, function(data) {
				//$('.result').html(data);
				var pic_src = $('#product_photo_'+element_id).attr('src');
				if($('#action_add2b').attr('value') == 'popup_window')
				{
					// add2bpopup
					var arData = data.split('<!-- add2basket -->');
					//yenisite_bs_flyObjectTo('#product_photo_'+element_id, '.yen-bs-box');
					if (arData[0].indexOf('<div class="yen-bs-count yen-bs-up">') != -1) {
						$('.yen-bs-box').html(arData[0]);
					}
					$('#add_2b_popup').html(arData[1]);
					$('#add_2b_popup').fadeIn('300');
					$('#mask').fadeIn('300');
					// end add2bpopup
				}
				else
				{
					if (data.indexOf('<!-- errors -->') != -1) {
						var arData = data.split('<!-- errors -->');
						jGrowl(arData[1], 'error');
						data = arData[0];
					}
					else if (data.indexOf('<div class="yen-bs-count yen-bs-up">') != -1) {
						yenisite_bs_flyObjectTo('#product_photo_'+element_id, '.yen-bs-box');
						$('.yen-bs-box').html(data);
					}
				}
			});
		}
		return false;
	});
	
	$('.ajax_add2basket_q').unbind('click')
	$('.ajax_add2basket_q').click(function(){
		if(!$(this).hasClass('button_in_basket'))
		{
			var element_id = $(this).attr('id').replace('b-','');
			// var href = $(this).attr('href') ;
			var action_add2b = $('#action_add2b').attr('value') ;
			var iblock_id = $('#ajax_iblock_id').attr('value');
			var iblock_id_sku = 0;
			if ($('#ajax_iblock_id_sku').length)
			{
				iblock_id_sku = $('#ajax_iblock_id_sku').attr('value');
			}

			//var get_params = href.substr(href.indexOf('?')+1).split('&');
			//var ob_post_params = JSON.parse('{"'+href.substr(href.indexOf('?')+1).split('&').join('","').split('=').join('":"')+'", "iblock_id":"'+iblock_id+'","sessid":"'+BX.message.bitrix_sessid+'"}');
			//JSON.parse(get_params)
			//replace('&', ',').replace('=',':')+'}' ;
			if($('#a2b'+element_id).serialize())
			{
				var req_params = $('#a2b'+element_id).serialize()+'&iblock_id='+iblock_id+'&iblock_id_sku='+iblock_id_sku+'&sessid='+BX.message.bitrix_sessid+'&action_add2b='+action_add2b;
				
				// SERVICES
				if($('#options-form input[type="checkbox"]:checked').size()>0)
					$('#options-form input[type="checkbox"]:checked').each(function(){
						req_params += '&SERVICE['+$(this).val()+']='+$('input[name="servicePrice_'+$(this).val()+'"]').val();
					});
				req_params['SITE_ID'] = SITE_ID;
				
				var url = SITE_TEMPLATE_PATH+'/ajax/add2basket.php';
				$.post(url, req_params, function(data) {
					var pic_src = $('#product_photo_'+element_id).attr('src');
					if($('#action_add2b').attr('value') == 'popup_window')
					{
						// add2bpopup
						var arData = data.split('<!-- add2basket -->');
						//yenisite_bs_flyObjectTo('#product_photo_'+element_id, '.yen-bs-box');
						if (arData[0].indexOf('<div class="yen-bs-count yen-bs-up">') != -1) {
							$('.yen-bs-box').html(arData[0]);
						}
						$('#add_2b_popup').html(arData[1]);
						$('#add_2b_popup').fadeIn('300');
						$('#mask').fadeIn('300');
						// end add2bpopup
					}
					else
					{
						if (data.indexOf('<!-- errors -->') != -1) {
							var arData = data.split('<!-- errors -->');
							jGrowl(arData[1], 'error');
							data = arData[0];
						}
						else if (data.indexOf('<div class="yen-bs-count yen-bs-up">') != -1) {
							if(!$('#b-'+element_id).hasClass('sku_button'))
							{
								yenisite_bs_flyObjectTo('#product_photo_'+element_id, '.yen-bs-box');
							}
							$('.yen-bs-box').html(data);
						}
					}
				});
			}
			else
			{
				$('#a2b'+element_id).submit();
			}
		}
			return false;
		});
		
	// for button in catalog view = TABLE
	$('.ajax_add2basket_t').unbind('click')
	$('.ajax_add2basket_t').click(function(){
		if(!$(this).hasClass('button_in_basket'))
		{
			
			var req_params = {};
			$('[name="quantity"]').each(function(){
				if(Number($(this).val())>0)
				{
					var id = $(this).parent().find('input[name="id"]').val();
					var quantity = Number($(this).val());
						
					req_params["id["+id+"]"] = id;
					req_params["quantity["+id+"]"] = quantity;
					
					//need for buy with props
					$(this).closest('tr').find('.ye-props select').each(function(){
						req_params[$(this).attr('name').replace('[', id+'[')] = $(this).val();
					});
				}
			});
			var action_add2b = "popup_basket";
			var iblock_id = $('#ajax_iblock_id').attr('value');
			var iblock_id_sku = 0;
			if ($('#ajax_iblock_id_sku').length)
			{
				iblock_id_sku = $('#ajax_iblock_id_sku').attr('value');
			}
			req_params.action = "ADD2BASKET";
			req_params.iblock_id = iblock_id;
			req_params.iblock_id_sku = iblock_id_sku;
			req_params.sessid = BX.message.bitrix_sessid;
			req_params.action_add2b = action_add2b;
			req_params.SITE_ID = SITE_ID;
			var url = SITE_TEMPLATE_PATH+'/ajax/add2basket.php';
			
			$.post(url, req_params, function(data) {
				if (data.indexOf('<!-- errors -->') != -1) {
					var arData = data.split('<!-- errors -->');
					jGrowl(arData[1], 'error');
					data = arData[0];
				}
				if (data.indexOf('<div class="yen-bs-count yen-bs-up">') != -1) {
					$('.yen-bs-box').html(data);
				}
				$('[name="quantity"]').val(0);
				checkQuantityTable(); // set in bitrix\templates\bitronic_XXX\static\js\system_script.js
			});
		}
		return false;
	});
	
	
	// DUBLICATE OF this handler(without .aprof-time2buy) there is in yenisite:main_spec/templates/.default/script.js
	// i don't not why
	// for main page
	$('.aprof-time2buy').off('click', '.ajax_add2basket_main');
	$('.aprof-time2buy').on('click', '.ajax_add2basket_main', function(){
		if(!$(this).hasClass('button_in_basket'))
		{
			var element_id = $(this).attr('id').replace('mb-','');
			var href = $(this).attr('href') ;
			var iblock_id = $('#ajax_iblock_id_'+element_id).attr('value');
			//var get_params = href.substr(href.indexOf('?')+1).split('&');
			var action_add2b = $('#action_add2b').attr('value') ;
			
			var ob_post_params = JSON.parse('{"'+href.substr(href.indexOf('?')+1).split('&').join('","').split('=').join('":"')+'", "iblock_id":"'+iblock_id+'","sessid":"'+BX.message.bitrix_sessid+'", "action_add2b":"'+action_add2b+'", "main_page":"Y"}');
			ob_post_params['SITE_ID'] = SITE_ID;
			//JSON.parse(get_params)
			//replace('&', ',').replace('=',':')+'}' ;
			var url = SITE_TEMPLATE_PATH+'/ajax/add2basket.php';
			$.post(url, ob_post_params, function(data) {
				//$('.result').html(data);
				var pic_src = $('#product_photo_'+element_id).attr('src');
				if($('#action_add2b').attr('value') == 'popup_window')
				{
					// add2bpopup
					var arData = data.split('<!-- add2basket -->');
					//yenisite_bs_flyObjectTo('#product_photo_'+element_id, '.yen-bs-box');
					if (arData[0].indexOf('<div class="yen-bs-count yen-bs-up">') != -1) {
						$('.yen-bs-box').html(arData[0]);
					}
					$('#add_2b_popup').html(arData[1]);
					$('#add_2b_popup').fadeIn('300');
					$('#mask').fadeIn('300');
					// end add2bpopup
				}
				else
				{
					if (data.indexOf('<!-- errors -->') != -1) {
						var arData = data.split('<!-- errors -->');
						jGrowl(arData[1], 'error');
						data = arData[0];
					}
					else if (data.indexOf('<div class="yen-bs-count yen-bs-up">') != -1) {
						yenisite_bs_flyObjectTo('#product_photo_'+element_id, '.yen-bs-box');
						$('.yen-bs-box').html(data);
					}
				}
			});
		}
		return false;
	});	
}

$(document).ready(ys_ajax_cb_buttons);
$(document).ajaxComplete(ys_ajax_cb_buttons);
// for composite technology
BX.addCustomEvent("onFrameDataReceived", ys_ajax_cb_buttons);