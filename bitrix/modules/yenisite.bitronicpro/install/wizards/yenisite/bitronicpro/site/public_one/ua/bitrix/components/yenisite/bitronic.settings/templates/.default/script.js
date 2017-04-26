(function($) {
	/**
	 * Runs functions given in arguments in series, each functions passing their results to the next one.
	 * Return jQuery Deferred object.
	 *
	 * @example
	 * $.waterfall(
	 *    function() { return $.ajax({url : first_url}) },
	 *    function() { return $.ajax({url : second_url}) },
	 *    function() { return $.ajax({url : another_url}) }
	 *).fail(function() {
 *    console.log(arguments)
 *).done(function() {
 *    console.log(arguments)
 *})
 *
 * @return jQuery.Deferred
	 */
	$.waterfall = function() {
		var steps   = [],
			dfrd    = $.Deferred(),
			pointer = 0;

		$.each(arguments, function(i, a) {
			steps.push(function() {
				var args = [].slice.apply(arguments), d;

				if (typeof(a) == 'function') {
					if (!((d = a.apply(null, args)) && d.promise)) {
						d = $.Deferred()[d === false ? 'reject' : 'resolve'](d);
					}
				} else if (a && a.promise) {
					d = a;
				} else {
					d = $.Deferred()[a === false ? 'reject' : 'resolve'](a);
				}

				d.fail(function() {
					dfrd.reject.apply(dfrd, [].slice.apply(arguments));
				})
					.done(function(data) {
						pointer++;
						args.push(data);

						pointer == steps.length
							? dfrd.resolve.apply(dfrd, args)
							: steps[pointer].apply(null, args);
					});
			});
		});

		steps.length ? steps[0]() : dfrd.resolve();

		return dfrd;
	}

})(jQuery);

function yenisite_settings_close(){
	if ($("#ys-bitronic-settings").css("bottom") == "-10px") {
		$("#ys-bitronic-settings").animate({bottom: '-780px'});
	}
}

$(document).ready(
	function(){
	

		$(".selectBox").selectBox();
		$("body").append($("#ys-bitronic-settings"));
		$("#ys-a-settings").click(function(){
			if($("#ys-bitronic-settings").css("bottom") != "-10px")
				$("#ys-bitronic-settings").animate({bottom: '-10px'});
			else
				$("#ys-bitronic-settings").animate({bottom: '-780px'});
		});
			
		/*----FOR CLOSE POPUP WINDOWS---*/
		//ON PUSH ESC
		$(document).keydown(function(eventObject){
			//27 - ASCII code of button 'ESC'
			if(eventObject.which == 27)
			{
				yenisite_settings_close();
			}
		});
		//ON CLICK OUTSIDE SETTINGS WINDOW
		var onSettings;
		$('#ys-bitronic-settings').add('ul.selectBox-dropdown-menu').mouseover(function(){onSettings=true;});
		$('#ys-bitronic-settings').add('ul.selectBox-dropdown-menu').mouseout(function(){onSettings=false;});

		// this code need for elements which changes during work page
		$('#fixbody a:not(#ys-bitronic-settings a)').on('click',function(){
			if(onSettings==false)
				yenisite_settings_close();
		});

		$(document).on('click', function(){
			if (onSettings == false)
				yenisite_settings_close();
		});
		/*   -------------------------------------------------------   */

		var sefCheckbox = $('[name="SETTINGS[SEF]"]'),
			smartFilterCheckbox = $('[name="SETTINGS[SMART_FILTER]"]'),
			buttonOk = $('[name="bitronic_ok"]'),
			formsubm = $('#ys-bitronic-settings-body').find('form');

		var loaderSymbols = ['0', '1', '2', '3', '4', '5', '6', '7'], loaderRate = 100, loaderIndex = 0,
			loader = function() {
				buttonOk.val( loaderSymbols[loaderIndex] );
				loaderIndex = loaderIndex < loaderSymbols.length - 1 ? loaderIndex + 1 : 0;
				setTimeout(loader, loaderRate);
			};

		buttonOk.on('click', function() {
			var action;
			buttonOk.addClass('sym');
			loader();
			if (sefCheckbox.length) {

				var iblocks = $('.ys-iblock');
				var count = iblocks.length;

				if ( sefCheckbox.parent().hasClass('checked') ) {
					action = 'add';
				} else {
					action = 'remove';
				}
				
				var funcs = [];
				iblocks.each(function() {
					var dtype = $(this).data('type');
					var diblock = $(this).data('iblock');

					funcs.push(function() {
						return $.ajax({
							url: 'http://' + window.location.host + '/bitrix/components/yenisite/bitronic.settings/sef_' +
								action + '.php?type=' + dtype + '&iblock=' + diblock,
							success: function(data) {  },
						});
					});
				});

				if (  (action == 'add' && $('[name="ys-sef-mode"]').length == 0)
					|| (action == 'remove' && $('[name="ys-sef-mode"]').length) ) {

					$.waterfall.apply(null, funcs).done(function() { formsubm.submit(); });
				} else {
					formsubm.submit();
				}

			} else {
				formsubm.submit();
			}
		});
	}
);
/*
 (function() {
 var app = {

 init: function () {
 this.setUpHandlers();
 },

 handlers: {
 sefOptionClickHandler: function() {
 if ($(this).parent().hasClass('checked')) {
 $(this).val('Y');
 } else {
 $(this).val('N');
 }
 }
 },

 setUpHandlers: function () {
 $('[name="SETTINGS[SEF]"]').on('click', this.handlers.sefOptionClickHandler);
 },
 }

 $(function() {
 app.init();
 });

 }());*/