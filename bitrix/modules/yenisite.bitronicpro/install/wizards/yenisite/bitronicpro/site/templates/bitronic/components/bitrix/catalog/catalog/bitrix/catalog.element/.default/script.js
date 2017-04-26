$(document).ready(function() {
	var oHref = $('div.item_detail_options a.button2'),
		href = oHref.attr('href'),
		i = 0;
	
	href = decodeURI(href);
	params = href.split('&');
		
	for (; i < params.length; i += 1) {
			
		$(this).find('.props select').each(function() {
			if ( params[i].indexOf( $(this).attr('name') ) != -1 ) {
				params.splice(i, 1);
				i--;
				// params[i] = '';
			}
		});
	}
		
	href = params.join('&');
	oHref.attr('href', href);
        
    
    //PRICE_LOWER form
    $('#ys-want_low_price').click(function(){
        var id = $(this).attr('href');
        
        var div = $('div#ys-price_lower-popup');
        div.empty();
        div.fadeIn(300);
        $('#mask').fadeIn('300');
        
        var loaderObj = createLoader(div);
        startPopupLoader(loaderObj);
        popupToCenter(div);
        
        var id = $(this).attr('href');
        var url = SITE_TEMPLATE_PATH+'/ajax/price_lower.php';
            $.ajax({
                type: "POST",
                url: url,
                data: {'ELEMENT_ID': id},
                dataType : "html",
                success: function(data){
                    $('div#ys-price_lower-popup').html(data);
                    stopPopupLoader(loaderObj);
                    popupToCenter(div);
                }
            });
        
        return false;
    });
    
    //FOUND_CHEAP form
    $('#ys-found_cheap').click(function(){
        var id = $(this).attr('href');
        
        var div = $('div#ys-found_cheap-popup');
        div.empty();
        div.fadeIn(300);
        $('#mask').fadeIn('300');
        
        var loaderObj = createLoader(div);
        startPopupLoader(loaderObj);
        popupToCenter(div);
        
        var url = SITE_TEMPLATE_PATH+'/ajax/found_cheap.php';
            $.ajax({
                type: "POST",
                url: url,
                data: {'ELEMENT_ID': id},
                dataType : "html",
                success: function(data){
                    $('div#ys-found_cheap-popup').html(data);
                    stopPopupLoader(loaderObj);
                    popupToCenter(div);
                }
            });
        
        return false;
    });
    
    //ELEMENT_EXIST form
    $('#ys-when_element_exist').click(function(){
        var id = $(this).attr('href');
        
        var div = $('div#ys-element_exist-popup');
        div.empty();
        div.fadeIn(300);
        $('#mask').fadeIn('300');
        
        var loaderObj = createLoader(div);
        startPopupLoader(loaderObj);
        popupToCenter(div);
        
        var url = SITE_TEMPLATE_PATH+'/ajax/element_exist.php';
            $.ajax({
                type: "POST",
                url: url,
                data: {'ELEMENT_ID': id},
                dataType : "html",
                success: function(data){
                    $('div#ys-element_exist-popup').html(data);
                    stopPopupLoader(loaderObj);
                    popupToCenter(div);
                }
            });
        
        return false;
    });
	
	// SERVICES
	$('#options-form .form-item input[type="checkbox"]').prop("checked", false);
	$('#options-form .form-item input[type="checkbox"]').change(function(){
		
		var checked = $(this).prop("checked");
		var value = $('#options-form input[name="servicePrice_'+$(this).val()+'"]').val();
		if ( $('#ys_top_price .allSumMain:first').length )
		{
			var price = parseFloat($('#ys_top_price .allSumMain:first').text().replace(/\s+/g, ''));
			if(checked == 1)
				price = Number(price) + Number(value);
			else
				price = Number(price) - Number(value);
			
			var new_price = yenisite_number_format(price, 0, '.', ' ') ;
			if ( $('#allSum').length )	$('#allSum').html(new_price + ' ');
			if ( $('#allSum_val').length )	$('#allSum_val').val(price);
			
			if ( $('#ys_top_price .allSumMain:first').length )	$('#ys_top_price .allSumMain:first').html(new_price + ' ');
			
		}
		else if ( $('.catalog-list-light table:not(.ys_sets_table) .priceTD .allSumMain').length )
		{
			$('.catalog-list-light table:not(.ys_sets_table) .priceTD').each(function(){
				
				var price = parseFloat($(this).find('.allSumMain:first').text().replace(/\s+/g, ''));	
				if(checked == 1)
					price = Number(price) + Number(value);
				else
					price = Number(price) - Number(value);
				var new_price = yenisite_number_format(price, 0, '.', ' ') ;
				$(this).find('.allSumMain:first').html(new_price + ' ');
			});
		}
    });
});

