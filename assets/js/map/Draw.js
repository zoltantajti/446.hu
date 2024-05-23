import { MCEditor } from "./MCEditor.js";
import { Rest } from "./Rest.js";
import { Toast } from "./Toast.js"

class Draw {
    constructor() { 
        this.drawnItems = new L.featureGroup();
        this.mode = 'drawmode:view';
        this.mce = new MCEditor();
        this.rest = new Rest();
    }

    init = function(map){ 
        this.map = map;
        this.map.addLayer(this.drawnItems);
        this.currentShape = null;
        this.map.on('click', (event) => this.onMapClick(event));

        this.drawPolygonButton = this.createButton('<i class="fa fa-fw fa-save"></i>', () => this.save());
        this.cancelDrawingButton = this.createButton('<i class="fa fa-fw fa-times"></i>', () => this.disableDraw());
        this.controlDiv = L.DomUtil.create('div','drawing-controls');
        this.controlDiv.appendChild(this.drawPolygonButton);
        this.controlDiv.appendChild(this.cancelDrawingButton);
        this.controlContainer = L.DomUtil.get(map.getContainer()).querySelector('.leaflet-control-zoom');
        
        

        //this.controlContainer.appendChild(this.controlDiv);
        
        
        //L.DomUtil.get(this.map.getContainer().querySelector('.leaflet-control-container')).appendChild(this.controlDiv);
    }

    createButton = function(text,onClick){
        const button = L.DomUtil.create('button','drawing-button');
        button.innerHTML = text;
        L.DomEvent.on(button, 'click', onClick);
        return button;
    }

    enableDraw = function() {
        this.controlContainer.appendChild(this.controlDiv);
        this.view = 'drawmode:draw';
        this.startDrawing('polygon');
    }

    disableDraw = function() {
        this.drawnItems.removeLayer(this.currentLayer);
        this.currentLayer = null;
        this.map.on('click', (event) => {});
        try{ this.controlContainer.removeChild(this.controlDiv); }catch{ };
        this.view = 'drawmode:view';
    };

    startDrawing = function(shapeType){
        this.currentShape = shapeType;
        this.currentLayer = null;
        this.map.off('click');
        this.map.on('click', (event) => this.onMapClick(event));
    }

    onMapClick = function(event) {
        if(this.view != 'drawmode:draw') return;
        if(!this.currentShape) return;
        const latlng = event.latlng;
        switch(this.currentShape){
            case 'polygon':
                if(!this.currentLayer){
                    this.currentLayer = L.polygon([latlng], {color: 'green'}).addTo(this.map);
                }else{
                    this.currentLayer.addLatLng(latlng);
                };
                break;
        };
        this.drawnItems.addLayer(this.currentLayer);
    }
    save = function() {
        this.view = 'drawmode:view';
        this.mce.init('restrictionDescription','link','bold italic underline numlist bullist link');
        console.log(this.currentLayer._latlngs[0]);
        $("#addRestrictedArea").show();
        $("#closeRestrictedArea").on('click', (event) => { this.disableDraw(); $("#addRestrictedArea").hide();});
        $("#cancelRestrictedArea").on('click', (event) => { this.disableDraw(); $("#addRestrictedArea").hide();});
        $("#saveRestrictedArea").on('click', (event) => {
            let data = {
                'coords': JSON.stringify(this.currentLayer._latlngs[0]),
                'color': $("#restColor").val(),
                'freqs': $("#restFreq").val(),
                'description': tinyMCE.get('restrictionDescription').getContent()
            };
            this.rest.saveRestZone(data).then((result) => {
                this.drawnItems.removeLayer(this.currentLayer);
                this.currentLayer = null;
                this.map.on('click', (event) => {});
                let toast = new Toast();
                toast.show("Köszi :)", "A zónát rögzítettük!");
                setTimeout((event) => {window.location.reload();}, 3000);
            });
        })
    }
}

export { Draw };