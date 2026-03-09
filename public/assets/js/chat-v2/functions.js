const scrollSmoothlyToBottom = async (id) => {
    const element = $(`${id}`);
    element.animate(
        {
            scrollTop: element.prop("scrollHeight"),
        },
        500
    );
    //console.log(element.prop("scrollHeight"));
};
const scrollSmoothlyToTop = async (id) => {
    const element = $(`${id}`);
    element.animate({
        scrollTop: element.offset().top,
    });
    $("html, body").animate({
        scrollTop: $("html, body").offset().top,
    });
    //console.log(element.offset().top);
};

TimeAgo.addDefaultLocale({
    locale: "id",
    now: {
        now: {
            current: "now",
            future: "in a moment",
            past: "just now",
        },
    },
    long: {
        year: {
            past: {
                one: "{0} year ago",
                other: "{0} years ago",
            },
            future: {
                one: "in {0} year",
                other: "in {0} years",
            },
        },
        month: {
            past: {
                one: "{0} month ago",
                other: "{0} months ago",
            },
            future: {
                one: "in {0} month",
                other: "in {0} months",
            },
        },
        day: {
            past: {
                one: "{0} day ago",
                other: "{0} days ago",
            },
            future: {
                one: "in {0} day",
                other: "in {0} days",
            },
        },
        hour: {
            past: {
                one: "{0} hour ago",
                other: "{0} hours ago",
            },
            future: {
                one: "in {0} hour",
                other: "in {0} hours",
            },
        },
        minute: {
            past: {
                one: "{0} minute ago",
                other: "{0} minutes ago",
            },
            future: {
                one: "in {0} minute",
                other: "in {0} minutes",
            },
        },
        second: {
            past: {
                one: "{0} second ago",
                other: "{0} seconds ago",
            },
            future: {
                one: "in {0} second",
                other: "in {0} seconds",
            },
        },
    },
});

function addNewCardAdditionalInfo(key = "", value = "") {
    $("#placeNewAdditional").append(`
                    <div>
                        <div class="mb-3">
                            <button type="button" class="btn btn-sm btn-danger float-end mb-2" onclick="removeThisCard(this)">
                                <i class="uil uil-trash-alt"></i>
                            </button>
                        </div>
                        <div>
                            <div class="form-group">
                                <label>Key</label>
                                <input type="text" name="optional_key[]" class="form-control" value="${key}">
                            </div>
                            <div class="form-group">
                                <label>Value</label>
                                <input type="text" name="optional_value[]" class="form-control" value="${value}">
                            </div>
                        </div>
                        <hr>
                    </div>`);
}

function removeThisCard(el) {
    $(el).parent().parent().remove();
}

function load_histories(user_id) {
    //console.log(user_id);
    $.get(
        listUrls.userHistory.replaceAll(":channel_user_id", user_id ?? ""),
        function (data) {
            $("#placeHistories").html(data);
        }
    );
}

function load_blast_histories(user_id) {
    //console.log(user_id);
    $.get(
        listUrls.blastHistory.replaceAll(":channel_user_id", user_id ?? ""),
        function (data) {
            $("#placeBlastHistories").html(data);
        }
    );
}

var IDB_ChannelUsers = [];
async function load_users() {
    IDB_ChannelUsers = await new IndexDB(currentAgent.id).get("chat_users");
    if (IDB_ChannelUsers == null) IDB_ChannelUsers = [];

    $.get(listUrls.syncAllUsers, async function (data) {
        data.forEach((users) => {
            // fine on idb channel users
            if (!IDB_ChannelUsers.find((x) => x.id == users.id)) {
                IDB_ChannelUsers.push(users);
            }

            $("#left-users ul.chat-list#placeAllUsers")
                .append(`<li class="read">
            <a href="javascript:selectUser(${
                users.id
            })" class="user-list-a" id="user-list-${users.id}">
                <div class="d-flex align-items-start">
                    <div class="flex-shrink-0 user-img online align-self-center me-3">
                        <div class="avatar-sm align-self-center">
                            <img class="avatar-title rounded-circle bg-soft-primary"
                                src="${
                                    users.photo ??
                                    "https://cdn-icons-png.flaticon.com/512/9187/9187604.png"
                                }">
                        </div>
                        <span class="user-status"></span>
                    </div>

                    <div class="flex-grow-1 overflow-hidden">
                        <h5 class="text-truncate font-size-14 mb-1">${
                            users.name ?? ""
                        }</h5>
                        <p class="text-truncate mb-0">Channel: ${
                            users.channel_name ?? ""
                        }</p>
                    </div>
                </div>
            </a>
        </li>`);
        });
        await new IndexDB(currentAgent.id).set("chat_users", IDB_ChannelUsers);

        $("#left-users #json_users").val(JSON.stringify(IDB_ChannelUsers));
    });
}

function openUser(response) {
    response = JSON.parse(response);
    $(".user-info").html(generate_user_info(response));
    load_histories(response.id);
    load_blast_histories(response.id);
}

function selectUser(id) {
    response = IDB_ChannelUsers.find((x) => x.id == id);
    $(".user-info").html(generate_user_info(response));
    load_histories(response.id);
    load_blast_histories(response.id); //
}

function addZero(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}

function createTicket(data) {
    Swal.fire({
        title: "Do you want to create ticket?",
        showDenyButton: true,
        // showCancelButton: true,
        confirmButtonText: "Yes",
        denyButtonText: `No`,
    }).then((result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            let src = String(listUrls.createTicketUrl)
                .replaceAll("{channel_code}", data.channel_code)
                .replaceAll("{chat_id}", data.chat_id)
                .replaceAll("{account_id}", data.account_id)
                .replaceAll("{agent_username}", data.agent_username)
                .replaceAll("&amp;", "&");
            //console.log("feature.extra.ticket.url", src);
            // let src = `https://cloud.uidesk.id/kanmo/auth_integration.aspx?id=null&channel=${data.channel_code}&n=1&threadid=${data.chat_id}&sessionid=${data.chat_id}&account=${data.account_id}&token=${data.agent_username}`;
            // let src = `https://cloud.uidesk.id/kanmo/auth_integration.aspx?id=null&channel={channel_code}&n=1&threadid={chat_id}&sessionid={chat_id}&account={account_id}&token={agent_username}`;
            // $('#createTicketModal iframe').attr("src", src);
            window.open(src, "_blank");
            // $('#createTicketModal').modal('show');
            // Swal.fire('Saved!', '', 'success');
        } else if (result.isDenied) {
            Swal.fire("Changes are not saved", "", "info");
        }
    });
}

async function updateAuxDatakelola(token_agent, value) {
    await fetch("https://datakelola.com/api/agent/aux", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({
            token_agent: token_agent,
            aux: value,
        }),
    })
        .then((res) => res.json())
        .then((data) => {
            //console.log(data);

            alert("updateAuxDatakelola says: " + data.message);
        });
}


function isAgent() {
    let is_agent = false;

    if(currentAgent != null && currentAgent.user_agent != null) {
        is_agent = true;
    }

    return is_agent;
}

function sendEventToParent(data={}) {
    if(isAgent()) {
        data.username = currentAgent.user_agent.username;
        window.parent.postMessage(data, 'https://iframe-datakelola.test/');
        window.parent.postMessage(data, 'https://iframe-datakelola.test/folder');
        window.parent.postMessage(data, 'https://cloud.uidesk.id/AHUOMNI');
    }

    return;
}
