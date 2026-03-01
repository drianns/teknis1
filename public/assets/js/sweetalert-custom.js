function confirmDelete(url, _options={}) {
    let options = {title:"Are you sure?", text:"You won't be able to revert this!", reload: false}
    Object.assign(options, _options)

    Swal.fire({
        title: options.title,
        text:  options.text,
        icon: 'warning',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        showCancelButton: true,
    }).then((result) => {
        if (result.isConfirmed) {
            console.log(url)
            $.post(url, {_method: "DELETE"}, (result) => {
                console.log(result)
                if(result.status) {
                    Swal.fire(
                        'Deleted!',
                        result.message,
                        'success'
                    ).then(() => {
                        if(options.reload) return window.location.reload();
                    })
                } else {
                    Swal.fire(
                        'Error!',
                        result.message,
                        'error'
                    )
                }
            })
        } else {
            Swal.fire(
                'Warning!',
                "Process has been aborted",
                'warning'
            )
        }
    })
}

function confirmDelete2(url, _options={}) {
    let options = {title:"Are you sure?", text:"You won't be able to revert this!", reload: false}
    Object.assign(options, _options)

    Swal.fire({
        title: options.title,
        text:  options.text,
        icon: 'warning',
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        showCancelButton: true,
    }).then((result) => {
        if (result.isConfirmed) {
            console.log(url)
            $.ajax({
                url: url,
                type: 'DELETE',
                success: function(result) {
                    if(result.status) {
                        Swal.fire(
                            'Deleted!',
                            result.message,
                            'success'
                        ).then(() => {
                            if(options.reload) return window.location.reload();
                        })
                    } else {
                        Swal.fire(
                            'Error!',
                            result.message,
                            'error'
                        )
                    }
                }
            });
        } else {
            Swal.fire(
                'Warning!',
                "Process has been aborted",
                'warning'
            )
        }
    })
}