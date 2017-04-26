$(document).ready(function() {
	$('.vote-item-header').find('.radio').click(function() {
		$(this).find('input:radio').attr("checked", true);
	});
	$('.vote-item-header').find('.radio').find('input:radio').change(function () {
		$('.vote-item-header').find('.radio').removeClass('active');
		$(this).closest('.radio').addClass('active')
	});
});
