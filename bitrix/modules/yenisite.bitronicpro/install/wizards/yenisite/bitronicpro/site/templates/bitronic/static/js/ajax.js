YS.Ajax.Init = function(YSajaxURL, site_id, iblock_id, YSajaxLocation) // init in bitrix\templates\bitronic_XXX\components\bitrix\catalog\catalog\result_modifier.php
{
	this.YSajaxURL = YSajaxURL;
	this.site_id = site_id;
	this.iblock_id = iblock_id;
	if(YSajaxLocation)
		this.locationUrl = YSajaxLocation;
	this.timer = null;
	this.init_flag = true;
	if(this.YSajaxURL.indexOf("catalog_filter.php") + 1)
		this.ajaxMode = 'catalog';
	else if(this.YSajaxURL.indexOf("compare.php") + 1)
		this.ajaxMode = 'compare';
	else // default
		this.ajaxMode = 'catalog';
	this.SkipParams = [];
}
YS.Ajax.SetOption = function(option, value)
{
	if(typeof option != 'undefined' && typeof value != 'undefined')
		this[option] = value;
}
YS.Ajax.Start = function(e)
{
	if(e)
		this.target = e.target || e.srcElement; // hack for IE8
	if(this.timer)
		clearTimeout(this.timer);
	this.timer = setTimeout(BX.delegate(function(){
		this.Reload(e);
	}, this), 100);
}
YS.Ajax.Reload = function(e)
{
	if($(".f_loader").size()<=0)
	{
		if(this.ajaxMode == 'catalog')
			$(".catalog").before('<div class="f_loader"></div>');
		else if(this.ajaxMode == 'compare')
			$(".catalog-compare-result").before('<div class="f_loader"></div>');	
		else if(this.ajaxMode == 'element')
			$(".item_detail_pictures").prepend('<div class="f_loader"></div>');		
	}
	this.loaderObj = $(".f_loader");
	
	YSstartButtonLoader(this.loaderObj);
	YS.Ajax.showLoader(this.loaderObj);
	if(this.ajaxMode == 'catalog' || typeof this.ajaxMode == 'undefined')
		this.ReloadCatalog();
	else if(this.ajaxMode == 'compare')
		this.ReloadCompare(e);
	else if(this.ajaxMode == 'element')
		this.ReloadElement(e);
	
}
YS.Ajax.ReloadCatalog = function()
{	
	saveFilterParams(); // set in bitrix\templates\bitronic_XXX\static\js\system_script.js
	
	var values = new Array;
	values[0] = {name: 'ajax', value: 'y'};
	var ys_param = $('select[name="page_count"], #view_field, #order_field, #by_field, #PAGEN_field, #SECTION_CODE, #abcd, [name="sort_form"] [name="f_Quantity"]');
	var SkipParams = this.SkipParams;
	if(ys_param.size()>0)
		ys_param.each(function() {
			if(!($.inArray($(this).prop('id'),SkipParams)+1))
			{
				values.push( {name : $(this).prop('name'), value : $(this).val()});
			}
		});
	
	values = values.concat(fParams); // fParams get in saveFilterParams()
	
	this.SkipParams = [];
	YScatalogLoading(this, values); 
}
YS.Ajax.ReloadCompare = function(e)
{
	var params = {
		'ys_compare_ajax':'y',
		'site_id':this.site_id,
		'iblock_id':this.iblock_id
	}
	var form = $(this.target).closest('form');
	params['action'] = form.find('input[name=action]').val();
	var arCheckName = ['ID' , 'pr_code'];
	
	for(var i = 0; i < arCheckName.length; ++i) {
		var arPropDel = [];
		form.find('input[name^=' + arCheckName[i] + ']:checked').each(function(indx, element){
			arPropDel.push($(this).val());
		});
		params[arCheckName[i]] = arPropDel;
	}
	
	var arSelectName = ['id'];
	
	for(var i = 0; i < arSelectName.length; ++i) {
		form.find('select[name^=' + arSelectName[i] + '] option:selected').each(function(indx, element){
			params[arSelectName[i]] = $(this).val();
		});
	}
	
	var curPath = window.location.pathname;
	
	if(form.find('#compare_var_input').length)
		params[form.find('#compare_var_input').prop('name')] = form.find('#compare_var_input').val();
	
	
	var varLoaderObj = this.loaderObj;
	
	if(this.locationUrl && params['action'] == 'DELETE_FROM_COMPARE_RESULT' && params['ID'].length)
	{
		var curSefUrl = this.locationUrl;
		for(var i = 0; i < params['ID'].length; ++i) {
			
			var elCode = $('#product_detail_' + params['ID'][i]).attr('data-el-code');
			var re = new RegExp("(?:-vs-)?(" + elCode + ")(?=-vs-)?", "gi");
			curSefUrl = curSefUrl.replace(re, '');
			re = new RegExp("^-vs-", "gi");
			curSefUrl = curSefUrl.replace(re, '');
		}
		if(curSefUrl != this.locationUrl)
		{
			setLocation(curPath.replace(this.locationUrl , curSefUrl));
			this.locationUrl = curSefUrl;
		}			
	}
	var _this = this;
	$.post(this.YSajaxURL, params, function(data) {
		YSstopButtonLoader(varLoaderObj);
		YS.Ajax.hideLoader(varLoaderObj);
		
		
		var re = new RegExp("(/bitrix/templates/.+/ajax/)", "gi");
		var data = data.replace(re, curPath);
		var parent = $("<div>"+data+"</div>");
		
		// add new element-code in url
		if(_this.locationUrl && params['action'] == 'ADD_TO_COMPARE_RESULT')
		{
			parent.find("div.catalog-compare-result .data-table a[id^=product_detail_]").each(function(indx, element){
				var re = new RegExp("(?:-vs-)?(" + $(this).attr('data-el-code') + ")(?=-vs-)?", "gi");
				if(!re.test(_this.locationUrl))
				{
					setLocation(curPath.replace(_this.locationUrl , _this.locationUrl + '-vs-' + $(this).attr('data-el-code')));
					_this.locationUrl += '-vs-' + $(this).attr('data-el-code');
				}
			});
		}
		
		var $result = parent.find("div.catalog-compare-result");
		$("#container div.catalog-compare-result").html($result.length ? $result.html() : data);
		
		$("#container div.catalog-compare-result").find("input.checkbox, input.radio").filter(function(){
			return $(this).parents("[id^=uniform]").size() == 0;
		}).uniform();
		
		
	});
}
YS.Ajax.ReloadElement = function(e)
{

	var params = {
		'ys_filter_ajax':'y',
		'site_id': this.site_id,
		'ELEMENT_ID': this.ELEMENT_ID,
		'iblock_id': this.iblock_id
	}
	
	var ys_param = $('input[name="ys-request-uri"]');
	if(ys_param.size()>0) {
		ys_param.each(function() {
			params[$(this).prop('name')] = $(this).val();
		});
	}
	
	if(this.offers_iblock_id > 0)
		params['offers_iblock_id'] = this.offers_iblock_id;
	var varLoaderObj = this.loaderObj;
	
	//$.post(this.YSajaxURL, params, function(data) {
	$.ajax({url: this.YSajaxURL, type: 'POST', data: params, success: function(data) {
		var hiddenImg = new Image();
		
		var parent = $("<div>"+data+"</div>");
		if(parent.find('.item_detail_pictures_cont img:first').length)
		{
			hiddenImg.src = parent.find('.item_detail_pictures_cont img:first').attr('src');
		
			$(hiddenImg).one('load',function() {
				YSstopButtonLoader(varLoaderObj);
				YS.Ajax.hideLoader(varLoaderObj);
				
				$(".item_detail_pictures_cont").html(parent.find(".item_detail_pictures_cont").html()).removeAttr('style');
				$(".item_detail_pictures_cont").after(parent.find("#ys-resizer-content"));
			
				parent.html("<div>"+data+"</div>"); // for execute JS in data
			});
		}
		else
		{
			YSstopButtonLoader(varLoaderObj);
			YS.Ajax.hideLoader(varLoaderObj);
				
			$(".item_detail_pictures_cont").html(parent.find(".item_detail_pictures_cont").html()).removeAttr('style');
			$(".item_detail_pictures_cont").after(parent.find("#ys-resizer-content"));
			
			parent.html("<div>"+data+"</div>"); // for execute JS in data
		}
	}});
}
YScatalogLoading = function(_this, values) { // used in template of smart filter

	var params = {
		'ys_filter_ajax':'y',
		'site_id':_this.site_id,
		'iblock_id':_this.iblock_id
	}
	var param_string = "";
	if(values.length > 1)
	{
		
		param_string += "?";
		for(var i = 0; i < values.length; ++i) {
			params[values[i].name] = values[i].value
			if(values[i].name != "ajax" && values[i].value.length)
			{
				param_string += (values[i].name+"="+values[i].value);
				if(i !== values.length -1)
					param_string += "&";
			}
		}
		
	}
	if(_this.locationUrl)
	{
		setLocation(_this.locationUrl);
		$('[name="ys-request-uri"]').val(_this.locationUrl);
	}
	else
	{
		setLocation(param_string);
	}
	// var param_str = "ys_filter_ajax=y&site_id="+_this.site_id+"&iblock_id="+_this.iblock_id
	// var param_str2 = "ys_filter_ajax=y&amp;site_id="+_this.site_id+"&amp;iblock_id="+_this.iblock_id
	var folder = $('input[name="ys-folder-url"]');
	
	var ys_param = $('input[name="ys-folder-url"], [name="ys-request-uri"], [name="ys-script-name"]');
	if(ys_param.size()>0)
		ys_param.each(function() {
			params[$(this).prop('name')] = $(this).val();
		});
		
	$.post(_this.YSajaxURL, params, function(data) {
		YSstopButtonLoader(_this.loaderObj);
		YS.Ajax.hideLoader(_this.loaderObj);
		
		var re = new RegExp("(/bitrix/templates/.+/ajax/catalog_filter.php)", "gi");
		var data = data.replace(re, "");
		
		var curPath = window.location.pathname;
		var re = /view-\d+\//;
		var curPath = curPath.replace(re, "");
		var re = /sort-\d+\//;
		var curPath = curPath.replace(re, "");
		var re = /page_count-\d+\//;
		var curPath = curPath.replace(re, "");
		var re = /page-\d+\//;
		var curPath = curPath.replace(re, "");
		var re = new RegExp("(/bitrix/templates/.+/ajax/)", "gi");
		var data = data.replace(re, curPath);
		
		var parent = $("<div>"+data+"</div>");
		
		//parent.find("input.checkbox, input.radio").uniform();
		$("#container").find("select").selectBox('destroy');

		$("#container table.abcd").html(parent.find("table.abcd").html());
		
		$("#container form[name='sort_form']").html(parent.find("form[name='sort_form']").html())
		$("#container div.catalog").html(parent.find("div.catalog").html())
		/*include*/$("#container div.catalog").html(parent.find("div.catalog").html())
		$("#container div.pager-block").html(parent.find("div.pager-block").html())
		/*include*/$("#container div.pager-block").html(parent.find("div.pager-block").html())

		$("#container").find("select").selectBox();
		$("#container").find("label > input.checkbox, label > input.radio").uniform();

			/*slider popup
			 -------------------------------------------------------*/
		$("#container").find('.sl_wrapper li, .catalog-list li').hover(function () {
				$(this).find('.item-popup').show();
				$(this).css({'z-index': 50});

				$(this).find('.item-popup').addClass('item-hover');
			}, function () {
				var openedMenu = $(this).find('.selectBox-menuShowing');

				if (openedMenu.length != 1) {

					$(this).find('.item-popup').fadeOut();
					$(this).css({'z-index': 1});

					$(this).find('.item-popup').removeClass('item-hover');
				}
			});

			
				var minh = 0;
		$("#container").find('.catalog-list li').each(function(){
			if(minh == 0 || $(this).height() > minh) {
				minh = $(this).height();
			}
		});

		$("#container").find('.catalog-list li').css('height', minh + 'px');
		
		var re = /<div[^>]*id="container"[^>]*>([\s\S]*?)#container/i;		// CUT only div#container if ajax load full page
		if(re.exec(data) != null )
		{
			var data = re.exec(data)[0];
		}
		parent.html("<div>"+data+"</div>"); // for execute JS in data
	})
};
function setLocation(curLoc){
	try {
		history.pushState(null, null, curLoc);
		return;
	} catch(e) {}
		location.hash = '#' + curLoc.substr(1)
}

YS.Ajax.Utils = {
	getElementPicture: function (elem, resizerSet) {
	
		var picSrc = '';
		
		if(typeof resizerSet == 'undefined')
			return 'params error';
			
		var postData = {
			'sessid': BX.bitrix_sessid(),
			'action': 'GET_ELEMENT_PICTURE',
			'element': elem,
			'resizer_set': resizerSet
		}

		BX.ajax({
			url: SITE_TEMPLATE_PATH + '/ajax/utils.php',
			method: 'POST',
			async: false,
			data: postData,
			dataType: 'json',
			onsuccess: function(result)
			{
				picSrc = result.PICT_SRC;
			}
		});
		
		return picSrc;
	},
}