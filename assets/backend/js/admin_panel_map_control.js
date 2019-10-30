
function init() {
	markers = [];
	container = document.getElementById('map');
	
    map = new google.maps.Map(container, {
      zoom: _address.map.zoom,
      center: {lat: _address.map.center.lat, lng: _address.map.center.lng}
    });
    var latlng = getInputCoordinates();
    setMarker(latlng);

    google.maps.event.addListener(map, 'click', function(event)
    {
    	var lat = event.latLng.lat();
    	var lng = event.latLng.lng();
    	var latlng = {lat: lat, lng: lng};
    	setInputCoordinates(latlng);
    	setInputAddress(latlng);
    	setMarker(latlng);
  	});
}

getInputCoordinates = function(){
	var input = document.getElementById('coordinates').value;
    if(input.length > 1)
    {
    	return JSON.parse(input);	
    }
    return {lat:0,lng:0};
}

setInputCoordinates = function(latlng){
	document.getElementById("coordinates_mutted").value = JSON.stringify(latlng);
	document.getElementById("coordinates").value = JSON.stringify(latlng);
}
setMarker = function(latlng)
{
	if(markers.length)
	{
		markers.pop().setMap(null);
	}
	var marker = new google.maps.Marker({
		position: latlng,
		map: map
	});
	markers.push(marker);
}
setInputAddress = function(latlng){
	var geocoder = new google.maps.Geocoder;
	geocoder.geocode({'location': latlng} , function(results, status)
	{
		if (status === google.maps.GeocoderStatus.OK) {
			if (results[0]) {
				document.getElementById("address").value = results[0].formatted_address;
			}
	    }
	});
}