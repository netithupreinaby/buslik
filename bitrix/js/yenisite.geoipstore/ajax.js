/*$(function() {
	$('[name="item_default"]').on('change', function() {
		$.ajax({
			url: '/bitrix/js/yenisite.geoipstore/ajax.php?itemId=' + $(this).val(),
			dataType: 'json',
			success: function(data) {
				var str ='';
				for (var i = 0; i < data.length; i++) {
					str += '<option value="' + data[i].STORE_ID +'">' + data[i].TITLE + '</option>';
				}

				$('[name="store_default"]').empty();
				$('[name="store_default"]').append(str);
			}
		});
	});
});*/