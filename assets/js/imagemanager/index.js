//"use strict";
let segment = "pickup";

const checkIsImage = (file) => {
    var extension = file.substr((file.lastIndexOf('.') + 1));
    switch(extension) {
        case 'jpg': case 'jpeg': case 'png': case 'gif': case 'webp': return true;
        default: return false;
    }
}
const formatBytes = (bytes, decimal = 2) => {
    if(!+bytes) return '0 b';

    const k = 1024;
    const dm = decimal < 0 ? 0 : decimal;
    const sizes = ['b', 'Kb', 'Mb'];

    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`
}
const getFilesInFolder = () => {
    return new Promise((resolve, reject) => {
        var fileNames = new Array();
        var path = 'Rest/getFolder';
        $.ajax({
            url: path,
            success: function(data){
                var items = JSON.parse(data);
				items.forEach((item) => {
					fileNames.push('./assets/uploads/' + item);
				});
				resolve(fileNames);
            }
        });
    });
}
const getFileName = (item, auto = false) => {
    var name = item.split('/')[item.split('/').length - 1].split('.')[0];
    return name;
}
const getFileSize = (item) => {
    return new Promise((resolve, reject) => {
        var xhr = new XMLHttpRequest();
        xhr.open('HEAD', item, true);
        xhr.onreadystatechange = () => {
            if(xhr.readyState === 4){
                if(xhr.status === 200){
                    resolve(formatBytes(xhr.getResponseHeader('Content-Length')));
                };
            };
        };
        xhr.send(null);
    })
}
const loadImages = (wipe = false) => {
    getFilesInFolder().then((data) => {
        data.forEach((item) => {
            var thumb = '<div class="col-md-2 image-manager-image-button custom-menu" onClick="selectImage(\'' + item + '\');">' + 
            '<img src="' + item + '" id="' + getFileName(item) + '" class="image-manager-thumb-image" onClick=""/><br/>' + 
            '<center>' + getFileName(item) + '</center>' +
            '</div>';
            $("#image-manager-frame").append(thumb);
        });
    })
}
const wipeFrame = () => {
    $("#image-manager-frame").html("");
}
const selectImage = (item) => {
    var name = getFileName(item);
    $(".image-manager-thumb-image").removeClass("image-selected");
    $("#" + name).addClass("image-selected");
    $("#image-name").html(name);
    $("#selected-image-path").val(item);
    getFileSize(item).then((size) => { $("#image-size").html(size); });
}

const progressHandling = (event) => { /*DO NOTHING*/ }

$(document).ready(() => {
    const modal = document.getElementById("image-manager-modal");
    $("#image-name").html("");
    $("#body").bind("ajaxSend", (elm, xhr, s) => {
        if(s.type == "POST"){
            xhr.setRequestHeader('X-CSRF-Token', csrf.token);
        }
    })
    
    modal.addEventListener('shown.bs.modal', (event) => {    
        wipeFrame();    
        loadImages();
    });
    const closeButton = document.getElementById("pick-image");
    closeButton.addEventListener('click', (event) => {
        const image = $("#selected-image-path").val();
        $(".image-manager-target").val(image);
        $("#image-name").html("");
        $("#image-size").html("");
        $("#modal-close-button").click();
    })
    const segmentButton = document.getElementById("image-upload-segment-button");
    segmentButton.addEventListener('click', (event) => {
        if(segment === "pickup"){
            $("#image-manager-controller").html(`
                <b>Új kép feltöltése:</b><br/>
                <form method="POST" action="Rest/uploadFile" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="image-file" class="form-label">Default file input example</label>
                        <input class="form-control" type="file" id="image-file">
                    </div>
                    <button type="button" id="uploadImage" class="btn btn-success">Feltöltés</button>
                </form>
                <div id="progressFrame"></div>
            `);
            const uploadImage = document.getElementById("uploadImage");
            uploadImage.addEventListener('click', async (event) => {
                event.preventDefault();
                if($("#image-file").val() === ""){
                    alert("Ki kell választanod egy képet!");
                }else{
                    let photo = document.getElementById("image-file").files[0];
                    var formData = new FormData();
                    formData.append("file", photo);
                    $.ajax({
                        type: "POST",
                        url: "rest/uploadFile",
                        xhr: () => {
                            var xhr = $.ajaxSettings.xhr();
                            if(xhr.upload){
                                xhr.upload.addEventListener('progress', progressHandling, false);
                            };
                            return xhr;
                        },
                        success: (data) => {
                            console.log(data);
                            data = $.parseJSON(data)
                            console.log(data);
                            if(data.success === true){
                                $("#image-upload-segment-button").click();
                                selectImage('./assets/uploads/' + data.data);
                            };
                        },
                        error: (error) => {
                            console.log("ERROR:", error);
                        },
                        async: true,
                        data: formData,
                        cache: false,
                        contentType: false,
                        processData: false,
                        timeout: 60000
                    });
                };
            })
            $("#image-upload-segment-button").html("Kép kiválasztása");
            segment = "upload";
        }else if(segment === "upload"){
            $("#image-manager-controller").html(`<div class="row"id="image-manager-frame"></div>`);
            $("#image-upload-segment-button").html("Új kép feltöltése");
            segment = "pickup";
            loadImages();
        }
    });

    $(document).on("contextmenu", ".custom-menu", (e) => {
        e.preventDefault();
        var contextMenu = $("<ul class='list-group'>").addClass("context-menu").css({
            position: "absolute",
            top: e.pageY + "px",
            left: e.pageX + "px",
            "z-index": "1060"
        }).appendTo("body");       
        $("<a href='javascript:;' class='list-group-item'>").html('<i class="fa-solid fa-x"></i> Törlés').appendTo(contextMenu).on("click", function(){
            $.ajax({
                type: "POST",
                url: "rest/removeFile",
                data: {path: $(e.target).attr("src")},
                success: (data) => {
                    data = $.parseJSON(data);
                    if(data.success){
                        loadImages(true);
                    }
                }
            })
        });
        $(document).on("click", () => contextMenu.remove() );
    });
});