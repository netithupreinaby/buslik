if (window.location.hash != '' && !(window.history && history.pushState)) {
	var uri = window.location.hash.replace('#', '?');
	window.location.href = document.location.pathname + uri;
}

function ys_smartfilter_setCookie(c_name,value,exmsecond)
{
	var exdate=new Date();
	exdate.setTime(exdate.getTime() + exmsecond);
	var c_value=escape(value) + ((exmsecond==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
}

function JCSmartFilter(ajaxURL, ajax_enable, YSajaxURL, site_id, iblock_id)
{
	this.ajaxURL = ajaxURL;
	this.ajax_enable = ajax_enable;
	this.YSajaxURL = YSajaxURL;
	this.site_id = site_id;
	this.iblock_id = iblock_id;
	this.form = null;
	this.timer = null;
	
	var bitronicAjaxFunctionName = "YScatalogLoading"; // set in bitrix\templates\bitronic_XXX\static\js\ajax.js
	var getType = {};
	// if not exist function YScatalogLoading
	if ( !window[bitronicAjaxFunctionName] || getType.toString.call(window[bitronicAjaxFunctionName]) !== '[object Function]')
		this.ajax_enable = "N";
}

var YSFilterRemoveDisable = false;

JCSmartFilter.prototype.keyup = function(input)
{
	$('#set_filter').addClass('disabled').prop('disabled', true);
	$('#del_filter').addClass('disabled').filter('button').prop('disabled', true);
	if(this.timer)
		clearTimeout(this.timer);
	this.timer = setTimeout(BX.delegate(function(){
		$('#set_filter').removeClass('disabled').prop('disabled', false);
		YSFilterRemoveDisable = true;
		this.reload(input);
	}, this), 1000);
}

JCSmartFilter.prototype.click = function(checkbox)
{
	$('#set_filter').addClass('disabled').prop('disabled', true);
	$('#del_filter').addClass('disabled').filter('button').prop('disabled', true);
	if(this.timer)
		clearTimeout(this.timer);
	this.timer = setTimeout(BX.delegate(function(){
		$('#set_filter').removeClass('disabled').prop('disabled', false);
		YSFilterRemoveDisable = true;
		this.reload(checkbox);
	}, this), 1000);
}

var YSFilterLastValuesLength;

JCSmartFilter.prototype.reload = function(input)
{
	this.position = BX.pos(input, true);
	this.form = BX.findParent(input, {'tag':'form'});
	if(this.form)
	{
		var values = new Array;
		values[0] = {name: 'ajax', value: 'y'};
		values[1] = {name: 'set_filter', value: 'y'};
		this.gatherInputsValues(values, BX.findChildren(this.form, {'tag':'input'}, true));

		if(this.ajax_enable == "N") 
		{
			this.loaderObj = $("#set_filter span.notloader");
		}
		else
		{
			this.loaderObj = $(".f_loader");
		}
		if(this.reload_filter != "N")
		{
			BX.ajax.loadJSON(
				this.ajaxURL,
				this.values2post(values),
				BX.delegate(this.postHandler, this)
			);
		}
		YSstartButtonLoader(this.loaderObj);
		YS.Ajax.showLoader(this);
		if(this.ajax_enable != "N") {
			YScatalogLoading(this, values); // set in bitrix\templates\bitronic_XXX\static\js\ajax.js
			YSFilterLastValuesLength = values.length;
		}
	}
}

JCSmartFilter.prototype.postHandler = function (result)
{
	if(this.ajax_enable == "N") 
	{
		YSstopButtonLoader(this.loaderObj);
	}
	if (YSFilterRemoveDisable) {
		$('#del_filter').removeClass('disabled').filter('button').prop('disabled', false);
	}

	if(result.ITEMS)
	{
		for(var PID in result.ITEMS)
		{
			var arItem = result.ITEMS[PID];
			if(arItem.PROPERTY_TYPE == 'N' || arItem.PRICE)
			{
			}
			else if(arItem.VALUES)
			{
				// for values which is not in arItem.VALUES
				$('.ys-opt-labels label[for^=arrFilter_' + arItem.ID + ']').prop("disabled", true).attr("class", "lvl2 lvl2_disabled");
				
				for(var i in arItem.VALUES)
				{
					var ar = arItem.VALUES[i];
					var control = BX(ar.CONTROL_ID);
					if(control)
					{
						BX.findParent(control, {'class':'lvl2'}).className = ar.DISABLED? 'lvl2 lvl2_disabled': 'lvl2';
						//control.parentNode.parentNode.parentNode.className = ar.DISABLED? 'lvl2 lvl2_disabled': 'lvl2';
						if(ar.DISABLED)	control.setAttribute('disabled','disabled');
						else control.removeAttribute('disabled');
					}
					else
					{
						if($('label[for^=arrFilter_' + arItem.ID + ']').size()>0)
						{
							$('label[for^=arrFilter_' + arItem.ID + ']:last').after('<br><label for="'+ ar.CONTROL_ID +'" class="lvl2"><input type="checkbox" class="checkbox" value="'+ ar.HTML_VALUE +'" name="'+ ar.CONTROL_NAME +'" id="'+ ar.CONTROL_ID +'" onclick="smartFilter.click(this)" />'+ ar.VALUE +'</label>');
							$('#'+ar.CONTROL_ID).uniform();
						}
					}
				}
			}
		}
		if(this.ajax_enable != "N" || !YSFilterRemoveDisable)
			return;
		var modef = BX('ye_result');
		var modef_num = BX('modef_num');
		var idea = $('#ye_idea');

		if ($('#ys_sections').length == 1 && result.ELEMENT_COUNT == 0) {
			if(modef.style.display == 'block') {
				modef.style.display = 'none';
			}
			idea.show();
		} else if(modef && modef_num) {
			modef_num.innerHTML = result.ELEMENT_COUNT;
			var hrefFILTER = BX.findChildren(modef, {tag: 'A'}, true);

			if(result.FILTER_URL && hrefFILTER)
				hrefFILTER[0].href = BX.util.htmlspecialcharsback(result.FILTER_URL);

			if(result.FILTER_AJAX_URL && result.COMPONENT_CONTAINER_ID)
			{
				BX.bind(hrefFILTER[0], 'click', function(e)
				{
					var url = BX.util.htmlspecialcharsback(result.FILTER_AJAX_URL);
					BX.ajax.insertToNode(url, result.COMPONENT_CONTAINER_ID);
					return BX.PreventDefault(e);
				});
			}

			if (result.INSTANT_RELOAD && result.COMPONENT_CONTAINER_ID) {
				var url = BX.util.htmlspecialcharsback(result.FILTER_AJAX_URL);
				BX.ajax.insertToNode(url, result.COMPONENT_CONTAINER_ID);
			} else {
				if(modef.style.display == 'none') {
					modef.style.display = 'block';
					if (idea.css('display') === 'block') {
						idea.hide();
					}
				}
				modef.style.top = this.position.top + 'px';
			}
		}
	}
	YS.Ajax.hideLoader(this);
}

JCSmartFilter.prototype.gatherInputsValues = function (values, elements)
{
	if(elements)
	{
		for(var i = 0; i < elements.length; i++)
		{
			var el = elements[i];
			if (el.disabled || !el.type)
				continue;

			switch(el.type.toLowerCase())
			{
				case 'text':
				case 'textarea':
				case 'password':
				case 'hidden':
				case 'select-one':
					if(el.value.length)
						values[values.length] = {name : el.name, value : el.value};
					break;
				case 'radio':
				case 'checkbox':
					if(el.checked)
						values[values.length] = {name : el.name, value : el.value};
					break;
				case 'select-multiple':
					for (var j = 0; j < el.options.length; j++)
					{
						if (el.options[j].selected)
							values[values.length] = {name : el.name, value : el.options[j].value};
					}
					break;
				default:
					break;
			}
		}
	}
}

JCSmartFilter.prototype.values2post = function (values)
{
	var post = new Array;
	var current = post;
	var i = 0;
	while(i < values.length)
	{
		var p = values[i].name.indexOf('[');
		if(p == -1)
		{
			current[values[i].name] = values[i].value;
			current = post;
			i++;
		}
		else
		{
			var name = values[i].name.substring(0, p);
			var rest = values[i].name.substring(p+1);
			if(!current[name])
				current[name] = new Array;

			var pp = rest.indexOf(']');
			if(pp == -1)
			{
				//Error - not balanced brackets
				current = post;
				i++;
			}
			else if(pp == 0)
			{
				//No index specified - so take the next integer
				current = current[name];
				values[i].name = '' + current.length;
			}
			else
			{
				//Now index name becomes and name and we go deeper into the array
				current = current[name];
				values[i].name = rest.substring(0, pp) + rest.substring(pp+1);
			}
		}
	}
	return post;
}

function YSstartButtonLoader(loaderObj)
{
    loaderObj.VALUE = loaderObj.val();
	loaderObj.HTML = loaderObj.html();
	//loaderObj.disabledProp = loaderObj.parent().prop("disabled");
	//loaderObj.disabledClass = loaderObj.parent().hasClass("disabled");
    loaderObj.WAIT_STATUS = true;
    loaderObj.SYMBOLS = ['0', '1', '2', '3', '4', '5', '6', '7'];
    loaderObj.WAIT_START = 0;
    loaderObj.WAIT_CURRENT = loaderObj.WAIT_START;
    loaderObj.Rate = 10;
    loaderObj.WAIT_FUNC = function(){
        if(loaderObj.WAIT_STATUS)
        {
            loaderObj.css('font-family', 'WebSymbolsLigaRegular');
            loaderObj.siblings('span.text').removeClass('show').addClass('hide');
            loaderObj.html(loaderObj.SYMBOLS[loaderObj.WAIT_CURRENT]);
            loaderObj.WAIT_CURRENT = loaderObj.WAIT_CURRENT < loaderObj.SYMBOLS.length-1 ? loaderObj.WAIT_CURRENT + 1 : loaderObj.WAIT_START;
            setTimeout(loaderObj.WAIT_FUNC, 1000 / loaderObj.Rate);
        }
        else {
            loaderObj.removeClass('loader').parent().prop("disabled", false).removeClass('active').removeClass('disable');
			//if (!loaderObj.disabledClass) loaderObj.parent().removeClass("disabled");
		}
    };
    
    loaderObj.addClass('loader').parent().prop("disabled", true).addClass('active').addClass('disable');
    loaderObj.WAIT_FUNC();
}

var buttonLoader;

function YSstopButtonLoader(loaderObj)
{
    loaderObj.WAIT_STATUS = false;
	if (buttonLoader.WAIT_STATUS) {
		buttonLoader.WAIT_STATUS = false;
		setTimeout( function(){
			buttonLoader.html(buttonLoader.HTML).removeAttr("style").parent().prop("disabled", true).addClass("disabled");
		}, 110);
	}
}
YS.Ajax.showLoader = function (_this) {
	if(_this.ajax_enable != "N") 
		$(".f_loader").fadeIn(100);
	else
		$(".loader").fadeIn(100);
}
YS.Ajax.hideLoader = function (_this) {
	if(_this.ajax_enable != "N") 
		$(".f_loader").fadeOut(500);
	else
		$(".loader").fadeOut(500);
}

$(function() {
	buttonLoader = $("#set_filter span.text");
	
	var vls = new Array;
	vls[0] = '0';
	vls[1] = '1';
	smartFilter.form = $("#smartfilter form")[0];
	smartFilter.gatherInputsValues(vls, BX.findChildren(smartFilter.form, {'tag':'input'}, true));
	YSFilterLastValuesLength = vls.length;
	
	
	$('#smartfilter').on('click', '.ys_no_available_link a', function(){
		var ib_id = $('#filter_ib_id').attr('value') ;
		ys_smartfilter_setCookie ('bitronic_f_Q_'+ib_id, 'N', 60000);
	});
	$('#smartfilter').on('click', '.ys_quantity_chbx', function(){
		var chk = $(this).find('input[name=f_Quantity]')
		var spanchk = $(this).find('span')
		if(spanchk.attr("checked") == "checked")
			chk.attr("value", "N")		
		else
			chk.attr("value", "Y")
		//var ib_id = $('#filter_ib_id').attr('value') ;
		
		//var expires = new Date();
		//expires.setTime(expires.getTime() + 3600);		
		/*var check_Quantity ;
		if($('#cf_Quantity').attr('checked') == 'checked')
			check_Quantity = 'Y' ;
		else
			check_Quantity = 'N' ;
		ys_smartfilter_setCookie ('bitronic_f_Q_'+ib_id, check_Quantity, 60000);*/
	});
	$('.ys-opt-labels').each(function() {
		if ( $(this).find('[checked="checked"]').length ) {
			$(this).show();
		}
	});
	
	$('#smartfilter').on('click', '.ys-opt', function() {
		if ($(this).hasClass('ys-hide')) {
			$(this).parent().next().animate({height: 'show'}, 300);
			$(this).addClass('ys-show').removeClass('ys-hide');
		} else {
			$(this).parent().next().animate({height: 'hide'}, 300);
			$(this).addClass('ys-hide').removeClass('ys-show');
		}
	});

	$('.ys-props-toggler').on('click', function() {
		var prev = $(this).prev(),
			next = $(this).next();

		if ($(this).hasClass('ys-props-hide')) {
			prev.animate({height: 'show'}, 300);
			$(this).addClass('ys-props-show').removeClass('ys-props-hide');

			if ($(this).hasClass('ys-props-more')) {
				$(this).hide();
				next.show();

				next.removeClass('ys-props-hide').addClass('ys-props-show');
			}

		} else {
			prev.prev().animate({height: 'hide'}, 300);
			$(this).addClass('ys-props-hide').removeClass('ys-props-show');

			if ($(this).hasClass('ys-props-less')) {
				$(this).hide();
				prev.show();

				prev.removeClass('ys-props-show').addClass('ys-props-hide');
			}
		}
		return false;
	});

	$("#smartfilter").on('click', "#ys_filter_bitronic input[type='text']", function() {
		$(this).select();		
	});

	if ( !$('#ye_mainmenu').length ) {
		$("#ys_filter_bitronic input[type='checkbox']").uniform();
	}
	
	var bitronicAjaxFunctionName = "YScatalogLoading"; // set in bitrix\templates\bitronic_XXX\static\js\ajax.js
	var getType = {};
	// if exist function YScatalogLoading
	if ( window[bitronicAjaxFunctionName] && getType.toString.call(window[bitronicAjaxFunctionName]) === '[object Function]')
	{
		$("body").on('click', '#smartfilter .showchild, #set_filter', function(e) {
			e.preventDefault();
			if ($(this).is($("#set_filter")) && $("#set_filter").prop('disabled')) return;
			//$("#set_filter").prop('disabled', true).addClass('disabled');
			$('#ye_result').hide();
			smartFilter.ajax_enable = "Y";
			smartFilter.reload_filter = "N";
			smartFilter.reload(e.target);
			smartFilter.ajax_enable = "N";
			smartFilter.reload_filter = "";
			YSstartButtonLoader(buttonLoader);
		});

		$("body").off('click', '#del_filter').on('click', '#del_filter', function(e) {
			e.preventDefault();
			if ($(this).hasClass('disabled')) return;
			$('#ye_result').hide();

			smartFilter.form = BX.findParent(e.target, {'tag':'form'});
			
			// clear slider polzunki
			$(smartFilter.form).find('[id^=limit-]').each(function(){
				var sliderOptions = $(this).slider("option");
				$(this).slider("values", [sliderOptions.min, sliderOptions.max]);
			});
			// clear slider input
			$(smartFilter.form).find('input[type=text]').val('');
			// clear checkbox
			$(smartFilter.form).find('input[type=checkbox]').prop('checked',false).closest('span.checked').removeClass('checked');
			
			
			var values = new Array;
			
			values[0] = {name: 'ajax', value: 'y'};
			values[1] = {name: 'del_filter', value: 'y'};
			smartFilter.gatherInputsValues(values, BX.findChildren(smartFilter.form, {'tag':'input'}, true));
			
			YSFilterRemoveDisable = false;
			//reload filter content
			BX.ajax.loadJSON(
				smartFilter.ajaxURL,
				smartFilter.values2post(values),
				BX.delegate(smartFilter.postHandler, smartFilter)
			);
			/*
			if(smartFilter.ajax_enable == "Y")
			*/
			if (YSFilterLastValuesLength != values.length) {
				//reload catalog content
				smartFilter.loaderObj = $(".f_loader");
				YSstartButtonLoader(smartFilter.loaderObj);
				YS.Ajax.showLoader(smartFilter);
				YScatalogLoading(smartFilter, values); // set in bitrix\templates\bitronic_XXX\static\js\ajax.js
			}
			YSFilterLastValuesLength = values.length;
			
			$(this).addClass('disabled').filter('button').prop('disabled', true);/*
			if (YSFilterLastValuesLength == values.length) {*/
				$("#set_filter").addClass('disabled').prop('disabled', true);/*
			} else {
				$("#set_filter").removeClass('disabled').prop('disabled', false);
			}*/
		});
	}
	
	if (typeof Tipped == "object") {
		Tipped.create('#ys_filter_bitronic .ye_q', {skin: 'white', radius: 5});
	}
	
});


/*SHOW HIDE FILTR*/
$(function() {
	$('.hide_filtr').on('click', function(e) {
		$(this).hide();
		$("#smartfilter").find(".ys-expand_table").addClass("toggleContent1").slideUp(400);		
		$('.show_filtr').show();
		$('html, body').animate({
			scrollTop: ($("#ys_filter_bitronic").offset().top - 40)
		}, 400);
		return false;
	});
	
	$('.show_filtr').on('click', function(e) {
		e.preventDefault();
		$(this).hide();
		$("#smartfilter").find(".ys-expand_table").slideDown(400);
		$('.hide_filtr').show();
		return false;
	});

});