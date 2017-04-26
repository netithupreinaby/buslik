<script>
$(document).ready(function(){
	$(document).on('change', 'select[name="<?=$arProperties["FIELD_NAME"]?>"]', function(){
		var option = $(this).find('option:selected');
		if (!option.hasClass('no-city')) {
			var text = option.text();
			$('[name="ORDER_PROP_<?=$arPropLocation['INPUT_FIELD_LOCATION']?>"]').val(text)
				.parent()
				.parent().hide();
			return;
		}
		
		$('[name="ORDER_PROP_<?=$arPropLocation['INPUT_FIELD_LOCATION']?>"]')
			.parent()
			.parent().css('display', 'table-row');
			
	}).on('change', 'select[name="COUNTRY"], select[name="REGION<?=$arProperties["FIELD_NAME"]?>"]', function(){
	
		$('[name="ORDER_PROP_<?=$arPropLocation['INPUT_FIELD_LOCATION']?>"]')
			.parent()
			.parent().hide();
	});
	setTimeout( function(){
		$('select[name="<?=$arProperties["FIELD_NAME"]?>"]').change();
	}, 500);
});
</script>