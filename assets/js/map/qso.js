import {Rest} from "./Rest.js"
import {Marker} from "./marker.js"
import { Reference } from "./Reference.js"
import { Polyline } from "./Polyline.js";

class Map {
    constructor(){
        this.ref = new Reference().ref;
    }

    init = function()
    {
        this.osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: ''
        });
        this.stadiaLayer = L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.{ext}', {
            minZoom: 0,
            maxZoom: 20,
            ext: 'png'
        });
        this.qsoLayer = L.layerGroup();
        this.baseLayers = {
            "OpenStreetMap": this.osmLayer,
            "Sötét": this.stadiaLayer
        };
        this.layers = [this.osmLayer, this.qsoLayer];
    }

    show = function() {
        this.init();
        this.map = L.map('map', {
            center: [47.1628, 19.5036],
            zoom: 8,
            layers: this.layers,
            type: "map"
        });
        this.map.on('click', (e) => { });
        this.map.on('contextmenu', (e) => { });
        L.control.layers(this.baseLayers).addTo(this.map);
    }

    getQSOs = function() {
        let rest = new Rest();
        rest.getQSOs().then((data) => {
            data.forEach((qso) => {

                console.log(qso);

                let myPos = JSON.parse(qso.myPos);
                let me = new Marker(1, myPos.lat, myPos.lon, 1, qso.my_callsign, "", "mobile_radio", null, null, false,  null,  "internal");
                me.create(this.qsoLayer)

                let remPos = JSON.parse(qso.remPos);
                let rem = new Marker(2, remPos.lat, remPos.lon, 1, qso.rem_callsign, "", "mobile_radio", null, null, false, null, "internal");
                rem.create(this.qsoLayer);

                var points = [
                    [myPos.lat, myPos.lon],
                    [remPos.lat, remPos.lon]
                ];

                let line = new Polyline(points, qso);
                line.setColor('red');
                line.create(this.qsoLayer);
            })
        })
    }
}

var map = new Map;
map.show();
map.getQSOs();