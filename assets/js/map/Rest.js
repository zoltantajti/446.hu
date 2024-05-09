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