function yenisite_number_format (number, decimals, dec_point, thousands_sep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    return s.join(dec);
}
/**
* Add params to add_url
*
* @param {Object} select object
*/
// add new function, modify by Ivan, 09.10.2013 ---->

function onRadioPropChange(radio){
	var a = $('div.item_detail_options a.button2'),
		href,
		params,
		i = 0,
		flag = 0,
		val = radio.name + '=' + radio.value;
		
		if (a.attr('href') === 'javascript:void(0);') {
			a.attr('href', a.attr('rev'));
		}
		
		href = a.attr('href');

		href = decodeURI(href);
		params = href.split('&');

		for (; i < params.length; i += 1) {
			if (params[i].indexOf(radio.name) != -1) {
				if (radio.value == 0) {
					params.splice(i, 1);
				} else {
					params[i] = val;
					flag = 1;
				}
			}
		}
		
		if ( radio.value != 0 && !flag ) {
			params.push(val);
		}

		href = params.join('&');
		a.attr('href', href);
	
	a.removeClass('button_in_basket');
	$('div.item_detail_options .ye-select > select').each(function(){
		if(this.value == 0 ){
			a.addClass('button_in_basket');
			return false;
		}
	});
	if (!a.hasClass('button_in_basket')) {
		var radioCollection = $('div.item_detail_options div.props input[type="radio"]');
		var radioTotal = 0, radioChecked = 0;
		var prevRadio = $();
		for (i = 0; i < radioCollection.length; i++) {
			var radio = radioCollection.eq(i);
			if (radio.attr('name') != prevRadio.attr('name')) radioTotal++;
			if (radio.is(':checked')) radioChecked++;
			prevRadio = radio;
		}
		if (radioChecked < radioTotal) {
			a.addClass('button_in_basket');
		}
	}
}

// <---- end modify
function onSelectChange(select) {
	var a = $('div.item_detail_options a.button2'),
		href,
		params,
		i = 0,
		flag = 0,
		val = select.name + '=' + select.value;
		
	$(select).next().find('span.selectBox-label').css('color', 'black');
	
	if (a.attr('href') === 'javascript:void(0);') {
		a.attr('href', a.attr('rev'));
	}
	
	href = a.attr('href');

	href = decodeURI(href);
	params = href.split('&');

	for (; i < params.length; i += 1) {
		if (params[i].indexOf(select.name) != -1) {
			if (select.value == 0) {
				params.splice(i, 1);
			} else {
				params[i] = val;
				flag = 1;
			}
		}
	}
	
	if ( select.value != 0 && !flag ) {
		params.push(val);
	}
	
	href = params.join('&');
	a.attr('href', href);
	
	a.removeClass('button_in_basket');
	$('div.item_detail_options .ye-select > select').each(function(){
		if(this.value == 0 ){
			a.addClass('button_in_basket');
			return false;
		}
	});
	if (!a.hasClass('button_in_basket')) {
		var radioCollection = $('div.item_detail_options div.props input[type="radio"]');
		var radioTotal = 0, radioChecked = 0;
		var prevRadio = $();
		for (i = 0; i < radioCollection.length; i++) {
			var radio = radioCollection.eq(i);
			if (radio.attr('name') != prevRadio.attr('name')) radioTotal++;
			if (radio.is(':checked')) radioChecked++;
			prevRadio = radio;
		}
		if (radioChecked < radioTotal) {
			a.addClass('button_in_basket');
		}
	}
}

