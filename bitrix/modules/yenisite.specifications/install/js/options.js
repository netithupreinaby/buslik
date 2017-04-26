$(document).ready(function () {
	$('#ys_spec_options_form').submit(function(event){
		if ($('input[name="responsibility_agreement"]').is(':checked')) {
			return;
		}
		alert(BX.message("RESPONSIBILITY_ALERT"));
		event.preventDefault();
		setTimeout(function(){
			BX.adminPanel.closeWait($(this).find('input[type="submit"]'));
		}, 50);
	});
});