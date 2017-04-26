$(document).ready(function() {
	
	var openedMenu,
		oHref = $('.item-popup > div.sl_info > a.button2'),
		href,
		i;
		
	oHref.each(function() {
		href = $(this).attr('href');
		
		try {
			href = decodeURI(href);
		} catch(e) {
			
		}
		
		params = href.split('&');
		
		for (i = 0; i < params.length; i += 1) {
			
			$(this).prev().find('select').each(function() {
				if ( params[i].indexOf( $(this).attr('name') ) != -1 ) {
					params.splice(i, 1);
					i--;
					// params[i] = '';
				}
			});
		}
		
		href = params.join('&');

		$(this).attr('href', href);
	});
	
	$('.catalog-list li').hover(function() {
		$(this).find('.item-popup').show();
		$(this).css({'z-index': 50});
		
		$(this).find('.item-popup').addClass('item-hover');
		
	}, function() {
		openedMenu = $(this).find('.selectBox-menuShowing');
		
		if ( openedMenu.length != 1 ) {

			$(this).find('.item-popup').fadeOut();
			$(this).css({'z-index': 1});
			
			$(this).find('.item-popup').removeClass('item-hover');
			
		} else {
		
		}
	});
});

/**
* Add params to ADD_URL
*
* @param {Object} select object
*/
// add new function, modify by Ivan, 09.10.2013 ---->
function onRadioPropChange(radio){
	var oHref = $(radio).closest('div.ye-props').next(),
		href,
		params,
		i = 0,
		flag = 0,
		val = radio.name + '=' + radio.value;
		
	// $('.item-hover .ye-props radio + a > span.selectBox-label').css('color', 'black');
		
	if (oHref.attr('href') === 'javascript:void(0);') {
		oHref.attr('href', oHref.attr('rev'));
	}
		
	href = oHref.attr('href');

	href = decodeURI(href);
	params = href.split('&');
	
	for (; i < params.length; i += 1) {
		if (params[i].indexOf(radio.name) != -1) {
			if (radio.value == 0) {
				params.splice(i, 1);
			} else {
				params[i] = val;
				flag = 1;
			}
		}
	}
	
	if (radio.value != 0 && !flag) {
		params.push(val);
	}

	href = params.join('&');

	oHref.attr('href', href);

	oHref.removeClass('button_in_basket');
	var container = $(radio).closest('div.ye-props');
	container.find('select').each(function(){
		if(this.value == 0 ){
			oHref.addClass('button_in_basket');
			return false;
		}
	});
	var radioCollection = container.find('input[type="radio"]');
	var radioTotal = 0, radioChecked = 0;
	var prevRadio = $();
	for (i = 0; i < radioCollection.length; i++) {
		var radio = radioCollection.eq(i);
		if (radio.attr('name') != prevRadio.attr('name')) radioTotal++;
		if (radio.is(':checked')) radioChecked++;
		prevRadio = radio;
	}
	if (radioChecked < radioTotal) {
		oHref.addClass('button_in_basket');
	}
}
// <---- end add modify
function onSelectChange(select) {	
	var oHref = $(select).closest('div.ye-props').next(),
		href,
		params,
		i = 0,
		flag = 0,
		val = select.name + '=' + select.value;

	if (!oHref.hasClass('button2')) oHref = $(select).closest('li').find('.button1');
		
	$('.item-hover .ye-props select + a > span.selectBox-label').css('color', 'black');
		
	if (oHref.attr('href') === 'javascript:void(0);') {
		oHref.attr('href', oHref.attr('rev'));
	}
		
	href = oHref.attr('href');

	href = decodeURI(href);
	params = href.split('&');
	
	for (; i < params.length; i += 1) {
		if (params[i].indexOf(select.name) != -1) {
			if (select.value == 0) {
				params.splice(i, 1);
			} else {
				params[i] = val;
				flag = 1;
			}
		}
	}
	
	if (select.value != 0 && !flag) {
		params.push(val);
	}

	href = params.join('&');

	oHref.attr('href', href);
	
	oHref.removeClass('button_in_basket');
	var container = $(select).closest('div.ye-props');
	container.find('select').each(function(){
		if(this.value == 0 ){
			oHref.addClass('button_in_basket');
			return false;
		}
	});
	var radioCollection = container.find('input[type="radio"]');
	var radioTotal = 0, radioChecked = 0;
	var prevRadio = $();
	for (i = 0; i < radioCollection.length; i++) {
		var radio = radioCollection.eq(i);
		if (radio.attr('name') != prevRadio.attr('name')) radioTotal++;
		if (radio.is(':checked')) radioChecked++;
		prevRadio = radio;
	}
	if (radioChecked < radioTotal) {
		oHref.addClass('button_in_basket');
	}
}

/**
* Validate params
*
*/
function onClick2Cart() {
	var oHref = $('.item-hover > div.sl_info > a.button2'),
		href = oHref.attr('href'),
		i = 0,
		tmp;
	
	if ( ( href.split('prop').length - 1 ) != $('.item-hover select').length ) {
		if (oHref.attr('href') !== 'javascript:void(0);') {
			oHref.attr('rev', href);
			oHref.attr('href', 'javascript:void(0);');
			
			$('.item-hover .ye-props select + a > span.selectBox-label').css('color', 'red');
			
			href = decodeURI(href);
			params = href.split('&');
			
			for (; i < params.length; i += 1) {
				tmp = params[i].split('=');
				
				$('.item-hover select').each(function() {
					if ( $(this).attr('name') === tmp[0] ) {
						$(this).next().children().eq(0).css('color', 'black');
					}
				});
			}
		}
	}
}