/**
* Validate params
*
*/
function onClick2Cart(target) {
	var a = $(target),
		href = a.attr('href'),
		i = 0,
		tmp;
	
	if ( ( href.split('prop').length - 1 ) != a.prev().find('select').length ) {
		if (a.attr('href') !== 'javascript:void(0);') {
			a.attr('rev', href);
			a.attr('href', 'javascript:void(0);');
			
			a.prev().find('span.selectBox-label').css('color', 'red');
			
			href = decodeURI(href);
			params = href.split('&');
			
			for (; i < params.length; i += 1) {
				tmp = params[i].split('=');
				
				a.prev().find('select').each(function() {
					if ( $(this).attr('name') === tmp[0] ) {
						$(this).next().children().eq(0).css('color', 'black');
					}
				});
			}
		}
	}
}


window.JCCatalogElement = function (arParams)
{
	this.showAbsent = true;
	this.showOldPrice = false;
	this.showOfferGroup = false;
	this.selectedValues = {};
	this.offers = [];
	this.obBuyBtn = null;
	this.obBuyBtnSet = null;
	this.obTreeRows = [];
	this.obPrice = null;
	this.offerNumOld = -1;
	this.offersIblock = null;
	
	this.visual = {
		ID: '',
		PICT_ID: '',
		SECOND_PICT_ID: '',
		QUANTITY_ID: '',
		QUANTITY_UP_ID: '',
		QUANTITY_DOWN_ID: '',
		PRICE_ID: '',
		DSC_PERC: '',
		SECOND_DSC_PERC: '',
		DISPLAY_PROP_DIV: '',
		BASKET_PROP_DIV: ''
	};
	this.product = {
		checkQuantity: false,
		maxQuantity: 0,
		stepQuantity: 1,
		isDblQuantity: false,
		canBuy: true,
		canSubscription: true,
		name: '',
		pict: {},
		id: 0,
		addUrl: '',
		buyUrl: ''
	};
	
	this.errorCode = 0;
	if ('object' === typeof arParams)
	{
		this.productType = parseInt(arParams.PRODUCT_TYPE, 10);
		this.visual = arParams.VISUAL;
		this.showAbsent = arParams.SHOW_ABSENT;
		this.showOldPrice = !!arParams.SHOW_OLD_PRICE;
		this.reloadPictures = !!arParams.AJAX_RELOAD_PICTURE;
		this.showOfferGroup = !!arParams.SHOW_OFFER_GROUP;
		switch (this.productType)
		{
			case 3://sku
				if (!!arParams.OFFERS && BX.type.isArray(arParams.OFFERS))
				{
					if (!!arParams.PRODUCT && 'object' === typeof(arParams.PRODUCT))
					{
						this.product.name = arParams.PRODUCT.NAME;
						this.product.id = arParams.PRODUCT.ID;
					}
					this.offers = arParams.OFFERS;
					this.offerNum = 0;
					if (!!arParams.OFFER_SELECTED)
					{
						this.offerNum = parseInt(arParams.OFFER_SELECTED, 10);
					}
					if (isNaN(this.offerNum))
					{
						this.offerNum = 0;
					}
					if (!!arParams.TREE_PROPS)
					{
						this.treeProps = arParams.TREE_PROPS;
					}
				}
				else
				{
					this.errorCode = -1;
				}
				break;
			default:
				this.errorCode = -1;
		}
	}
	if (0 === this.errorCode)
	{
		BX.ready(BX.delegate(this.Init,this));
	}
}

