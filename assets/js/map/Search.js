class Search {
    
    init(map){
        var searchControl = new L.esri.Controls.Geosearch().addTo(map);
        var results = new L.layerGroup().addTo(map);

        searchControl.on('results', (data) => {
            results.clearLayers();
            for (var i = data.results.length - 1; i >= 0; i--) {
                results.addLayer(L.marker(data.results[i].latlng));
            }
        });
    }
};

export { Search };