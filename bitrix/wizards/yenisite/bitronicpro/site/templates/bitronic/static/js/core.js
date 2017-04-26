function init_ys_guestbook_popup(popupDivID)
{
    var text = $('#'+popupDivID+' button.button2').html();
    var button_html = $("<span class='text show'>"+text+"</span><span class='notloader'></span>");
    $('#'+popupDivID+' button.button2').html(button_html);
    $('#'+popupDivID+' #ys-guestbook:visible').valid();
//    $('#'+popupDivID+' #ys-guestbook input:text').css('border', '1px red solid');
}


/*
 * LOADER API
 * -----------------*/
function startPopupLoader(loaderObj)
{
    loaderObj.WAIT_STATUS = true;
//        loaderObj.WAIT_MAX = 10;
    loaderObj.SYMBOLS = ['0', '1', '2', '3', '4', '5', '6', '7'];
    loaderObj.WAIT_START = 0;
    loaderObj.WAIT_CURRENT = loaderObj.WAIT_START;
    loaderObj.Rate = 10;
    loaderObj.WAIT_FUNC = function(){
        if(loaderObj.WAIT_STATUS)
        {
            loaderObj.html(loaderObj.SYMBOLS[loaderObj.WAIT_CURRENT]);
            loaderObj.WAIT_CURRENT = loaderObj.WAIT_CURRENT < loaderObj.SYMBOLS.length-1 ? loaderObj.WAIT_CURRENT + 1 : loaderObj.WAIT_START;
            setTimeout(loaderObj.WAIT_FUNC, 1000 / loaderObj.Rate);
        }
        else
            loaderObj.removeClass('loader').addClass('notloader');
    };

    loaderObj.removeClass('notloader').addClass('loader');
    loaderObj.WAIT_FUNC();
}
    
function stopPopupLoader(loaderObj)
{
    loaderObj.WAIT_STATUS = false;
}
    
function createLoader(popupDiv)
{
    var loader = findLoader(popupDiv);
    if (loader === false)
    {
        $(popupDiv).html("<span class='notloader ws' style='display: block; width: 100%; text-align: center; font-size: 18px;'>0</span>");
        loader = findLoader(popupDiv);
    }

    return loader;
}
    
function findLoader(popupDiv)
{
    var loader = $(popupDiv).find('span.notloader');
    if (loader.length > 0)
        return loader;
    else
        return false;
}

/*
 * POPUP API
 * ---------------------*/

function popupToCenter(popupDiv)
{
	/* wtf?
    var y = ($(window).height() - popupDiv.height()) / 2;
    var x = ($(window).width() - popupDiv.width()) / 2;
    popupDiv.css('top', y + 'px').css('left', x + 'px');
	*/// maybe you meant this?
	popupDiv.css({
		'left':'50%',
		'margin-left': '-' + (popupDiv.width()/2) + 'px'
	});
}
function popupAuthForm() 
{
	var url = SITE_TEMPLATE_PATH+'/ajax/auth_form.php';
	var div = $('div#login-popup');
	div.empty();
	div.fadeIn(300);
	$('#mask').fadeIn('300');

	var loaderObj = createLoader(div);
	startPopupLoader(loaderObj);
	popupToCenter(div);

	$.ajax({
		type: "POST",
		url: url,
		data: "",
		dataType : "html",
		success: function(data){
			stopPopupLoader(loaderObj);
			div.html(data);
			popupToCenter(div);
		}
	});
}

