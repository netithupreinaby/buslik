/* 	function for work with library jGrowl; script and css including in header.php  */
function jGrowl(text,theme,header) {
	if(theme=="error") {
		header = BX.message('JGROWL_ERROR');
	}

	$.jGrowl.defaults.position = 'bottom-left';		/* position of messages: top-left, top-right, bottom-left, bottom-right, center	*/
	$.jGrowl.defaults.check = 1000;					/* time in millisecond with which jGrowl will check posts to be removed from screen	*/
	$.jGrowl.defaults.closer = false;				/* show button 'Close all' 	*/
	$.jGrowl.closerTemplate = "Close all message";	/* message on button 'Close all'	*/
	$.jGrowl(text,{
		theme: 		theme,			/* theme of message. Create in 'bitronic_template/styles.css'	*/
		header: 	header,			/* title of message	*/
		life:		7000,			/* lifetime message in millisecond 	*/
		sticky: 	false,			/* closed only by user 	*/ 
		glue:		'after',		/* where show new message 'after' or 'before' last message 	*/
		//closeTemplate:	'',		/* symbol of button 'close'	*/
	});
}
/*	function for close user menu in header	*/
function yenisite_um_close() {
	if($('ul.user_menu').hasClass('opened')) {
		$('ul.user_menu').removeClass('opened').addClass('closed').fadeOut();
	}
}

function setSortFields(order, by, e) {
	$('#order_field, [name="order"]').attr('value', order);
	$('#by_field, [name="by"]').attr('value', by);
	e = e || window.event;
	ajaxButtonClick(e);
	return false;
}

function setViewField(view, e) {
	$('#view_field, [name="view"]').attr('value', view);
	e = e || window.event;
	ajaxButtonClick(e);
	return false;
}

function setQuantity(id, operation) {
	var q = $(id).attr('value');
	if(operation == '-' && q > 1)
		q --;
	if(operation == '+' )
		q++;	
	$(id).attr('value', q);	
	$('#BasketRefresh').attr('value', 'Y');
	
	document.forms['basket_form'].submit();
}

function setQuantityTable(id, operation, step) {
	if (!step) step = 1;
	step = Number(step) * 1000;
	var q = Number($(id).attr('value')) * 1000;
	if(operation == '-') q -= step;
	if(operation == '+') q += step;
	q /= 1000;
	if (q < 0 || isNaN(q))
		q = 0;
	$(id).attr('value', q);
	 
	checkQuantityTable();
}
function checkQuantityTable() {
	var reg = /\(\d*\.?\d*\)/;
	var text = $('[name="sort_form"] .filter .ajax_add2basket_t span').text();
	var nonZeroInput = $('input[name="quantity"][value!="0"]');
	var totalQuantity = 0;
	if(nonZeroInput.size()>0)
	{
		nonZeroInput.each(function(){
			var curVal = Number($(this).val());
			if (isNaN(curVal)) return;
			totalQuantity += curVal * 1000;
		});
		totalQuantity /= 1000;
	}
	if (totalQuantity > 0) {
		if(reg.test($('[name="sort_form"] .filter .ajax_add2basket_t span').text()))
			$('[name="sort_form"] .filter .ajax_add2basket_t span').text(text.replace(reg, "("+totalQuantity+")"));
		else
			$('[name="sort_form"] .filter .ajax_add2basket_t span').text(text+"("+totalQuantity+")");
		$('[name="sort_form"] .filter .ajax_add2basket_t').removeClass("button_in_basket");
	} else {
		$('[name="sort_form"] .filter .ajax_add2basket_t span').text(text.replace(reg, ""));
		$('[name="sort_form"] .filter .ajax_add2basket_t').addClass("button_in_basket");
	}
}
	
function setDelete(id) {
	$(id).attr('value', 'Y');	
	 $('#BasketRefresh').attr('value', 'Y');
	document.forms['basket_form'].submit();
}
function setDelay(id, val) {
	$(id).attr('value', val);	
	 $('#BasketRefresh').attr('value', 'Y');
	document.forms['basket_form'].submit();
}

function setPageCount(val, sef) {
	/*var loc = window.location.pathname.toString();

	if (loc.indexOf('page_count-') === -1) {
		window.location.pathname = loc + 'page_count-' + val + '/';
	} else {
		window.location.pathname = loc.replace(new RegExp('page_count-(\\d+)', 'g'), 'page_count-' + val);
	}

*/
	
	if(sef)
	{
		var loc = window.location.pathname.toString();

		if (loc.indexOf('page_count-') === -1) {
			YS.Ajax.locationUrl = loc + 'page_count-' + val + '/';
		} else {
			YS.Ajax.locationUrl = loc.replace(new RegExp('page_count-(\\d+)', 'g'), 'page_count-' + val);
		}
		
		YS.Ajax.locationUrl += window.location.search.toString();
	}
	$('[name="page_count"]').attr('value', val);
	
	if(window.pageYOffset > $('form[name=sort_form]').offset().top-60)
		$('html,body').animate({scrollTop: $('form[name=sort_form]').offset().top-60},800);
		
	YS.Ajax.Start();
	return false;
}

