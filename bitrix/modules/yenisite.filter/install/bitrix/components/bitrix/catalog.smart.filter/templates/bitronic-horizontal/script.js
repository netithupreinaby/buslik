function ys_smartfilter_setCookie(c_name,value,exmsecond)
{
	var exdate=new Date();
	exdate.setTime(exdate.getTime() + exmsecond);
	var c_value=escape(value) + ((exmsecond==null) ? "" : "; expires="+exdate.toUTCString());
	document.cookie=c_name + "=" + c_value;
}


function JCSmartFilter(ajaxURL)
{
	this.ajaxURL = ajaxURL;
	this.form = null;
	this.timer = null;
}

JCSmartFilter.prototype.keyup = function(input)
{
	if(this.timer)
		clearTimeout(this.timer);
	this.timer = setTimeout(BX.delegate(function(){
		this.reload(input);
	}, this), 1000);
}

JCSmartFilter.prototype.click = function(checkbox)
{
	if(this.timer)
		clearTimeout(this.timer);
	this.timer = setTimeout(BX.delegate(function(){
		this.reload(checkbox);
	}, this), 1000);
}

JCSmartFilter.prototype.reload = function(input)
{
	this.position = BX.pos(input, true);
	this.form = BX.findParent(input, {'tag':'form'});
	if(this.form)
	{
		var values = new Array;
		values[0] = {name: 'ajax', value: 'y'};
		this.gatherInputsValues(values, BX.findChildren(this.form, {'tag':'input'}, true));

		YS.Ajax.showLoader();

		BX.ajax.loadJSON(
			this.ajaxURL,
			this.values2post(values),
			BX.delegate(this.postHandler, this)
		);
	}
}

JCSmartFilter.prototype.postHandler = function (result)
{
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
				for(var i in arItem.VALUES)
				{
					var ar = arItem.VALUES[i];
					var control = BX(ar.CONTROL_ID);
					if(control)
					{
						control.parentNode.parentNode.parentNode.className = ar.DISABLED? 'lvl2 lvl2_disabled': 'lvl2';
					}
				}
			}
		}
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
	YS.Ajax.hideLoader();
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

$(function() {
	$('.ys_no_available_link a').on('click', function(){
		var ib_id = $('#filter_ib_id').attr('value') ;
		ys_smartfilter_setCookie ('bitronic_f_Q_'+ib_id, 'N', 60000);
	});
	$('#set_filter').on('click', function(){
		var ib_id = $('#filter_ib_id').attr('value') ;
		
		//var expires = new Date();
		//expires.setTime(expires.getTime() + 3600);		
		var check_Quantity ;
		if($('#cf_Quantity').attr('checked') == 'checked')
			check_Quantity = 'Y' ;
		else
			check_Quantity = 'N' ;
		ys_smartfilter_setCookie ('bitronic_f_Q_'+ib_id, check_Quantity, 60000);
	});
	$('.ys-opt-labels').each(function() {
		if ( $(this).find('[checked="checked"]').length ) {
			$(this).show();
		}
	});
	
	$('.ye_tit').on('click', function() {
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
	});

	$("#ys_filter_bitronic input[type='text']").click(function() {
		$(this).select();		
	});

	if ( !$('#ye_mainmenu').length ) {
		$("#ys_filter_bitronic input[type='checkbox']").uniform();
	}
});