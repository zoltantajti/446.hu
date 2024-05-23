import {Rest} from "./Rest.js"
import {Marker} from "./marker.js"
import {Coverage} from "./Coverage.js"
import { ContextMenu } from "./ContextMenu.js";
import { Search } from "./Search.js";
import { Reference } from "./Reference.js";
import { Attrs } from "./Attrs.js";
import { Weather } from "./Weather/Weather.js";
import { Polygon } from "./Polygon.js";

class Map {
    constructor() {
        this.cms = new ContextMenu();
        this.search = new Search();
        this.ref = new Reference().ref;
        this.weatherControl = new Weather();
    }

    init = function() {
        this.base = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            minZoom: 7,    
            maxZoom: 18,            
            attribution: ''
        });
        this.hybrid = L.tileLayer('https://{s}.tile-cyclosm.openstreetmap.fr/cyclosm/{z}/{x}/{y}.png', {
	        minZoom: 7,
	        maxZoom: 18,
	        attribution: ''
        });

        this.baseLayers = {
            "Alap": this.base,
            "Hibrid": this.hybrid,
        };

        this.mobileRadioLayer = L.layerGroup();
        this.desktopRadioLayer = L.layerGroup();
        this.parrotLayer = L.layerGroup();
        this.parrotCoverageLayer = L.layerGroup();
        this.stationLayer = L.layerGroup();
        this.unkownLayer = L.layerGroup();
        this.QTHLocator = L.layerGroup();
        this.TempLocations = L.layerGroup();
        this.eventLayer = L.layerGroup();
        this.weatherLayer = L.layerGroup();
        this.weatherMLayer = L.layerGroup();
        this.restAreas = L.layerGroup();

        if(this.ref === "internal"){
            this.markerCategories = {
                "Kézi rádió": this.mobileRadioLayer,
                "Asztali rádió": this.desktopRadioLayer,
                "Papagáj": this.parrotLayer,
                //"Papagáj lefedettség": this.parrotCoverageLayer,
                "Amatőr átjátszó": this.stationLayer,
                //"Ismeretlen": this.unkownLayer,
                "QTH Lokátor": this.QTHLocator,
                "Kitelepülések": this.TempLocations,
                "Események": this.eventLayer,
                "Időjárástérkép": this.weatherLayer,
                "Időjárás markerek": this.weatherMLayer,
                "Tiltott területek": this.restAreas
            }
            this.layers = [
                this.base,
                this.TempLocations, 
                this.mobileRadioLayer, 
                this.desktopRadioLayer, 
                this.parrotLayer, 
                this.stationLayer, 
                this.eventLayer,
                this.restAreas,
                //this.weatherLayer,
                //this.weatherMLayer
            ];
        }else{
            this.markerCategories = {
                "Kézi rádió": this.mobileRadioLayer,
                "Asztali rádió": this.desktopRadioLayer,
                "Papagáj": this.parrotLayer,
                "Amatőr átjátszó": this.stationLayer
            };
            this.layers = [this.base, this.mobileRadioLayer, this.desktopRadioLayer, this.parrotLayer];
        };
    }

    show = function() {
        this.init();

        let center = [47.1628, 19.5036];
        let zoom = 8;

        let attrs = new Attrs();
        let _center = attrs.getAttributes('center');
        if(_center != null){
            let centerArray = _center.split(':');
            if(centerArray.length == 3){
                center = [centerArray[0], centerArray[1]];
                zoom = centerArray[2];
            };
        }

        this.map = L.map('windy', {
            center: center,
            zoom: zoom,
            layers: this.layers,
            type: "map"
        });
        this.map.on('click', (e) => { });
        this.map.on('contextmenu', (e) => {
            if(this.ref === "internal") { this.cms.show(e, null); };
        })
        L.control.layers(this.baseLayers, this.markerCategories).addTo(this.map);
        
        this.QTHLocator.addLayer(L.maidenhead({color : 'rgba(255, 0, 0, 0.4)'}));

        this.cms.initFromMap(this);

        let options = {
            apiKey: '89fcf7a64197f34ff0c3f596521057db',
            lang: 'hu',
            units: 'metric',
            template: `<div class="weatherIcon"><img src=":iconurl"></div><div>Hőmérséklet: <b>:temperature°C</b></div><div>Pára: <b>:humidity%</b></div><div>Szél: <b>:winddirection :windspeed m/s</b></div>`,}
        let wControl = new L.Control.Weather(options);
        this.map.addControl(wControl);

        let wx = new Weather();
        wx.init(this.weatherLayer);
        wx.getCities(this.weatherMLayer);
    }

    getMarkers = function() {
        let rest = new Rest();
        rest.getMarkers().then((data) => { 
            data.forEach((_marker) => {
				//console.log(_marker.title, _marker.lat, _marker.lon);
                let markerObject = new Marker(_marker.id, _marker.lat, _marker.lon, _marker.active, _marker.title, _marker.description, _marker.type, _marker.parrotState, _marker.parrotRadius, _marker.hasUser, _marker.userID, this.ref);
                switch(_marker.type){
                    case "mobile_radio": 
                        markerObject.create(this.mobileRadioLayer); 
                        break;
                    case "desktop_radio": 
                        markerObject.create(this.desktopRadioLayer); 
                        break;
                    case "station": 
                        markerObject.create(this.stationLayer); 
                        break;
                    case "parrot": 
                        let __marker = markerObject.create(this.parrotLayer);
                        this.parrotLayer.addLayer(__marker);
                        let coverage = new Coverage(__marker, _marker.parrotRadius, _marker); 
                        this.parrotCoverageLayer.addLayer(coverage);
                        if(this.ref === "internal"){
                            __marker.on('contextmenu', (e) => { this.cms.show(e, this.parrotLayer.getLayerId(__marker)); });
                            this.map.on('click', (e) => { this.cms.hide(); })
                        };
                        break;
                }
            })
        });   
    }

    getTempMarkers = function() {
        let rest = new Rest();
        rest.getTempMarkers().then((data) => {
            data.forEach((_marker) => {
                let attrs = {
                    "freq": _marker.freq,
                    "ctcss": _marker.ctcss,
                    "dcs": _marker.dcs,
					"from": _marker.from,
					"to": _marker.to
                };				
                let markerObject = new Marker(_marker.id, _marker.lat, _marker.lon, 1, _marker.title, _marker.content, "temp_location", null, null, true, _marker.createdAt, this.ref, attrs);
                markerObject.create_temp(this.TempLocations);
            });
        });
        
    }

    getEvents = function() {
        if(this.ref === "internal"){
            let rest = new Rest();
            rest.getEvents().then((data) => {
                data.forEach((_marker) => {
                    var icon = {
                        type: _marker.iType,
                        image: _marker.icon,
                        color: _marker.iColor
                    }
                    let markerObject = new Marker(_marker.id, _marker.lat, _marker.lon, 1, _marker.title, _marker.description, 'event', icon, null, false, null, this.ref);
                    markerObject.create(this.eventLayer);
                });
            });
        };
    }

    getRestAreas = function() {
        if(this.ref === "internal"){
            let rest = new Rest();
            rest.getRestZones().then((data) => {
                data.forEach((_zone) => {
                    let _coords = JSON.parse(_zone.coords);
                    let coords = [];
                    _coords.forEach((point) => {coords.push([point.lat, point.lng]);});
                    let zoneObject = new Polygon(coords, _zone.color, _zone.description);
                    zoneObject.create(this.restAreas);
                })
            })
        }
    }
};

var map = new Map;
map.show();
map.getMarkers();
map.getEvents();
map.getTempMarkers();
map.getRestAreas();