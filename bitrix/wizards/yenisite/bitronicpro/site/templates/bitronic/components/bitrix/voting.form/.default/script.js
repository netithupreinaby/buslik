$(document).ready(function() {
	var radio = $('form.vote-form').find('.radio');
	radio.click(function() {
		$(this).find('input:radio').prop("checked", true);
		radio.not(this).removeClass('active');
		$(this).addClass('active');
	})
	.find('input:radio').change(function () {
		radio.removeClass('active');
		$(this).closest('.radio').addClass('active');
	});
});