$(function() {
	
	function geoIpStoreComponentInit () 
	{
		var ysPopUp  = YS.GeoIPStore.PopUpWindow;
		var ysGeoCore  = YS.GeoIPStore.Core;

		$('.ys-geoip-store-city').on('click', ysPopUp.showPopUpGeoIPStore);

		$('.ys-geoipstore-itemlink').on('click', function() {
			ysGeoCore.setActiveItem( $(this).data('ys-item-id') );
			ysPopUp.hidePopUpGeoIPStore();

			$('.ys-geoip-store-city').text( $(this).text() );
			var span = $('.ys-geoipstore-item span.sym').detach();
			$(this).after(span);

			$('.ys-geoipstore-cont-active').removeClass('ys-geoipstore-cont-active');
			$(this).parent().parent().addClass('ys-geoipstore-cont-active');
		});

		$('#mask, a.close').on('click', function() {
			ysPopUp.hidePopUpGeoIPStore();
		});

		var delTo = $('.ys-del-to').detach();
		$('.ys-del-from').after(delTo);
	
	}
	geoIpStoreComponentInit();
	BX.addCustomEvent("onFrameDataReceived", geoIpStoreComponentInit);
});