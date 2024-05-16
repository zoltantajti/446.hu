class Attrs {
    constructor(){
        var script = document.querySelector('script[type="module"]');
        var src = script.getAttribute('src');
        var queryString = src.split('?')[1];
        this.params = new URLSearchParams(queryString);
    }

    getAttributes = function(key){
        return this.params.get(key);
    }
    
}

export { Attrs }; 