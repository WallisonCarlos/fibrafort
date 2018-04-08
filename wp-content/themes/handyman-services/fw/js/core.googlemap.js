function handyman_services_googlemap_init(dom_obj, coords) {
	"use strict";
	if (typeof HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'] == 'undefined') handyman_services_googlemap_init_styles();
	HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'].geocoder = '';
	try {
		var id = dom_obj.id;
		HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id] = {
			dom: dom_obj,
			markers: coords.markers,
			geocoder_request: false,
			opt: {
				zoom: coords.zoom,
				center: null,
				scrollwheel: false,
				scaleControl: false,
				disableDefaultUI: false,
				panControl: true,
				zoomControl: true, //zoom
				mapTypeControl: false,
				streetViewControl: false,
				overviewMapControl: false,
				styles: HANDYMAN_SERVICES_STORAGE['googlemap_styles'][coords.style ? coords.style : 'default'],
				mapTypeId: google.maps.MapTypeId.ROADMAP
			}
		};
		
		handyman_services_googlemap_create(id);

	} catch (e) {
		
		dcl(HANDYMAN_SERVICES_STORAGE['strings']['googlemap_not_avail']);

	};
}

function handyman_services_googlemap_create(id) {
	"use strict";

	// Create map
	HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].map = new google.maps.Map(HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].dom, HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].opt);

	// Add markers
	for (var i in HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].markers)
		HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].markers[i].inited = false;
	handyman_services_googlemap_add_markers(id);
	
	// Add resize listener
	jQuery(window).resize(function() {
		if (HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].map)
			HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].map.setCenter(HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].opt.center);
	});
}

function handyman_services_googlemap_add_markers(id) {
	"use strict";
	for (var i in HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].markers) {
		
		if (HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].markers[i].inited) continue;
		
		if (HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].markers[i].latlng == '') {
			
			if (HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].geocoder_request!==false) continue;
			
			if (HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'].geocoder == '') HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'].geocoder = new google.maps.Geocoder();
			HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].geocoder_request = i;
			HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'].geocoder.geocode({address: HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].markers[i].address}, function(results, status) {
				"use strict";
				if (status == google.maps.GeocoderStatus.OK) {
					var idx = HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].geocoder_request;
					if (results[0].geometry.location.lat && results[0].geometry.location.lng) {
						HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].markers[idx].latlng = '' + results[0].geometry.location.lat() + ',' + results[0].geometry.location.lng();
					} else {
						HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].markers[idx].latlng = results[0].geometry.location.toString().replace(/\(\)/g, '');
					}
					HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].geocoder_request = false;
					setTimeout(function() { 
						handyman_services_googlemap_add_markers(id); 
						}, 200);
				} else
					dcl(HANDYMAN_SERVICES_STORAGE['strings']['geocode_error'] + ' ' + status);
			});
		
		} else {
			
			// Prepare marker object
			var latlngStr = HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].markers[i].latlng.split(',');
			var markerInit = {
				map: HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].map,
				position: new google.maps.LatLng(latlngStr[0], latlngStr[1]),
				clickable: HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].markers[i].description!=''
			};
			if (HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].markers[i].point) markerInit.icon = HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].markers[i].point;
			if (HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].markers[i].title) markerInit.title = HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].markers[i].title;
			HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].markers[i].marker = new google.maps.Marker(markerInit);
			
			// Set Map center
			if (HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].opt.center == null) {
				HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].opt.center = markerInit.position;
				HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].map.setCenter(HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].opt.center);				
			}
			
			// Add description window
			if (HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].markers[i].description!='') {
				HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].markers[i].infowindow = new google.maps.InfoWindow({
					content: HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].markers[i].description
				});
				google.maps.event.addListener(HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].markers[i].marker, "click", function(e) {
					var latlng = e.latLng.toString().replace("(", '').replace(")", "").replace(" ", "");
					for (var i in HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].markers) {
						if (latlng == HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].markers[i].latlng) {
							HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].markers[i].infowindow.open(
								HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].map,
								HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].markers[i].marker
							);
							break;
						}
					}
				});
			}
			
			HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'][id].markers[i].inited = true;
		}
	}
}

function handyman_services_googlemap_refresh() {
	"use strict";
	for (id in HANDYMAN_SERVICES_STORAGE['googlemap_init_obj']) {
		handyman_services_googlemap_create(id);
	}
}

function handyman_services_googlemap_init_styles() {
	// Init Google map
	HANDYMAN_SERVICES_STORAGE['googlemap_init_obj'] = {};
	HANDYMAN_SERVICES_STORAGE['googlemap_styles'] = {
		'default': []
	};
	if (window.handyman_services_theme_googlemap_styles!==undefined)
		HANDYMAN_SERVICES_STORAGE['googlemap_styles'] = handyman_services_theme_googlemap_styles(HANDYMAN_SERVICES_STORAGE['googlemap_styles']);
}