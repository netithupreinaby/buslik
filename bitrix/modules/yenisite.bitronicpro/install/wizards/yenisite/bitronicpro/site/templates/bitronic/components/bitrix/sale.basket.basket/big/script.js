function setQuantity(id, operation) {
	var q = $(id).attr('value');
	if(operation == '-' && q > 1)
		q --;
	if(operation == '+' )
		q++;	
	$(id).attr('value', q);	
		
	recalcBasketAjax();
}
function setDelete(id) {
	$(id).attr('value', 'Y');	
	 
	recalcBasketAjax();
}
function setDelay(id, val) {
	$(id).attr('value', val);	
	
	recalcBasketAjax();
}
function recalcBasketAjax()
{
	BX.showWait();

	var property_values = {},
		action_var = BX('action_var').value,
		items = BX('basket_items'),
		delayedItems = BX('delayed_items');
	
	var postData = {
		'sessid': BX.bitrix_sessid(),
		'site_id': BX.message('SITE_ID'),
		'props': property_values,
		'action_var': action_var,
		'select_props': BX('column_headers').value,
		'offers_props': BX('offers_props').value,
		'quantity_float': BX('quantity_float').value,
		'count_discount_4_all_quantity': BX('count_discount_4_all_quantity').value,
		'price_vat_show_value': BX('price_vat_show_value').value,
		'hide_coupon': BX('hide_coupon').value,
		'use_prepayment': BX('use_prepayment').value,
		'coupon': !!BX('coupon') ? BX('coupon').value : ""
	};

	postData[action_var] = 'recalculate';

	if (!!items && items.rows.length > 0)
	{
		for (var i = 0; items.rows.length > i; i++)
		{	
		
			postData['QUANTITY_' + items.rows[i].id] = BX('QUANTITY_' + items.rows[i].id).value;
			if(BX('DELETE_I_' + items.rows[i].id) && BX('DELETE_I_' + items.rows[i].id).value == 'Y')
				postData['DELETE_' + items.rows[i].id] = 'Y';
			else if(BX('DELAY_' + items.rows[i].id) && BX('DELAY_' + items.rows[i].id).value == 'Y')
				postData['DELAY_' + items.rows[i].id] = 'Y';			
		}
	}

	if (!!delayedItems && delayedItems.rows.length > 0)
	{
		for (var i = 0; delayedItems.rows.length > i; i++)
		{
			if(BX('DELETE_D_' + delayedItems.rows[i].id) && BX('DELETE_D_' + delayedItems.rows[i].id).value == 'Y')
				postData['DELETE_' + delayedItems.rows[i].id] = 'Y';
			else if(BX('UN_DELAY_' + delayedItems.rows[i].id) && BX('UN_DELAY_' + delayedItems.rows[i].id).value == 'N')
				postData['DELAY_' + delayedItems.rows[i].id] = 'N';
			else
				postData['DELAY_' + delayedItems.rows[i].id] = 'Y';
		}
	}
	

	BX.ajax({
		url: '/bitrix/components/bitrix/sale.basket.basket/ajax.php',
		method: 'POST',
		data: postData,
		dataType: 'json',
		onsuccess: function(result)
		{
			BX.closeWait();
			updateBasketTable(null, result);
		}
	});
}