function v1ClickInitHandler()
{
	$('.button_v1click').unbind('click');
	$('a.button_v1click').click(function () {
		if(!$(this).hasClass('button_in_basket')){
			var id = $(this).attr('id');
			var url = SITE_TEMPLATE_PATH+'/ajax/fastorder_form.php';

			var div = $('div#ys-fastorder-popup');
			div.empty();
			div.fadeIn(300);
			$('#mask').fadeIn('300');
			
			var loaderObj = createLoader(div);
			startPopupLoader(loaderObj);
			popupToCenter(div);
			
			$.ajax({
				type: "POST",
				url: url,
				data: ({PRODUCT_ID: id}),
				dataType : "html",
				success: function(data){
					stopPopupLoader(loaderObj);
					div.html(data);
					popupToCenter(div);
				}
			});
		}
	});
}
// for composite technology
BX.addCustomEvent("onFrameDataReceived", v1ClickInitHandler);
BX.addCustomEvent("onFrameDataReceived", function() {
    $("#search_select, .toggle-list, .param_block select, .select, .typeselect").selectBox();
	
	$("input.checkbox, input.radio").filter(function(){
    return $(this).parents("[id^=uniform]").size() == 0;
	}).uniform();
	
	$('a.enter').off('click');
	$('a.enter').on('click', popupAuthForm);
});

