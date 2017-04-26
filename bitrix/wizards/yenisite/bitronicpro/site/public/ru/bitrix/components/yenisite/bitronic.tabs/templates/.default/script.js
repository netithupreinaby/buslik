var a = [];
a['NEW'] = 1;
a['HIT'] = 2;
a['SALE'] = 3;
a['BESTSELLER'] = 4;

$(document).ready(function() {
	$('.sl_wrapper').children('.tab_block').attr('style', 'display: none');
	
	val = $('.slider_cat').children('li[class]:first').children('span').attr('id');
	val = val.replace('tab_', '');
	$('.sl_wrapper').children('.tab_block:nth-child(' + a[val] + ')').attr('style', 'display: block');
});