function updateBasketTable(basketItemId, res)
{

	var table = BX("basket_items");

	if (!table)
		return;

	//var rows = table.rows,
		//newBasketItemId = res['BASKET_ID'],
		//arItem = res['BASKET_DATA']['GRID']['ROWS'][newBasketItemId],
		//lastRow = rows[rows.length - 1],
		//newRow = document.createElement('tr'),
		//arColumns = res['COLUMNS'].split(','),
		//bShowDeleteColumn = false,
		//bShowDelayColumn = false,
		//bShowPropsColumn = false,
		//bShowPriceType = false,
		//bUseFloatQuantity = (res['PARAMS']['QUANTITY_FLOAT'] == 'Y') ? true : false;
		
	// delete product rows
	
	for (var i = 0; i < BX("basket_items").rows.length; i++)
	{
		if (res.BASKET_DATA.GRID.ROWS === undefined || res.BASKET_DATA.GRID.ROWS[BX("basket_items").rows[i].getAttribute('id')] === undefined)
		{
			BX.remove(BX(BX("basket_items").rows[i].getAttribute('id')));
		}
	}
	for (var i = 0; i < BX("delayed_items").rows.length; i++)
	{
		
		if (res.BASKET_DATA.GRID.ROWS === undefined || res.BASKET_DATA.GRID.ROWS[BX("delayed_items").rows[i].getAttribute('id')] === undefined)
		{
			BX.remove(BX(BX("delayed_items").rows[i].getAttribute('id')));
		}
	}
	if(BX("basket_items_not_available"))
		for (var i = 0; i < BX("basket_items_not_available").rows.length; i++)
		{
			if (res.BASKET_DATA.GRID.ROWS === undefined || res.BASKET_DATA.GRID.ROWS[BX("basket_items_not_available").rows[i].getAttribute('id')] === undefined)
			{
				BX.remove(BX(BX("basket_items_not_available").rows[i].getAttribute('id')));
			}
		}
	
	if(BX("basket_items").rows.length <= 0) $('.basket_basket_items').hide();
	else $('.basket_basket_items').show();
	if(BX("delayed_items").rows.length <= 0) $('.basket_delayed_items').hide();
	else $('.basket_delayed_items').show();
	
	// update product params after recalculation
	for (var id in res.BASKET_DATA.GRID.ROWS)
	{
		if (res.BASKET_DATA.GRID.ROWS.hasOwnProperty(id))
		{
			var item = res.BASKET_DATA.GRID.ROWS[id];
			
			if(item.DELAY == 'Y')
			{
				$('.basket_delayed_items').show();
				if(BX.findChild(BX("basket_items"), {'tag':'tr', 'attr' : {'id':id}}, true))
				{
					var copyItem;
					copyItem = BX.clone(BX(item.ID));
					
					BX.remove(BX(item.ID));
					
					if(BX("basket_items").rows.length <= 0)
						$('.basket_basket_items').hide();
					BX.findChild(copyItem, {'tag':'input', 'attr' : {'name':'QUANTITY_'+item.ID}}, true).setAttribute('disabled','disabled');
					BX.findChild(copyItem, {'tag':'input', 'attr' : {'name':'DELETE_'+item.ID}}, true).setAttribute('id','DELETE_D_'+item.ID);
					BX.findChild(copyItem, {'tag':'input', 'attr' : {'name':'DELAY_'+item.ID}}, true).setAttribute('id','UN_DELAY_'+item.ID);
					BX.hide(BX.findChild(copyItem, {'tag':'button', 'class' : 'button4'}, true));
					BX.hide(BX.findChild(copyItem, {'tag':'button', 'class' : 'button5'}, true));
					var delayButton = BX.findChild(BX.findChild(copyItem, {'class' : 'delay'}, true), {'tag' : 'button'}, true);
					delayButton.setAttribute('onclick',"setDelay('#UN_DELAY_"+item.ID+"', 'N'); return false;");
					delayButton.innerHTML = '&#0125;'
					
					var deleteButton = BX.findChild(BX.findChild(copyItem, {'class' : 'delete'}, true), {'tag' : 'button'}, true);
					deleteButton.setAttribute('onclick',"setDelete('#DELETE_D_"+item.ID+"'); return false;");
					
					var newRow = BX('delayed_items').insertRow();
					newRow.outerHTML = copyItem.outerHTML;
				}
				
			}
			else if(item.DELAY == 'N')
			{
				$('.basket_basket_items').show();
				if(BX.findChild(BX("delayed_items"), {'tag':'tr', 'attr' : {'id':id}}, true))
				{
					var copyItem;
					copyItem = BX.clone(BX(item.ID));
					
					BX.remove(BX(item.ID));
					
					if(BX("delayed_items").rows.length <= 0)
						$('.basket_delayed_items').hide();
					BX.findChild(copyItem, {'tag':'input', 'attr' : {'name':'QUANTITY_'+item.ID}}, true).removeAttribute('disabled');
					BX.findChild(copyItem, {'tag':'input', 'attr' : {'name':'DELETE_'+item.ID}}, true).setAttribute('id','DELETE_I_'+item.ID);
					BX.findChild(copyItem, {'tag':'input', 'attr' : {'name':'DELAY_'+item.ID}}, true).setAttribute('id','DELAY_'+item.ID);
					BX.show(BX.findChild(copyItem, {'tag':'button', 'class' : 'button4'}, true), 'inline-block');
					BX.show(BX.findChild(copyItem, {'tag':'button', 'class' : 'button5'}, true), 'inline-block');
					var delayButton = BX.findChild(BX.findChild(copyItem, {'class' : 'delay'}, true), {'tag' : 'button'}, true);
					delayButton.setAttribute('onclick',"setDelay('#DELAY_"+item.ID+"', 'Y'); return false;");
					delayButton.innerHTML = '&#0123;'
					
					var deleteButton = BX.findChild(BX.findChild(copyItem, {'class' : 'delete'}, true), {'tag' : 'button'}, true);
					deleteButton.setAttribute('onclick',"setDelete('#DELETE_I_"+item.ID+"'); return false;");
					
					var newRow = BX('basket_items').insertRow();
					newRow.outerHTML = copyItem.outerHTML;
				}
				
			}
			
			if (BX('discount_value_' + id))
				BX('discount_value_' + id).innerHTML = item.DISCOUNT_PRICE_PERCENT_FORMATED;

			if (BX('current_price_' + id))
				BX('current_price_' + id).innerHTML = item.PRICE_FORMATED;

			if (BX('old_price_' + id))
				BX('old_price_' + id).innerHTML = (item.FULL_PRICE_FORMATED != item.PRICE_FORMATED) ? item.FULL_PRICE_FORMATED : '';

			if (BX('sum_' + id))
				BX('sum_' + id).innerHTML = item.SUM;

			// if the quantity was set by user to 0 or was too much, we need to show corrected quantity value from ajax response
			if (BX('QUANTITY_' + id))
			{
				BX('QUANTITY_' + id).value = item.QUANTITY;
				BX('QUANTITY_' + id).defaultValue = item.QUANTITY;
			}
		}
	}
	
	// update coupon info
	if (BX('coupon'))
	{
		var couponClass = "";

		if (BX('coupon_approved') && BX('coupon').value.length == 0)
		{
			BX('coupon_approved').value = "N";
		}

		if (res.hasOwnProperty('VALID_COUPON'))
		{
			couponClass = (!!res['VALID_COUPON']) ? 'good' : 'bad';

			if (BX('coupon_approved'))
			{
				BX('coupon_approved').value = (!!res['VALID_COUPON']) ? 'Y' : 'N'
			}
		}

		if (BX('coupon_approved') && BX('coupon').value.length > 0)
		{
			couponClass = BX('coupon_approved').value == "Y" ? "good" : "bad";
		}else
		{
			couponClass = "";
		}
		
		BX.removeClass('coupon',"good");
		BX.removeClass('coupon',"bad");
		BX.addClass('coupon',couponClass);
	}

	// update warnings if any
	if (res.hasOwnProperty('WARNING_MESSAGE'))
	{
		var warningText = '';

		for (var i = res['WARNING_MESSAGE'].length - 1; i >= 0; i--)
			warningText += res['WARNING_MESSAGE'][i] + '<br/>';

		BX('warning_message').innerHTML = warningText;
	}
	
	// update total basket values
	if (BX('allWeight_FORMATED'))
		BX('allWeight_FORMATED').innerHTML = res['BASKET_DATA']['allWeight_FORMATED'].replace(/\s/g, '&nbsp;');

	if (BX('allSum_wVAT_FORMATED'))
		BX('allSum_wVAT_FORMATED').innerHTML = res['BASKET_DATA']['allSum_wVAT_FORMATED'].replace(/\s/g, '&nbsp;');

	if (BX('allVATSum_FORMATED'))
		BX('allVATSum_FORMATED').innerHTML = res['BASKET_DATA']['allVATSum_FORMATED'].replace(/\s/g, '&nbsp;');

	if (BX('allSum_FORMATED'))
	{
		if(BX.findChild(BX('allSum_FORMATED'), {'class':'rubl'}, true))
			BX.findChild(BX('allSum_FORMATED'), {'tag':'strong'}).innerHTML = res['BASKET_DATA']['allSum_FORMATED'].replace(/[^\d\s]/g, '') + BX.findChild(BX('allSum_FORMATED'), {'class':'rubl'}, true).outerHTML;
		else
			BX.findChild(BX('allSum_FORMATED'), {'tag':'strong'}).innerHTML = res['BASKET_DATA']['allSum_FORMATED'].replace(/\s/g, '&nbsp;');
	}
	if (BX('PRICE_WITHOUT_DISCOUNT'))
		BX('PRICE_WITHOUT_DISCOUNT').innerHTML = (res['BASKET_DATA']['PRICE_WITHOUT_DISCOUNT'] != res['BASKET_DATA']['allSum_FORMATED']) ? res['BASKET_DATA']['PRICE_WITHOUT_DISCOUNT'].replace(/\s/g, '&nbsp;') : '';
	
}