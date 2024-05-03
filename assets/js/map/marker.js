import { Icon } from "./Icon.js";

class Marker {
    constructor(id, lat, lon, active, title, description, type, parrotState, parrotRadius, hasUser, userID, ref = "public"){
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
    }

    create = (_layer) => {
        this.marker = L.marker([this.lat, this.lon], {
            icon: new Icon(this.type, this.parrotState),
            dbID: this.id,
            type: this.type,
            status: this.parrotState
        });
        if(this.ref === "internal"){
            let link = "";
            if(this.hasUser){
                link = `<br/><hr/><a title="Profil megnyitása" href="internal/profile/${this.userID}" target="_balnk"><i class="fa fa-fw fa-user"></i></a>`;
            };

            let popup = `<b>${this.title}</b><hr/>${this.description}${link}`;

            this.marker.bindPopup(popup);
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