$(function(){
	$("#ys_filter_bitronic .toggle-list").selectBox();    
    $("input.checkbox").uniform();
	//$("#search_form").change(function(){
		//$("#search_form").attr("action",  $(".selectBox-selected a").attr('rel'));
	//});
	$(".s_submit").click(function(){
		$("#search_form").submit();
	});
	$("#ys_filter_bitronic input[type='text']").addClass('txt');
	$("#ys_filter_bitronic input[type='text']").click(function(){
			$(this).select();		
	});
	
});