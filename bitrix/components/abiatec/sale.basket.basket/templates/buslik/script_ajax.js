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
BX.ready(function(){
	if($('.user_basket').find("select").length)
		{
			skuPropClickHandlerSet();
			$('.user_basket').find("select").selectBox('destroy').selectBox();
			$('.basket_basket_items').find("select").selectBox('enable');
			$('.basket_delayed_items, .basket_not_available_items').find("select").selectBox('disable');
		}
});
function skuPropClickHandlerSet() 
{
	lastSelected = [];
	var sku_props = BX.findChildren(BX('basket_items'), {tagName: 'select'}, true);
	if (!!sku_props && sku_props.length > 0)
	{
		for (i = 0; sku_props.length > i; i++)
		{
			lastSelected[sku_props[i].id] = sku_props[i].selectedIndex;
			$(sku_props[i]).off('change').on('change', BX.delegate(function(e){ skuPropClickHandler(e);}, this));
			// BX.bind(sku_props[i], 'change', BX.delegate(function(e){ skuPropClickHandler(e);}, this));
		}
	}
}

function skuPropClickHandler(e)
{
	if (!e) e = window.event;
	var target = BX.proxy_context.options[BX.proxy_context.selectedIndex];

	if (!!target && target.hasAttribute('data-value-id'))
	{
		var loaderObj = $(".f_loader");
		startPopupLoader(loaderObj);
		YS.Ajax.showLoader(loaderObj);

		var basketItemId = target.getAttribute('data-element'),
			property = target.getAttribute('data-property'),
			property_values = {},
			postData = {},
			action_var = BX('action_var').value;

		property_values[property] = target.getAttribute('value');

		// if already selected element is clicked
		if (BX.hasClass(target, 'bx_active'))
		{
			YS.Ajax.hideLoader(loaderObj);
			stopPopupLoader(loaderObj);
			return;
		}

		// get other basket item props to get full unique set of props of the new product
		var all_sku_props = BX.findChildren(BX(basketItemId), {tagName: 'select'}, true);
		if (!!all_sku_props && all_sku_props.length > 0)
		{
			for (var i = 0; all_sku_props.length > i; i++)
			{
				if (all_sku_props[i].id == 'prop_' + property + '_' + basketItemId)
				{
					continue;
				}
				else
				{
					var sku_prop_value = all_sku_props[i].options[all_sku_props[i].selectedIndex];
					if (!!sku_prop_value)
					{
						if (sku_prop_value.hasAttribute('value'))
								property_values[sku_prop_value.getAttribute('data-property')] = sku_prop_value.getAttribute('value');

					}
				}
			}
		}

		postData = {
			'basketItemId': basketItemId,
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
			'use_prepayment': BX('use_prepayment').value
		};

		postData[action_var] = 'select_item';
		BX.ajax({
			url: '/bitrix/components/bitrix/sale.basket.basket/ajax.php',
			method: 'POST',
			data: postData,
			dataType: 'json',
			onsuccess: function(result)
			{
				YS.Ajax.hideLoader(loaderObj);
				stopPopupLoader(loaderObj);
				if(result.CODE != 'ERROR')
				{	
					for (var id in lastSelected) {
						obData = {
							attrs: { selected: ''}
						};
						BX.adjust(BX(id).options[lastSelected[id]], obData);
						lastSelected[id] = BX(id).selectedIndex;
					}
					updateBasketTable(basketItemId, result);
				}
				else
				{
					for (var id in lastSelected) {
						BX(id).selectedIndex = lastSelected[id];
						obData = {
							attrs: { selected: 'selected'}
						};
						BX.adjust(BX(id).options[BX(id).selectedIndex], obData);
						$('#'+id).selectBox('refresh');
					}
					
				}
			}
		});
	}
}