function YSErrorPlacement(error, element, popup)
{
    var elem = $(element),
    corners = ['left center', 'right center'],
    flipIt = elem.parents('td.right').length > 0 || elem.parents('span.right').length > 0;
 
    if(!error.is(':empty')) {
        elem.filter(':not(.valid)').qtip({
            overwrite: false,
            content: error,
            position: {
                my: corners[ flipIt ? 0 : 1 ],
                at: corners[ flipIt ? 1 : 0 ],
            },
            show: {
                event: false,
                ready: true
            },
            hide: true,
            style: {
                classes: 'qtip-red'
            }
        })
        .qtip('option', 'content.text', error);
        elem.css('border', '1px red solid');
    }
    else
    {
        elem.qtip('destroy');
        elem.css('border', '');
    }
	
	if(typeof(popup)!=='undefined')
		if (popup.validate().checkForm()) {
			$('.popup:visible button').removeClass("button_in_basket").removeAttr('disabled');
		}else{
			$('.popup:visible button').addClass("button_in_basket").attr('disabled','disabled');
		}
}
function saveFilterParams() {
	
	fParams = []; // Filter params
	
	//if kombox.filter is sef mode
	var form = $('.kombox-filter form');
	if(form.length)
	{
		if(form.data('sef') == 'yes'){
			len = fParams.length;
			return; //then break
		}
	}
	
	//len;
	setF = $('[name="sort_form"] input[name^=set_filter]');

	// Save filter parameters
	$('[name=sort_form] input[name^=arrFilter], [name=sort_form] input[id^=arrFilter]').each(function() {
		fParams.push({name: $(this).attr('name'), value: $(this).val()});
	});
	 
	if (setF.length && setF.val().length) {
		fParams.push({name: 'set_filter', value: $('input[name=set_filter]').val()});
	}
	len = fParams.length;
}
function ajaxButtonClick(e, noScroll)
{
	var el = $(e.target),
		next = el.next(),
		href = next.attr('href'),
		curr = $(e.currentTarget);
		
	if(noScroll != true && window.pageYOffset > $('form[name=sort_form]').offset().top-60)
		$('html,body').animate({scrollTop: $('form[name=sort_form]').offset().top-60},800);
	
	if (el.hasClass('button11') || el.hasClass('button12') ||
		el.attr('href') !== undefined && !el.hasClass('nav-hidden')) 
	{
		// hack for our SEF
		if ($('input[name="ys-sef"]').length)
		{
			saveFilterParams();
			
			if (el.attr('href') !== undefined) {
				next = el;
				href = next.attr('href');
			}

			if ( curr.hasClass('f_view')
				||  curr.hasClass('f_price')
				|| curr.hasClass('f_name')
				|| curr.hasClass('f_pop')
				|| curr.hasClass('f_sales')) 
			{

				if ( href.charAt(href.length - 1) !== '/' ) 
				{
					href += '/';
					next.attr('href', href);
				}
			}
			
			var url = href;
			if(window.komboxSefUrl){
				if(window.komboxSefUrl.length
				&& el.closest('.pager').length == 0
				&& el.closest('.one-step').length == 0) {
					
					if(url.charAt(url.length - 1) !== '/') 
						url += '/';

					url += 'filter/' + window.komboxSefUrl;
				}
			}

			if(len)
			{
				if (setF.length || $('input[name=smart-filter-params]').length) 
				{
					if(href.indexOf('?') < 0){
						href += '?';
						url += '?';
					}
					for (var i = 0; i < len; i += 1) 
					{
						if(href.indexOf(fParams[i].name) < 0)
						{
							href += fParams[i].name + '=' + fParams[i].value;
							url += fParams[i].name + '=' + fParams[i].value;
							if (i != len - 1) 
							{
								href += '&';
								url += '&';
							}
						}
					}
				}
			}
			
			next.attr('href', encodeURI(href));
			if (typeof href !=="undefined") 
				YS.Ajax.locationUrl = encodeURI(url);
			//window.location = encodeURI(href);
		}// if ($('input[name="ys-sef"]').length)
	}
	e.preventDefault ? e.preventDefault() : e.returnValue = false;  // hack for IE8
	YS.Ajax.Start(e);
}
$(function() {
	
	$('.pager-block').on('click', '.one-step a, .pager a', function(e) {
		if ($(e.target).hasClass('nav-hidden') || $(e.target).hasClass('active')) {
			e.preventDefault();
		}
		else
		{
			if($('form.smartfilter').size()>0 && $('form.smartfilter input[type=hidden][name^=PAGEN_]').size()<=0)
				$('form.smartfilter').prepend('<input type="hidden" name="PAGEN_1" id="PAGEN_1">');
			else if($('.kombox-filter form').size()>0 && $('.kombox-filter form input[type=hidden][name^=PAGEN_]').size()<=0)
				$('.kombox-filter form').prepend('<input type="hidden" name="PAGEN_1" id="PAGEN_1">');
			if(typeof($(e.target).attr('data-page')) !=='undefined')
			{
				$('#PAGEN_field').attr('value', $(e.target).attr('data-page'));
				$('.kombox-filter input[name^=PAGEN_], form.smartfilter input[name^=PAGEN_]').attr('value', $(e.target).attr('data-page'));
			}
			else
				$('#PAGEN_field').attr('value', '');
				
			ajaxButtonClick(e);
		}
	});
	
	$('table.abcd').on('click' , 'a', function(e) {
		if($('form.smartfilter').size()>0 && $('form.smartfilter input[type=hidden][name=letter]').size()<=0)
			$('form.smartfilter').prepend('<input type="hidden" name="letter" id="letter">');
		else if($('.kombox-filter form').size()>0 && $('.kombox-filter form input[type=hidden][name=letter]').size()<=0)
			$('.kombox-filter form').prepend('<input type="hidden" name="letter" id="letter">');
		$('input[name="letter"]').attr('value', $(this).attr('data-abcd'));
		YS.Ajax.SkipParams.push('PAGEN_field');
		ajaxButtonClick(e);
	});
	$("#search_select").change(function(){	    
	    var selectVal = $('#search_select :selected').val();	   
		$("#search_form").attr("action",  selectVal);
	});
	
	$(".s_submit").click(function(){
		$("#search_form").submit();
	});


	var minh = 0;
    $('.catalog-list li').each(function(){
        if(minh == 0 || $(this).height() > minh) {
        	minh = $(this).height();
        }
    });

    $('.catalog-list li').css('height', minh + 'px');

    $('a[href*="ADD2BASKET"]').find('button').click(function(){
		var par = $(this).parent();

		if(par.attr("href")) {
			window.location = par.attr("href");
		} else {
			par = $(this).parent().parent();
			window.location = par.attr("href");
		}
		return false;
	});
});

