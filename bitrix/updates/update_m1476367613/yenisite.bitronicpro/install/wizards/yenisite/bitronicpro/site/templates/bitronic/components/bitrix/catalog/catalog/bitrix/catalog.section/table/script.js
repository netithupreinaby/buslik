$(document).ready(function() {
});

/**
* Add params to ADD_URL
*
* @param {Object} select object
*/
function onSelectChange(select) {
	
	var elemId = select.id.split('-')[1],
		flag = 0,
		sel = $(select);
		
	sel.next().find('span.selectBox-label').css('color', 'black');
	
	$('form').each(function() {
		if ( $(this).attr('name') === ('a2b' + elemId) ) {
			$(this).children('input').each(function() {
				if ( $(this).attr('name') == select.name ) {
					$(this).attr('value', select.value);
					flag = 1;
				}
			});
			
			if (!flag) {
				$(this).append('<input type="hidden" name="' +  select.name + '" value="' + select.value + '" />');
			}
		}
	});
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
							console.log( $(this).attr('name') + ' ' + propName );
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