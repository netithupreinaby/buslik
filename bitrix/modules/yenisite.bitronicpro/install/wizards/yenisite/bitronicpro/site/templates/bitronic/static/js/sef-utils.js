$(function() {
	if($('div.catalog').size()>0)
	{
	$('.pager a, .one-step a, .f_view a, .f_price a, .f_name a, .f_pop a, .f_sales a').each(function() {
		var href = $(this).attr('href');

		var par = href.split('?');

		if (par[1] !== undefined) {
			par = par[0];

			$(this).attr('href', par);
		}
	});

	$('.pager a, .one-step a').each(function() {
		var href = $(this).attr('href');

		if (href.indexOf('page-1') !== -1) {

			href = href.replace(new RegExp('page-1/', 'g'), '');
		}
		$(this).attr('href', href);
	});
	}
});