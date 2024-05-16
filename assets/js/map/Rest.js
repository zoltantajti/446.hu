import { Toast } from "./Toast.js"

class Rest {
    /*Get base markers from db; Output: Promise<JSON>*/
    getMarkers = function() {
        return new Promise(function(resolve, reject){
            $.getJSON("Rest/getMapMarkers", function(data){
                resolve(data);
            });
        });
    }
    getTempMarkers = function() {
        return new Promise(function(resolve,reject){
            $.getJSON("Rest/getMapTempMarkers", function(data){
                resolve(data);
            });
        });
    }
    getMarkerById = function(id) {
        return new Promise(function(resolve,reject){
            $.getJSON("Rest/getMarkerById/" + id, function(data){
                resolve(data);
            })
        })
    }

    getQSOs = function() {
        return new Promise(function(resolve,reject){
            $.getJSON("Rest/getQSOs", function(data){
                resolve(data);
            });
        });
    }

    getEvents = function() {
        return new Promise(function(resolve, reject){
            $.getJSON("Rest/getMapEvents", function(data){
                resolve(data);
            });
        });
    }

    saveMarker = function() {
        var lat = $("#lat").val();
        var lon = $("#lon").val();
        var type = $("#type").val();
        var title = $("#title").val();
        var description = tinyMCE.get('description').getContent();
        var fields = {
            lat: lat,
            lon: lon,
            type: type,
            title: title,
            description: description
        };
        
        $.post("Rest/addMarker", fields, function(data, status){
            $("#addNewMarker").hide();
            $("#lat").val('');
            $("#lon").val('');
            $("#type").val('mobile_radio');
            $("#title").val('');
            $("#description").val('');

            let toast = new Toast();
            toast.show("Köszi :)", "Hamarosan megnézzük!");
        });
    }
    saveTempMarker = function() {
        var lat = $("#temp_lat").val();
        var lon = $("#temp_lon").val();
        var from = $("#from").val();
        var to = $("#to").val();
        var title = $("#temp_title").val();
        var freq = $("#freq").val();
        var ctcss = $("#ctcss").val();
        var dcs = $("#dcs").val();
        var content = tinyMCE.get('content').getContent();
        var fields = {
            lat: lat,
            lon: lon,
            from: from,
            to: to,
            title: title,
            content: content,
            freq: freq,
            ctcss: ctcss,
            dcs: dcs
        };
        
        $.post("Rest/addTempMarker", fields, function(data, status){
            $("#addNewMarkerTemp").hide();
            $("#lat").val('');
            $("#lon").val('');
            $("#type").val('mobile_radio');
            $("#title").val('');
            $("#description").val('');

            let toast = new Toast();
            toast.show("Köszi :)", "Hamarosan megnézzük!");

            setTimeout((event) => {
                window.location.reload();
            }, 3000);
        });
    }

    updateMarker = function(id) {
        var lat = $("#lat").val();
        var lon = $("#lon").val();
        var type = $("#type").val();
        var title = $("#title").val();
        var description = tinyMCE.get('description').getContent();
        var fields = {
            id: id,
            lat: lat,
            lon: lon,
            type: type,
            title: title,
            description: description
        };
        $.post("Rest/updateMarker", fields, function(data, status){
            $("#addNewMarker").hide();
            $("#lat").val('');
            $("#lon").val('');
            $("#type").val('mobile_radio');
            $("#title").val('');
            $("#description").val('');

            let toast = new Toast();
            toast.show("Köszi :)", "Hamarosan megnézzük!");
        });
    }
};

export { Rest };