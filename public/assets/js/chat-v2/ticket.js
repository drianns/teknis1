$("#chat_ticket_category").on('change', function() {
    var value = $(this).val()
    var uri = listUrls.ticketGetByCategory ?? '';
    uri = uri.replace(':variable', value);
    $.getJSON(uri, function(data) {
        $('#chat_ticket_category_type').empty();
        $('#chat_ticket_category_type').append('<option hidden>Category Type</option>');
        $.each(data, function(key, val) {
            $('select[name="chat_ticket_category_type"]').append('<option value="' + val
                .id + '">' + val.name + '</option>');
        });
    });
})
$("#chat_ticket_category_type").on('change', function() {
    var value = $(this).val()
    var uri = listUrls.ticketGetByCategoryType ?? '';
    uri = uri.replace(':variable', value);
    $.getJSON(uri, function(data) {
        $('#chat_ticket_category_detail').empty();
        $('#chat_ticket_category_detail').append('<option hidden>Category Detail</option>');
        $.each(data, function(key, val) {
            $('select[name="chat_ticket_category_detail"]').append('<option value="' + val
                .id + '">' + val.name + '</option>');
        });
    });
})
$("#chat_ticket_category_detail").on('change', function() {
    var value = $(this).val()
    var uri = listUrls.ticketGetByCategoryDetail ?? '';
    uri = uri.replace(':variable', value);
    $.getJSON(uri, function(data) {
        $('#chat_ticket_category_problem').empty();
        $('#chat_ticket_category_problem').append('<option hidden>Category Problem</option>');
        $.each(data, function(key, val) {
            $('select[name="chat_ticket_category_problem"]').append('<option value="' + val
                .id + '">' + val.name + '</option>');
        });
    });
})
