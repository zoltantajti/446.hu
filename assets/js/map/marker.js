import { Icon } from "./Icon.js";
import { Rest } from "./Rest.js";

class Marker {
    constructor(id, lat, lon, active, title, description, type, parrotState, parrotRadius, hasUser, userID, ref = "public", attrs = null){
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
        this.hasUser = hasUser;
        this.userID = userID;
        this.attrs = attrs;
    }

    create = (_layer) => {
        if(this.lat == null || this.lon == null){
            var rest = new Rest();
            var data = {'callsign': this.title,'message': 'missing GPS coordinates! Marker ignoring!'};
            rest.createErrorTicket(data).then((result) => {
                console.log(result);
            });
            return null;
        }else{
            this.marker = L.marker([this.lat, this.lon], {
                icon: new Icon(this.type, this.parrotState),
                dbID: this.id,
                type: this.type,
                status: this.parrotState
            });
            if(this.ref === "internal"){
                let link = "";
                if(this.hasUser){
                    link = `<br/><hr/><a class="leaflet-link" title="Profil megnyitása" href="internal/profile/${this.userID}" target="_balnk"><i class="fa fa-fw fa-user"></i></a>`;
                };
                let popup = `<b>${this.title}</b><hr/>${this.description}${link}`;
                this.marker.bindPopup(popup);
                this.marker.on('contextmenu', (e) => { });
            }else if(this.ref === "public"){
                if(this.type === "parrot"){
                    this.marker.bindPopup(`<b>${this.title}</b><hr/>${this.description}`);
                    this.marker.on('contextmenu', (e) => { });
                }else{
                    this.marker.bindPopup(`<b>${this.title}</b><hr/>Az információk csak<br/> a belső térképen érhetőek el!`);
                    this.marker.on('contextmenu', (e) => { });
                };
            }
            _layer.addLayer(this.marker);
            return this.marker;
        };
    }

    create_temp = (_layer) => {
        this.marker = L.marker([this.lat, this.lon], {
            icon: new Icon(this.type, this.parrotState),
            dbID: this.id,
            type: this.type,
            status: this.parrotState
        });
        if(this.ref === "internal"){
			let link = `<br/><hr/><a class="leaflet-link" title="Profil megnyitása" href="internal/profile/${this.userID}" target="_balnk"><i class="fa fa-fw fa-user"></i></a>`;
			let popup = `<b>${this.title}</b><hr/>Kezdete: <b>${this.attrs.from}</b><br/>Vége: <b>${this.attrs.to}</b><br/><hr/>Frekvencia: <b>${this.attrs.freq} MHz</b><br/>CTCS: <b>${this.attrs.ctcss} Hz</b><br/>DCS: <b>${this.attrs.dcs}</b><hr/>${this.description}${link}`;
            this.marker.bindPopup(popup);
            this.marker.on('contextmenu', (e) => { });
            this.marker.bindTooltip(`${this.title} kitelepülése`);
            _layer.addLayer(this.marker);
            return this.marker;
        }
    }
};

export { Marker };