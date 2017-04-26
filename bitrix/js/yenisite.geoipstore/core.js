var YS = YS || {};

if(typeof YS.namespace !== 'function') {
	/**
	 * Create new namespace
	 *
	 * @param {String} namespace name
	 */
	YS.namespace = function(ns_string) {
		var parts = ns_string.split('.'),
			parent = YS,
			i;

		if (parts[0] === 'YS') {
			parts = parts.slice(1);
		}

		for (i = 0; i < parts.length; i+= 1) {
			if (typeof parent[parts[i]] === "undefined") {
				parent[parts[i]] = {};
			}
			parent = parts[i];
		}
		return parent;
	};
}

if (typeof YS.onBeforeEvent !== "function") {
	/**
	 * Trigger event "eName" and execute "_callback" function after all event handlers
	 *
	 * @param {String} eName - name of triggering event, can contain namespace after dot: "event.namespace"
	 * @param {Function} _callback - function that will be called after all event handlers completion
	 * @param {Array} [_callbackArgs] list of callback arguments if any
	 */
	YS.onBeforeEvent = function (eName, _callback, _callbackArgs) {
		// check required arguments
		if (typeof eName == "undefined" || typeof _callback != "function") return;
		// check optional arguments and fill default values
		if (typeof _callbackArgs == "undefined") {
			_callbackArgs = [];
		}
		// split event namespace into separate variable
		var eNamespace;
		var arEvent = eName.split('.');
		eName = arEvent[0];
		if (arEvent.length > 1) {
			eNamespace = arEvent[1];
		}
		// define internal callback function with countdown counter
		var callback = function (){
			callback.count--;
			if (callback.count > 0) return;
			_callback.apply(this, _callbackArgs);
		};
		callback.count = 0;

		// get all events attached to window object through jQuery
		var events = jQuery._data( window, "events" );
		// check if there are any handlers with event name given
		if (typeof events[eName] == "undefined") return callback();
		events = events[eName];

		// combine internal callback function & external callback arguments into one array
		var handlerArgs = [callback].concat(_callbackArgs);
		// iterate through all event handlers
		for (var i=0; i<events.length; i++) {
			// check event namespace
			if (typeof eNamespace != "undefined" && events[i].namespace != eNamespace) continue;
			// call event handler
			// each handler must return TRUE if it has started background operation
			// which execution must be completed before calling callback function
			// NOTE: event handler must call callback function from first argument itself in that case
			if (!!events[i].handler.apply(this, handlerArgs)) callback.count++;
		}
		if (callback.count < 1) callback();
	};
	/*
	======= EVENT HANDLER EXAMPLES =======
	#1. Simple handler
	$(window).on('event.namespace', function(callback, arg1, arg2, ...){
		console.log('do something on event: ' + arg1.toString());
		// there is no background operation running
		// no need to call callback and return anything
	});
	#2. Handler with background operation
	$(window).on('event', function(callback){
		$.ajax({
			url: 'someURL',
			complete: callback
		});
		// callback must be executed after background process completed
		return true;
	});
	*/
}

YS.namespace('YS.GeoIPStore.PopUpWindow');

var redirectHost = function(hostname) {
	location.hostname = hostname;
	window.location.hostname = hostname;
};

YS.GeoIPStore.Core = (function () {
	setActiveItem = function (id) {
		var isRedir, domain, serverName, newHost;

		$.ajax({
			url: '/bitrix/js/yenisite.geoipstore/ajax.php?action=isRedirect&id=' + id + '&uri=' + encodeURIComponent(window.location.pathname),
			dataType: 'json',

			success: function (data) {
				if (data.IS_REDIRECT == "Y") {
					isRedir = true;
					domain = data.DOMAIN;
					serverName = data.SERVER_NAME;
				}
				if (isRedir) {
					var host = window.location.hostname;
					var tmpAr = host.split('.');
					var check = tmpAr.length;

					if (jsUtils.in_array(host, serverName)) {
						if (domain.length > 0) {
							domain += '.';
						}
						newHost = "" + domain + window.location.hostname;
					} else {
						if (domain.length > 0) {
							tmpAr[0] = domain;
						} else {
							tmpAr.shift();
						}
						newHost = tmpAr.join('.');
					}
					YS.onBeforeEvent('redirect.GeoIPStore', redirectHost, [newHost]); // redirectHost(newHost);
				} else {
					$.ajax({
						url: '/bitrix/js/yenisite.geoipstore/ajax.php?action=setActive&id=' + id,
						complete: function (jqXHR, status) {
							YS.onBeforeEvent('reload.GeoIPStore', function(){
								location.reload();
							});
						}
					});
				}
			}
		});
	};
	
	updateActiveItem = function () {
		$.ajax({
			url: '/bitrix/js/yenisite.geoipstore/ajax.php?action=update',
			dataType: 'json',
			success: function(data) {
				if ('UPDATE' in data && data.UPDATE !== 'N')
				{
					$('.ys-geoip-store-city').text(data.CITY_NAME);
					var span = $('#ys-geoipstore .ys-geoipstore-itemlink + .sym').detach();
					$('.ys-geoipstore-itemlink').each(function() {
						if (parseInt($(this).data('ys-item-id')) === parseInt(data.ID)) {
							$(this).after(span);
							$('.ys-geoipstore-cont-active').removeClass('ys-geoipstore-cont-active');
							$(this).parent().parent().addClass('ys-geoipstore-cont-active');
						}
					});
				}
				else
				{
					YS.GeoIPStore.Core.setActiveItem(data.ID);
				}
			}
		});
	};

	return {
		setActiveItem: setActiveItem,
		updateActiveItem: updateActiveItem,
	};
})();

YS.GeoIPStore.PopUpWindow = (function() {
	var showPopUpGeoIPStore = function() {
			$('#ys-geoipstore').show();
			$('#mask').show();
		},

		hidePopUpGeoIPStore = function() {
			$('#ys-geoipstore').hide();
			$('#mask').hide();
		};

	return {
		showPopUpGeoIPStore: showPopUpGeoIPStore,
		hidePopUpGeoIPStore: hidePopUpGeoIPStore
	};
})();