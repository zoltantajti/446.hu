class Polyline {
    constructor(points, qso) { 
        this.points = points;
        this.qso = qso;
    }

    create = (_layer) => {
        this.line = L.polyline(this.points, {color: this.color});
        this.line.on('click', (e) => { 
            $("#off-title").html(`${this.qso.my_callsign} <i class="fa fa-fw fa-arrow-right"></i> ${this.qso.rem_callsign}`);
            $("#off-body").html(`
            Dátum: <b>${this.qso.date} ${this.qso.time}</b><br/>
            Frekvencia: <b>${this.qso.freq}, CT: ${this.qso.ctcs}, DCS: ${this.qso.dcs}</b><br/>
            Típus: <b>${this.qso.suffix}</b><br/>
            Mód: <b>${this.qso.mode} ${this.qso.parrot_name}</b><br/>
            Távolság: <b>${this.qso.distance}</b><br/>

            <hr/>
            ${this.qso.comment}
            `)
            $("#offCanvas").addClass("show");
        });
        _layer.addLayer(this.line);
    }

    setColor = (color) => {
        this.color = color;
    }
}

export { Polyline };