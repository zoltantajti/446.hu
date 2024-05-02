$(document).ready((event) => {
    
    
});

let mylat, mylon, remlat, remlon = 0;

const getGeoCode = (address) => {
    address = encodeURIComponent(address);
    return new Promise((resolve, reject) => {
        $.getJSON(`https://geocode.maps.co/search?q=${address}&api_key=663371914590f062701345usb7a1683`, (data) => {
            let latlon = {'lat' : data[0]['lat'], 'lon' : data[0]['lon']};
            resolve(latlon);
        }).fail((error) => {
            reject(error);
        });
    });
}
const getQTHCode = (object) => {
    let lat = object.lat;
    let lon = object.lon;
    var QTHlon = (1 * lon + 180) / 20;
    var QTHlat = (1 * lat + 90) / 10;
    var QTHlocator = String.fromCharCode(Math.floor(QTHlon) + 65) + String.fromCharCode(Math.floor(QTHlat) + 65);
    
    QTHlon = (QTHlon %1) * 10;
    QTHlat = (QTHlat %1) * 10;
    QTHlocator = QTHlocator + String.fromCharCode(Math.floor(QTHlon) + 48) + String.fromCharCode(Math.floor(QTHlat) + 48);
    
    QTHlon = (QTHlon %1) * 24;
    QTHlat = (QTHlat %1) * 24;
    QTHlocator = QTHlocator + String.fromCharCode(Math.floor(QTHlon) + 97) + String.fromCharCode(Math.floor(QTHlat) + 97);
    
    return QTHlocator.toUpperCase();
}
const calculateQTH = (prefix) => {
    let address = $(`#${prefix}_country`).val() + ", " + $(`#${prefix}_county`).val() + ", " + $(`#${prefix}_city`).val() + " " + $(`#${prefix}_address`).val();
    getGeoCode(address).then((latlon) => {
        if(prefix === "my"){ 
            mylat = latlon.lat; mylon = latlon.lon;
            $("#myPos").val(JSON.stringify(latlon))
        };
        if(prefix === "rem"){ 
            remlat = latlon.lat; remlon = latlon.lon; 
            $("#remPos").val(JSON.stringify(latlon))
        };        
        let location = getQTHCode(latlon);        
        $(`#${prefix}_qth`).val(getQTHCode(latlon));
    });
}
function FNCoordinates(qth)
{
    var text = String(qth);
    text = text.toUpperCase();
    let lon = 20 * (text.charCodeAt(0) - 65);
    let lat = 10 * (text.charCodeAt(1) - 65);
    lon = lon + 2 * (text.charCodeAt(2) - 48);
    lat = lat + (text.charCodeAt(3) - 48);
    lon = lon + (text.charCodeAt(4) - 65 + 0.5) / 12;
    lat = lat + (text.charCodeAt(5) - 65 + 0.5) / 24;
    lon = lon -180;
    lat = lat -90;
    return {lat: lat.toFixed(8), lon: lon.toFixed(8)};
}
function degreesToRadians(degrees){
    return degrees * (Math.PI / 180);
}
function calculateDistance(my, rem) {
    const earthRadiusKm = 6371;
    const φ1 = degreesToRadians(my.lat);
    const φ2 = degreesToRadians(rem.lat);
    const Δφ = degreesToRadians(rem.lat - my.lat);
    const Δλ = degreesToRadians(rem.lon - my.lon);
    const a = Math.sin(Δφ / 2) * Math.sin(Δφ / 2) +Math.cos(φ1) * Math.cos(φ2) * Math.sin(Δλ / 2) * Math.sin(Δλ / 2);
    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    const distance = earthRadiusKm * c;
    return distance;
}
$("#calculateDistance").on('click', (event) => {
    let error = false;
    if($("#my_qth").val().length === 0){
        alert("Először generáld le a QTH lokátor kódod!");
        $("#my_country").focus();
        error = true;
    };
    if($("#rem_qth").val().length === 0 && !error){
        alert("Először generáld le az Ellenállomás QTH lokátor kódját!");
        $("#rem_country").focus();
        error = true;
    };
    if(!error){
        let myPos = FNCoordinates($("#my_qth").val());
        let remPos = FNCoordinates($("#rem_qth").val());
        let distance = calculateDistance(myPos, remPos);
        $("#distance").val(`${distance.toFixed(2)} Km`);
    }
});
$("#mode").change((event) => {
    if($("#mode").val() === "/D"){
        $("#parrotFrame").addClass("hidden");
        $("#amateurFrame").addClass("hidden");
    }else if($("#mode").val() === "/P"){
        $("#parrotFrame").removeClass("hidden");
        $("#amateurFrame").addClass("hidden");
    }else if($("#mode").val() === "/A"){
        $("#parrotFrame").addClass("hidden");
        $("#amateurFrame").removeClass("hidden");
    }
})