window.JCCatalogElement.prototype.Init = function()
{
	var i = 0,
		strPrefix = '',
		TreeItems = null;
	var detailOptions = BX.findChildren(BX(this.visual.ID),{'class':'item_detail_options'}, true);
	if(detailOptions.length)
		this.obPrice = BX.findChildren(detailOptions[0], {'class':'price'}, true);

	if (!this.obPrice)
	{
		this.errorCode = -16;
	}
	this.offersIblock = BX('ajax_iblock_id_sku').getAttribute('value');
	YS.Ajax.SetOption('offers_iblock_id' , this.offersIblock);

	if (!!this.visual.BUY_ID)
	{
		this.obBuyBtn = BX(this.visual.BUY_ID);
		this.obBuyBtnSet = BX(this.visual.BUY_ID_SET);
	}
	
	if (3 === this.productType)
	{
		if (!!this.visual.TREE_ID)
		{
			this.obTree = BX(this.visual.TREE_ID);
			if (!this.obTree)
			{
				this.errorCode = -256;
			}
			strPrefix = this.visual.TREE_ITEM_ID;
			for (i = 0; i < this.treeProps.length; i++)
			{
				this.obTreeRows[i] = {
					LIST: BX(strPrefix+this.treeProps[i].ID+'_list'),
					CONT: BX(strPrefix+this.treeProps[i].ID+'_cont')
				};
							
				if (/*!this.obTreeRows[i].LEFT || !this.obTreeRows[i].RIGHT || */!this.obTreeRows[i].LIST || !this.obTreeRows[i].CONT)
				{
					this.errorCode = -512;
					break;
				}
			}
		}
		if (!!this.visual.QUANTITY_MEASURE)
		{
			this.obMeasure = BX(this.visual.QUANTITY_MEASURE);
		}
	}
	if (0 === this.errorCode)
	{
		switch (this.productType)
		{
			case 3://sku
				
				for (i = 0; i < this.obTreeRows.length; i++)
				{
					//BX.bind(this.obTreeRows[i].LIST, 'change', BX.delegate(this.SelectOfferProp, this));
					$(this.obTreeRows[i].LIST).on('change', BX.delegate(this.SelectOfferProp, this));
				}
				this.SetCurrent();
				break;
		}
	}
};

window.JCCatalogElement.prototype.GetCanBuy = function(arFilter)
{
	var i = 0,
		j,
		boolSearch = false,
		boolOneSearch = true;

	for (i = 0; i < this.offers.length; i++)
	{
		boolOneSearch = true;
		for (j in arFilter)
		{
			if (arFilter[j] !== this.offers[i].TREE[j])
			{
				boolOneSearch = false;
				break;
			}
		}
		if (boolOneSearch)
		{
			if (this.offers[i].CAN_BUY)
			{
				boolSearch = true;
				break;
			}
		}
	}
	return boolSearch;
};

window.JCCatalogElement.prototype.SetCurrent = function()
{
	var i = 0,
		j = 0,
		arCanBuyValues = [],
		strName = '',
		arShowValues = false,
		arFilter = {},
		tmpFilter = [],
		current = this.offers[this.offerNum].TREE;

	for (i = 0; i < this.treeProps.length; i++)
	{
		strName = 'PROP_'+this.treeProps[i].ID;
		arShowValues = this.GetRowValues(arFilter, strName);
		
		if (!arShowValues)
		{
			break;
		}
		if (BX.util.in_array(current[strName], arShowValues))
		{
			arFilter[strName] = current[strName];
		}
		else
		{
			arFilter[strName] = arShowValues[0];
			this.offerNum = 0;
		}
		if (this.showAbsent)
		{
			arCanBuyValues = [];
			tmpFilter = [];
			tmpFilter = BX.clone(arFilter, true);
			for (j = 0; j < arShowValues.length; j++)
			{
				tmpFilter[strName] = arShowValues[j];
				
				if (this.GetCanBuy(tmpFilter))
				{
					arCanBuyValues[arCanBuyValues.length] = arShowValues[j];
				}
			}
		}
		else
		{
			arCanBuyValues = arShowValues;
		}
		
		this.UpdateRow(i, arFilter[strName], arShowValues, arCanBuyValues);
	}
	
	this.selectedValues = arFilter;
	this.ChangeInfo();
};

