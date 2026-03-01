
/* MODULE PENCARIAN DATA */
function cariUser(el) {
    let val = $(el).val();
    val = val.toUpperCase();
    let USERS = IDB_ChannelUsers ?? [];

    let filter = USERS.filter((user) => {
        return (
            String(user.id).toUpperCase().search(val) != -1 ||
            user.created_at.toUpperCase().search(val) != -1 ||
            user.channel_name.toUpperCase().search(val) != -1 ||
            user.name.toUpperCase().search(val) != -1
        );
    });

    result_filter = filter;

    let html = "";
    filter.forEach((search) => {
        html += `<li class="read">
                                <a href="javascript:selectUser(${
                                    search.id
                                })" class="user-list-a" id="user-list-${
            search.id
        }">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0 user-img online align-self-center me-3">
                                            <div class="avatar-sm align-self-center">
                                                <img class="avatar-title rounded-circle bg-soft-primary"
                                                    src="${
                                                        search.photo ??
                                                        "https://cdn-icons-png.flaticon.com/512/9187/9187604.png"
                                                    }">
                                            </div>
                                            <span class="user-status"></span>
                                        </div>

                                        <div class="flex-grow-1 overflow-hidden">
                                            <h5 class="text-truncate font-size-14 mb-1">${
                                                search.name ?? ""
                                            }</h5>
                                            <p class="text-truncate mb-0">Channel: ${
                                                search.channel_name ?? ""
                                            }</p>
                                        </div>
                                    </div>
                                </a>
                            </li>`;
    });

    $("#left-users ul.chat-list#placeAllUsers").html(html);
}

function onSearchHistory(el) {
    let val = $(el).val().toUpperCase();

    let HISTORIES = JSON.parse($("#json_histories").val());
    let filter = HISTORIES.filter((history) => {
        return (
            String(history.id).toUpperCase().search(val) != -1 ||
            history.created_at.toUpperCase().search(val) != -1 ||
            history.channel_name.toUpperCase().search(val) != -1 ||
            history.channel_user_name.toUpperCase().search(val) != -1
        );
    });

    let html = "";
    filter.forEach((search) => {
        html += `<li><a href="javascript:openChatHeader('${search.id}')"><h5 class="font-size-14 mb-0">${search.created_at}</h5></a></li>`;
    });

    $("#placeHistories ul.chat-list").html(html);
}

function onSearchHeader(el) {
    let val = $(el).val().toUpperCase();

    let HEADERS = JSON.parse($("#json_headers").val());
    let filter = HEADERS.data.filter((header) => {
        return (
            String(header.id).toUpperCase().search(val) != -1 ||
            header.created_at.toUpperCase().search(val) != -1 ||
            (header.channel_user?.name ?? header.channel_page?.name ?? "")
                .toUpperCase()
                .search(val) != -1
        );
    });

    fetchHeaders({
        data: filter,
    });
}
