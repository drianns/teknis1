class Instance {
    constructor() {
        this.lib = {
            http: new Http(),
            ticket: new Ticket(),
        };
    }
}

window.INSTANCE = new Instance();