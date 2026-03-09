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
        return await fetch(url, {
            method: options.method ?? this.method,
            headers: options.headers ?? this.headers,
            body: JSON.stringify(data)
        }).then(res => res.json().catch(err => {
            if (err?.message) {
                err = err.message
            }
            Swal.fire({
                toast: true,
                position: "top-end",
                timer: 3500,
                timerProgressBar: true,
                icon: "error",
                title: "Error: "+ err,
                showConfirmButton: false,
                showCancelButton: false,
                showDenyButton: false,
            })
            return false
        }));
    }

    async sendRaw(url, data=[], options = {method: 'POST'}) {
        return await fetch(url, {
            method: options.method ?? this.method,
            headers: options.headers ?? this.headers,
            body: JSON.stringify(data)
        })
    }
}
