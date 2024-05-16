class Propagation {
    constructor(){
        this.power = 1;
        this.freq = 446.00625;
        this.distance = 5;
        
    }
    getWeather = async function(postCode = 3100, country = "hu")
    {
        $.getJSON('https://api.openweathermap.org/data/2.5/weather?zip=3065,hu&mode=json&units=metric&lang=hu&appid=89fcf7a64197f34ff0c3f596521057db')
        .then((result) =>{
            return new Promise((resolve,reject) => {
                resolve(result);
            })
        });
    }
    calc = async function()
    {
        this.getWeather().then((weather) =>{
            console.log(weather);
            const terjedesi_veszteseg_dB = 20 * Math.log10(this.distance);
            const szel_atenuacio_dB = 0.01 * weather.wind.speed * Math.pow(Math.cos((weather.wind.deg - 180) * Math.PI / 180) + 1, 2);
            const homerseklet_atenuacio_dB = 0.1 * (weather.temp - 25);
            const legnyomas_atenuacio_dB = 0.01 * (weather.pres - 1013);
            const paratartalom_atenuacio_dB = 0.1 * (weather.hum - 50);
            const felhozet_atenuacio_dB = 0.5 * weather.clouds.all;
            //const csapadek_atenuacio_dB = 5 * this.rain;

            const atenuacio_dB = terjedesi_veszteseg_dB + szel_atenuacio_dB + homerseklet_atenuacio_dB + legnyomas_atenuacio_dB + paratartalom_atenuacio_dB + felhozet_atenuacio_dB + csapadek_atenuacio_dB;
            const atenuacio_W = Math.pow(10, (-atenuacio_dB / 10));
            const atvett_teljesitmeny_W = this.power / atenuacio_W;

            console.log(atvett_teljesitmeny_W.toFixed(2) + " dBm");
        });
        
        




        
    }
}

export { Propagation };