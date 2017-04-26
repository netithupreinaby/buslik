$(function() {
	var obj = [],
		url = window.location.href,
		arr = url.split('/').slice(0, 5),
		len,
		delId = [];

	url = arr.join('/');

	// Del previous
	$('[name ^= ys-del-code-]').each(function() {
		var code = $(this).attr('name').split('ys-del-code-')[1],
			id = $(this).val();

		delId.push(id);
	});

	if (delId.length) {
		for (var i = 0; i < delId.length; i += 1) {
			$.ajax({
		  		url: url + "/?action=DELETE_FROM_COMPARE_LIST&id=" + delId[i],
		  		async: false,
			});
		}
	}
	// -----
	
	$('[name ^= ys-comp-code-]').each(function() {
		var code = $(this).attr('name').split('ys-comp-code-')[1],
			id = $(this).val();

		obj.push({'code': code, 'id': id});
	});

	len = obj.length;

	if (len) {
		for (var i = 0; i < len; i += 1) {
			$.ajax({
		  		url: url + "/?action=ADD_TO_COMPARE_LIST&id=" + obj[i]['id'],
		  		async: false,
			});
		}
	}

	$.ajax({
	  	url: url + "/?action=COMPARE",
	  	async: false,
	  	success: function(out) {
	  		// var tmp = out.split('<div class="ys_article">')[1];
	  		// tmp = '<div class="ys_article">' + tmp.split('<!-- .ys_article -->')[0];
			var tmp = $(out).find('#container').html();
	  		$('#container').html(tmp);
	  		$('#container').find('.checkbox').uniform();
	  	}	
	});
});