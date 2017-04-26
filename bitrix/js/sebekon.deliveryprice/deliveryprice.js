if (!window['$sebekon_jq_delivery'] && window['jQuery']) { var $sebekon_jq_delivery = jQuery;}


var sebekon_deliveryprice_order_click = function(event) {	
	
	if (event.preventDefault)
		event.preventDefault();
	
	if (event.returnValue)
		event.returnValue = false;
	
	if (event.stopPropagation)
		event.stopPropagation();
		
	if (event.cancelBubble)
		event.cancelBubble = true;
		
	$sebekon_jq_delivery('#SEBEKON_DELIVERYPRICE_ORDER').modal('show');
	$sebekon_jq_delivery('#SEBEKON_DELIVERYPRICE_ORDER .modal-body').css('width','605');
	$sebekon_jq_delivery('#SEBEKON_DELIVERYPRICE_ORDER .modal-body').css('opacity','1');
	$sebekon_jq_delivery('#SEBEKON_DELIVERYPRICE_ORDER').css('opacity','1');
	$sebekon_jq_delivery('#SEBEKON_DELIVERYPRICE_ORDER .modal-body').load('/bitrix/components/sebekon/delivery.calc/order.php');
	return false;
}

$sebekon_jq_delivery(document).ready(function(){
	$sebekon_jq_delivery(document).on('click', '.sebekon_delivery_price_link', function(event){
		return sebekon_deliveryprice_order_click(event);
	});
});

function number_format( number, decimals, dec_point, thousands_sep ) {  // Format a number with grouped thousands
		
	var i, j, kw, kd, km;
	// input sanitation & defaults
	if( isNaN(decimals = Math.abs(decimals)) ){
		decimals = 2;
	}
	if( dec_point == undefined ){
		dec_point = ",";
	}
	if( thousands_sep == undefined ){
		thousands_sep = ".";
	}
	i = parseInt(number = (+number || 0).toFixed(decimals)) + "";
 
	if( (j = i.length) > 3 ){
		j = j % 3;
	} else{
		j = 0;
	}
	km = (j ? i.substr(0, j) + thousands_sep : "");
	kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);
	kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");
	return km + kw + kd;
}

function getFormatPrice(price, zone){	
	if ($sebekon_jq_delivery.isArray(zone)) {
		zone = zone.pop();
	}
	if(zone && zone["DP_DEC_CNT"]>0){
		var mult = Math.pow(10, zone["DP_DEC_CNT"]);
		price = Math.round(price/mult) * mult;
	}else{
		price = Math.round(price*100)/100;
	}
	return number_format(price, zone["DP_DECIMAL_CNT"], zone["DP_DECIMAL_POINT"], zone["DP_THOUSAND_POINT"])
}


function getPointsFromCoords (coords) {
	var res = new Array();
	var is_array = false;
	is_array = $sebekon_jq_delivery.isArray(coords);
	var max_depth=10;
	 
	
	while (is_array) {
		var buf = new Array();
		for (var i = 0, l = coords.length; i < l; i++) {
		
			if ($sebekon_jq_delivery.isArray(coords[i])) {
				for (var j = 0, l2 = coords[i].length; j < l2; j++) buf.push(coords[i][j]);
			} else if (typeof coords[i] == 'number') {
				is_array = false;
				buf.push(coords[i].toPrecision(6));
			} else if (coords[i].toString) {
				is_array = false;
				buf.push(coords[i].toString());
			}
		}
		if (0==max_depth--) is_array = false;			
		coords = buf;
	}
	for (var i = 1, l = coords.length; i < l; i+=2) {
		res.push({'x':coords[i-1], 'y':coords[i]});
	}
	return res;		
}

sebDelivery = new SebekonDelivery();