$(document).ready(function () {	
	v1ClickInitHandler();
//empty links
	$('a[href=#]').click(function () {
		return false;
	});

	//select
	$('#search_field').click(function () {
		$(this).parent().find('.similar_items').slideToggle(200);
		$(this).toggleClass('active');
		$(this).val('');
	});


	/*BASKET
	 ------------------------------------------------*/
	$(window).scroll(function () {
		if ($(window).scrollTop() >= 100) {
			$('.basket-box').addClass('scrollBasket');
		} else {
			$('.basket-box').removeClass('scrollBasket');
		}
	});

	/*Menu
	 ------------------------------------------------*/
	$('#ye_mainmenu ul li').hover(function () {
		if ($(this).prev().is(".bornone")) {

			$(this).prev().removeClass('bornone');
		} else {
			$(this).prev().addClass('bornone');
		}
	});

	$('#ye_mainmenu ul li a').click(function () {
		if ($(this).parent().is(".act")) {
			$(this).parent().removeClass('act');
		} else {
			$(this).parent().addClass('act');
		}
	});
	


	/*INPUTS, SELECTS
	 -------------------------------------------------*/
	$("#search_select, .toggle-list, .param_block select, .select, .typeselect").selectBox();
	//$(".show_filter select").selectBox();

	$("input.checkbox, input.radio").filter(function(){
    return $(this).parents("[id^=uniform]").size() == 0;
	}).uniform();

	/*SEARCH
	 -------------------------------------------------*/
	$('#send-form input.text').focus(function () {
		$(this).val('');
	});
   
    $('.shop-info .phones').click(function(){
        var url = SITE_TEMPLATE_PATH+'/ajax/callback_phone.php';
        
        var div = $('div#ys-callback_phone-popup');
        div.empty();
        div.fadeIn(300);
        $('#mask').fadeIn('300');
    
        var loaderObj = createLoader(div);
        startPopupLoader(loaderObj);
        popupToCenter(div);
        
        $.ajax({
            type: "POST",
            url: url,
            data: "",
            dataType : "html",
            success: function(data){
                    stopPopupLoader(loaderObj);
                    div.html(data);
                    popupToCenter(div);
            }
        });
    });
    
	/* ENTER, LOGIN
	 ---------------------------------------------------*/
	$('a.enter').on('click',popupAuthForm);


	$('a.uid').click(function () {
		if ($('.user_menu').hasClass('closed')) {
			$('.basket-popup').removeClass('opened').addClass('closed').fadeOut();
			$('.user_menu').removeClass('closed').addClass('opened').fadeIn();
		} else {
			$('.user_menu').removeClass('opened').addClass('closed').fadeOut();
		}
		;
	});

	/*----FOR CLOSE USER MENU---*/
	//ON PUSH ESC
	$(document).keydown(function (eventObject) {
		//27 - ASCII code of button 'ESC'
		if (eventObject.which == 27)
			yenisite_um_close();
	});
	//ON CLICK OUTSIDE USER MENU
	var onUserMenu;
	$('div.user').mouseover(function () {
		onUserMenu = true;
	});
	$('div.user').mouseout(function () {
		onUserMenu = false;
	});

	// this code need for elements which changes during work page
	$('#fixbody a:not(div.user a)').on('click', function () {
		if (onUserMenu == false)
			yenisite_um_close();
	});

	$(document).on('click', function () {
		if (onUserMenu == false)
			yenisite_um_close();
	});
	/*   -------------------------------------------------------   */

	/*MASK
	 ------------------------------*/
	$(document).on('click', '#mask, a.close, a.ys-feedback_add_popup_close', function () {
		$('#mask, .popup, #pic_popup').hide();
        $('.qtip').remove();
	});
	/*----FOR CLOSE POPUP WINDOWS---*/
	//ON PUSH ESC
	$(document).keydown(function (eventObject) {
		//27 - ASCII code of button 'ESC'
		if (eventObject.which == 27) {
            //$('#mask, .popup, #pic_popup').hide();
            $('#mask, .popup, #pic_popup').click();
		}
	});


	/*BASKET POPUP
	 -----------------------------*/
	$(".count a.count_link").click(function () {
		if ($('#bag-popup').hasClass('closed')) {
			$('.basket-popup').removeClass('opened').addClass('closed');
			$('#bag-popup').removeClass('closed').addClass('opened');
			$('#bag-popup').parent().parent().addClass('up');
			/*$('#mask').show().addClass('glass');*/
			/*basket resize*/
			var hw = $(window).height();
			var ht = $('#bag-popup .bask_wr table').height();
			if (ht > (hw - 200)) {
				$('.count .bask_wr').css('height', hw - 300).addClass('hh');
			}
			;
			$(window).resize(function () {
				var hw = $(window).height();
				var ht = $('#bag-popup .bask_wr table').height();
				if (ht > (hw - 200)) {
					$('.count .bask_wr').css('height', hw - 300).addClass('hh');
				}
				;
			});
		}
		else {
			$('#bag-popup').removeClass('opened').addClass('closed');
			/*$('#mask').show().removeClass('glass');*/

		}
	});

	//$(".basket-box .compare .compare_link").on("click", function(){
	$(".basket-box").on("click", ".compare .compare_link", function () {
		//$(".compare .compare_link").click(function(){
		if ($('#compare-popup').hasClass('closed')) {
			//$('.basket-popup').removeClass('opened').addClass('closed');
			$('#compare-popup').removeClass('closed').addClass('opened');
			//alert('12345');
			/*basket resize*/
			var hw = $(window).height();
			var ht = $('#compare-popup .bask_wr table').height();
			if (ht > (hw - 200)) {
				$('.compare .bask_wr').css('height', hw - 200).addClass('hh');
			}
			;
			$(window).resize(function () {
				var hw = $(window).height();
				var ht = $('#compare-popup .bask_wr table').height();
				if (ht > (hw - 200)) {
					$('.compare .bask_wr').css('height', hw - 200).addClass('hh');
				}
				;
			});

		}
		else {
			;
			$('#compare-popup').removeClass('opened').addClass('closed');

		}
	});

	/*DETAIL IMG
	 -----------------------------*/
	
	// popup img click
	var ico = 0;
	var hiddenImg = new Image();
	
	$('.item_detail').on('click',".photoID a", function () {
		$('.pop_img img').attr('src', $(this).attr('href'));
		$('.pop_descr').html($(this).find('img').attr('alt'));

		var p = $('#pic_popup');

		var popup_height = p.height();
		if (popup_height < 400)
			popup_height = 663;
		var y = ($(window).height() - popup_height) / 2;
		var x = ($(window).width() - p.width()) / 2;

		//console.log('y=' + y + '; x=' + x + '; $(window).height()=' + $(window).height() + '; $(window).width()=' + $(window).width() + '; p.height()=' + p.height() + '; p.width()=' + p.width());

		$('#mask').show();
		p.css('top', y + 'px').css('left', x + 'px').show();

		ico = parseInt($(this).attr('class').replace('ico_', ''));
		
		hiddenImg.src = $(this).attr('href');
		checkPopupImgButtons();
		return false;
	});
	
	// select img 
	$('.item_detail').on('click',".item_gal a", function () {
	//$(".item_gal a").click(function(){                    
		var path = $(this).attr("data-image");
		var title = $(this).attr("title");
		var pathb = $(this).attr("href");
		var bufImg = new Image();
		if(path != $(".stick_img img").not('.mark_holiday').attr("src"))
		{
			$('.item_gal a').removeClass('act');
			$(".stick_img").closest('a').attr("href", pathb).attr("class", $(this).attr("class")).attr("title",title);
			$(this).addClass('act');
			bufImg.src = path;
			$(bufImg).one('load',function() {
				$(".stick_img img").not('.mark_holiday').animate(
					{opacity: "hide"},
					300,
					function(){
						$(this).attr("src", path);
						$(this).animate({opacity: "show"},300);
					}
				);
			});
		}
		return false;
	});
	
	// popup IMG next button
	
	var prevImgHeight = 500;
	var loaderObjPicPopupDetail = findLoader($('#pic_popup'));
	$('#pic_popup .next').click(function () {
		if($(this).hasClass('button_in_basket'))
			return;
		ico = ico + 1;
		if ($('.ico_' + ico).length) {
			//$('.pop_img img').attr('src', $('.ico_' + ico).attr('href'));
			$('.pop_descr').html($('.ico_' + ico).find('img').attr('alt'));
			
			hiddenImg.src =  $('.ico_' + ico).attr('href');
			startPopupLoader(loaderObjPicPopupDetail);
			loaderObjPicPopupDetail.show();
			if(prevImgHeight>0)
				$('.pop_img').css('height',prevImgHeight+'px');
			else $('.pop_img').css('height','500px');
			$('.pop_img img').hide();
			$(hiddenImg).one('load',function() {
				$('.pop_img').css('height','auto');
				$('.pop_img img').attr('src', hiddenImg.src);
				$('.pop_img img').show();
				prevImgHeight = $('.pop_img').height();
				stopPopupLoader(loaderObjPicPopupDetail);
				loaderObjPicPopupDetail.hide();
				checkPopupImgButtons();
			});
		}
		else ico = ico - 1;
	});
	
	// popup IMG prev button
	$('#pic_popup .prev').click(function () {
		if($(this).hasClass('button_in_basket'))
			return;
		ico = ico - 1;
		if (ico < 0) {
			ico = 0;
			return;
		}
		hiddenImg.src =  $('.ico_' + ico).attr('href');
			startPopupLoader(loaderObjPicPopupDetail);
			loaderObjPicPopupDetail.show();
			if(prevImgHeight>0)
				$('.pop_img').css('height',prevImgHeight+'px');
			else 
				$('.pop_img').css('height','500px');
			$('.pop_img img').hide();
			
			$(hiddenImg).one('load',function() {
				$('.pop_img').css('height','auto');
				$('.pop_img img').attr('src', hiddenImg.src);
				$('.pop_img img').show();
				prevImgHeight = $('.pop_img').height();
				stopPopupLoader(loaderObjPicPopupDetail);
				loaderObjPicPopupDetail.hide();
				
				checkPopupImgButtons();
			});
		//$('.pop_img img').attr('src', $('.ico_' + ico).attr('href'));
		$('.pop_descr').html($('.ico_' + ico).find('img').attr('alt'));
	});
	function checkPopupImgButtons()
	{
		if($('.ico_' + (ico+1)).size() > 0)
			$('#pic_popup .next').removeClass('button_in_basket');
		else
			$('#pic_popup .next').addClass('button_in_basket');
			
		if($('.ico_' + (ico-1)).size() > 0)
			$('#pic_popup .prev').removeClass('button_in_basket');
		else
			$('#pic_popup .prev').addClass('button_in_basket');
	}
	
	/* pic_popup in table view */
	createTablePopupImgLoader();
	$(document).ajaxComplete(createTablePopupImgLoader);

	/*BASKET DELETE
	 ------------------------------*/
	$('#compare-popup a.delete').click(function () {
		$(this).parent().parent().hide();
		var hw = $(window).height();
		var ht = $('#compare-popup .bask_wr table').height();
		if (ht < (hw - 200)) {
			$('.compare .bask_wr').css('height', 'auto');
		}
		;
	});

	$('#bag-popup a.delete').click(function () {
		$(this).parent().parent().hide();
		var hw = $(window).height();
		var ht = $('#bag-popup .bask_wr table').height();
		if (ht < (hw - 200)) {
			$('.count .bask_wr').css('height', 'auto');
		}
		;
	});

	/*slider popup
	 -------------------------------------------------------*/
	$('.sl_wrapper li, .catalog-list li').hover(function () {
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

	/*SLIDER
	 --------------------------------------------------------*/
	/*var slw = $('.sl_wrapper').width();
	 $('.sl_wrapper li').css({width : slw/4});
	 $(window).resize(function(){
	 var slw = $('.sl_wrapper').width();
	 $('.sl_wrapper li').css({width : slw/4});
	 });*/

	/*TV
	 ------------------------------------------------------*/
	/* #######  this code transfer in bitrix/templates/bitronic_XXX/components/yenisite/catalog.section.all/top_block/script.js #######
	#################################################################################################
	
	$('.tv_menu a').each(function () {
		if ($(this).text().length > 20)
			$(this).parent().addClass('wide');
	});

	var useAuto = $('#use').attr("value");
	var delayAuto = $('#delay').attr("value");

	if (useAuto == "Y") {
		var i = 0;
		setInterval(function () {

			i = i + 1;
			$('.tab_nav a').eq(i).click();
			if (i == $('.tv_pager ul.tab_nav li a').length) {
				i = 0;
			}
			;

		}, 1000 * delayAuto);
	}
	;

	*/
	/*ALT POPUP
	 --------------------------------------------------------*/
	/* $('.sl_img').append('<div class=img_popup><div class=top_line></div><div class=cont></div></div>');
	 $('.item-popup .sl_img img').hover(function(){
	 $(this).parent().find('div.img_popup .cont').html($(this).attr('alt'));
	 $(this).parent().find('.img_popup').show();
	 }, function(){
	 $('.sl_img .img_popup').fadeOut();
	 }); */

	/*BASKET ADD LABEL
	 ------------------------------------------------*/
	$('.add_to_basket').click(function () {
		$('#add_message').animate({ right: '0'}, 500).delay(2000).animate({ right: '-200'}, 800);
	});

	$('#add_message').click(function () {
		$('#add_message').animate({ right: '-200'}, 800);
	});

	/*MARK
	 -------------------------------------------------*/

	$('.catalog-list li, .catalog-list-view li, .item_detail_pic').each(function () {
		var mark = $(this).find('.mark').length;
		if (mark > 1) {
			$(this).find('.mark:eq(0)').css({'top': 30});
			$(this).find('.mark:eq(1)').css({'top': 60});
			$(this).find('.mark:eq(2)').css({'top': 90});
			$(this).find('.mark:eq(3)').css({'top': 120});
		}
	});


	/*ITEM DESCR TABS
	 ----------------------------------------------------*/
	for (var i = 0; i < 6; i++) {
		$('.slider_cat li:eq(' + i + '), .slider_cat li:eq(' + i + ') a.main').bind('click', { i: i }, function (event) {
			var data = event.data;
			var i = data.i

			$('.slider_cat li').removeClass('active');
			$('.slider_cat li:eq(' + i + ')').addClass('active');
			$('.tab_block').hide();
			$('.tab_block:eq(' + i + ')').show();
		});

	}

	/*CALENDAR
	 --------------------------------------------------*/
	/*$('.calc').datepicker({
	 firstDay: 1
	 });*/

	/*RESIZE ORG_MENU
	 --------------------------------------------------*/
	$(window).resize(function () {
		var orw = $('.ord_menu').width;
		if (orw < 1000) {
			$('.ord_menu li a').css({'PaddingLeft': 10});
		}
		;
	});

	/*SLIDER SELECTOR
	 ----------------------------------------------------*/
	/*$("#limit").slider({
	 range: true,
	 min: 0,
	 max: 500,
	 values: [75, 300],
	 slide: function(event, ui) {
	 $("#amount").val('' + ui.values[0] + ' ' + ui.values[1]);
	 }
	 });


	 if($("#amount").length > 0)
	 $("#amount").attr("value", '' + $("#limit").slider("values", 0) + ' ' + $("#limit").slider("values", 1));*/

	if ($('input[name="ys-sef"]').length != 1
		&& $('input[name="404"]').length != 1
		&& window.location.pathname.indexOf('.html') == -1
		&& window.location.pathname.indexOf('view-') != -1) {
		
		var tmp = window.location.href.split('/'),
			arTmp = [];

		for (var i = 0; i < tmp.length; i++) {
			if (tmp[i].indexOf('view-') == 0) continue;
			if (tmp[i].indexOf('sort-') == 0) continue;
			if (tmp[i].indexOf('page_count-') == 0) continue;
			if (tmp[i].indexOf('page-') == 0) continue;

			arTmp.push(tmp[i]);
		}

		var newLoc = arTmp.join('/');

		if (window.location != newLoc) {
			window.location.replace(newLoc);
		}
	}
	
	
	/*FLY CATALOG HEADER
	 ----------------------------------------------------*/
	if($('[name="sort_form"] .filter').size()>0)
	{
		var catalogHeaderOffsetTop = $('[name="sort_form"] .filter').offset().top;
		
		var basketFlyTop;
		
		function flyCatalogHeader() {
			
			if(window.pageYOffset>catalogHeaderOffsetTop && window.pageYOffset<$('.pager-block').offset().top)
			{	
				$('[name="sort_form"] .filter').css("position", "fixed");
				$('[name="sort_form"] .filter').css("top", "0px");
				$('[name="sort_form"] .filter').css("width", $('[name="sort_form"] .filter').parent().width()+'px');
				$('[name="sort_form"] .filter').css("background-color", $('#site').css("background-color"));
				$('[name="sort_form"] .filter').css("z-index", '51');
				
				if($('div.yen-bs-box').css('left').replace('px', '') > 585)
				{
					if($('div.yen-bs-box').hasClass('yen-bs-scrollBasket'))
					{
						$('div.yen-bs-box').removeClass('yen-bs-scrollBasket');
						basketFlyTop = $('div.yen-bs-box').css('top').replace('px', '');
					}
					$('div.yen-bs-box').css('position', 'fixed');
					$('div.yen-bs-box').css('top', ($('[name="sort_form"] .filter').outerHeight())+'px')
					return false; // that for does not works handler on SCROLL in bitrix:sale.basket.basket.small/bitronic/result.modifier
				}
			}
			else
			{
				$('[name="sort_form"] .filter').css("position", "relative");
				$('[name="sort_form"] .filter').css("width", "auto");
				$('[name="sort_form"] .filter').css("z-index", 'auto');
				if($('div.yen-bs-box').css('left').replace('px', '') > 585)
				{
					$('div.yen-bs-box').removeClass('yen-bs-scrollBasket');
					$('div.yen-bs-box').css('top', basketFlyTop + 'px');
				}
			}
		}
		
		$(document).scroll(flyCatalogHeader).ajaxComplete(flyCatalogHeader);
	}
	
	/*FOR CATALOG VIEW TABLE
	 ----------------------------------------------------*/
	$(".catalog").on('click','.catalog-list-light input[name="quantity"]',function() {
		$(this).select();		
	});
	$(".catalog").on('change','.catalog-list-light input[name="quantity"]',function() {
		if(Number($(this).val())<0)
			$(this).val(0);
		checkQuantityTable(); // set in bitrix\templates\bitronic_XXX\static\js\system_script.js
	});
	
});

$(window).load(createTipped);
$(document).ajaxComplete(createTipped);
function createTipped() {
	/*
	 * Demonstrations: Skins
	 */
	if (typeof Tipped == "object")
	{
	Tipped.hideAll();
	Tipped.create(".yen-bs-close[title]," +
		".phones[title]," +
		".close[title]," +
		".yen-settings-close[title]," +
		".yen-um-close[title]," +
		"#pic_popup .button7[title]," +
		"#pic_popup .button8[title]," +
		".pay a[title]," +
		".yen-bb-close[title]," +
		".button6[title]," +
		".yen-bs-button4[title]," +
		".yen-bs-button5[title]," +
		".button4[title]," +
		".button5[title]," +
		".button11[title]," +
		".button12[title]," +
		".search .s_submit[title]," +
		".yen-ys-button6[title]," +
		".button-eye[title]," +
		".star[title]," +
		".ys_close_add2b[title]", { skin: 'black' });

	/*Tipped.create("#demo_skins_dark");
	 Tipped.create("#demo_skins_black", { skin: 'black' });
	 Tipped.create("#demo_skins_light", { skin: 'light' });

	 Tipped.create("#demo_skins_white", { skin: 'white' });
	 Tipped.create("#demo_skins_yellow", { skin: 'yellow' });
	 Tipped.create("#demo_skins_gray", { skin: 'gray' });

	 Tipped.create("#demo_skins_blue", "Skins are optimized to look good on any background", { skin: 'blue' });
	 Tipped.create("#demo_skins_red", "Great for error messages", { skin: 'red' });
	 Tipped.create("#demo_skins_green", "A nice green skin", { skin: 'green' });

	 Tipped.create("#demo_skins_tiny", "Small black tooltips are always useful", { skin: 'tiny' });*/
	}
}

var YS = YS || {};

/**
 * Create new namespace
 *
 * @param {String} namespace name
 */
YS.namespace = function (ns_string) {
	var parts = ns_string.split('.'),
		parent = YS,
		i;

	if (parts[0] === 'YS') {
		parts = parts.slice(1);
	}

	for (i = 0; i < parts.length; i += 1) {
		if (typeof parent[parts[i]] === "undefined") {
			parent[parts[i]] = {};
		}
		parent = parts[i];
	}
	return parent;
};

YS.namespace('YS.Ajax');

YS.Ajax = (function () {

	/**
	 * Show AJAX loader
	 */
	showLoader = function (loaderObj) {
		if(typeof(loaderObj) != 'undefined')
			loaderObj.fadeIn(100);
		else
			$(".loader").fadeIn(100);
	},

	/**
	 * Hide AJAX loader
	 */
		hideLoader = function (loaderObj) {
			if(typeof(loaderObj) != 'undefined')
				loaderObj.fadeOut(500);
			else
				$(".loader").fadeOut(500);
		};

	return {
		showLoader: showLoader,
		hideLoader: hideLoader
	};
})();

	/*VOTE
	 ----------------------------------------------------*/
	 
if(!window.voteScript) window.voteScript =
{
	trace_vote: function(my_div, flag)
	{
		if(flag)
			while( $(my_div).length > 0 ) {
				$(my_div).addClass('star-over');
				my_div = $(my_div).prev();
			}
		else
			while( $(my_div).length > 0 ) {
				$(my_div).removeClass('star-over');
				my_div = $(my_div).prev();
			}
	},
	do_vote: function(div, parent_id, arParams)
	{
		var r = div.id.match(/^vote_(\d+)_(\d+)$/);

		var vote_id = r[1];
		var vote_value = r[2];

		function __handler(data)
		{
			var obContainer = document.getElementById(parent_id);
			if (obContainer)
			{
				//16a �� ������������, ��� ������ �������� ������ ���� ������� (�������� div ��� table)
				var obResult = document.createElement("DIV");
				//console.log(data);
				obResult.innerHTML = data;
				obContainer.parentNode.replaceChild(obResult.firstChild, obContainer);
			}
		}

		//PShowWaitMessage('wait_' + parent_id, true);

		var url = '/bitrix/components/bitrix/iblock.vote/component.php'
		
		arParams['vote'] = 'Y';
		arParams['vote_id'] = vote_id;
		arParams['rating'] = vote_value;

		var TID = CPHttpRequest.InitThread();
		CPHttpRequest.SetAction(TID, __handler);
		CPHttpRequest.Post(TID, url, arParams);
		jGrowl(T_IBLOCK_VOTE_MSG,'ok');
	}
}