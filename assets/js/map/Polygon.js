class Polygon {
    constructor(points, color = "red", description = "") {
        this.points = points;
        this.color = color;
        this.description = description
    }

    create = (_layer) => {
        this.zone = L.polygon(this.points, {color: this.color});
        this.zone.bindPopup(this.description);
        this.zone.on('contextmenu', (e) => { });
        _layer.addLayer(this.zone);
    }
}
export { Polygon }