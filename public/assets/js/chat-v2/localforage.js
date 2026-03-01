
console.log('localforage is: ', localforage);
class IndexDB {
    db;
    table = [];
    constructor(dbname) {
        this.db = localforage;
        this.db.config({
            driver: localforage.INDEXEDDB,
            name: dbname,
        });
    }

    async get(key) {
        try {
            let table = await this.db.getItem(key);
            this.table[key] = table
            return table;
        } catch (error) {
            console.log(error);
        }

    }

    async objectWhere(source, key, value) {
        return this.table[source].filter((item) => item[key] === value);
    }

    async objectIndex(source, item) {
        return this.table[source].findIndex((i) => i === item);
    }

    async set(key, value) {
        return await this.db.setItem(key, value);
    }

    async remove(key) {
        return await this.db.removeItem(key);
    }

    async clear() {
        return await this.db.clear();
    }

    async keys() {
        return await this.db.keys();
    }

    async length() {
        return await this.db.length();
    }

    async iterate(iterator) {
        return await this.db.iterate(iterator);
    }

    async close() {
        return await this.db.close();
    }
}

var IDDBAGENT = (new IndexDB(currentAgent.id));

async function getLastSync() {
    return await IDDBAGENT.get("last_sync").then(async function(objects) {
        if(objects == null){
            objects = {}
        }

        return objects;
    })
}

async function updateLastSync(key, value) {
    await IDDBAGENT.get("last_sync").then(async function(objects) {
        if(objects == null){
            objects = {}
        }

        objects[key] = value

        await IDDBAGENT.set("last_sync", objects).then(function(value) {
            // Do other things once the value has been saved.
            console.log(value);
        }).catch(function(err) {
            // This code runs if there were any errors
            console.log(err);
        });
    })
}


async function updateLastSyncChatBody(key, value) {
    IDB_Headers = await (new IndexDB(currentAgent.id)).get("chat_headers");
    if (IDB_Headers == null) IDB_Headers = [];
    console.log("KEY", key)
    let index = await IDB_Headers.findIndex((item) => item.id == key)
    if(index < 0) return {'current': 0, 'new': value}

    if(!Object.keys(IDB_Headers[index]).includes('last_sync')){
        IDB_Headers[index].last_sync = 0
    }

    let sync_now = IDB_Headers[index].last_sync ?? 0;

    if (value > sync_now) {
        IDB_Headers[index].last_sync = value
        await (new IndexDB(currentAgent.id)).set("chat_headers", IDB_Headers)
    }

    return {'current': sync_now, 'new': value}
}

async function clearDB() {
    await IDDBAGENT.clear()
    window.location.reload();
}
