$(document).ready((event) => { 
    const toolTipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...toolTipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

    /*const openChatBtn = document.getElementById("openChat");
    const closeChatBtn = document.getElementById("closeChat");
    const popupContainer = document.getElementById("popupContainer");
    openChatBtn.addEventListener('click', (event) => {
        let openAI = new OpenAI();
        $("#popupContainer").fadeIn();
    });
    closeChatBtn.addEventListener('click', (event) => {
        $("#popupContainer").fadeOut();
    })*/

    if(window.location.href.includes('qso/add')){
        $("#my_qth").on('keyup', (event) => {
            if($("#my_qth").val().length >= 6){
                let qth = $("#my_qth").val();
                let coords = qthToCoords(qth);
                $("#myPos").val(JSON.stringify(coords));
                $("#my_address_line_1").fadeOut();
                $("#my_address_line_2").fadeOut();
            }else{
                $("#my_address_line_1").fadeIn();
                $("#my_address_line_2").fadeIn();
            }
        })
        $("#rem_qth").on('keyup', (event) => {
            if($("#rem_qth").val().length >= 6){
                let qth = $("#rem_qth").val();
                let coords = qthToCoords(qth);
                $("#remPos").val(JSON.stringify(coords));
                $("#rem_address_line_1").fadeOut();
                $("#rem_address_line_2").fadeOut();
            }else{
                $("#rem_address_line_1").fadeIn();
                $("#rem_address_line_2").fadeIn();
            }
        })
    }

    if(window.location.href.includes('profile')){
        var profileRegExp = /\/profile\/(\d+)/;
        if(profileRegExp.test(window.location.href)){
            const qthCodeField = document.getElementById('qthCode');
            let parts = window.location.href.split('/');
            let userID = parts[parts.length - 1];
            if(qthCodeField){
                qthCodeField.innerHTML = '<i class="fa fa-fw fa-spinner fa-spin"></i>';
                $.getJSON('Rest/getUserAddressByID/' + userID,(data) => {
                    let county = address = precision = null;
                    if(data.country !== "Magyarország"){
                        address = data.country +"," + data.city;
                        precision = 3;
                    }else{
                        county = (data.country === "Magyarország" && data.county !== "Budapest") ? data.county + " Vármegye," : "Budapest,";
                        address = `${data.country}, ${county} ${data.city}, ${data.address}`;
                        precision = 4;
                    };                    
                    $.getJSON(`https://geocode.maps.co/search?q=${address}&api_key=663371914590f062701345usb7a1683`, (geo) => {
                        console.log(address);
                        if(geo.length === 0){
                            qthCodeField.innerHTML = '<span class="text-danger">N/A</span> <i class="fa fa-fw fa-exclamation-triangle text-danger" title="Kérd meg, hogy pontosítsa a címét!"></i>';
                            $("#errorBefore").before('<div class="alert alert-danger">A felhasználó nem adta meg pontosan a címét, ezért a QTH lokátor kód nem számolható ki!');
                            $("#qthIcon").addClass("text-danger");
                            $("#qthRow").addClass("text-bg-light");
                        }else{
                            let latlon = {'lat': geo[0]['lat'], 'lon': geo[0]['lon']};
                            let qth = getQTHCode(latlon, precision);    
                            qthCodeField.innerHTML = qth;
                        };
                    });
                })
            }
        }else{
            initRadios();
            initAntennas();
            initFreqs();
        };
    };    
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
const getQTHCode = (object, _precision = 4) => {
    let lat = object.lat;
    let lon = object.lon;
    var ydiv_arr = [10, 1, 1/24, 1/240, 1/240/24];
    var d1 = "ABCDEFGHIJKLMNOPQRSTUVWXYZ".split("");
    var d2 = "ABCDEFGHIJKLMNOPQRSTUVWX".split("");
    var locator = "";
    var x = lon;
    var y = lat;
    var precision = _precision;
    while (x < -180) { x += 360; }
    while (x > 180) { x -= 360; }
    x = x + 180;
    y = y + 90;
    locator += d1[Math.floor(x / 20)] + d1[Math.floor(y / 10)];
    for (var i = 0; i < 4; i++) {
        if (precision > i + 1) {
            var rlon = x % (ydiv_arr[i] * 2);
            var rlat = y % (ydiv_arr[i]);
            if ((i % 2) == 0) {
                locator += Math.floor(rlon / (ydiv_arr[i + 1] * 2)) + "" + Math.floor(rlat / (ydiv_arr[i + 1]));
            } else {
                locator += d2[Math.floor(rlon / (ydiv_arr[i + 1] * 2))] + "" + d2[Math.floor(rlat / (ydiv_arr[i + 1]))];
            }
        }
    };
    return locator.replace('AE','JN').replace('BE','JN');
}
function qthToCoords(locator) {
    var text = String(locator);
    text = text.toUpperCase();
    let lon = 20 * (text.charCodeAt(0) - 65);
    let lat = 10 * (text.charCodeAt(1) - 65);
    lon = lon + 2 * (text.charCodeAt(2) - 48);
    lat = lat + (text.charCodeAt(3) - 48);
    lon = lon + (text.charCodeAt(4) - 65 + 0.5) / 12;
    lat = lat + (text.charCodeAt(5) - 65 + 0.5) / 24;
    lon = lon -180;
    lat = lat -90;

    return {lat: lat.toFixed(6), lon: lon.toFixed(6)};
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
    }else if($("#mode").val() === "/P"){
        $("#parrotFrame").removeClass("hidden");
        $("#repeater-caption").html('Papagáj');
        $("#repeater-field").attr('Placeholder', 'Papagáj neve');
    }else if($("#mode").val() === "/A"){
        $("#parrotFrame").removeClass("hidden");
        $("#repeater-caption").html('Állomás');
        $("#repeater-field").attr('Placeholder', 'Állomás neve');
    }
})

