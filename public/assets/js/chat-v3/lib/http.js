class Http {
    constructor() {
        this.method = 'POST';
        this.headers = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content"),
        };
    }

    async send(url, data=[], options = {method: 'POST'}) {
        let fetchOptions = {
            method: options.method ?? this.method,
            headers: options.headers ?? this.headers,
        };
        if ((options.method ?? this.method).toUpperCase() !== 'GET') {
            fetchOptions.body = JSON.stringify(data);
        }
        return await fetch(url, fetchOptions).then(res => res.json());
    }

    async sendFormData(url, data=[], options = {method: 'POST'}) {
        return await fetch(url, {
            method: options.method ?? this.method,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content"),
                'Accept': 'application/json',
            },
            body: data,
            contentType: false,
            processData: false,
        })
    }

    async sendRaw(url, data=[], options = {method: 'POST'}) {
        return await fetch(url, {
            method: options.method ?? this.method,
            headers: options.headers ?? this.headers,
            body: JSON.stringify(data)
        })
    }
}