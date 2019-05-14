<!DOCTYPE html>
<html lang = "en-US">
<head>
  <meta charset = "UTF-8">
  <title>Signal Cartel Member Map</title>
  <script src="/mma/html/js/jquery-1.11.1.min.js"></script>
  <script src="/mma/html/js//jquery-ui.min.js"></script>
  <link rel="stylesheet" type="text/css" href="/mma/html/css/oppasignalstyle.css" />
  <link rel='stylesheet' id='twentyseventeen-fonts-css'  href='https://fonts.googleapis.com/css?family=Libre+Franklin%3A300%2C300i%2C400%2C400i%2C600%2C600i%2C800%2C800i&#038;subset=latin%2Clatin-ext' type='text/css' media='all' />
  <!-- include these libraries for map functionality -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin=""/>
  <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js" integrity="sha512-QVftwZFqvtRNi0ZyCtsznlKSWOStnDORoefr1enyq5mVL4tmKB3S/EnC3rRJcxCPavG10IcrVGSmPh6Qw5lwrg==" crossorigin=""></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> 
</head>
	<style>
html, body {height: 100%;}
#maindiv { height: 65%;}
#map { width: 100%; min-height: 100%;}
.info { padding: 6px 8px; font: 14px/16px Arial, Helvetica, sans-serif; background: white; background: rgba(255,255,255,0.8); box-shadow: 0 0 15px rgba(0,0,0,0.2); border-radius: 5px; } .info h4 { margin: 0 0 5px; color: #777; }
</style>
<body>
  <div class="header">
    <img class="bim" src="/mma/img/cras2.jpg"/>
    <div class="site-branding-text">
      <a href="https://www.signalcartel.com/" class="custom-logo-link" rel="home" itemprop="url"><img width="85.44" height="80" src="https://www.signalcartel.com/wp-content/uploads/2017/05/cropped-SignalLogo_NoWords300-1.jpg" class="custom-logo" alt="SIGNAL CARTEL" itemprop="logo" sizes="100vw" /></a>
      <div class="ttbox">
        <p class="site-title"><a href="https://www.signalcartel.com/">SIGNAL CARTEL</a></p>
        <p class="site-description">A Home for Peaceful Explorers</p>
      </div>
    </div>
  </div>

  <div class="navbar">
    <div class="navinner">
      <a href="https://www.signalcartel.com">Home</a>
    </div>	
  </div>

  <div id="maindiv">
    <div id="map">
    </div>
  </div>

  <div class="footer">
    <div class="navinner">
    <p>Proudly powered by signaleers</p>
    </div>
  </div>
</body>
<script type="text/javascript" src="/mma/html/js/map:coords.js"></script>
<script src="/mma/rest/map_data.php?usstates=on"></script>
<script src="/mma/rest/map_data.php"></script>

<script>
var xcorner1 = L.latLng(90,180);
var xcorner2 = L.latLng(-60,-180);
var xbounds  = L.latLngBounds(xcorner1, xcorner2);

//setup Map
var map = L.map('map', {
    center: [50, 0],
    zoomSnap: 0.1,
    zoom: 2,
    maxBounds: [
        [-60, -180],
        [90, 180]
    ]
}).fitBounds(xbounds);

//intializing basemap
L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFwYm94IiwiYSI6ImNpejY4NXVycTA2emYycXBndHRqcmZ3N3gifQ.rJcFIG214AriISLbB6B5aw',
    {
        id: 'mapbox.light',
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' + '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' + 'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 8,
        bounds: [
            [-90, -180],
            [90, 180]
        ],

    }).addTo(map);

//Function for Layer-interaction

function onEachFeature(feature, layer) {
	
    if (feature.properties.hasPilots == true ) {
            layer.bindTooltip("<strong>" + feature.properties.name + "</strong><br/>" + " Pilots flying: " + feature.properties.pilots, {sticky: true, interactive: false, nowrap: true, direction: 'top'})
        }	
    layer.on({
        mouseover: highlightFeature,
        mouseout: resetHighlight,

});
}

//adding GeoJSON-File with country-data and pilots	
var layerWorld = L.geoJson(worldData, {
    style: function (feature) {
        var density = feature.properties.pilots;
        var fillColor = "#FFFFFF";
        var FillOpacity = .6;
        var weight = 1;

        if (density > 13) {
            fillColor = "#800026";
        } else if (density >= 11) {
            fillColor = "#bd0026";
        } else if (density >= 9) {
            fillColor = "#e31a1c";
        } else if (density >= 7) {
            fillColor = "#fc4e2a";
        } else if (density >= 5) {
            fillColor = "#fd8d3c";
        } else if (density >= 3) {
            fillColor = "#feb24c";
        } else if (density >= 1) {
            fillColor = "#fed976";
        } else {
            FillOpacity = 0;
            weight = 0.5
        }

        var tempStyle = {
            "color": "#000000",
            "weight": weight,
            "fillColor": fillColor,
            "fillOpacity": FillOpacity
        }

        return tempStyle;
    },


    onEachFeature: onEachFeature


}).addTo(map);

var layerUs = L.geoJson(usData, {
    style: function (feature) {
        var density = feature.properties.pilots;
        var fillColor = "#FFFFFF";
        var FillOpacity = .6;
        var weight = 1;

        if (density > 13) {
            fillColor = "#800026";
        } else if (density >= 11) {
            fillColor = "#bd0026";
        } else if (density >= 9) {
            fillColor = "#e31a1c";
        } else if (density >= 7) {
            fillColor = "#fc4e2a";
        } else if (density >= 5) {
            fillColor = "#fd8d3c";
        } else if (density >= 3) {
            fillColor = "#feb24c";
        } else if (density >= 1) {
            fillColor = "#fed976";
        } else {
            FillOpacity = 0;
            weight = 0.5
        }

        var tempStyle = {
            "color": "#000000",
            "weight": weight,
            "fillColor": fillColor,
            "fillOpacity": FillOpacity
        }

        return tempStyle;
    },

    onEachFeature: onEachFeature

});

// General legend
var info = L.control();

info.onAdd = function (map) {
    this._div = L.DomUtil.create('div', 'info');
    this.update();
    return this._div;
};

info.update = function (props) {
    this._div.innerHTML = '<h4>Signal Cartel Pilots</h4>' +   'Hover over a state' + "<br/>" + "Signal Pilots: " + "<strong>" + allPilots + "</strong>";
};

//Layer element to switch between US and States data

var overlayMaps = {
	"All Countries": layerWorld,
	"US States": layerUs
};

L.control.layers(overlayMaps,null,{position: 'topleft', collapsed:false}).addTo(map);

info.addTo(map);

//Legend showing the names of opted-in pilots

function highlightFeature(e) {
	var layer = e.target;
	info.update(layer.feature.properties);
} 

function resetHighlight(e) {
	info.update();
	}

var info = L.control();

info.onAdd = function (map) {
	this._div = L.DomUtil.create('div', 'info');
	this.update();
	return this._div;
};

info.update = function (props) {
	this._div.innerHTML = '<h4>Pilots from:</h4>' +  (props ?
		'<b>' + props.name + '</b><br />' + props.alts  
		: 'Hover over a state');
};

info.addTo(map);


</script>
</html>	