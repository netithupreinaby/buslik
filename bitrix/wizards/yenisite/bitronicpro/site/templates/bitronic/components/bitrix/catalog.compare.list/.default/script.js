function yenisite_bb_close(){
	if($('#compare-popup').hasClass('opened')) {
		$('#compare-popup').removeClass('opened').addClass('closed');
	}
}

$(document).ready(function(){
	/*----FOR CLOSE COMPARE BOX ---*/
	//ON PUSH ESC
	$(document).keydown(function(eventObject){
		if(eventObject.which==27)	//27 - ASCII code of button 'ESC'
			yenisite_bb_close();
	});
	//ON CLICK OUTSIDE COMPARE WINDOW
	var onCompare;
	$(".basket-box").on("mouseover", "form .compare", function(){onCompare=true;});
	$(".basket-box").on("mouseout", "form .compare", function(){onCompare=false;});
	//$('.compare').mouseout(function(){onCompare=false;});
	
		// this code need for elements which changes during work page
		$('#fixbody a:not(.compare a)').on('click',function(){
			if(onCompare==false)
				yenisite_bb_close();
		});
		
	$(document).on('click',function(){
		if(onCompare==false)
			yenisite_bb_close();
	});
	/*   -------------------------------------------------------   */
});

