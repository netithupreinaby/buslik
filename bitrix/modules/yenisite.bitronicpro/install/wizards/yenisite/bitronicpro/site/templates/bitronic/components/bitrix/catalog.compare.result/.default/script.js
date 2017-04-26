$(document).on('click', 'form input.button[type=submit]', function(e){
	YS.Ajax.Start(e);
	return false;
});

function addProp(name, prop, e) {
	e = e || window.event;
	$('#compare_var_input').prop({'name': name, 'value': prop});
	
	YS.Ajax.Start(e);
	return false;
}