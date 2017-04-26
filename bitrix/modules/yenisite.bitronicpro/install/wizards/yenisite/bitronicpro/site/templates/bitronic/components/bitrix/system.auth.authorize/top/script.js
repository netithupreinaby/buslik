$(document).ready(function() {

	$('.button-eye').mousedown(function(){
		document.getElementsByName("USER_PASSWORD")[0].setAttribute('type','text');
	});	

	$('.button-eye').mouseup(function(){
		document.getElementsByName("USER_PASSWORD")[0].setAttribute('type','password');
	});	
	
});