var YS = YS || {};

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

YS.namespace('YS.Ajax');

YS.Ajax = (function() {

	/**
	* Show AJAX loader
	*/
	showLoader = function() {
		$(".loader").fadeIn(100);
	},

	/**
	* Hide AJAX loader
	*/
	hideLoader = function() {
		$(".loader").fadeOut(500);
	};

	return {
		showLoader: showLoader,
		hideLoader: hideLoader
	};
})();