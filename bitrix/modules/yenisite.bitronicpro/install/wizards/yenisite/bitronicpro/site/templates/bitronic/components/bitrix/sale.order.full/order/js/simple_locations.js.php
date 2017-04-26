<script>
$(document).ready(function(){
	$('select[name="<?=$arProperties["FIELD_NAME"]?>"]').on('change', function(){
		var option = $(this).find('option:selected');
		if (option.length > 0) {
			var text = option.text();
			if (text.indexOf(' - ') != -1) {
				$('[name="ORDER_PROP_<?=$arPropLocation['INPUT_FIELD_LOCATION']?>"]').val(text.substring(text.indexOf(' - ')+3))
					.parent()
					.parent().hide();
				return;
			}
		}
		
		$('[name="ORDER_PROP_<?=$arPropLocation['INPUT_FIELD_LOCATION']?>"]')
			.parent()
			.parent().css('display', 'table-row');
	}).change();
});
</script>