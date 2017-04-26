function loadCitiesList(country_id, arParams, site_id, region_id)
{
	property_id = arParams.CITY_INPUT_NAME;

	function __handlerCitiesList(data)
	{
		//alert(data);
		var obContainer = document.getElementById('LOCATION_' + property_id);
		if (obContainer)
		{
			obContainer.innerHTML = data;
			PCloseWaitMessage('wait_container_' + property_id, true);
		}
	}

	arParams.COUNTRY = parseInt(country_id);
	arParams.REGION = parseInt(region_id);
	arParams.SITE_ID = site_id;
	
	if (arParams.COUNTRY <= 0) return;

	PShowWaitMessage('wait_container_' + property_id, true);
	
	var TID = CPHttpRequest.InitThread();
	CPHttpRequest.SetAction(TID,__handlerCitiesList);
	CPHttpRequest.Post(TID, '/bitrix/components/bitrix/sale.ajax.locations/templates/.default/ajax.php', arParams);
}