function YSstartButtonLoader(loaderObj)
{
    loaderObj.VALUE = loaderObj.val();
    loaderObj.WAIT_STATUS = true;
    loaderObj.SYMBOLS = ['0', '1', '2', '3', '4', '5', '6', '7'];
    loaderObj.WAIT_START = 0;
    loaderObj.WAIT_CURRENT = loaderObj.WAIT_START;
    loaderObj.Rate = 10;
    loaderObj.WAIT_FUNC = function(){
        if(loaderObj.WAIT_STATUS)
        {
            loaderObj.css('font-family', 'WebSymbolsLigaRegular');
            //loaderObj.siblings('span.text').removeClass('show').addClass('hide');
            loaderObj.html(loaderObj.SYMBOLS[loaderObj.WAIT_CURRENT]);
            loaderObj.WAIT_CURRENT = loaderObj.WAIT_CURRENT < loaderObj.SYMBOLS.length-1 ? loaderObj.WAIT_CURRENT + 1 : loaderObj.WAIT_START;
            setTimeout(loaderObj.WAIT_FUNC, 1000 / loaderObj.Rate);
        }
        else
            loaderObj.removeClass('loader').parent().prop("disabled", false).removeClass('active').removeClass('disable');
    };
    
    loaderObj.addClass('loader').parent().prop("disabled", true).addClass('active').addClass('disable');
    loaderObj.WAIT_FUNC();
}

function YSstopButtonLoader(loaderObj)
{
    loaderObj.WAIT_STATUS = false;
}
function createTablePopupImgLoader()
{
	var hiddenImg = new Image();
	var loaderObjPicPopupTable = findLoader($('#pic-table-popup'));
	$('a.table_big_img').mouseover(function () {
		var p = $('#pic-table-popup');
		
		if(!(hiddenImg.src.indexOf($(this).attr('rel')) + 1))
		{
			hiddenImg.src = $(this).attr('rel');
			startPopupLoader(loaderObjPicPopupTable);
			loaderObjPicPopupTable.show();
			$('.pop_img img').hide();
			$(hiddenImg).load(function() {
				$('.pop_img img').attr('src', hiddenImg.src);
				$('.pop_img img').show();
				stopPopupLoader(loaderObjPicPopupTable);
				loaderObjPicPopupTable.hide();
			});
		}
		
		var img_pos = $(this).children('img').position();
		var x = img_pos.left - p.width() - 5;
		
		p.css('top', img_pos.top + 'px').css('left', x + 'px').show();
		
	}).mouseout(function () {
		$('#pic-table-popup').hide();
	});
}