window.JCCatalogElement.prototype.ChangeInfo = function()
{
	var i = 0,
		j,
		index = -1,
		obData = {},
		boolOneSearch = true,
		strPrice = '';

	for (i = 0; i < this.offers.length; i++)
	{
		boolOneSearch = true;
		for (j in this.selectedValues)
		{
			if (this.selectedValues[j] !== this.offers[i].TREE[j])
			{
				boolOneSearch = false;
				break;
			}
		}
		if (boolOneSearch)
		{
			index = i;
			break;
		}
	}
	
	if (-1 < index)
	{
		for (i = 0; i < this.offers.length; i++)
		{
			if (this.showOfferGroup && this.offers[i].OFFER_GROUP)
			{
				if (i !== index)
				{
					BX.adjust(BX(this.visual.OFFER_GROUP+this.offers[i].ID), { style: {display: 'none'} });
				}
			}
		}
		
		if (this.showOfferGroup && this.offers[index].OFFER_GROUP)
		{
			BX.adjust(BX(this.visual.OFFER_GROUP+this.offers[index].ID), { style: {display: ''} });
		}
		if (this.showSkuProps && !!this.obSkuProps)
		{
			if (0 === this.offers[index].DISPLAY_PROPERTIES.length)
			{
				BX.adjust(this.obSkuProps, {style: {display: 'none'}, html: ''});
			}
			else
			{
				BX.adjust(this.obSkuProps, {style: {display: ''}, html: this.offers[index].DISPLAY_PROPERTIES});
			}
		}
		if (!!this.obPrice)
		{
			if(BX('allSum') && BX('allSum_val'))	// for complete sets
			{
				var bCompleteSet = true;
				if(this.offerNumOld > -1)
					var deltaPrice = this.offers[index].PRICE.DISCOUNT_VALUE - this.offers[this.offerNumOld].PRICE.DISCOUNT_VALUE;
				else
					var deltaPrice = this.offers[index].PRICE.DISCOUNT_VALUE;
				
				var pricePrintTemplate = this.offers[index].PRICE.PRINT_DISCOUNT_VALUE.replace(/[\s,.]/g, '');
				var sumOld = parseFloat(BX('allSum_val').getAttribute('value'));
				var sumNew = sumOld + parseFloat(deltaPrice);
				sumNew = parseInt(Math.round(sumNew));
				BX('allSum').innerHTML = pricePrintTemplate.replace(/\d+/g , sumNew);
				BX('allSum_val').setAttribute('value', sumNew);
			}
			
			for(i = 0; i < this.obPrice.length; i++)
			{
				if(BX.findChild(this.obPrice[i], {'class':'rubl'}, true))
				{
					strRubSymbol = BX.findChild(this.obPrice[i], {'class':'rubl'}, true).outerHTML;
					strPrice = '<span class="allSumMain">';
					if(bCompleteSet && sumNew > 0)
						strPrice += sumNew + strRubSymbol;
					else
						strPrice += this.offers[index].PRICE.PRINT_DISCOUNT_VALUE.replace(/[^\d\s,]/g, '') + strRubSymbol;
					if (this.showOldPrice && (this.offers[index].PRICE.DISCOUNT_VALUE !== this.offers[index].PRICE.VALUE))
					{
						strPrice += ' <span class="oldprice">'+this.offers[index].PRICE.PRINT_VALUE.replace(/[^\d\s,]/g, '') + strRubSymbol +'</span>';
					}
					strPrice += '</span>';
				}
				else
				{
					strPrice = '<span class="allSumMain">';
					if(bCompleteSet && sumNew > 0 && pricePrintTemplate.length)
						strPrice += pricePrintTemplate.replace(/\d+/g , sumNew);
					else
						strPrice += this.offers[index].PRICE.PRINT_DISCOUNT_VALUE;
					if (this.showOldPrice && (this.offers[index].PRICE.DISCOUNT_VALUE !== this.offers[index].PRICE.VALUE))
					{
						strPrice += ' <span class="oldprice">'+this.offers[index].PRICE.PRINT_VALUE +'</span>';
					}
					strPrice += '</span>';
				}
					
				BX.adjust(this.obPrice[i], {html: strPrice});
			}
			
			if (this.showPercent)
			{
				if (this.offers[index].PRICE.DISCOUNT_VALUE !== this.offers[index].PRICE.VALUE)
				{
					obData = {
						style: {
							display: ''
						},
					html: this.offers[index].PRICE.DISCOUNT_DIFF_PERCENT
					};
				}
				else
				{
					obData = {
						style: {
							display: 'none'
						},
						html: ''
					};
				}
				if (!!this.obDscPerc)
				{
					BX.adjust(this.obDscPerc, obData);
				}
				if (!!this.obSecondDscPerc)
				{
					BX.adjust(this.obSecondDscPerc, obData);
				}
			}
		}
		if (!!this.obBuyBtn && this.offers[index].ID > 0)
		{
			obData = {
				attrs: { 'data-sku-id': this.offers[index].ID}
			};
			if(!!this.offers[index].CAN_BUY)
			{
				BX.removeClass(this.obBuyBtn, 'button_in_basket');
			}
			else
			{
				BX.addClass(this.obBuyBtn, 'button_in_basket');
			}
			BX.adjust(this.obBuyBtn, obData);
			if(!!this.obBuyBtnSet)
				BX.adjust(this.obBuyBtnSet, obData);
		}
		this.offerNumOld = index;
		this.offerNum = index;
		if(this.reloadPictures)
		{
			YS.Ajax.SetOption('ajaxMode' , 'element');
			YS.Ajax.SetOption('ELEMENT_ID' , this.offers[this.offerNum].ID);

			YS.Ajax.Start();  // for change all photos of element
		}
	}
};