$("#profile-countries").change((event) => {
    if($("#profile-countries").val() === "Magyarország"){
        $("#profile-counties").attr("list", "counties");
        $("#profile-cities").attr("list", null);
    }else{
        alert("OK");
        $("#profile-counties").attr("list", null);
        $("#profile-cities").attr("list", null);
    }
})
$("#profile-counties").change((event) => {
    let county = $("#profile-counties").val();
    $.getJSON("Rest/getCitiesByCounty/" + county, (data) => {
        $("#cities").empty();
        data.forEach((city,index) => {
            let content = `<option value="${city.name}">`;
            $("#cities").append(content);
        })
    })
})

let radios = [];
let antennas = [];
let freqs = [];

const initRadios = () => {
    let radios = JSON.parse($("#radios").val());
    radios.forEach((radio) => { createRadioItem(radio); })
}
const initAntennas = () => {
    let radios = JSON.parse($("#antennas").val());
    radios.forEach((radio) => { createAntennaItem(radio); })
}
const initFreqs = () => {
    let radios = JSON.parse($("#freqs").val());
    radios.forEach((radio) => { createFreqItem(radio); })
}

const createRadioItem = (name, fromInput = false) => {
    const id = name.replace('/\s+/g', "-");
    const removeLink = document.createElement("a");
    removeLink.href = "javascript:;";
    removeLink.innerHTML = '<i class="fa fa-fw fa-times red"></i>';
    removeLink.addEventListener('click', () => {
        const radioItem = document.getElementById("RI_" + id);
        if(radioItem){
            let index = radios.indexOf(name);
            if(index !== -1){
                radios.splice(index, 1);
            };
            radioItem.remove();
            updateRadioField();
        }
    });
    const radioItem = document.createElement("span");
    radioItem.classList.add("radioItem");
    radioItem.classList.add("input-group-text");
    radioItem.id = "RI_" + id;
    radioItem.textContent = name;
    radioItem.appendChild(removeLink);
    $("#radiosFrameAfter").after(radioItem);
    radios.push(name);
    updateRadioField();
}
const createAntennaItem = (name, fromInput = false) => {
    const id = name.replace('/\s+/g', "-");
    const removeLink = document.createElement("a");
    removeLink.href = "javascript:;";
    removeLink.innerHTML = '<i class="fa fa-fw fa-times red"></i>';
    removeLink.addEventListener('click', () => {
        const radioItem = document.getElementById("AI_" + id);
        if(radioItem){
            let index = antennas.indexOf(name);
            if(index !== -1){
                antennas.splice(index, 1);
            };
            radioItem.remove();
            updateAntennaField();
        }
    });
    const radioItem = document.createElement("span");
    radioItem.classList.add("radioItem");
    radioItem.classList.add("input-group-text");
    radioItem.id = "AI_" + id;
    radioItem.textContent = name;
    radioItem.appendChild(removeLink);
    $("#antennasFrameAfter").after(radioItem);
    antennas.push(name);
    updateAntennaField();
}
const createFreqItem = (name, fromInput = false) => {
    const id = name.replace('/\s+/g', "-");
    const removeLink = document.createElement("a");
    removeLink.href = "javascript:;";
    removeLink.innerHTML = '<i class="fa fa-fw fa-times red"></i>';
    removeLink.addEventListener('click', () => {
        const radioItem = document.getElementById("FI_" + id);
        if(radioItem){
            let index = freqs.indexOf(name);
            if(index !== -1){
                freqs.splice(index, 1);
            };
            radioItem.remove();
            updateFreqField();
        }
    });
    const radioItem = document.createElement("span");
    radioItem.classList.add("radioItem");
    radioItem.classList.add("input-group-text");
    radioItem.id = "FI_" + id;
    radioItem.textContent = name;
    radioItem.appendChild(removeLink);
    $("#freqsFrameAfter").after(radioItem);
    freqs.push(name);
    updateFreqField();
}

const updateRadioField = () => {
    $("#radios").val(JSON.stringify(radios));
}
const updateAntennaField = () => {
    $("#antennas").val(JSON.stringify(antennas));
}
const updateFreqField = () => {
    $("#freqs").val(JSON.stringify(freqs));
}

$("#addRadio").on('click', (event) => {
    if($("#radios_input").val().length == 0){
        alert("A rádió mező nem lehet üres!");
    }else{
        createRadioItem($("#radios_input").val());
        $("#radios_input").val("");
    }
})
$("#addAntenna").on('click', (event) => {
    if($("#antennas_input").val().length == 0){
        alert("Az antenna mező nem lehet üres!");
    }else{
        createAntennaItem($("#antennas_input").val());
        $("#antennas_input").val("");
    }
})
$("#addFreq").on('click', (event) => {
    if($("#freqs_input").val().length == 0){
        alert("Az antenna mező nem lehet üres!");
    }else{
        createFreqItem($("#freqs_input").val());
        $("#freqs_input").val("");
    }
})