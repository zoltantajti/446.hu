import { iconUrls } from "./Icon.js";
import { Toast } from "./Toast.js";
import { Rest } from "./Rest.js";

class ContextMenu {
    constructor() { 
        this.menu = L.DomUtil.create('div', 'custom-context-menu', document.body); 
        this.toast = new Toast();
    }
    initFromMap = (base) => { this.base = base; }

    show = (e, markerID = null) => {
        if(e.target.options.type == "parrot" && markerID != null){
            this.parrotMenu(e, markerID);
        }else if(e.target.options.type == "map" && markerID === null){
            this.mapMenu(e);
        }
        this.menu.style.left = e.containerPoint.x + 'px';
        this.menu.style.top = e.containerPoint.y + 'px';
        this.menu.style.display = 'block';
        e.originalEvent.preventDefault();
    }
    hide = function(){
        this.menu.style.display = 'none';
    }

    findMarkerById = (markerID) => {
        const allMarkers = [...this.base.mobileRadioLayer.getLayers(), ...this.base.parrotLayer.getLayers()];
        return allMarkers.find(marker => marker._leaflet_id === markerID);
    }
    modifyParrotState = function(state, id, markerID){
        const marker = this.findMarkerById(markerID);
        let _state = (state === 1) ? "active" : "inactive";
        $.post("Rest/setState", {id: id, state: state}, (data) => {
            marker.setIcon(L.icon({
                iconUrl: iconUrls["parrot_" + _state],
                iconSize: [32,32]
            }));
            this.hide();
        })
    }

    parrotMenu = (e, markerID) => {
        this.menu.id = 'customContextMenu';
        this.menu.innerHTML = `
            <ul class="list-group list-group-flush">
                <a href="javascript:;" id="parrotWorks" class="list-group-item list-group-item-success"><i class="fa fa-fw fa-check"></i> Működik</a>
                <a href="javascript:;" id="parrotNotWorks" class="list-group-item list-group-item-danger"><i class="fa fa-fw fa-times"></i> Nem működik</a>
                <a href="javascript:;" id="parrotEdit" class="list-group-item list-group-item-dark"><i class="fa fa-fw fa-pencil"></i> Szerkesztés</a>
            </ul>
        `;

        let parrotWorks = document.getElementById("parrotWorks");
        parrotWorks.addEventListener('click', (event) => { 
            const id = e.target.options.dbID;
            this.modifyParrotState(1, id, markerID);
            this.toast.show("Köszi :)", "Köszönjük a visszajelzésed!");
        });
        let parrotNotWorks = document.getElementById("parrotNotWorks");
        parrotNotWorks.addEventListener('click', (event) => { 
            const id = e.target.options.dbID;
            this.modifyParrotState(0, id, markerID);
            this.toast.show("Köszi :)", "Köszönjük a visszajelzésed!");
        });

        let parrotEdit = document.getElementById('parrotEdit');
        parrotEdit.addEventListener('click', (event) => {
            const id = e.target.options.dbID;
            let rest = new Rest();
            rest.getMarkerById(id).then((marker) => {
                this.hide();
                $("#map-modal-title").html('Marker módosítása');
                $("#lat").val(marker.lat).attr("readonly","true");
                $("#lon").val(marker.lon).attr("readonly","true");
                $("#type").val(marker.type);
                $("#title").val(marker.title);
                tinymce.get('description').setContent(marker.description);
                $("#addNewMarker").show();
                btnSaveMarker.addEventListener('click', (event) => {
                    let rest = new Rest();
                    rest.updateMarker(id);
                });
                let btnClearMarker = document.getElementById("btnClearMarker");
                btnClearMarker.addEventListener('click', (event) => {
                    $("#lat").val("").attr("readonly","true");
                    $("#lon").val("").attr("readonly","true");
                    $("#type").val("");
                    $("#title").val("");
                    tinymce.get('description').setContent("");
                    $("#addNewMarker").hide();
                });
            });
        })
    }

    mapMenu = (e) => {
        this.menu.id = 'customContextMenu';
        this.menu.innerHTML = `
            <ul class="list-group list-group-flush">
                <a href="javascript:;" id="btnAddNewMarker" class="list-group-item list-group-item-success">Új marker</a>
            </ul>
        `;
        let addNewMarker = document.getElementById("btnAddNewMarker");
        addNewMarker.addEventListener('click', (event) => { 
            var latlng = e.latlng;
            let marker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(this.base.map);
            this.hide();
            $("#map-modal-title").html('Új marker hozzáadása');
            $("#lat").val(latlng.lat).attr("readonly","true");
            $("#lon").val(latlng.lng).attr("readonly","true");
            $("#addNewMarker").show();
            let btnSaveMarker = document.getElementById("btnSaveMarker");
            btnSaveMarker.addEventListener('click', (event) => {
                let rest = new Rest();
                rest.saveMarker();
            });
            let btnClearMarker = document.getElementById("btnClearMarker");
            btnClearMarker.addEventListener('click', (event) => {
                marker.removeFrom(this.base.map);
                $("#addNewMarker").hide();
            });            
        });
    };    
};
export { ContextMenu }