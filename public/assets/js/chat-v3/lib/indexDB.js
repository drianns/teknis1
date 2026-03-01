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
