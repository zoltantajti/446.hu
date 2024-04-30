class Coverage {
    constructor(marker, radiusKM, item, color = "red", fillColor = "red", fillOpacity = 0.2){
        if(marker.options.status === "1"){
            const {lat, lng} = marker.getLatLng();
            const circle = L.circle([lat,lng], {
                radius: radiusKM * 1000,
                color: color,
                fillColor: fillColor,
                fillOpacity: fillOpacity
            });
            circle.bindPopup(`<b>${item.title}</b><hr/>${item.description}`);
            return circle;
        }else{
            const {lat, lng} = marker.getLatLng();
            const circle = L.circle([lat,lng], {
                radius: radiusKM * 0,
                color: color,
                fillColor: fillColor,
                fillOpacity: fillOpacity
            });
            circle.bindPopup(`<b>${item.title}</b><hr/>${item.description}`);
            return circle;
        }
    }
};

export { Coverage };