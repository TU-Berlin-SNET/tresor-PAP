// A custom icon for the vertices.
var icon = L.icon ({
	iconUrl: 'inc/style/polygonDrawer/marker.png',
	iconSize: [16, 16],
	iconAnchor: [8, 8],
	popupAnchor: [0,0]
});
	
var map;
var polygon;
var vertices = [];
var polygons = [];

$(function() {
	var mapboxTiles = L.tileLayer('https://{s}.tiles.mapbox.com/v3/philip-raschke.hl1oj1jl/{z}/{x}/{y}.png', {
			attribution: '<a href="http://www.mapbox.com/about/maps/" target="_blank">Terms &amp; Feedback</a>'
	});
	
	map = L.map("map").addLayer(mapboxTiles).setView([0,0], 18);
	polygon = L.polygon([]).addTo(map);
	
	map.on('click', function(event) {
		var vertex = _vertex(event.latlng.lat, event.latlng.lng);	
		vertices.push(vertex);
		view();
		
	});
	
	map.on('contextmenu', function(event) {
		deselect();
	});
	
});
	
function _vertex(lat, lng) {
	var vertex = L.marker([lat, lng], { draggable: true, icon: icon }).addTo(map);
	
	vertex.on('contextmenu', function(event) {
		removeVertex(vertex);
		view();
	});
	
	vertex.on('drag', function(event) {
		view();
	});
	
	return vertex;
}

function _polygon(latlng) {
	var instance = L.polygon([latlng]).addTo(map);
	
	instance.on('click', function(event) {
		if(vertices.length != 0)
			deselect();
		polygons.splice(polygons.indexOf(instance), 1);
		polygon.setLatLngs(instance.getLatLngs());
		map.removeLayer(instance);
		
		for(var i=0; i < polygon.getLatLngs().length; i++) {
			var vertex = _vertex(polygon.getLatLngs()[i].lat, polygon.getLatLngs()[i].lng);
			vertices.push(vertex);
		}
		view();
	});
	
	instance.on('contextmenu', function(event) {
		polygons.splice(polygons.indexOf(instance), 1);
		map.removeLayer(instance);
	});
	
	return instance;
}

function deselect() {
	for(var i=0; i < vertices.length; i++) {
		map.removeLayer(vertices[i]);
	}
	polygons.push(_polygon(polygon.getLatLngs()));
	vertices = [];
	view();	
}	

function removeVertex(vertex) {		
	map.removeLayer(vertex);
	vertices.splice(vertices.indexOf(vertex), 1);
}

function view() {
	var pos = [];
	for(var i=0; i < vertices.length; i++) {
		pos.push(vertices[i].getLatLng());
	}
	polygon.setLatLngs(pos);
}

function load(location) {
	for(var i=0; i < location.length; i++) {
		polygons.push(_polygon(location[i]));	
	}
}

function reset(id) {
	if(vertices.length != 0)
		deselect();
	var array = [];
	for(var i=0; i < polygons.length; i++) {
		var tmp = polygons[i].getLatLngs();
		tmp.push(tmp[0]);
		array.push(tmp);
		map.removeLayer(polygons[i]);
	}
	polygons = [];
	$("#location-"+id)[0].value = JSON.stringify(array);
}