$(function(){
	var tabContainers = $('div.ys_tv_tab');
	var initialCost = parseFloat($('.ys-sum').find("strong").text().replace(',','.'),10);
	//console.log(initialCost); 
	tabContainers.hide().filter(':first').show();
	$('.ys_tab_nav a').click(function () {
			tabContainers.hide().filter(this.hash).show();
			//$('.ys_tab_nav a').removeClass('ys_active');
			$(this).parent().parent().find("a").removeClass('ys_active');
			$(this).addClass('ys_active');
			$(this).parent().find('input').attr("checked", "checked"); 			
			$('.ys_tab_nav a[href='+$(this).attr("href")+']').addClass('ys_active');
			
			setDelivery(initialCost);
					
			return false;
		});
		
	setDelivery(initialCost);
	
	$('td.ys-ibcount input[type=text]').on('change', function(){
		$('#order').attr('name', 'no_order');
		$('#calculate').attr('name', 'calculate');
		$('#BasketRefresh').attr('value', 'Y');
		document.forms['basket_form'].submit();
	});
});

function setDelivery(initialCost)
{
	var activeButton = $('a.ys_active');
	if(activeButton.find("[name = 'PROPERTY[DELIVERY_E]']").attr('placeholder')>0)
	{
		$('.ys-delivery').show();
		if(/\d+/.test($('.ys-delivery').find("strong").text()))
		{	
			var newDeliveryCost = $('.ys-delivery').find("strong").html().replace(/\d+/, activeButton.find("[name = 'PROPERTY[DELIVERY_E]']").attr('placeholder'));
			$('.ys-delivery').find("strong").text(newDeliveryCost.text());
			
			var newCost = $('.ys-sum').find("strong").html(parseFloat(initialCost,10) + parseFloat(activeButton.find("[name = 'PROPERTY[DELIVERY_E]']").attr('placeholder'),10));
			$('.ys-sum').find("strong").text(newCost.text());
		}
	}
	else
	{
		$('.ys-delivery').hide();
		
		var newCost = $('.ys-sum').find("strong").html(initialCost);
		$('.ys-sum').find("strong").text(newCost.text());
		
	}
}

function setQuantity(id, operation){
	var q = $(id).attr('value');
	if(operation == '-' && q > 1)
		q --;
	if(operation == '+' )
		q++;	
	 $(id).attr('value', q);
	 $('#BasketRefresh').attr('value', 'Y');
	
	document.forms['basket_form'].submit();
}
