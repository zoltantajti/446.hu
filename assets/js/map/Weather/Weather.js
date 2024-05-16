class Weather {
    constructor() {
        this.layers = {};

        this.layers['temp'] = L.tileLayer('https://tile.openweathermap.org/map/temp_new/{z}/{x}/{y}.png?appid=89fcf7a64197f34ff0c3f596521057db',{minZoom: 7,maxZoom: 15});
        this.layers['wind'] = L.tileLayer('https://tile.openweathermap.org/map/wind_new/{z}/{x}/{y}.png?appid=89fcf7a64197f34ff0c3f596521057db',{minZoom: 7,maxZoom: 15});
        this.layers['clouds'] = L.tileLayer('https://tile.openweathermap.org/map/clouds_new/{z}/{x}/{y}.png?appid=89fcf7a64197f34ff0c3f596521057db',{minZoom: 7,maxZoom: 15});
        this.layers['precip'] = L.tileLayer('https://tile.openweathermap.org/map/precipitation_new/{z}/{x}/{y}.png?appid=89fcf7a64197f34ff0c3f596521057db',{minZoom: 7,maxZoom: 15});
    }

    init = function(_layer) {
        Object.values(this.layers).forEach(layer => { _layer.addLayer(layer); });
    }

    getCities = function(_layer) {
        let cities = [1011,2400,3100,3500,4400,5600,4150,5000,6000,6700,7100,7400,8000,8200,8900,9000,9400];
        cities.forEach((city) => {
            $.getJSON(`https://api.openweathermap.org/data/2.5/weather?zip=${city},hu&mode=json&units=metric&lang=hu&appid=89fcf7a64197f34ff0c3f596521057db`, (result) => {
                let frame = L.divIcon({
                    className: 'wx-icon',
                    html: `
<img src="https://openweathermap.org/img/wn/${result.weather[0].icon}.png" style="width:25px;"/>
<b>${result.main.temp.toFixed(0)}°C</b><br/>
<i class="fa-solid fa-arrow-up-long" style="transform:rotate(${result.wind.deg}deg);"></i> 
${result.wind.speed} Km/ó
                    `,
                    iconSize: [100,50],
                    iconAnchor: [50,25]
                });
                let gust = (result.wind.gust) ? result.wind.gust + " Km/ó lökésekkel": "";
                let rain = ""; let snow = "";
                if(result.rain && result.rain["1h"]){
                    rain += result.rain["1h"] + "mm eső várható 1 órán belül<br/>";
                };
                if(result.rain && result.rain["3h"]){
                    rain += result.rain["3h"] + "mm eső várható 3 órán belül";
                };
                if(result.snow && result.snow["1h"]){
                    snow += result.snow["1h"] + "mm hó várható 1 órán belül<br/>";
                };
                if(result.snow && result.snow["3h"]){
                    snow += result.snow["3h"] + "mm hó várható 3 órán belül<br/>";
                };
                let popup = `
                    <center><b>${result.name}</b></center>
                    <center><b>${result.weather[0].description}</b></center>
                    <center><img src="https://openweathermap.org/img/wn/${result.weather[0].icon}@2x.png" /></center>
                    <hr/>
                    Hőmérséklet: <b>${result.main.temp}°C</b><br/>
                    Hőérzet: <b>${result.main.feels_like}°C</b><br/>
                    Páratartalom: <b>${result.main.humidity}%</b><br/>
                    Felhőzet: <b>${result.clouds.all}%</b><br/>
                    Szél: <b><i class="fa-solid fa-arrow-up-long" style="transform:rotate(${result.wind.deg}deg);"></i> ${result.wind.speed} Km/h 
                    ${gust}</b><br/>
                    <b>${rain}${snow}</b><br/>
                `;
                let marker = L.marker([result.coord.lat, result.coord.lon], {icon: frame});
                marker.bindPopup(popup);
                _layer.addLayer(marker);
            })
        });
    }
}

export { Weather };