function recalcBasketAjax()
{
	var loaderObj = $(".f_loader");
	startPopupLoader(loaderObj);
	YS.Ajax.showLoader(loaderObj);
	
	var property_values = {},
		action_var = BX('action_var').value,
		items = BX('basket_items'),
		delayedItems = BX('delayed_items');
		notAvailItems = BX('not_available_items');
	
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

	if (!!notAvailItems && notAvailItems.rows.length > 0)
	{
		for (var i = 0; notAvailItems.rows.length > i; i++)
		{
			if(BX('DELETE_N_' + notAvailItems.rows[i].id) && BX('DELETE_N_' + notAvailItems.rows[i].id).value == 'Y')
				postData['DELETE_' + notAvailItems.rows[i].id] = 'Y';
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
			YS.Ajax.hideLoader(loaderObj);
			stopPopupLoader(loaderObj);
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
	if(BX("not_available_items"))
		for (var i = 0; i < BX("not_available_items").rows.length; i++)
		{
			if (res.BASKET_DATA.GRID.ROWS === undefined || res.BASKET_DATA.GRID.ROWS[BX("not_available_items").rows[i].getAttribute('id')] === undefined)
			{
				BX.remove(BX(BX("not_available_items").rows[i].getAttribute('id')));
			}
		}
	
	if(BX("basket_items").rows.length <= 0) $('.basket_basket_items').hide();
	else $('.basket_basket_items').show();
	if(BX("delayed_items").rows.length <= 0) $('.basket_delayed_items').hide();
	else $('.basket_delayed_items').show();
	if(BX("not_available_items").rows.length <= 0) $('.basket_not_available_items').hide();
	else $('.basket_not_available_items').show();
	
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
					$(BX(item.ID)).find("select").selectBox('destroy');
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
					for(var i=0; i<item.PROPS.length; i++)
					{
						$(copyItem).find("select#prop_"+item.PROPS[i].CODE+"_"+item.PROPS[i].BASKET_ID+" option").removeAttr('selected');
						
						$(copyItem).find("select#prop_"+item.PROPS[i].CODE+"_"+item.PROPS[i].BASKET_ID+" ").find("option[value = "+item.PROPS[i].VALUE+"],option[data-value-id = "+item.PROPS[i].VALUE+"]").attr('selected','selected');
					}
					var newRow = BX('delayed_items').insertRow(-1);
					newRow.outerHTML = copyItem.outerHTML;
				}
				
			}
			else if(item.DELAY == 'N')
			{
				$('.basket_basket_items').show();
				if(BX.findChild(BX("delayed_items"), {'tag':'tr', 'attr' : {'id':id}}, true))
				{
					var copyItem;
					$(BX(item.ID)).find("select").selectBox('destroy');
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
					for(var i=0; i<item.PROPS.length; i++)
					{
						$(copyItem).find("select#prop_"+item.PROPS[i].CODE+"_"+item.PROPS[i].BASKET_ID+" option").removeAttr('selected');
						
						$(copyItem).find("select#prop_"+item.PROPS[i].CODE+"_"+item.PROPS[i].BASKET_ID+" ").find("option[value = "+item.PROPS[i].VALUE+"],option[data-value-id = "+item.PROPS[i].VALUE+"]").attr('selected','selected');
					}
					var newRow = BX('basket_items').insertRow(-1);
					newRow.outerHTML = copyItem.outerHTML;
				}
				else if(!BX(item.ID)) // if ADD NEW Element in Basket
				{
					/*	var copyItem, copyItemId;
						if(BX('basket_items'))
							copyItemId = BX.findChild(BX('basket_items'), {'tag':'tr'}, true).getAttribute('id');
					copyItem = BX.clone(BX(copyItemId));
					copyItem.setAttribute('id',item.ID);
					var countInput = BX.findChild(copyItem, {'tag':'input', 'attr' : {'name':'QUANTITY_'+copyItemId}}, true);
					countInput.removeAttribute('disabled');
					countInput.id = 'QUANTITY_'+item.ID;
					countInput.name = 'QUANTITY_'+item.ID;
					
					BX.findChild(copyItem, {'tag':'input', 'attr' : {'name':'DELETE_'+copyItemId}}, true).id = 'DELETE_I_'+item.ID;
					BX.findChild(copyItem, {'tag':'input', 'attr' : {'name':'DELETE_'+copyItemId}}, true).name = 'DELETE_'+item.ID;
	
					BX.findChild(copyItem, {'tag':'input', 'attr' : {'name':'DELAY_'+copyItemId}}, true).id = 'DELAY_'+item.ID;
					BX.findChild(copyItem, {'tag':'input', 'attr' : {'name':'DELAY_'+copyItemId}}, true).name = 'DELAY_'+item.ID;
					
					BX.findChild(copyItem, {'tag':'a'}, true).href = item.DETAIL_PAGE_URL;
					BX.findChild(copyItem, {'tag':'a'}, true).innerHTML = item.NAME;

					var plusButton = BX.findChild(copyItem, {'tag':'button', 'class' : 'button4'}, true);
					plusButton.setAttribute('onclick',"setQuantity('#QUANTITY_"+item.ID+"', '+'); return false;");
					
					var minusButton = BX.findChild(copyItem, {'tag':'button', 'class' : 'button5'}, true);
					minusButton.setAttribute('onclick',"setQuantity('#QUANTITY_"+item.ID+"', '-'); return false;");

					var delayButton = BX.findChild(BX.findChild(copyItem, {'class' : 'delay'}, true), {'tag' : 'button'}, true);
					delayButton.setAttribute('onclick',"setDelay('#DELAY_"+item.ID+"', 'Y'); return false;");
					delayButton.innerHTML = '&#0123;'
					
					var deleteButton = BX.findChild(BX.findChild(copyItem, {'class' : 'delete'}, true), {'tag' : 'button'}, true);
					deleteButton.setAttribute('onclick',"setDelete('#DELETE_I_"+item.ID+"'); return false;");
					
					var img = BX.findChild(BX.findChild(copyItem, {'class' : 'ibimg'}, true), {'tag' : 'img'}, true);
					img.setAttribute('src',YS.Ajax.Utils.getElementPicture(item));
					
					
					var itemRowTemplate = '<tr id="'+item.ID+'">'		+
						'<td class="ibimg">'	+		 	  
							'<input type="hidden" name="DELETE_'+item.ID+'" id="DELETE_I_'+item.ID+'" value="" />'	+
							'<input type="hidden" name="DELAY_'+item.ID+'" id="DELAY_'+item.ID+'" value="" />'	+
							'<img src="'+item.PICT+'" alt="" />'	+
						'</td>'	+
						'<td class="ibname">'	+
							'<h3><a href="'+item.DETAIL_PAGE_URL+'">'+item.NAME+'</a></h3>'	+
							// <?foreach($arBasketItems['PROPS'] as $prop):?>
								// <b><?=$prop['NAME'];?>: <?=$prop['VALUE'];?></b>
								// <br />
							// <?endforeach;?>
						'</td>'	+
						'<td class="ibprice"><span class="price">'+item.PRICE+'</span></td>'	+
						'<td class="ibcount">'	+
							'<input type="text" name="QUANTITY_'+item.ID+'"  id="QUANTITY_'+item.ID+'" value="'+item.QUANTITY+'" class="txt w32" onChange="recalcBasketAjax();" />'	+
							'<button onclick="'+"setQuantity('#QUANTITY_'"+item.ID+", '+'); return false;"+'" class="button4">+</button>' 	+
							'<button onclick="'+"setQuantity('#QUANTITY_'"+item.ID+", '-'); return false;"+'" class="button5">-</button>'	+
						'</td>'	+
						'<td class="ibdel delay">'	+
							'<button onclick="'+"setDelay('#DELAY_'"+item.ID+", 'Y'); return false;"+'" class="button6 sym" title="'+'OTLOZHIT'+'">&#0123;</button>'	+
						'</td>'	+
						'<td class="ibdel delete">'	+
							'<button onclick="'+"setDelete('#DELETE_I_'"+item.ID+"); return false;"+'" class="button6 sym" title="'+'DELETIT'+'">&#206;</button>'	+
						'</td>'	+
			  		'</tr>';
					
					
					var newRow = BX('basket_items').insertRow(-1);
					newRow.outerHTML = copyItem.outerHTML;
					*/
				}
				
			}
			// TODO: refresh image ONLY for changed item
			if(BX.findChild(BX(item.ID), {'tag':'img'}, true))
				//BasketParams set in result_modifier
				BX.findChild(BX(item.ID), {'tag':'img'}, true).src = YS.Ajax.Utils.getElementPicture(item, BasketParams.RESIZER_SET);
			if(BX.findChild(BX(item.ID), {'class':'rubl'}, true))
				BX.findChild(BX(item.ID), {'tag':'span', 'class':'price'}, true).innerHTML = item.PRICE_FORMATED.replace(/[^\d\s,]/g, '') + BX.findChild(BX(item.ID), {'class':'rubl'}, true).outerHTML;
			else
				BX.findChild(BX(item.ID), {'tag':'span', 'class':'price'}, true).innerHTML = item.PRICE_FORMATED.replace(/\s/g, '&nbsp;');
				
			if (BX('discount_value_' + id))
				BX('discount_value_' + id).innerHTML = item.DISCOUNT_PRICE_PERCENT_FORMATED;

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
			
			if(BX.findChild(BX.findChild(BX(item.ID), {'tag':'td', 'class':'ibname'}, true), {'tag':'a'}, true) && item.NAME)
				BX.findChild(BX.findChild(BX(item.ID), {'tag':'td', 'class':'ibname'}, true), {'tag':'a'}, true).innerText = item.NAME;
		}
	}
	if($('.user_basket').find("select").length)
	{
		skuPropClickHandlerSet();
		$('.user_basket').find("select").selectBox('destroy').selectBox();
		$('.basket_basket_items').find("select").selectBox('enable');
		$('.basket_delayed_items, .basket_not_available_items').find("select").selectBox('disable');
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
	{
		if(BX.findChild(BX('allSum_wVAT_FORMATED'), {'class':'rubl'}, true))
			BX.findChild(BX('allSum_wVAT_FORMATED'), {'tag':'span'}).innerHTML = res['BASKET_DATA']['allSum_wVAT_FORMATED'].replace(/[^\d\s,]/g, '') + BX.findChild(BX('allSum_wVAT_FORMATED'), {'class':'rubl'}, true).outerHTML;
		else
			BX.findChild(BX('allSum_wVAT_FORMATED'), {'tag':'span'}).innerHTML = res['BASKET_DATA']['allSum_wVAT_FORMATED'].replace(/\s/g, '&nbsp;');
	}
	
	if (BX('allVATSum_FORMATED'))
	{
		if(BX.findChild(BX('allVATSum_FORMATED'), {'class':'rubl'}, true))
			BX.findChild(BX('allVATSum_FORMATED'), {'tag':'span'}).innerHTML = res['BASKET_DATA']['allVATSum_FORMATED'].replace(/[^\d\s,]/g, '') + BX.findChild(BX('allVATSum_FORMATED'), {'class':'rubl'}, true).outerHTML;
		else
			BX.findChild(BX('allVATSum_FORMATED'), {'tag':'span'}).innerHTML = res['BASKET_DATA']['allVATSum_FORMATED'].replace(/\s/g, '&nbsp;');
	}

	if (BX('allSum_FORMATED'))
	{
		if(BX.findChild(BX('allSum_FORMATED'), {'class':'rubl'}, true))
			BX.findChild(BX('allSum_FORMATED'), {'tag':'strong'}).innerHTML = res['BASKET_DATA']['allSum_FORMATED'].replace(/[^\d\s,]/g, '') + BX.findChild(BX('allSum_FORMATED'), {'class':'rubl'}, true).outerHTML;
		else
			BX.findChild(BX('allSum_FORMATED'), {'tag':'strong'}).innerHTML = res['BASKET_DATA']['allSum_FORMATED'].replace(/\s/g, '&nbsp;');
	}
	if (BX('PRICE_WITHOUT_DISCOUNT'))
		BX('PRICE_WITHOUT_DISCOUNT').innerHTML = (res['BASKET_DATA']['PRICE_WITHOUT_DISCOUNT'] != res['BASKET_DATA']['allSum_FORMATED']) ? res['BASKET_DATA']['PRICE_WITHOUT_DISCOUNT'].replace(/\s/g, '&nbsp;') : '';
	
}