window.JCCatalogElement.prototype.SelectOfferProp = function()
{
	
	var i = 0,
		value = '',
		strTreeValue = '',
		arTreeItem = [],
		RowItems = null,
		target = BX.proxy_context.options[BX.proxy_context.selectedIndex];

	if (!!target && target.hasAttribute('data-treevalue'))
	{
		strTreeValue = target.getAttribute('data-treevalue');
		arTreeItem = strTreeValue.split('_');
		if (this.SearchOfferPropIndex(arTreeItem[0], arTreeItem[1]))
		{
			RowItems = BX.findChildren(target.parentNode, {tagName: 'option'}, false);
			if (!!RowItems && 0 < RowItems.length)
			{
				for (i = 0; i < RowItems.length; i++)
				{
					value = RowItems[i].getAttribute('data-onevalue');
					if (value === arTreeItem[1])
					{
						BX.addClass(RowItems[i], 'bx_active');
					}
					else
					{
						BX.removeClass(RowItems[i], 'bx_active');
					}
				}
				
				$(target).selectBox('refresh');
			}
		}
	}
};

window.JCCatalogElement.prototype.SearchOfferPropIndex = function(strPropID, strPropValue)
{
	var strName = '',
		arShowValues = false,
		i, j,
		arCanBuyValues = [],
		index = -1,
		arFilter = {},
		tmpFilter = [];

	for (i = 0; i < this.treeProps.length; i++)
	{
		if (this.treeProps[i].ID === strPropID)
		{
			index = i;
			break;
		}
	}

	if (-1 < index)
	{
		for (i = 0; i < index; i++)
		{
			strName = 'PROP_'+this.treeProps[i].ID;
			arFilter[strName] = this.selectedValues[strName];
		}
		strName = 'PROP_'+this.treeProps[index].ID;
		arShowValues = this.GetRowValues(arFilter, strName);
		if (!arShowValues)
		{
			return false;
		}
		if (!BX.util.in_array(strPropValue, arShowValues))
		{
			return false;
		}
		arFilter[strName] = strPropValue;
		for (i = index+1; i < this.treeProps.length; i++)
		{
			strName = 'PROP_'+this.treeProps[i].ID;
			arShowValues = this.GetRowValues(arFilter, strName);
			if (!arShowValues)
			{
				return false;
			}
			if (this.showAbsent)
			{
				arCanBuyValues = [];
				tmpFilter = [];
				tmpFilter = BX.clone(arFilter, true);
				for (j = 0; j < arShowValues.length; j++)
				{
					tmpFilter[strName] = arShowValues[j];
					if (this.GetCanBuy(tmpFilter))
					{
						arCanBuyValues[arCanBuyValues.length] = arShowValues[j];
					}
				}
			}
			else
			{
				arCanBuyValues = arShowValues;
			}
			if (!!this.selectedValues[strName] && BX.util.in_array(this.selectedValues[strName], arCanBuyValues))
			{
				arFilter[strName] = this.selectedValues[strName];
			}
			else
			{
				arFilter[strName] = arCanBuyValues[0];
			}
			this.UpdateRow(i, arFilter[strName], arShowValues, arCanBuyValues);
		}
		this.selectedValues = arFilter;
		this.ChangeInfo();
	}
	return true;
};

