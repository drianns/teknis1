
function campaign_setting(id) {
    $("#setting-campaign").html("");
    $.get("/bot-campaign/schedule/" + id, (result) => {
        $("#setting-campaign").html(result);
    });
}
