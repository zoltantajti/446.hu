const domain = "446-hu";var scripts = document.getElementsByTagName('script');var src = scripts[scripts.length - 1].src;var token = src.match(/token=[0-9]+/);
$(document).ready(function(data){
    var scrollspy = document.querySelector('.scrollspy-messages');if(scrollspy){scrollspy.scrollTop = scrollspy.scrollHeight;}
})

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
const openAttachmentModal = (file) => {
    
    const modal = new bootstrap.Modal('#attachmentModal');
    $.get(file, (ctx) => {
        const ext = determineFileExt(ctx);
        $("#attModalBody").html('<img src="data:image/'+ext+';base64,' + ctx + '" class="img-responsive" style="width:100%"/>');
    });
    modal.show();
}

function determineFileExt(b64){
    let binary = atob(b64);
    let magicNumber = binary.charCodeAt(0).toString(16) + binary.charCodeAt(1).toString(16);
    if (magicNumber === "8950") {
        return "png";
    } else if (magicNumber === "ffd8") {
        return "jpeg";
    } else if (magicNumber === "2550") {
        return "pdf";
    } else if (magicNumber === "d0cf") {
        var subMagicNumber = binaryData.charCodeAt(2).toString(16) + binaryData.charCodeAt(3).toString(16);
        if (subMagicNumber === "cf11" || subMagicNumber === "0908") {
            return "doc";
        } else {
            return "xls";
        }
    } else if (magicNumber === "504b") {
        var subMagicNumber = binaryData.charCodeAt(2).toString(16) + binaryData.charCodeAt(3).toString(16);
        if (subMagicNumber === "0304") {
            return "docx";
        } else {
            return "xlsx";
        }
    };
}

function refreshMails(){
    $(".loader").css('display','flex').show();
    $.get('Rest/readImap').then((success) => {document.location.href = 'admin/conversations/list'; });
}