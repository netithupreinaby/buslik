$(function(){
	$("#ys_filter_bitronic .toggle-list").selectBox();    
    if(!($("#ys-a-settings").length))
	{
		$("input.checkbox").uniform();
	}
	$(".s_submit").click(function(){
		$("#search_form").submit();
	});
	$("#ys_filter_bitronic input[type='text']").addClass('txt');
	$("#ys_filter_bitronic input[type='text']").click(function(){
			$(this).select();		
	});
	
});