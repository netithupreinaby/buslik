$(document).ready(function() {
	
	var oHref = $('.item_links > a.button2'),
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
});

/**
* Add params to ADD_URL
*
* @param {Object} select object
*/
function onSelectChange(select) {	
	var oHref = $(select).parent().parent().next(),
		href,
		params,
		i = 0,
		flag = 0,
		val = select.name + '=' + select.value;
	
	$(select).next().find('span.selectBox-label').css('color', 'black');
		
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
}

/**
* Validate params
*
*/
function onClick2Cart(target) {
	var a = $(target),
		href = a.attr('href'),
		i = 0,
		tmp;
	
	if ( ( href.split('prop').length - 1 ) != a.prev().find('select').length ) {
		if (a.attr('href') !== 'javascript:void(0);') {
			a.attr('rev', href);
			a.attr('href', 'javascript:void(0);');
			
			a.prev().find('span.selectBox-label').css('color', 'red');
			
			href = decodeURI(href);
			params = href.split('&');
			
			for (; i < params.length; i += 1) {
				tmp = params[i].split('=');
				
				a.prev().find('select').each(function() {
					if ( $(this).attr('name') === tmp[0] ) {
						$(this).next().children().eq(0).css('color', 'black');
					}
				});
			}
		}
	}
}