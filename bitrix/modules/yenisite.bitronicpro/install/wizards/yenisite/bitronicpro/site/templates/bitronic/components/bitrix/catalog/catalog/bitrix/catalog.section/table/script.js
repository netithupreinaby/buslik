$(document).ready(function() {	
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
function onClick2Cart(target) {
	var button = $(target),
		elemId = target.id.split('-')[1],
		counter = 0,
		trProps = button.parent().parent().next(),
		selectNum = trProps.find('select').length,
		propName,
		flag = 0;
	
	// Если количество selectов равно 0, то return true;
	if (selectNum == 0) {
		return true;
	}
	
	$('form').each(function() {
		if ( $(this).attr('name') === ('a2b' + elemId) ) {
			trProps.find('select + a > span.selectBox-label').css('color', 'red');
		
			$(this).children('input').each(function() {
				if ( $(this).attr('name').indexOf('prop') != -1 && $(this).attr('value') != '0' ) {
					propName = $(this).attr('name');
					counter++;
					trProps.find('select').each(function() {
						if ( $(this).attr('name') == propName ) {
							$(this).next().find('span.selectBox-label').css('color', 'black');
						}
					});
				}
			});
			
			if (counter == selectNum) {
				flag = 1;
				return ;
			}
		}
	});
	
	if (flag) {
		return true;
	}
	
	return false;
}