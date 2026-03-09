
function blast_setting(id) {
    $("#setting-blast").html("");
    $.get("/blast/schedule/" + id, (result) => {
        $("#setting-blast").html(result);
    });
}
