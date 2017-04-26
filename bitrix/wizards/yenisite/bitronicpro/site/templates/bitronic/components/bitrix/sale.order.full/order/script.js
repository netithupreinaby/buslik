 $(function(){
 	$("form").submit(function() {// обрабатываем отправку формы    
		$("form").find("input[name='NEW_NAME']").each(function() {
			if(!$(this).val())
				{$(this).val(unescape("%20"));}
		})
	   
		$("form").find("input[name='NEW_LAST_NAME']").each(function() {
			if(!$(this).val())
				{$(this).val(unescape("%20"));}
		})
	   
		return true; 
	})	
});
function ChangeGenerate(val)
{
	if(val)
		document.getElementById("sof_choose_login").style.display='none';
	else
		document.getElementById("sof_choose_login").style.display='block';

	try{document.order_reg_form.NEW_LOGIN.focus();}catch(e){}
}