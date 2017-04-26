/* ----   FOR BITRONIC   ---- */
$(document).ready(function(){
	$("[name='submitbutton']").attr('onClick', '');
	$("[name='submitbutton']").click(function(){
		var price = $(".sale_data-table tfoot tr.last td.price").text();
		price = parseInt(price.replace(/\s+/g, ''));
		cookie_name = 'vk__paymentVkredit';
		var cookie_val = document.cookie.match ( '(^|;) ?vk__paymentVkredit=([^;]*)(;|$)' );
		if(cookie_val instanceof Array)
		{
			if($("#"+cookie_val[2]).attr('checked')=="checked" && price<=3000 )
			{
				jGrowl(BX.message('TCBANK_KUPIVKREDIT_ERROR'),'error');
			}
			else
			{
				submitForm('Y');
			}
		}
		else
		{
			submitForm('Y');
		}
	 });
});