function SebekonDelivery (compid, handlers, clickEvent) {

	this.compid = compid;
	this.delivers = new Array();
	this.map = {};
	this.search = {};
	this.params = {};
	this.searchTimeout = false;
	this.maps = new Array();
	this.zones = new Array();
	this.routes = new Array();
	this.geometries = new Array();
	this.points = new Array();
	this.placemarks = new Array();
	this.last_request = null;
	this.price = {};
	this.dargCoords = {};
	this.checkTimeout = null;
	this.calcState = 'ready';
	this.handlers = {};
	this.bound = {min: [0, 0], max: [0, 0]};
	this.coords = new Array();
	this.coordsName = '';
	this.lbls = {};
	this.clickEvent = 'dblclick';
	
	if (clickEvent) {
		this.clickEvent = clickEvent;
	}
	
	if (handlers) {
		this.handlers = handlers;
	}
	
	//функция по формированию массива карт и зон
	this.load = function (maps, lbls) {
		for(map_id in maps["MAPS"]) {
			var map = maps["MAPS"][map_id];
			if(map["PROPS"]["ZONES"]["VALUE"].length <= 0) continue;
			
			this.maps[map['ID']] = {
				"NAME" : map["NAME"],
				"ID" : map["ID"],
				"ZONES": {}
			};
			
			for(x in map["PROPS"]["ZONES"]["VALUE"]) {
				this.maps[map['ID']]['ZONES'][map["PROPS"]["ZONES"]["VALUE"][x]] = '';
			}
		}
		
		for (zone_id in maps['ZONES']){
			var zone = maps['ZONES'][zone_id];
			
			var coordinates = getPointsFromCoords(zone["PROPS"]["ZONE_COORDS"]["VALUE"] ? JSON.parse(zone["PROPS"]["ZONE_COORDS"]["VALUE"]) : [[]]);		
			for(var i in coordinates) {
				if (coordinates[i].x>this.bound.max[0] || this.bound.max[0]==0) this.bound.max[0] = coordinates[i].x;
				if (coordinates[i].x<this.bound.min[0] || this.bound.min[0]==0) this.bound.min[0] = coordinates[i].x;
				if (coordinates[i].y>this.bound.max[1] || this.bound.max[1]==0) this.bound.max[1] = coordinates[i].y;			
				if (coordinates[i].y<this.bound.min[1] || this.bound.min[1]==0) this.bound.min[1] = coordinates[i].y;
			}
			
			
			var price_interval = [];
			if (this.getSafeValue(zone, "PRICE_INTERVALS") && $sebekon_jq_delivery.isArray(zone["PROPS"]["PRICE_INTERVALS"]['VALUE'])) {
				for(_key in zone["PROPS"]["PRICE_INTERVALS"]['VALUE']) {
					var price = zone["PROPS"]["PRICE_INTERVALS"]['VALUE'][_key];
					price = price.toString().split('-');
					var interval = zone["PROPS"]["PRICE_INTERVALS"]['DESCRIPTION'][_key].toString().split('-');
					price_interval.push({
							'from':this.intval(interval[0]), 
							'to':this.intval(interval[1]), 
							'price':this.floatval(price[0]), 
							'price_km':this.floatval(price[1])
					});
				}
			}
			
			this.zones[zone.ID] = {
				"PRICE_FIX" : this.floatval(zone["PROPS"]["PRICE_FIX"]["VALUE"]),
				"PRICE_KM"	: this.floatval(zone["PROPS"]["PRICE_KM"]["VALUE"]),
				"ZONE_FROM"	: zone["PROPS"]["ZONE_FROM"]["VALUE"],
				"PRICE_INTERVALS" : price_interval,
				"DP_DECIMAL_CNT": 		parseInt(this.getSafeValue(zone, "DP_DECIMAL_CNT")),
				"DP_DEC_CNT": 			parseInt(this.getSafeValue(zone, "DP_DEC_CNT")),
				"DP_DECIMAL_POINT": 	(this.getSafeValue(zone, "DP_DECIMAL_POINT")?this.getSafeValue(zone, "DP_DECIMAL_POINT"):'.'),
				"DP_THOUSAND_POINT": 	(this.getSafeValue(zone, "DP_THOUSAND_POINT")?this.getSafeValue(zone, "DP_THOUSAND_POINT"):' '),
				'GEOMETRY' 			: {
						type: 'Polygon',
						coordinates: zone["PROPS"]["ZONE_COORDS"]["VALUE"] ? JSON.parse(zone["PROPS"]["ZONE_COORDS"]["VALUE"]) : [[]]
					},
				'PROPS': zone['PROPS'],
				'SORT': zone['SORT']
			}
		}
		this.bound.min[0] = this.floatval(this.bound.min[0]);
		this.bound.min[1] = this.floatval(this.bound.min[1]);
		this.bound.max[0] = this.floatval(this.bound.max[0]);
		this.bound.max[1] = this.floatval(this.bound.max[1]);
		
		this.lbls = lbls;
		
	}
	
	//функция по центрированию
	this.boundsInit = function() {
		var self = this;
		
		if (self.handlers['onBoundsInit']) {
			return self.handlers['onBoundsInit'](self);
		}
		
		if (this.bound.max[0]!=0 && this.bound.min[0]!=0 && this.bound.max[1]!=0 && this.bound.min[1]!=0) {
			var diff = (this.bound.max[0]-this.bound.min[0])*(this.bound.max[0]-this.bound.min[0]) + (this.bound.max[1]-this.bound.min[1])*(this.bound.max[1]-this.bound.min[1]);
			self.map.setBounds([this.bound.min, this.bound.max], {checkZoomRange: true,  precizeZoom: true, callback:function(){				
				if (self.map.getZoom()<8 && diff<81) self.map.setZoom(8);}
			});
		}
	}
	
	// функция по инициализации поиска
	this.searchInit = function() {
		var self = this;
		
		if (self.handlers['onSearchInit']) {
			return self.handlers['onSearchInit'](self);
		}
		
		var DP_Collection = new ymaps.GeoObjectCollection();
		$sebekon_jq_delivery('#DP_search_form'+self.compid).submit(function () {
			var search_query = $sebekon_jq_delivery('#DP_search_form'+self.compid+' input:first').val();
			ymaps.geocode(search_query, {results: 1}).then(function (res) {
				DP_Collection.removeAll();
				DP_Collection = res.geoObjects;
				DP_Collection.each(function(el){
					self.setDestination(false, el.geometry.getCoordinates());
				});
			});
			return false;
		});
		
		var searchOptions = {resultsPerPage: 10};
		if (self.bound.max[0]!=0 && self.bound.min[0]!=0 && self.bound.max[1]!=0 && self.bound.min[1]!=0) {			
			searchOptions.boundedBy = [self.bound.min, self.bound.max];
			searchOptions.strictBounds = true;
		}
		searchOptions.options = searchOptions;

		self.search = new ymaps.control.SearchControl(searchOptions);
		$sebekon_jq_delivery('#DP_search_form'+self.compid+' input[type=text]').keypress(function(){
			clearTimeout(self.searchTimeout);
			self.searchTimeout = setTimeout(function(){
				var search_query = $sebekon_jq_delivery('#DP_search_form'+self.compid+' input:first').val();
				$sebekon_jq_delivery('.sebekon-help_block').remove();
				self.search.search(search_query).then(function (geoObjectsArr) {
					$sebekon_jq_delivery('body').append('<div class="sebekon-help_block"></div>');
					$sebekon_jq_delivery('.sebekon-help_block').css('z-index',$sebekon_jq_delivery('#DP_search_form'+self.compid).parents('.modal').css('z-index')+2);
					
					if (self.search.getResultsArray && (typeof self.search.getResultsArray == 'function')) {
						//Апи версии 2.1.+
						geoObjectsArr = self.search.getResultsArray();
					}
					
					var i;
					for(i=0; i<geoObjectsArr.length; i++) {							
						$sebekon_jq_delivery('.sebekon-help_block').append('<div data-text="'+geoObjectsArr[i].properties.get('text')+'">'+geoObjectsArr[i].properties.get('balloonContentBody')+'</div>');
					}
					
					$sebekon_jq_delivery('.sebekon-help_block').css('left', $sebekon_jq_delivery('#DP_search_form'+self.compid+' input[type=text]').offset().left);
					$sebekon_jq_delivery('.sebekon-help_block').css('top', $sebekon_jq_delivery('#DP_search_form'+self.compid+' input[type=text]').offset().top+27);
					
					$sebekon_jq_delivery('.sebekon-help_block').show();
				});
			}, 300);
		});
		$sebekon_jq_delivery(document).on('click', '.sebekon-help_block > div', function(){
			$sebekon_jq_delivery('#DP_search_form'+self.compid+' input[type=text]').val($sebekon_jq_delivery(this).attr('data-text'));
			$sebekon_jq_delivery('.sebekon-help_block').remove();
		});
	}
	
	// функция по инициализации карты
	this.mapInit = function() {
		var self = this;
		
		if (self.handlers['onMapInit']) {
			return self.handlers['onMapInit'](self);
		}
		
		self.map = new ymaps.Map("map"+self.compid, {
			center: [55.76, 37.64],
			zoom: 8,
			behaviors: ['drag']
		});
	}

	//функция по установке точки на карте
	this.addPlacemark = function (coords, properties, options) {
		var self = this;
		var placemark = {};
		placemark = new ymaps.Placemark(coords, properties, options);
		placemark.events.add('dragend', function(obj){
			var index = -1;
			if (self.dragCoords) {				
				for (var i in self.points) {
					if (self.points[i]==self.dragCoords) {index = i;break;}
				}				
			}
			if (index>=0) {
				self.points[index] = placemark.geometry.getCoordinates();
				self.placemarks[index] = placemark;
			} else {
				self.points.push(placemark.geometry.getCoordinates());
				self.placemarks.push(placemark);
			}
			
			self.dragEnd(placemark.geometry.getCoordinates());
			
			self.recalc();			
		});
		placemark.events.add('dragstart', function(obj){
			self.dragCoords = placemark.geometry.getCoordinates();
			for(var mapID in self.delivers){
				for(var zoneID in self.delivers[mapID]){
					self.map.geoObjects.remove(self.delivers[mapID][zoneID]["ROUTE"]);
				}
			}
		});
		placemark.events.add(self.clickEvent, function(){
			var index = -1;
			var scoords = placemark.geometry.getCoordinates();
			if (scoords) {				
				for (var i in self.points) {
					if (self.points[i]==scoords) {index = i;break;}
				}				
			}
			if (index>=0) {
				self.points.splice(index,1);
				self.placemarks.splice(index,1);
			}
			self.map.geoObjects.remove(placemark);
			self.recalc();
		});
		self.map.geoObjects.add(placemark);
		self.placemarks.push(placemark);
		self.points.push(coords);
		self.recalc();
	}
	
	this.clear = function (recalc) {
		var self = this;
		for (var i in self.placemarks) {
			self.map.geoObjects.remove(self.placemarks[i]);
		}
		self.points = new Array();
		self.placemarks = new Array();
		if (recalc) {
			self.recalc();
		}
	}

	this.recalc = function(){	
		var self = this;
		if (self.calcState!='ready') {			
			//если уже идет рассчет, откладываем пересчет на некоторое время
			clearTimeout(self.checkTimeout);
			self.checkTimeout = setTimeout("sebDelivery"+self.compid+".recalc();",100);
			return;
		}		
		self.calcState = 'zones';
		//очищаем имеющиеся маршруты
		for(var mapID in self.delivers){
			for(var zoneID in self.delivers[mapID]){
				self.map.geoObjects.remove(self.delivers[mapID][zoneID]["ROUTE"]);
			}
		}
		
		self.delivers = new Array();
		self.showPreloader();

		var data = {maps: new Array(), points: new Array(), params: self.params};
		for(var i in self.maps) {
			data.maps.push(self.maps[i].ID);
		}
		for(var i in self.points) {
			data.points.push(self.points[i]);
		}
		
		$sebekon_jq_delivery.ajax({
			url: '/bitrix/components/sebekon/delivery.calc/sort_point.php',
			data: data,			
			success: function (result) {
				for (var mapID in result) {
					self.delivers[mapID] = new Array();
					for(var zoneID in result[mapID]){
						self.delivers[mapID][zoneID] = {'STATE':'calc','PRICE':0,'ROUTE': {}, 'ROUTES_TO_CALC': 0};
					}
				}
				self.calcState = 'calc';
				for (var mapID in result) {
					for(var zoneID in result[mapID]){
						self.calcZonePrice(mapID, zoneID, result[mapID][zoneID]);
					}
				}
			},
			dataType: 'json'
		});
		
		setTimeout("sebDelivery"+self.compid+".checkRecalc();",100);
	}
	
	//функция по ожиданию процесса рассчета стоимости доставки
	this.checkRecalc = function () {
		var self = this;		
		// проверяем посчитаны ли все доставки
		if (self.calcState == 'calc') {
			var state = 'ready';
			for (var mapID in self.delivers) {
				for(var zoneID in self.delivers[mapID]) {
					if (self.delivers[mapID][zoneID]['STATE'] != 'ready') {
						state='calc';
					}
				}
			}
			self.calcState = state;
		}
		
		if (self.calcState=='ready') {
		
			for(mapID in self.delivers) {	
				var len = 0;
				var interval = {};
				var in_interval = false;
				var zones_to_remove = new Array();
				for(var zoneID in self.delivers[mapID]) {
					if (self.zones[zoneID]["PRICE_INTERVALS"] && self.zones[zoneID]["PRICE_INTERVALS"].length>0) {
						if (isNaN(self.delivers[mapID][zoneID]['LENGTH'])) {
							zones_to_remove.push(zoneID);
						} else {
							len = parseFloat(self.delivers[mapID][zoneID]['LENGTH']);
							in_interval = false;
							for (var x in self.zones[zoneID]["PRICE_INTERVALS"]) {
								interval = self.zones[zoneID]["PRICE_INTERVALS"][x];
								if (len>=interval.from && (interval.to==0 || interval.to>len)) {
									in_interval = true;
									self.delivers[mapID][zoneID]["PRICE"] = parseFloat(interval.price);
									
									if (interval.price_km > 0) {
										self.delivers[mapID][zoneID]["PRICE"] += parseFloat((len - interval.from)*interval.price_km);
									}
								}
							}
							if (!in_interval) {
								zones_to_remove.push(zoneID);
							}
						}
					}					
				}
				
				for(var x in zones_to_remove) {
					self.delivers[mapID].splice(zones_to_remove[x],1);
				}
				
			}
		
			self.showResults();			
		} else {			
			setTimeout("sebDelivery"+self.compid+".checkRecalc();",100);
		}
	}
	
	//функция по расчету стоимости доставки
	this.calcZonePrice = function(mapID, zoneID, points){
		var self = this;
		if(self.zones[zoneID]["PRICE_FIX"]!==''){
			self.zones[zoneID]["PRICE_FIX"] = parseFloat(self.zones[zoneID]["PRICE_FIX"]);
		}
		
		self.zones[zoneID]["PRICE_KM"] 	= parseFloat(self.zones[zoneID]["PRICE_KM"]);
		var has_intervals = false;
		if (self.zones[zoneID]["PRICE_INTERVALS"] && self.zones[zoneID]["PRICE_INTERVALS"].length>0) {
			has_intervals = true;
		}
		
		//если указана фиксированная цена в зоне и не указаана цена за км, берем фикс цену
		if(self.zones[zoneID]["PRICE_FIX"]!=='' && ((self.zones[zoneID]["PRICE_KM"]<=0 && !has_intervals) || !self.zones[zoneID]["ZONE_FROM"])) {
			self.delivers[mapID][zoneID]['PRICE'] = self.zones[zoneID]["PRICE_FIX"]*points.length;
			self.delivers[mapID][zoneID]['STATE'] = 'ready';
		}
		
		//если есть тариф на удаленность строим маршрут по этим точкам
		if((self.zones[zoneID]["PRICE_KM"]>0 || has_intervals) && self.zones[zoneID]["ZONE_FROM"]){
			//получаем точки зоны, до которой нужно выполнить расчет
			zpoints = getPointsFromCoords(self.geometries[self.zones[zoneID]["ZONE_FROM"]].geometry.getCoordinates());
			//запускаем расчет стоимости по маршрутам
			self.calcKm(zpoints, points, mapID, zoneID);
			return 'CALC';
		}
		
		return 0;
	}
	
	//функция для расчета длинны маршрута
	this.calcKm = function(pointsFrom, pointsTo, mapID, zoneID){
		var self = this;
		var route = new Array();
		self.delivers[mapID][zoneID]["LENGTH"] = 9999999999;
		self.delivers[mapID][zoneID]["PRICE"] = 9999999999;
		self.delivers[mapID][zoneID]["ROUTES_TO_CALC"] = 0;
		
		var distance = new Array();
		var min_distance = 9999999999999;
		for(var i = 0; i<pointsFrom.length; i++){
			distance[i] = 0;
			for(var j = 0; j<pointsTo.length; j++){
				distance[i] += (pointsFrom[i]["x"]-pointsTo[j].x)*(pointsFrom[i]["x"]-pointsTo[j].x);
				distance[i] += (pointsFrom[i]["y"]-pointsTo[j].y)*(pointsFrom[i]["y"]-pointsTo[j].y);
			}
			if (distance[i]<min_distance) {
				min_distance = distance[i];
			}
		}
		for(var i = 0; i<pointsFrom.length; i++){
			if(Math.abs(min_distance - distance[i]) > 0.5*min_distance) continue;
			self.delivers[mapID][zoneID]["ROUTES_TO_CALC"]++;
			route = new Array();
			route.push({ type: 'wayPoint', point: [pointsFrom[i]["x"], pointsFrom[i]["y"]] });
			for(var j = 0; j<pointsTo.length; j++){
				route.push({ type: 'wayPoint', point: [pointsTo[j].x, pointsTo[j].y]});
			}
			ymaps.route(route).then(
				function (route) {
					self.delivers[mapID][zoneID]["ROUTES_TO_CALC"]--;					
					var routeLen = (route.getLength())/1000; //в километрах
					if (routeLen<self.delivers[mapID][zoneID]["LENGTH"]) {
						self.delivers[mapID][zoneID]["LENGTH"] = routeLen;
						self.delivers[mapID][zoneID]["PRICE"] = routeLen*self.zones[zoneID]["PRICE_KM"];
						if(self.zones[zoneID]["PRICE_FIX"]!==''){
							self.delivers[mapID][zoneID]["PRICE"] += parseFloat(self.zones[zoneID]["PRICE_FIX"]);
						}
						self.delivers[mapID][zoneID]["ROUTE"] = route;
					}
					if (self.delivers[mapID][zoneID]["ROUTES_TO_CALC"]==0)
						self.delivers[mapID][zoneID]["STATE"] = 'ready';
				}, 
				function (){
					self.delivers[mapID][zoneID]["ROUTES_TO_CALC"]--;
					if (self.delivers[mapID][zoneID]["ROUTES_TO_CALC"]==0)
						self.delivers[mapID][zoneID]["STATE"] = 'ready';
				}
			);
		}
	}
	
	//функция по рисованию зон
	this.drawZones = function () {
		var self = this;
		for (zone_id in this.zones){
			var zone = this.zones[zone_id];
			var style = {
				zIndex: this.intval(zone["SORT"]),
				interactivityModel: 'default#transparent'
			};
			
			if(zone["PROPS"]["ZONE_COLOR"]["VALUE"]) {
				style['fill'] = true;
				style['fillColor'] = zone["PROPS"]["ZONE_COLOR"]["VALUE"];
				style['opacity'] = 0.3;
			} else {
				style['fill'] = false;
				style['stroke'] = false;
			}
			this.geometries[zone_id] = new ymaps.GeoObject({geometry: zone.GEOMETRY}, style);
			self.map.geoObjects.add(self.geometries[zone_id]);
		}
		self.map.events.add(self.clickEvent, function (e) {
			self.setDestination(e);
		});
	}
	
	this.intval = function(mixed_var) {
		var tmp;
		if( typeof( mixed_var ) == 'string' ){
			tmp = parseInt(mixed_var);
			if(isNaN(tmp)){
				return 0;
			} else{
				return tmp.toString(10);
			}
		} else if( typeof( mixed_var ) == 'number' ){
			return Math.floor(mixed_var);
		} else{
			return 0;
		}
	}
	
	this.floatval = function(mixed_var) {
		return (parseFloat(mixed_var) || 0);
	}
	
	this.showPreloader = function () {
		var self = this;
		
		if (self.handlers['onPreload']) {
			return self.handlers['onPreload'](self);
		}
		
		if (window['showPreloader'+self.compid]) {
			return window['showPreloader'+self.compid]();
		}
	}
	
	this.dragEnd = function (coords) {
		var self = this;
		
		if (self.handlers['onDragEnd']) {
			return self.handlers['onDragEnd'](self, coords);
		}
		
		if (window['sebekon_dragend'+self.compid]) {
			return window['sebekon_dragend'+self.compid](coords);
		}
		
		self.coords = coords;
		ymaps.geocode(self.coords).then(function (res) {
			var names = [];
			res.geoObjects.each(function (obj) {
				names.push(obj.properties.get('text'));
			});
			self.coordsName = names[0];
		});
	}
	
	
	this.setDestination = function (e, coords) {
		var self = this;
		
		if (self.handlers['onSetDestination']) {
			return self.handlers['onSetDestination'](self, e, coords);
		}
		
		if (window['setDestination'+self.compid]) {
			return window['setDestination'+self.compid](e, coords);
		}
		
		var properties = {
			iconContent: self.points.length+2
		},
		options = { 
			draggable: true,
			zIndex: 10000,
			zIndexActive: 10000,
			zIndexHover: 10000
		};
		
		if(typeof coords == 'undefined'){
			coords = e.get('coordPosition');
		}
		if(typeof coords == 'undefined'){
			coords = e.get('coords');
		}
		if(self.params['MULTI_POINTS']!='Y') {
			self.clear(false);
		}
		self.addPlacemark(coords, properties, options);
	}
	
	
	this.showResults = function () {
		var self = this;
		
		if (window['showResults'+self.compid]) {
			return window['showResults'+self.compid]();
		}
	
		if (self.handlers['onResult']) {
			return self.handlers['onResult'](self);
		}
		
		var deliveryText = "";
		if ($sebekon_jq_delivery('#SEBEKON_DELIVERYPRICE_ORDER').size()>0) {
			$sebekon_jq_delivery('#SEBEKON_DELIVERYPRICE_ORDER button.btn-primary').hide();			
			$sebekon_jq_delivery('.sebekon_select_btn').hide();
		}
		$sebekon_jq_delivery('.delivery_basket_btn').hide();
		
		for(mapID in self.delivers) {	
			var total = 0;
			var has_zones = false;
			var len = 0;
			var selectedZone = false;
			for(var zoneID in self.delivers[mapID]) {
				if (!isNaN(self.delivers[mapID][zoneID]['PRICE'])) {
					total += parseFloat(self.delivers[mapID][zoneID]['PRICE']);
					has_zones = true;
					selectedZone = zoneID;
				} 
				if (!isNaN(self.delivers[mapID][zoneID]['LENGTH'])) {
					len += parseFloat(self.delivers[mapID][zoneID]['LENGTH']);
					has_zones = true;
					selectedZone = zoneID;
				}
			}
			
			if(len==0 && has_zones){
				deliveryText += '<p><label>'+self.coordsName + ": <strong>" + getFormatPrice(total,self.zones[selectedZone]) + " "+this.lbls['sebekon_RUB']+".</strong> ("+self.maps[mapID]["NAME"]+")</label></p>";
				self.price[mapID] = {"DELIVERY_PRICE" :  parseFloat(total), "MAP_ID": mapID, "LENGTH": 0};
				
				$sebekon_jq_delivery('.delivery_basket_btn').show();
				$sebekon_jq_delivery('.sebekon_select_btn').show();
				$sebekon_jq_delivery('#SEBEKON_DELIVERYPRICE_ORDER button.btn-primary').show();
				
			}else if(len>0){
				self.price[mapID] = {"DELIVERY_PRICE" :  parseFloat(total), "MAP_ID": mapID, "LENGTH": len};
				deliveryText += '<p><label>' + self.coordsName + ": <strong>" + getFormatPrice(total,self.zones[selectedZone]) + " "+this.lbls['sebekon_RUB']+".</strong> ["+number_format(len, 1,',',' ')+" "+this.lbls['sebekon_DP_IBLOCK_KM']+"]";
				deliveryText += " ("+self.maps[mapID]["NAME"]+")</label></p>";
				
				if(self.delivers[mapID][zoneID]['ROUTE']){
					self.delivers[mapID][zoneID]['ROUTE'].options.set({zIndex: 9999, zIndexActive: 9999, zIndexHover: 9999});
					self.map.geoObjects.add(self.delivers[mapID][zoneID]['ROUTE']);
				}
				$sebekon_jq_delivery('#SEBEKON_DELIVERYPRICE_ORDER button.btn-primary').show();
				$sebekon_jq_delivery('.sebekon_select_btn').show();
				$sebekon_jq_delivery('.delivery_basket_btn').show();

			} else {
				deliveryText += '<p><label>'+ self.coordsName + ": "+this.lbls['sebekon_DP_PUBLIC_UNPOSSIBLE']+" (" + self.maps[mapID]["NAME"] + ")</label></p>";
				self.price[mapID] = null;
		
			}			
		}
		
		$sebekon_jq_delivery('#DELIVERS'+self.compid).html(deliveryText);
		sebekon_deliverycalc_params = {prices: self.price, action:'prices', coords: self.coords, name: self.coordsName};
		
		return;
	}
	
	
	this.getSafeValue = function (element, propertyCode) {
		if (element["PROPS"] && element["PROPS"][propertyCode] && element["PROPS"][propertyCode]['VALUE']) {
			return element["PROPS"][propertyCode]['VALUE'];
		}
		
		return ;
	}
}