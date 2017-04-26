$(document).ready(function () {
	$('.button_v1click').click(
		function(){
				var p = $('#add-popup-buy1click');
				var y = ($(window).height() - p.height()) / 2;
				var x = ($(window).width() - p.width()) / 2;
				
				$('#mask').show();
				p.css('top', y + 'px').css('left', x + 'px').show();
			
		}
	);
	
	$('#form_fastorder').submit(function(){
		var error_array = new Array();
		
		// validate NAME
		if($('div.grain-fo-name span.grain-fo-req').size()>0)
		{
			var name = $('#form_fastorder input[name = FASTORDER_NAME]').val();
			var patt = /^\S+\s+\S+.*$/i;
			if(!patt.test(name))
				error_array.push('NAME');
		}
		// validate EMAIL
		if($('div.grain-fo-email span.grain-fo-req').size()>0)
		{
			var email = $('#form_fastorder input[name = FASTORDER_EMAIL]').val();
			var patt = /^.+@.+[.].{2,}$/i;
			if(!patt.test(email))
				error_array.push('EMAIL');	
		}
		// validate PHONE
		if($('div.grain-fo-phone span.grain-fo-req').size()>0)
		{
			var phone = $('#form_fastorder input[name = FASTORDER_PHONE]').val();
			
			var patt = /^( +)?((\+?7|8) ?)?((\(\d{3}\))|(\d{3}))?( )?(\d{1,3}[\- ]?\d{2}[\- ]?\d{2})( +)?$/i;
			if(!patt.test(phone))
				error_array.push('PHONE');
		}
		
		// show ERROR if exist
		if (error_array.length>0) {
			error_array.forEach(function(object, index) {
				jGrowl(BX.message('GRAIN_FO_ERROR_'+object),'error');
				$('#form_fastorder input[name = FASTORDER_'+object+']').focus();
			});
			return false; 
		}
	});
	
});