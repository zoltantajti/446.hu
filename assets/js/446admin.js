const domain = "sun-city-hu";
var scripts = document.getElementsByTagName('script');
var src = scripts[scripts.length - 1].src;
var token = src.match(/token=[0-9]+/);

$("#filter").keyup((event) => {
    let input = $("#filter");
    let val = input.val();
    let regex = /[\s\W]/;
    if(regex.test(val)){
        input.addClass("is-invalid");
        $("#UserFilterButton").prop("disabled", true);
    }else{
        if(input.hasClass("is-invalid")){
            input.removeClass("is-invalid");
        };
        $("#UserFilterButton").prop("disabled", false);
    }
})

$("#UserFilterButton").on('click', (event) => {
    let filter = $("#filter").val();
    window.location.assign('admin/users/' + filter);
})
$("#markerFilterButton").on('click', (event) => {
    let filter = $("#filter").val();
    window.location.assign('admin/markers/' + filter);
})
$("#place_button").on('click', (event) => {
    let value =  $("#place").val();
    value = encodeURIComponent(value);
    $.getJSON(`https://geocode.maps.co/search?q=${value}&api_key=663371914590f062701345usb7a1683`, (data) => {
        $("#lat").val(data[0].lat).prop("readonly", true);
        $("#lon").val(data[0].lon).prop("readonly", true);
    });
})