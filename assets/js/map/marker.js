import { Icon } from "./Icon.js";

class Marker {
    constructor(id, lat, lon, active, title, description, type, parrotState, parrotRadius, ref = "public"){
        this.id = id;
        this.lat = lat;
        this.lon = lon;
        this.active = active;
        this.title = title;
        this.description = description;
        this.type = type;
        this.parrotState = parrotState;
        this.parrotRadius = parrotRadius;
        this.ref = ref;
    }

    create = (_layer) => {
        this.marker = L.marker([this.lat, this.lon], {
            icon: new Icon(this.type, this.parrotState),
            dbID: this.id,
            type: this.type,
            status: this.parrotState
        });
        if(this.ref === "internal"){
            this.marker.bindPopup(`<b>${this.title}</b><hr/>${this.description}`);
            this.marker.on('contextmenu', (e) => { });
        }else if(this.ref === "public"){
            this.marker.bindPopup(`<b>${this.title}</b><hr/>Az információk csak<br/> a belső térképen érhetőek el!`);
            this.marker.on('contextmenu', (e) => { });
        }
        _layer.addLayer(this.marker);
        return this.marker;
    }
};

export { Marker };