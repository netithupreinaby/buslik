
	var sebekon_deliverycalc_params = {};	
	
	var sebekon_deliveryprice_order_click = function(event) {	
		
		if (event.preventDefault)
			event.preventDefault();
		
		if (event.returnValue)
			event.returnValue = false;
		
		if (event.stopPropagation)
			event.stopPropagation();
			
		if (event.cancelBubble)
			event.cancelBubble = true;
			
		$sebekon_jq_delivery('#SEBEKON_DELIVERYPRICE_ORDER').modal('show');
		$sebekon_jq_delivery('#SEBEKON_DELIVERYPRICE_ORDER').on('hidden', function () {
			$sebekon_jq_delivery('.sebekon-help_block').remove();
		});
		$sebekon_jq_delivery('#SEBEKON_DELIVERYPRICE_ORDER .sebekon-modal-body').css('width','630');
		$sebekon_jq_delivery('#SEBEKON_DELIVERYPRICE_ORDER .sebekon-modal-body').css('opacity','1');
		$sebekon_jq_delivery('#SEBEKON_DELIVERYPRICE_ORDER').css('opacity','1');
		$sebekon_jq_delivery('#SEBEKON_DELIVERYPRICE_ORDER .sebekon-modal-body').load('/bitrix/components/sebekon/delivery.calc/order.php');
		var top = $sebekon_jq_delivery('#SEBEKON_DELIVERYPRICE_ORDER').offset().top;
		$sebekon_jq_delivery('#SEBEKON_DELIVERYPRICE_ORDER').css('position', 'absolute');
		$sebekon_jq_delivery('#SEBEKON_DELIVERYPRICE_ORDER').css('top', top);
		return false;
	}
	
	var submitParentForm = function() {
		$sebekon_jq_delivery.ajax({
			url: "/bitrix/components/sebekon/delivery.calc/order.php",
			data: sebekon_deliverycalc_params,
			success:  function(data){
				sebekon_delivery_refresh_options();
				$sebekon_jq_delivery('.sebekon-help_block').remove();
				if (window.submitForm) {
					submitForm();
				} else {
					if (window.BX && window.BX.saleOrderAjax) {
						window.BX.saleOrderAjax.submitFormProxy();
					} else {
						$sebekon_jq_delivery('a.sebekon_delivery_price_link').eq(0).parents('form').submit();
					}
				}
				$sebekon_jq_delivery('#SEBEKON_DELIVERYPRICE_ORDER').modal('hide');
			},
			dataType: 'json'
		});
	}