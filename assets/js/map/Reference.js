class Reference  {
    constructor() {
        const location = window.location.href;
        if(location.includes("public")){
            this.ref = "public";
        }else if(location.includes("internal")){
            this.ref = "internal";
        }
    }
}

export { Reference };