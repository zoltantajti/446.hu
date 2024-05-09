import {Rest} from "./Rest.js"
import {Marker} from "./marker.js"
import {Coverage} from "./Coverage.js"
import { ContextMenu } from "./ContextMenu.js";
import { Search } from "./Search.js";
import { Reference } from "./Reference.js";

class Map {
    constructor() {
        this.cms = new ContextMenu();
        this.search = new Search();
        this.ref = new Reference().ref;
    }

    init = function() {
        this.osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: ''
        });
        /*this.stadiaLayer = L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.{ext}', {
            minZoom: 0,
            maxZoom: 20,
            ext: 'png'
        });*/

        this.baseLayers = {
            "OpenStreetMap": this.osmLayer,
            //"Sötét": this.stadiaLayer
        };

        this.mobileRadioLayer = L.layerGroup();
        this.desktopRadioLayer = L.layerGroup();
        this.parrotLayer = L.layerGroup();
        this.parrotCoverageLayer = L.layerGroup();
        this.stationLayer = L.layerGroup();
        this.unkownLayer = L.layerGroup();
        this.QTHLocator = L.layerGroup();

        this.eventLayer = L.layerGroup();

        if(this.ref === "internal"){
            this.markerCategories = {
                "Kézi rádió": this.mobileRadioLayer,
                "Asztali rádió": this.desktopRadioLayer,
                "Papagáj": this.parrotLayer,
                "Papagáj lefedettség": this.parrotCoverageLayer,
                "Amatőr átjátszó": this.stationLayer,
                "Ismeretlen": this.unkownLayer,
                "QTH Lokátor": this.QTHLocator,
                "Események": this.eventLayer
            }
            this.layers = [this.osmLayer, this.mobileRadioLayer, this.desktopRadioLayer, this.parrotLayer, this.stationLayer, this.eventLayer];
        }else{
            this.markerCategories = {
                "Kézi rádió": this.mobileRadioLayer,
                "Asztali rádió": this.desktopRadioLayer,
                "Papagáj": this.parrotLayer,
                "Amatőr átjátszó": this.stationLayer
            };
            this.layers = [this.osmLayer, this.mobileRadioLayer, this.desktopRadioLayer, this.parrotLayer];
        };
    }

    show = function() {
        this.init();
        this.map = L.map('map', {
            center: [47.1628, 19.5036],
            zoom: 8,
            layers: this.layers,
            type: "map"
        });
        this.map.on('click', (e) => {
            
        });
        this.map.on('contextmenu', (e) => {
            if(this.ref === "internal") { this.cms.show(e, null); };
        })
        L.control.layers(this.baseLayers, this.markerCategories).addTo(this.map);
        
        this.QTHLocator.addLayer(L.maidenhead({color : 'rgba(255, 0, 0, 0.4)'}));

        this.cms.initFromMap(this);
    }

    getMarkers = function() {
        let rest = new Rest();
        rest.getMarkers().then((data) => { 
            data.forEach((_marker) => {
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
                    let markerObject = new Marker(_marker.id, _marker.lat, _marker.lon, 1, _marker.title, _marker.description, 'event', icon, null);
                    markerObject.create(this.eventLayer);
                });
            });
        };
    }
};

var map = new Map;
map.show();
map.getMarkers();
map.getEvents();

tinymce.init({
    selector: 'textarea#description',
    plugins: 'link',
    toolbar: 'bold italic underline numlist bullist link'
})