window.JCCatalogElement.prototype.UpdateRow = function(intNumber, activeID, showID, canBuyID)
{
	var i = 0,
		showI = 0,
		value = '',
		countShow = 0,
		obData = {},
		pictMode = false,
		isCurrent = false,
		selectIndex = 0,
		currentShowStart = 0,
		RowItems = null;

	if (-1 < intNumber && intNumber < this.obTreeRows.length)
	{
		RowItems = BX.findChildren(this.obTreeRows[intNumber].LIST, {tagName: 'option'}, false);
		
		if (!!RowItems && 0 < RowItems.length)
		{
			countShow = showID.length;
			obData = {
				props: { className: '' },
				style: { },
				attrs: { selected: ''}
			};
			
			for (i = 0; i < RowItems.length; i++)
			{
				value = RowItems[i].getAttribute('data-onevalue');
				isCurrent = (value === activeID);
				
				if (BX.util.in_array(value, canBuyID))
				{
					obData.props.className = (isCurrent ? 'bx_active' : '');
					obData.attrs.selected = (isCurrent ? 'selected' : '');
				}
				else
				{
					obData.props.className = (isCurrent ? 'bx_active bx_missing' : 'bx_missing');
					obData.attrs.selected = '';
				}
				obData.style.display = 'none';
				obData.attrs.disabled = 'disabled';
				
				if (BX.util.in_array(value, showID))
				{
					obData.style.display = '';
					obData.attrs.disabled = '';
					if (isCurrent)
					{
						selectIndex = showI;
					}
					showI++;
				}
								
				BX.adjust(RowItems[i], obData);
			}
			$(this.obTreeRows[intNumber].LIST).selectBox('refresh');
		}
	}
};

window.JCCatalogElement.prototype.GetRowValues = function(arFilter, index)
{
	var i = 0,
		j,
		arValues = [],
		boolSearch = false,
		boolOneSearch = true;

	if (0 === arFilter.length)
	{
		for (i = 0; i < this.offers.length; i++)
		{
			if (!BX.util.in_array(this.offers[i].TREE[index], arValues))
			{
				arValues[arValues.length] = this.offers[i].TREE[index];
			}
		}
		boolSearch = true;
	}
	else
	{
		for (i = 0; i < this.offers.length; i++)
		{
			boolOneSearch = true;
			for (j in arFilter)
			{
				if (arFilter[j] !== this.offers[i].TREE[j])
				{
					boolOneSearch = false;
					break;
				}
			}
			if (boolOneSearch)
			{
				if (!BX.util.in_array(this.offers[i].TREE[index], arValues))
				{
					arValues[arValues.length] = this.offers[i].TREE[index];
				}
				boolSearch = true;
			}
		}
	}
	return (boolSearch ? arValues : false);
};