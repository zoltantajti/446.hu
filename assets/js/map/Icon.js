const iconUrls = {
    "mobile_radio" : "./assets/images/markers/mobile_radio.png",
    "desktop_radio" : "./assets/images/markers/desktop_radio.png",
    "parrot_active" : "./assets/images/markers/parrot_active.png",
    "parrot_inactive" : "./assets/images/markers/parrot_inactive.png",
    "station" : "./assets/images/markers/station.png",
    "unkown" : "./assets/images/markers/question.png",
}
const icons = {
    "mobile_radio": L.icon({
        iconUrl: iconUrls["mobile_radio"],
        iconSize: [32,32],
        iconAnchor: [16,16],
        popupAnchor: [0,-16]
    }),
    "desktop_radio": L.icon({
        iconUrl: iconUrls["desktop_radio"],
        iconSize: [32,32],
        iconAnchor: [16,16],
        popupAnchor: [0,-16]
    }),
    "parrot_active": L.icon({
        iconUrl: iconUrls["parrot_active"],
        iconSize: [32,32],
        iconAnchor: [16,16],
        popupAnchor: [0,-16]
    }),
    "parrot_inactive": L.icon({
        iconUrl: iconUrls["parrot_inactive"],
        iconSize: [32,32],
        iconAnchor: [16,16],
        popupAnchor: [0,-16]
    }),
    "station": L.icon({
        iconUrl: iconUrls["station"],
        iconSize: [32,32],
        iconAnchor: [16,16],
        popupAnchor: [0,-16]
    }),
    "unkown": L.icon({
        iconUrl: iconUrls["unkown"],
        iconSize: [32,32],
        iconAnchor: [16,16],
        popupAnchor: [0,-16]
    })
};

class Icon {
    constructor(type, parrotState){
        if(type !== "parrot" && type !== "event"){
            return icons[type];
        }else if(type === "event"){
            var icon = L.ExtraMarkers.icon({
                icon: parrotState.image,
                markerColor: parrotState.color,
                shape: parrotState.type,
                prefix: 'fa'
            });
            return icon;
        }else{
            const s = (parrotState == 1) ? "active" : "inactive";
            return icons[type + "_" + s];
        }
    }
};
export { Icon, iconUrls };