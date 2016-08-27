//setup map	
var mapOptions = {
		streetViewControl: false,
    center: new google.maps.LatLng(12.8797, 121.7740), //philippines
    zoom: 6,
    mapTypeId: google.maps.MapTypeId.ROADMAP
};	

var map = new google.maps.Map(document.getElementById('map'), mapOptions);		
//end of setting the map

//setup markerOptions
var markerOptions = {
	map: map,
	draggable: false
}
//end of setting the marker options

//setup autocomplete
var acOptions = {
	types: [],
	componentRestrictions: {country: 'ph'}

};

var autocomplete = new google.maps.places.Autocomplete(document.getElementById('navSearchBox'),acOptions);
autocomplete.bindTo('bounds',map);
var infoWindow = new google.maps.InfoWindow();

google.maps.event.addListener(autocomplete, 'place_changed', function() {
	infoWindow.close();
	var place = autocomplete.getPlace();
	if (place.geometry.viewport) {
 		map.fitBounds(place.geometry.viewport);
	} else {
		map.setCenter(place.geometry.location);
    	map.setZoom(17);
	}

	infoWindow.setContent('<div><strong>' + place.name + '</strong><br>');		
});							
//end of setting the autocomplete


