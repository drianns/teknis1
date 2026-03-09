var current_chat_id;

var last_page_list_chat = 1;
var page_list_chat = 1;

var selected_header_id = null;

var response_chat_headers = [];

const url = new URL(window.location);
var urlParams = new URLSearchParams(url.search);

var lastSync = null;
var lastSyncHistory = null;
var IDB_Headers = [];
var IDB_Bodies = [];
let currDate = new Date();

const simpleBar = new SimpleBar(document.getElementById("chat-message-list"));
$(document).ready(async () => {
    simpleBar.getScrollElement().addEventListener("scroll", function () {
        //console.log("scrolling");
        /*console.log(
            "scrolling(1)",
            $(this).scrollTop(),
            $(this).innerHeight(),
            $(this)[0].scrollHeight - 50
        );*/
        if (
            $(this).scrollTop() + $(this).innerHeight() >=
            $(this)[0].scrollHeight - 10
        ) {
            /*console.log(
                "scrolling",
                page_list_chat,
                last_page_list_chat,
                selected_header_id
            );*/
            if (page_list_chat != last_page_list_chat) {
                page_list_chat++;
                getChatHeader(selected_header_id, page_list_chat, true, false);
            }
        }
    });

    load_users();
    // IDB_ChannelUsers = await new IndexDB(currentAgent.id).get("chat_users");

    let get_IDB_Headers = await new IndexDB(currentAgent.id).get("chat_headers");
    IDB_Headers = (get_IDB_Headers == null)? IDB_Headers : get_IDB_Headers;
    //console.log(IDB_Headers);
    // chat_headers_history
    lastSync = await getLastSync();
    //console.log(lastSync);

    try {
        await syncHeader(lastSync.chat_headers ?? 0);
    } catch (error) {
        console.log(error)
    }

    try {
        await syncHeaderHistory(lastSync.chat_headers_history ?? 0);
    } catch (error) {
        console.log(error)
    }

    await getChatHeader(null, 1, false, true);

    IDB_Bodies = await new IndexDB(currentAgent.id).get("chat_bodies");

    if (urlParams.has("is_active")) {
        await openChatHeader(urlParams.get("is_active"));
        urlParams.delete("is_active");

        // let current_url = [url.origin, url.pathname, '?', params.toString()].join('');
        let current_url = ["?", urlParams.toString()].join("");
        window.history.replaceState(null, null, current_url);
    }

    let intval = 0;
    setInterval(async () => {
        // console.log("interval", intval++);
        await getChatHeader();
    }, 10000);

    function adjustTextareaHeight(editor) {
        var textarea = editor.parent().find('textarea');
        textarea.css('height', 'auto'); // Reset the height
        textarea.css('height', editor[0].scrollHeight + 'px'); // Set the height to the scroll height
    }

    $("#reply-msg textarea[name=message]").emojioneArea({
        container: "#textarea-emoji",
        events: {
            keydown: function(editor, event) {
                if (event.which === 13) { // Enter key
                    event.preventDefault();
                    var selection = window.getSelection();
                    var range = selection.getRangeAt(0);
                    var br = document.createElement("br");

                    range.deleteContents();
                    range.insertNode(br);

                    // Ensure there is a space after the <br> to place the cursor
                    var space = document.createElement('br');
                    range.insertNode(space);
                    range.setStartAfter(space);
                    range.setEndAfter(space);
                    selection.removeAllRanges();
                    selection.addRange(range);

                    // Scroll into view if necessary
                    br.scrollIntoView({block: 'nearest', inline: 'nearest'});

                    adjustTextareaHeight(editor);
                }
            },
            change: function(editor, event) {
                // Adjust the textarea size whenever the content changes
                adjustTextareaHeight(editor);
            }
        },
        enter: false // Disable default enter key behavior
    });

    $(".chooseTemplate").click(function() {
        var templateId = $(this).data("template-id")
        console.log($("#"+templateId).text())
        $("#reply-msg textarea[name=message]")[0].emojioneArea.setText($("#"+templateId).text())
        // $("#chat-input-text").val($("#"+templateId).text())
        $("#listTemplateChat").modal("hide")
    })

    $("#formEndChat").submit((e) => {
        e.preventDefault();
        let form = $("#formEndChat");
        let data = new FormData(form[0]);
        // data.append("chat_header_id", form.attr('data-header-id'))
        // //console.log("chat_header_id", form.attr('data-header-id'));

        // disabled the submit button
        $("button.chat-end").prop("disabled", true);

        $.ajax({
            type: "POST",
            // enctype: 'multipart/form-data',
            url: listUrls.chatEnd.replaceAll(
                ":header_id",
                $("#reply-msg").attr("data-header-id")
            ),
            data: form.serialize(),
            // processData: false,
            // contentType: false,
            // cache: false,
            // timeout: 800000,
            success: function (result) {
                // $("#output").text(data);
                //console.log("SUCCESS : ", result);
                $(".chat-input-section").hide();
                $('.nav-tabs a[href="#home2"]').tab("show");
                $("#formEndChat textarea").val("");
                // $("#formEndChat #placeNewAdditional input").val("");
                $("#placeNewAdditional").html("");
                // handleSocket(form.data('chat-id'), {type: "outbox", name: "You", message: form.find("textarea[name=message]").val()})

                // UPDATE IDB CHAT HEADER
                let index = IDB_Headers.findIndex(
                    (chat_header) =>
                        chat_header.id == $("#reply-msg").attr("data-header-id")
                );
                if (index >= 0) {
                    let chat_header = IDB_Headers[index];
                    chat_header.status = "close";
                    chat_header.ended_at = new Date().toISOString();

                    // Update chat_header on IDB
                    IDB_Headers[index] = chat_header;
                    new IndexDB(currentAgent.id).set(
                        "chat_headers",
                        IDB_Headers
                    );
                }

                if (result.data.is_create_ticket == 1) {
                    createTicket(result.data);
                } else {
                    Swal.fire({
                        icon: "success",
                        title: "Success",
                        text: "Chat has been closed",
                    });
                }
            },
            error: function (e) {
                // $("#output").text(e.responseText);
                //console.log("ERROR : ", e);
                $("button.chat-end").prop("disabled", false);
            },
        }).done(() => {
            getChatHeader(null, 1, false, true);
            // $("#textarea-emoji").disable();
            // $("button.chat-send").prop("disabled", true);
            // $(".chat-input-section textarea").disable();
            // $(".chat-input-section input").disable();
        });
    });

    $("#formEndChatTicket").submit((e) => {
        e.preventDefault();
        let form = $("#formEndChatTicket");
        let data = new FormData(form[0]);

        // disabled the submit button
        $("button.chat-end-ticket").prop("disabled", true);

        $.ajax({
            type: "POST",
            // enctype: 'multipart/form-data',
            url: listUrls.chatEndWithTicket.replaceAll(
                ":header_id",
                $("#reply-msg").attr("data-header-id")
            ),
            data: form.serialize(),
            // processData: false,
            // contentType: false,
            // cache: false,
            // timeout: 800000,
            success: function (result) {
                // $("#output").text(data);
                //console.log("SUCCESS : ", result);
                $(".chat-input-section").hide();
                $('.nav-tabs a[href="#home2"]').tab("show");
                $("#formEndChat textarea").val("");
                // $("#formEndChat #placeNewAdditional input").val("");
                $("#placeNewAdditional").html("");
                // handleSocket(form.data('chat-id'), {type: "outbox", name: "You", message: form.find("textarea[name=message]").val()})
                Swal.fire({
                    icon: "success",
                    title: "Success",
                    text: "Chat has been closed",
                });
            },
            error: function (e) {
                // $("#output").text(e.responseText);
                //console.log("ERROR : ", e);
                $("button.chat-end-ticket").prop("disabled", false);
            },
        }).done(() => {
            getChatHeader(null, 1, false, true);
            // $("#textarea-emoji").disable();
            // $("button.chat-send").prop("disabled", true);
            // $(".chat-input-section textarea").disable();
            // $(".chat-input-section input").disable();
        });
    });

    $("#reply-msg").submit((e) => {
        e.preventDefault();
        let form = $("#reply-msg");
        let data = new FormData(form[0]);
        data.append("chat_header_id", form.attr("data-header-id"));
        //console.log("chat_header_id", form.attr("data-header-id"));

        let messageText = $("#reply-msg textarea[name=message]").val();

        // disabled the submit button
        $("button.chat-send").prop("disabled", true);

        $.ajax({
            type: "POST",
            enctype: "multipart/form-data",
            url: form.attr("action"),
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 800000,
            success: async function (data) {
                // $("#output").text(data);
                //console.log("SUCCESS : ", data);
                $("button.chat-send").prop("disabled", false);
                $("#reply-msg textarea[name=message]").val("");
                $(".emojionearea-editor").html("");

                let currDate = new Date();
                let date =
                    currDate.getFullYear() +
                    "-" +
                    addZero(currDate.getMonth() + 1) +
                    "-" +
                    addZero(currDate.getDate());
                let time =
                    currDate.getHours() +
                    ":" +
                    currDate.getMinutes() +
                    ":" +
                    currDate.getSeconds();

                // UPDATE IDB CHAT HEADER
                let index = IDB_Headers.findIndex(
                    (chat_header) =>
                        chat_header.id == $("#reply-msg").attr("data-header-id")
                );
                if (index >= 0) {
                    let chat_header = IDB_Headers[index];
                    await syncAndGetChatBodies(chat_header.id)

                    // if(typeof chat_header.latest_message == "undefined") {
                    //     chat_header.latest_message = {}
                    // }
                    chat_header.chat_status = getChatStatus(chat_header);
                    chat_header.updated_at = date + " " + time;
                    chat_header.latest_message = {
                        sender: {
                            name: chat_header?.channel_page?.name ?? "-",
                        },
                        message: messageText,
                        created_at: date + " " + time,
                    };

                    // Update chat_header on IDB
                    IDB_Headers[index] = chat_header;
                    new IndexDB(currentAgent.id).set(
                        "chat_headers",
                        IDB_Headers
                    );

                    if ($("#list-header-" + chat_header.id).length) {
                        await $("#list-header-" + chat_header.id).remove();
                    }

                    if (
                        [IDB_Headers[index].chat_status, 1].includes(
                            $("ul#sampleTabs li>a.active").data(
                                "chat-status"
                            ) ?? 2
                        )
                    ) {
                        await $("#chat-list").prepend(itemHeader(IDB_Headers[index]));
                    }
                }

                await openChatHeader($("#reply-msg").attr("data-header-id"));
                // handleSocket(form.data('chat-id'), {type: "outbox", name: "You", message: form.find("textarea[name=message]").val()})
            },
            error: function (e) {
                // $("#output").text(e.responseText);
                //console.log("ERROR : ", e);
                $("button.chat-send").prop("disabled", false);
            },
        });
    });

});

function getRefreshChatHeaders() {
    clearDB();
}

async function removeHeaderIfHandledOther() {
    if (IDB_Headers == null) IDB_Headers = [];
    IDB_Headers = IDB_Headers.filter(
        (chat_header) => chat_header.handle_by == null || chat_header.handle_by == currentAgent.id
    );
}

async function storeChatHeaderIntoIndexDB(header) {
    if (IDB_Headers == null || IDB_Headers.length == 0) {
        IDB_Headers = [];
        IDB_Headers.push(header);
    } else {
        let findOn_IndexDB = await IDB_Headers.find(
            (item) => item.id == header.id
        );
        //console.log("IDB_Headers objectWhere", findOn_IndexDB);

        if (findOn_IndexDB == undefined) {
            //console.log("IDB_Headers first", IDB_Headers);
            IDB_Headers.push(header);
            // //console.log("IDB_Headers push", IDB_Headers);
        } else {
            let findIndex = IDB_Headers.findIndex(
                (item) => item.id == header.id
            );
            //console.log("findIndex", findIndex);
            if (findIndex >= 0) {
                IDB_Headers[findIndex] = header;
            }
        }
    }

    if (typeof header.channel_user !== "undefined") {
        // await syncAndGetChannelUser(
        //     header.channel_user.id,
        //     header.channel_user
        // );
    }

    await removeHeaderIfHandledOther();
    await new IndexDB(currentAgent.id).set("chat_headers", IDB_Headers);
}
async function syncHeaderHistory(lastSync, nextUrl = null) {
    await $.ajax({
        url: nextUrl != null ? nextUrl : listUrls.syncHeaderHistory,
        method: "POST",
        data: { time_last_sync: lastSync },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        beforeSend: function () {
            $("#alert-loading").show();
        },
        success: async function (response) {
            let headers = response;
            if(typeof response.data != "undefined"){
                headers = response.data
            }
            //console.log("syncHeader", headers);

            updateLastSync("chat_headers_history", Date.now());
            //console.log("IDB_Headers", IDB_Headers);
            // let IndexDB_headers = [];

            // //console.log("IDB_Headers", typeof IDB_Headers);

            headers.forEach(async (header) => {
                if (header.company_id == companyId) {
                    await storeChatHeaderIntoIndexDB(header);
                }
            });


            if (response.next_page_url != null) {
                await syncHeaderHistory(lastSync, response.next_page_url);
            }
        },
        error: function (e) {
            //console.log("ERROR : ", e);
        },
        complete: function () {
            $("#alert-loading").hide();
        },
    });
    // await fetch(listUrls.syncHeader, {
    //     method: "POST",
    //     headers: {
    //         "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    //     },
    //     body: JSON.stringify({ time_last_sync: lastSync ?? 0 }),
    // })
    //     .then((res) => res.json())
    //     .then(async (headers) => {
    //     });
}
async function syncHeader(lastSync, nextUrl = null) {
    await $.ajax({
        url: nextUrl != null ? nextUrl : listUrls.syncHeader,
        method: "POST",
        data: { time_last_sync: lastSync },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        beforeSend: function () {
            $("#alert-loading").show();
        },
        success: async function (response) {
            let headers = response;
            if(typeof response.data != "undefined"){
                headers = response.data
            }
            //console.log("syncHeader", headers);

            updateLastSync("chat_headers", Date.now());
            //console.log("IDB_Headers", IDB_Headers);
            // let IndexDB_headers = [];

            // //console.log("IDB_Headers", typeof IDB_Headers);

            headers.forEach(async (header) => {
                if (header.company_id == companyId) {
                    console.log("MASUK SINI?? 422")
                    await storeChatHeaderIntoIndexDB(header);
                    // if (IDB_Headers == null || IDB_Headers.length == 0) {
                    //     IDB_Headers = [];
                    //     IDB_Headers.push(header);
                    // } else {
                    //     let findOn_IndexDB = await IDB_Headers.find(
                    //         (item) => item.id == header.id
                    //     );
                    //     //console.log("IDB_Headers objectWhere", findOn_IndexDB);

                    //     if (findOn_IndexDB == undefined) {
                    //         //console.log("IDB_Headers first", IDB_Headers);
                    //         IDB_Headers.push(header);
                    //         // //console.log("IDB_Headers push", IDB_Headers);
                    //     } else {
                    //         let findIndex = await IDB_Headers.findIndex(
                    //             (item) => item.id == header.id
                    //         );
                    //         //console.log("findIndex", findIndex);
                    //         if (findIndex >= 0) {
                    //             IDB_Headers[findIndex] = header;
                    //         }
                    //     }
                    // }

                    // if (typeof header.channel_user !== "undefined") {
                    //     // await syncAndGetChannelUser(
                    //     //     header.channel_user.id,
                    //     //     header.channel_user
                    //     // );
                    // }
                }
            });
            // await removeHeaderIfHandledOther();
            // await new IndexDB(currentAgent.id).set("chat_headers", IDB_Headers);


            if (response.next_page_url != null) {
                await syncHeader(lastSync, response.next_page_url);
            }
        },
        error: function (e) {
            //console.log("ERROR : ", e);
        },
        complete: function () {
            $("#alert-loading").hide();
        },
    });
    // await fetch(listUrls.syncHeader, {
    //     method: "POST",
    //     headers: {
    //         "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    //     },
    //     body: JSON.stringify({ time_last_sync: lastSync ?? 0 }),
    // })
    //     .then((res) => res.json())
    //     .then(async (headers) => {
    //     });
}
async function syncHeaderById(chat_header_id) {

    // console.log("syncHeaderById", "chat_header_id", chat_header_id);
    if(chat_header_id == null) return;
    await $.ajax({
        url: listUrls.syncHeader,
        method: "POST",
        data: { header_id: chat_header_id },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: async function (headers) {
            if(typeof headers.data != "undefined"){
                headers = headers.data
            }
            //console.log("syncHeader", headers);

            updateLastSync("chat_headers", Date.now());
            //console.log("IDB_Headers", IDB_Headers);
            // let IndexDB_headers = [];

            // //console.log("IDB_Headers", typeof IDB_Headers);

            headers.forEach(async (header) => {
                if (header.company_id == companyId) {
                    if (IDB_Headers == null || IDB_Headers.length == 0) {
                        IDB_Headers = [];
                        IDB_Headers.push(header);
                    } else {
                        let findOn_IndexDB = await IDB_Headers.find(
                            (item) => item.id == header.id
                        );
                        //console.log("IDB_Headers objectWhere", findOn_IndexDB);

                        if (findOn_IndexDB == undefined) {
                            //console.log("IDB_Headers first", IDB_Headers);
                            IDB_Headers.push(header);
                            // //console.log("IDB_Headers push", IDB_Headers);
                        } else {
                            let findIndex = await IDB_Headers.findIndex(
                                (item) => item.id == header.id
                            );
                            //console.log("findIndex", findIndex);
                            if (findIndex >= 0) {
                                IDB_Headers[findIndex] = header;
                            }
                        }
                    }

                    if (typeof header.channel_user !== "undefined") {
                        await syncAndGetChannelUser(
                            header.channel_user.id,
                            header.channel_user
                        );
                    }
                }
            });

            await removeHeaderIfHandledOther();
            await new IndexDB(currentAgent.id).set("chat_headers", IDB_Headers);
        },
        error: function (e) {
            //console.log("ERROR : ", e);
        },
    });
    // await fetch(listUrls.syncHeader, {
    //     method: "POST",
    //     headers: {
    //         "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
    //     },
    //     body: JSON.stringify({ time_last_sync: lastSync ?? 0 }),
    // })
    //     .then((res) => res.json())
    //     .then(async (headers) => {
    //     });
}

function getChatStatus(chat_header) {
    let d = new Date();
    d.setDate(d.getDate() - 1);

    if (chat_header.status == "open" && chat_header.handle_by == null) return 2;
    if (
        chat_header.latest_message !== null &&
        chat_header.status == "open" &&
        chat_header.handle_by == currentAgent.id
    )
        return 3;
    if (
        chat_header.latest_message !== null &&
        chat_header.status == "close" &&
        chat_header.handle_by == currentAgent.id &&
        new Date(chat_header.ended_at).getTime() >= d.getTime()
    )
        return 4;

    return 1;
}
async function getChatHeader(
    selected = null,
    page = 1,
    append_chat = false,
    reset_page = false
) {
    $("#loader").html(loader);
    if (reset_page) {
        page = 1;
        page_list_chat = 1;
        response_chat_headers = [];
    }
    var chat_status = $("ul#sampleTabs li>a.active").data("chat-status") ?? 2;
    console.log("chat_status", chat_status);
    var data = {
        chat_status: chat_status,
        page: page,
    };
    if (selected != null) {
        data.header_id = selected;
    }

    let dataHeaders = [];

    //console.log(chat_status);

    if (chat_status == 2)
        dataHeaders = IDB_Headers.filter(
            (item) => item.latest_message !== null && item.status == "open" && item.handle_by == null
        );
    if (chat_status == 3)
        dataHeaders = IDB_Headers.filter(
            (item) => item.latest_message !== null && item.status == "open" && item.handle_by == currentAgent.id
        );
    if (chat_status == 4) {
        let d = new Date();
        // d.setDate(d.getDate() - 1);
        d.setHours(0, 0, 0, 0);

        dataHeaders = IDB_Headers.filter(
            (item) => {

                // console.log("chat_status", new Date(item.ended_at).getTime(), d.getTime());
                // console.log("chat_status", item.ended_at, new Date(item.ended_at));
                // console.log("chat_status", new Date(item.ended_at), d);
                // console.log("chat_status", item.status, item.id, new Date(item.ended_at).getTime() >= d.getTime());
                return item.latest_message !== null && item.status == "close" && new Date(item.ended_at).getTime() >= d.getTime()
            }
        );
        console.log("dataHeaders resolved", dataHeaders);

    }
    // if (chat_status == "4")
    //     dataHeaders = IDB_Headers.filter((item) => item.status == "close");
    if (chat_status == 1) dataHeaders = IDB_Headers.filter(
        (item) => item.latest_message !== null
    );
    dataHeaders.map((item) => {
        item.updated_time = new Date(item.updated_at).getTime();
        item.chat_status = chat_status;
    });

    //console.log(dataHeaders);
    dataHeaders.sort((a, b) => {
        return b.updated_time - a.updated_time;
    });
    //console.log(dataHeaders);

    //console.log(dataHeaders.length);

    await fetchHeaders({ data: dataHeaders }, selected, append_chat);

    $("#json_headers").text(
        JSON.stringify(
            {
                data: dataHeaders,
            },
            null,
            2
        )
    );
    // await $.post(listUrls.chatGetHeader, data, async (response) => {
    //     //console.log("getChatHeader", response);
    //     response.data.map((item) => {
    //         item.chat_status = chat_status;
    //     });

    //     // updateLastSync("chat_headers", Date.now());
    //     // await IDDBAGENT.set("chat_headers", response.data)
    //     //     .then(async function (value) {
    //     //         // Do other things once the value has been saved.
    //     //         //console.log(value);
    //     //         await fetchHeaders(response, selected, append_chat);
    //     //     })
    //     //     .catch(function (err) {
    //     //         // This code runs if there were any errors
    //     //         //console.log(err);
    //     //     });
    //     last_page_list_chat = response.last_page;
    //     response_chat_headers = response_chat_headers.concat(response.data);
    //     $("#json_headers").text(
    //         JSON.stringify(
    //             {
    //                 data: response_chat_headers,
    //             },
    //             null,
    //             2
    //         )
    //     );
    // });
}

function itemHeader(header) {
    console.log("header", header)
    // //console.log(moment(header.latest_message.created_at).year() )
    // let time_ago = moment(header.latest_message.created_at)
    // //console.log(header.latest_message.created_at)
    let status = "read";
    // if (typeof header.latest_message?.has_seen == 0) {
    if (
        typeof header.latest_message == "undefined" ||
        (header.latest_message?.has_seen ?? 0) == 0
    ) {
        status = "unread";
    }

    let chat_name = "";
    if (header.channel_account_id != null) {
        chat_name = header.channel_account?.name ?? "";
    } else {
        chat_name = header.channel_page?.name ?? "";
    }

    let status_badge = "";
    if(header.handle_by == null) {
        status_badge = `style="background-color: #f34e4e;"`;
    } else {
        if(header.started_at != null) {
            status_badge = `style="background-color: #348feb;"`;
        }
    }

    let unread_badge = "";
    if (header?.total_not_seen > 0) {
        unread_badge = `<span class="badge bg-success rounded-pill">${header?.total_not_seen}</span>`;
    }



    return `<li class="${status}" id="list-header-${header.id}">
                            <a href="javascript:openChatHeader('${
                                header.id
                            }')" class="chat-list-a" id="chat-list-${
        header.id
    }">
                                <div class="d-flex align-items-start">
                                    <div class="flex-shrink-0 user-img online align-self-center me-3">
                                        <div class="avatar-sm align-self-center">
                                            <img class="avatar-title rounded-circle bg-soft-primary" src="${
                                                header.channel?.icon_src ?? ""
                                            }">
                                        </div>
                                        <span class="user-status" ${status_badge}></span>
                                    </div>

                                    <div class="flex-grow-1 overflow-hidden">
                                        <h5 class="text-truncate font-size-14 mb-1">[${chat_name}] ${
        header.chat_from_name ?? ""
    }</h5>
                                        <p class="text-truncate mb-0">${
                                            header.latest_message?.sender
                                                ?.name ?? ""
                                        } : ${
        header.latest_message?.message ?? ""
    }</p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <div class="font-size-11">${new TimeAgo(
                                            "id"
                                        ).format(
                                            moment(
                                                header.latest_message
                                                    ?.created_at ?? header.updated_at
                                            ).toDate()
                                        )}</div>
                                        <p class="text-truncate mb-0">${unread_badge}</p>
                                    </div>
                                </div>
                            </a>
                        </li>`;
}
async function fetchHeaders(response, selected = null, append_chat = false) {
    let headers_ui = ``;

    let response_data = await response.data.sort((a, b) => {
        return a.updated_at - b.updated_at;
    });
    response_data.forEach((header) => {
        headers_ui += itemHeader(header);
    });

    // <div class="font-size-11">${new TimeAgo('id').format(moment(header.latest_message.created_at).toDate())}</div>
    if (!append_chat) {
        $("#chat ul.chat-list#chat-list").html('');
        $("#chat ul.chat-list#chat-list").html(headers_ui);
    } else {
        $("#chat ul.chat-list#chat-list").append(headers_ui);
    }

    $("a.chat-list-a").removeClass("chat-open");
    $("a#chat-list-" + selected).addClass("chat-open");
    $(".chat-open").focus();
    $("#loader").html("");
}

async function idbGetHeader(header_id) {
    // console.log("header_id", header_id);
    if (IDB_Headers == null) IDB_Headers = [];
    let index = IDB_Headers.findIndex(
        (chat_header) => chat_header.id == header_id ?? null
    );
    // console.log("index", index);
    if (index < 0) {
        await syncHeaderById(header_id);
    }

    let header = IDB_Headers.find(
        (chat_header) => chat_header.id == header_id ?? null
    );

    // console.log("header", header);

    return header;
}

async function syncAndGetChannelUser(channel_user_id, datas = null) {
    if (IDB_ChannelUsers == null) IDB_ChannelUsers = [];
    let index = IDB_ChannelUsers.findIndex(
        (channel_user) => channel_user.id == channel_user_id
    );

    if (index < 0) {
        if (datas !== null) {
            IDB_ChannelUsers.push(datas);
        } else {
            // $.post("/chat/syncChannelUser", {
            //     )
            IDB_ChannelUsers.push({
                id: channel_user_id,
                name: channel_user_id,
            });

            //console.log("proses sync channel user");
        }
        new IndexDB(currentAgent.id).set("chat_users", IDB_ChannelUsers);
    } else {
        if (datas.length > 0) {
            IDB_ChannelUsers[index] = datas;
            new IndexDB(currentAgent.id).set("chat_users", IDB_ChannelUsers);
        }
    }
    index = IDB_ChannelUsers.findIndex(
        (channel_user) => channel_user.id == channel_user_id
    );

    //console.log(IDB_ChannelUsers);

    return IDB_ChannelUsers[index];
}

async function requestSyncBodys(header_id, updateLastSync, type='open') {
    let url = type == 'close'? listUrls.syncBodiesHistory : listUrls.syncBodies
    await $.post(
        url,
        {
            header_id: header_id,
            lastSync: (updateLastSync.current ?? 0)-300000,
        },
        async (response) => {
            //console.log("772", response);
            await response.chat_bodies.map((chat_body) => {
                chat_body.updated_time = new Date(
                    chat_body.created_at
                ).getTime();

                // find on chatBodies
                let getIndex = IDB_Bodies[header_id].chat_bodies.findIndex(
                    (item) => item.id == chat_body.id
                );
                if (getIndex >= 0) {
                    IDB_Bodies[header_id].chat_bodies[getIndex] = chat_body;
                } else {
                    IDB_Bodies[header_id].chat_bodies.push(chat_body);
                }
            });

            IDB_Bodies[header_id].chat_bodies.sort((a, b) => {
                return a.id - b.id;
            });

            new IndexDB(currentAgent.id).set("chat_bodies", IDB_Bodies);
        }
    );
}

async function syncAndGetChatBodies(header_id) {
    if (IDB_Bodies == null) IDB_Bodies = [];

    let chat_header = IDB_Headers.find(
        (chat_header) => chat_header.id == header_id
    )

    //console.log(IDB_Bodies[header_id]);
    let updateLastSync = await updateLastSyncChatBody(header_id, Date.now())
    if (IDB_Bodies[header_id] !== undefined) {
        // Sync chat_bodies with server if lastSync is older than chat_bodies
        // await $.post(
        //     listUrls.syncBodies,
        //     {
        //         header_id: header_id,
        //         lastSync: (updateLastSync.current ?? 0)-300000,
        //     },
        //     async (response) => {
        //         //console.log("772", response);
        //         await response.chat_bodies.map((chat_body) => {
        //             chat_body.updated_time = new Date(
        //                 chat_body.created_at
        //             ).getTime();

        //             // find on chatBodies
        //             let getIndex = IDB_Bodies[header_id].chat_bodies.findIndex(
        //                 (item) => item.id == chat_body.id
        //             );
        //             if (getIndex >= 0) {
        //                 IDB_Bodies[header_id].chat_bodies[getIndex] = chat_body;
        //             } else {
        //                 IDB_Bodies[header_id].chat_bodies.push(chat_body);
        //             }
        //         });

        //         IDB_Bodies[header_id].chat_bodies.sort((a, b) => {
        //             return a.id - b.id;
        //         });

        //         new IndexDB(currentAgent.id).set("chat_bodies", IDB_Bodies);
        //     }
        // );

        await requestSyncBodys(header_id, (updateLastSync.current ?? 0)-300000, 'open');
        if(chat_header.status == 'close') await requestSyncBodys(header_id, (updateLastSync.current ?? 0)-300000, 'close');
    } else {
        IDB_Bodies[header_id] = {
            chat_bodies: [],
            chat_addition_infos: [],
        };
        // new IndexDB(currentAgent.id).set("chat_bodies", IDB_Bodies);

        // await $.post(
        //     listUrls.syncBodies,
        //     {
        //         header_id: header_id,
        //         lastSync: updateLastSync.current ?? 0,
        //     },
        //     async (response) => {
        //         IDB_Bodies[header_id].chat_addition_infos =
        //             response.chat_addition_infos;
        //         await response.chat_bodies.map((chat_body) => {
        //             // find on chatBodies
        //             let getIndex = IDB_Bodies[header_id].chat_bodies.findIndex(
        //                 (item) => item.id == chat_body.id
        //             );
        //             if (getIndex >= 0) {
        //                 IDB_Bodies[header_id].chat_bodies[getIndex] = chat_body;
        //             } else {
        //                 IDB_Bodies[header_id].chat_bodies.push(chat_body);
        //             }

        //             IDB_Bodies[header_id].chat_bodies.sort((a, b) => {
        //                 return new Date(a.created_at) - new Date(b.created_at);
        //             });
        //         });

        //         new IndexDB(currentAgent.id).set("chat_bodies", IDB_Bodies);
        //     }
        // );


        await requestSyncBodys(header_id, (updateLastSync.current ?? 0)-300000, 'open');
        if(chat_header.status == 'close') await requestSyncBodys(header_id, (updateLastSync.current ?? 0)-300000, 'close');
    }

    return IDB_Bodies[header_id];
}

async function openChatHeader(header_id) {
    selected_header_id = header_id;
    let chat_header = await idbGetHeader(header_id);
    // console.log("chat_header", chat_header);

    let channel_user = await syncAndGetChannelUser(
        chat_header.channel_user_id,
        chat_header.channel_user ?? []
    );
    // console.log("channel_user", channel_user);

    if (IDB_Bodies == null) IDB_Bodies = [];

    var chatBodies = await syncAndGetChatBodies(header_id);
    //console.log("chatBodies", chatBodies);

    //console.log("IDB_Bodies", IDB_Bodies);

    load_histories(channel_user.id);
    load_blast_histories(channel_user.id);
    $("#placeNewAdditional").html("");
    chatBodies.chat_addition_infos.forEach((additional) => {
        addNewCardAdditionalInfo(additional.key, additional.value);
    });

    //selected chat
    $("a.chat-list-a").removeClass("chat-open");
    $("a#chat-list-" + header_id).addClass("chat-open");
    $(".chat-open").focus();

    $("button.chat-send").prop("disabled", false);
    if(chat_header.channel_id === 7) {
        $("#header-datas h5").text(chat_header.chat_from_name ?? "");
    } else {
        $("#header-datas h5").text(channel_user.name ?? "");
    }
    $("#header-datas img").attr(
        "src",
        channel_user.photo_src ?? "/assets/images/icons/user.png"
    );
    $(".chat-conversation").attr("data-chat-id", chat_header.chat_id);
    $(".chat-conversation").attr("data-header-id", chat_header.id);
    $("form#reply-msg").attr("data-chat-id", chat_header.chat_id);
    $("form#reply-msg").attr("data-header-id", chat_header.id);
    $('.chat-conversation[data-chat-id="'+chat_header.chat_id+'"] ul').html(
        ""
    );

    $(".chat-input-section").show();
    $("button.chat-end").prop("disabled", false);

    $(".user-info").html(generate_user_info(channel_user));
    // current_chat_id = response.channel_page.chat_id;
    chatBodies.chat_bodies.forEach((body) => {
        // console.log(body.id, body.attachment_info ?? []);
        //console.log(chat_header.chat_id);

        let c_date = new Date(body.created_at);
        let h =
            c_date.getHours() < 10
                ? "0" + c_date.getHours()
                : c_date.getHours();
        let m =
            c_date.getMinutes() < 10
                ? "0" + c_date.getMinutes()
                : c_date.getMinutes();
        if (body.sender_type == "user") {
            /*console.log(
                inbox(
                    body.sender,
                    body.message,
                    h + ":" + m,
                    body.attachment_info ?? []
                )
            );*/

            // replace user name with user chat name from chat header
            body.sender.name = chat_header.chat_from_name;

            $('.chat-conversation[data-chat-id="'+chat_header.chat_id+'"] ul').append(
                inbox(
                    body.sender,
                    body.message,
                    h + ":" + m,
                    body.attachment_info ?? []
                )
            );
        } else if (body.sender_type == "page") {
            let sender = body.sender;
            sender.is_bot = false
            if (body.message_id != null) {
                let message_id = body.message_id;
                sender.is_bot = message_id.startsWith("bot:");
            }
            if (chat_header.channel_account_id != null)
                sender = chat_header.channel_account;
            $('.chat-conversation[data-chat-id="'+chat_header.chat_id+'"] ul').append(
                outbox(
                    sender,
                    body.message,
                    h + ":" + m,
                    body.attachment_info ?? []
                )
            );
        }
    });
    scrollSmoothlyToBottom(".chat-conversation .simplebar-content-wrapper");

    if (
        (chat_header.handle_by != null &&
            chat_header.handle_by != (currentAgent.id ?? "")) ||
        chat_header.status == "close"
    ) {
        $(".chat-input-section").hide();
        $("button.chat-end").prop("disabled", true);
        $("#nav-item-end-chat").addClass("d-none");
        $("#nav-item-end-chat-ticket").addClass("d-none");
    } else {
        $("#nav-item-end-chat").removeClass("d-none");
        $("#nav-item-end-chat-ticket").removeClass("d-none");
    }
}


async function closeBySocketEvent(chat_header) {
    chat_header.status = "close";
    chat_header.chat_status = getChatStatus(chat_header);

    if($("#list-header-" + chat_header.id + "]").length > 0) {
        $("#list-header-" + chat_header.id + "]").remove();
    }

    return chat_header;
}

async function removeWhenNotHandler(chat_header_new) {
    if(chat_header_new.handle_by == currentAgent.id) {
        return chat_header_new;
    }

    return null;
}


async function handleSocket(chat_id, datas) {
    //console.log("disini?", chat_id, datas.type, datas);
    //console.log(typeof window.parent);
    //console.log(window.parent);
    // window.parent.postMessage(datas, "*");

    // console.log("onSocket", chat_id, datas);
    if(typeof datas.chat_header !== "undefined" && datas.chat_header.company_id != currentAgent.company_id) {
        // console.log("socket chat header not same company", datas.chat_header.company_id, currentAgent.company_id);
        return;
    }

    console.log("Socket Event", chat_id, datas.type, datas);


    let currDate = new Date();
    let date =
        currDate.getFullYear() +
        "-" +
        addZero(currDate.getMonth() + 1) +
        "-" +
        addZero(currDate.getDate());
    let time =
        currDate.getHours() +
        ":" +
        currDate.getMinutes() +
        ":" +
        currDate.getSeconds();

    sendEventToParent({
        chat: {
            type: datas.type,
            chat_header_id: chat_id,
            message: datas.message,
            created_at: date + " " + time,
        }
    });


    if (typeof datas.channel_user !== "undefined") {
        await syncAndGetChannelUser(datas.channel_user.id, datas.channel_user);
    }

    if (IDB_Headers == null) IDB_Headers = [];

    let chat_header_id = datas.chat_header_id ?? (datas.chat_header?.id ?? null)
    console.log("chat_header_id", chat_header_id)

    if(chat_header_id == null) return;

    let index = IDB_Headers.findIndex(
        (chat_header) => chat_header.id == chat_header_id
    );

    console.log("index", index);
    if (index < 0) {
        await syncHeaderById(chat_header_id);
    } else {
        let findOnIDB = IDB_Headers[index];

        if(datas.type == "close") {
            // Update chat_header on IDB
            IDB_Headers[index] = await closeBySocketEvent(findOnIDB);
        } else if(datas.type == "change_handler") {
            // Update chat_header on IDB
            IDB_Headers[index] = await removeWhenNotHandler(findOnIDB);
        } else if(datas.type == "outbox") {
            console.log("handle socket outbox");
            IDB_Headers[index].latest_message = await datas.chat_header.latest_message;
            getChatHeader(null, 1, false, false);
        } else if(datas.type == "inbox") {
            if(findOnIDB.status != datas.chat_header.status) IDB_Headers[index].status = datas.chat_header.status;
            if(findOnIDB.handle_by != datas.chat_header.handle_by) IDB_Headers[index].handle_by = datas.chat_header.handle_by;
        }
        if(IDB_Headers[index] == null) IDB_Headers.splice(index, 1);
        await (new IndexDB(currentAgent.id)).set("chat_headers", IDB_Headers);

        await removeHeaderIfHandledOther();
    }

    // UPDATE IDB CHAT HEADER
    index = IDB_Headers.findIndex(
        (chat_header) => chat_header.id == datas.chat_header_id ?? null
    );
    if (index >= 0) {
        let chat_header = IDB_Headers[index];

        // if(typeof chat_header.latest_message == "undefined") {
        //     chat_header.latest_message = {};
        // }

        chat_header.chat_status = getChatStatus(chat_header);
        chat_header.updated_at = date + " " + time;
        chat_header.latest_message = {
            sender: datas.sender ?? { name: "" },
            message: datas.message,
            created_at: date + " " + time,
        };

        // Update chat_header on IDB
        IDB_Headers[index] = chat_header;
        new IndexDB(currentAgent.id).set("chat_headers", IDB_Headers);

        //console.log(IDB_Headers[index]);

        // //console.log(chat_header.id, itemHeader(IDB_Headers[index]))

        if ($("#list-header-" + chat_header.id).length) {
            await $("#list-header-" + chat_header.id).remove();
            //console.log(chat_header.id, IDB_Headers[index]);
        }

        if (
            [chat_header.chat_status, 1].includes(
                $("ul#sampleTabs li>a.active").data("chat-status") ?? 2
            )
        ) {
            await $("#chat-list").prepend(itemHeader(chat_header));
        }
    }

    // $('#list-header-'+findOnIDB.id).remove()
    // $('#chat-list').prepend(itemHeader(findOnIDB));

    // if (selected_header_id) {
    //     await getChatHeader(selected_header_id, 1, false, true);
    // } else {
    //     await getChatHeader(null, 1, false, true);
    // }

    // console.log("form-id", $("form#reply-msg").attr("data-header-id"))
    if (
        $("form#reply-msg").attr("data-header-id") == datas.chat_header_id ||
        $("form#reply-msg").attr("data-header-id") == datas?.chat_header?.id
    ) {
        // console.log("masuk ke sini?")
        // await syncAndGetChatBodies(datas.chat_header_id);

        let header_id = null;
        if(typeof datas.chat_header_id !== "undefined") {
            header_id = datas.chat_header_id
        } else if(typeof datas?.chat_header?.id !== "undefined") {
            header_id = datas?.chat_header?.id
        }

        if(header_id !== null && header_id == selected_header_id) {
            await openChatHeader(header_id);
        }
        //console.log("chat-view data-header-id match");
        // if (datas.type == "inbox") {
        //     //console.log("chat-view data.type inbox");
        //     $(".chat-conversation[data-chat-id=" + chat_id + "] ul").append(
        //         inbox(datas.sender, datas.message, "", datas.attachments)
        //     );
        // } else if (datas.type == "outbox") {
        //     //console.log("chat-view data.type outbox");
        //     $(".chat-conversation[data-chat-id=" + chat_id + "] ul").append(
        //         outbox(datas.sender, datas.message, "", datas.attachments)
        //     );
        // }
    } else {
        //console.log("chat-view data-header-id not match");
    }
}

// async function removeHeaderIfHandledOther(chat_id, user_id) {
//     if (IDB_Headers == null) IDB_Headers = [];
//     let index = IDB_Headers.findIndex(
//         (chat_header) => chat_header.handle_by == null && chat_header.id == chat_id
//     );

//     if (index >= 0) {
//         if(user_id != currentAgent.id) {
//             IDB_Headers.splice(index, 1);
//         } else {
//             IDB_Headers[index].handle_by = user_id;
//         }
//         await new IndexDB(currentAgent.id).set("chat_headers", IDB_Headers);
//         await getChatHeader(null, 1, false, true);
//     }
// }

function Linkify(inputText) {
    //URLs starting with http://, https://, or ftp://
    var replacePattern1 =
        /(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim;
    var replacedText = inputText.replace(
        replacePattern1,
        '<a href="$1" target="_blank">$1</a>'
    );

    //URLs starting with www. (without // before it, or it'd re-link the ones done above)
    var replacePattern2 = /(^|[^\/])(www\.[\S]+(\b|$))/gim;
    var replacedText = replacedText.replace(
        replacePattern2,
        '$1<a href="http://$2" target="_blank">$2</a>'
    );

    //Change email addresses to mailto:: links
    var replacePattern3 =
        /(([a-zA-Z0-9_\-\.]+)@[a-zA-Z_]+?(?:\.[a-zA-Z]{2,6}))+/gim;
    var replacedText = replacedText.replace(
        replacePattern3,
        '<a href="mailto:$1">$1</a>'
    );

    return replacedText;
}

function attachment_file_message(attachment_info) {
    let attachments = "";
    if (attachment_info.type == "file") {
        attachments += `<div class="card border shadow-none mb-2">
                                <a href="javascript: void(0);" class="text-body">
                                    <div class="p-2">
                                        <div class="d-flex">
                                            <div class="avatar-sm align-self-center me-2">
                                                <div class="avatar-title rounded bg-transparent text-primary font-size-18">
                                                    <i class="uil uil-file-alt"></i>
                                                </div>
                                            </div>

                                            <div class="overflow-hidden me-auto">
                                                <h5 class="font-size-13 text-truncate mb-1">File</h5>
                                                <p class="text-muted text-truncate mb-0"><a href="${image_url(
                                                    attachment_info.payload.local_url
                                                )}" target="_blank">Download</a></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>`;
    } else if (attachment_info.type == "document") {
        attachments += `<div class="card border shadow-none mb-2">
                                <a href="javascript: void(0);" class="text-body">
                                    <div class="p-2">
                                        <div class="d-flex">
                                            <div class="avatar-sm align-self-center me-2">
                                                <div class="avatar-title rounded bg-transparent text-primary font-size-18">
                                                    <i class="uil uil-file-alt"></i>
                                                </div>
                                            </div>

                                            <div class="overflow-hidden me-auto">
                                                <h5 class="font-size-13 text-truncate mb-1">Document</h5>
                                                <p class="text-muted text-truncate mb-0"><a href="${image_url(
                                                    attachment_info.payload.local_url
                                                )}" target="_blank">Download</a></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>`;
    } else if (attachment_info.type == "sticker") {
        attachments += `<img class="img-fluid" src="${image_url(
            attachment_info.payload.local_url
        )}">`;
    } else if (attachment_info.type == "image") {
        attachments += `<img class="img-fluid" src="${image_url(
            attachment_info.payload.local_url
        )}">`;
    } else if (attachment_info.type == "video") {
        attachments += `<video
        id="msg-${(new Date).getMilliseconds()}"
        class="video-js"
        controls
        preload="auto"
        width="300"
        height="164"
        data-setup="{}"
      >
        <source src="${image_url(
            attachment_info.payload.local_url
        )}" type="video/mp4" />
      </video>`;
    }
    return attachments;
}

function image_url(url) {
    return isValidUrl(url) ? url : listUrls.baseUrl + url;
}

const isValidUrl = (urlString) => {
    var urlPattern = new RegExp(
        "^(https?:\\/\\/)?" + // validate protocol
            "((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|" + // validate domain name
            "((\\d{1,3}\\.){3}\\d{1,3}))" + // validate OR ip (v4) address
            "(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*" + // validate port and path
            "(\\?[;&a-z\\d%_.~+=-]*)?" + // validate query string
            "(\\#[-a-z\\d_]*)?$",
        "i"
    ); // validate fragment locator
    return !!urlPattern.test(urlString);
};

function isJSON(str) {
    try {
        let check = JSON.parse(str);
        return check && typeof check == "object";
    } catch (e) {
        return false;
    }
}

function nl2br (str, is_xhtml) {
    if (typeof str === 'undefined' || str === null) {
        return '';
    }
    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
}

function generate_message(message) {
    //console.log("message", message);
    //console.log("isJSON", isJSON(message));
    if (isJSON(message)) {
        let json = JSON.parse(message);
        //console.log("json", json);
        let msg = `<p>${nl2br(json.text) ?? ""}</p>`;
        json.buttons.forEach((button) => {
            if (button.type == "call") {
                msg += `<a href="call:+62${
                    button.phone ?? ""
                }" class="btn btn-block btn-outline-secondary btn-sm w-100 mb-1">${
                    button.text ?? ""
                }</a>`;
            } else if (button.type == "url") {
                msg += `<a href="${
                    button.url ?? ""
                }" class="btn btn-block btn-outline-secondary btn-sm w-100 mb-1">${
                    button.text ?? ""
                }</a>`;
            } else {
                msg += `<button type="button" class="btn btn-block btn-outline-secondary btn-sm w-100 mb-1">${
                    button.text ?? ""
                }</button>`;
            }
        });

        return msg;
    } else {
        return Linkify(message ?? "");
    }
}

function generate_user_info(sender) {
    //console.log(sender);
    if (sender == null) {
        return "";
    }

    if ($("#offcanvasWithBothOptions").length > 0) {
        $("#offcanvasWithBothOptions").attr("data-channel-user-id", sender.id);
        $("#offcanvasWithBothOptions").attr(
            "data-channel-id",
            sender.channel_id
        );
    }

    if ($(".card-user-info").length > 0) {
        $(".card-user-info").show();
    }
    return `<div class="card card-body p-2 bg-soft-secondary"><div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 avatar me-3 d-sm-block d-none">
                                            <img src="${
                                                sender?.photo ??
                                                "https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/No_image_available.svg/2048px-No_image_available.svg.png"
                                            }" alt="" class="img-thumbnail d-block rounded-circle">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="font-size-14 mb-1 text-truncate text-white"><a href="#" class="text-dark">${
                                                sender?.name ?? "-"
                                            }</a></h5>
                                            <p class="text-muted text-truncate mb-0">${
                                                sender?.account_id ?? "-"
                                            }</p>
                                        </div>
                                    </div></div>`;
}

function inbox(sender, message, time = "", attachment_datas = []) {
    //console.log("attachment_datas", attachment_datas);
    let attachments = ``;
    attachment_datas =
        typeof attachment_datas == "string"
            ? JSON.parse(attachment_datas)
            : attachment_datas;
    // if(has_attachment) {
    attachment_datas.forEach((attachment_info) => {
        attachments += attachment_file_message(attachment_info);
    });
    // }
    return `
                <li>
                    <div class="conversation-list mb-0">
                        <div class="ctext-wrap">
                            <div class="chat-avatar">
                                <img src="${
                                    sender?.photo_src ??
                                    "https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/No_image_available.svg/2048px-No_image_available.svg.png"
                                }" alt="avatar-2">
                            </div>
                            <div class="ctext-wrap-content">
                                <h5 class="conversation-name"><a href="#" class="user-name">${
                                    sender?.name ?? "-"
                                }</a> <span class="time">${time}</span></h5>
                                <p class="mb-0">
                                    ${generate_message(message ?? "")}
                                    ${attachments}
                                </p>
                            </div>
                        </div>
                    </div>
                </li>`;
}

function outbox(sender, message, time = "", attachment_datas = []) {
    console.log("outbox", sender)
    //console.log(attachment_datas);
    let attachments = ``;
    attachment_datas =
        typeof attachment_datas == "string"
            ? JSON.parse(attachment_datas)
            : attachment_datas;
    // if(has_attachment) {
    attachment_datas.forEach((attachment_info) => {
        attachments += attachment_file_message(attachment_info);
    });
    return `
                <li class="right">
                    <div class="conversation-list mb-0">
                        <div class="ctext-wrap">
                            <div class="chat-avatar">
                                <img src="${
                                    sender?.photo_src ??
                                    "https://upload.wikimedia.org/wikipedia/commons/thumb/a/ac/No_image_available.svg/2048px-No_image_available.svg.png"
                                }" alt="avatar-2">
                            </div>
                            <div class="ctext-wrap-content">
                                <h5 class="conversation-name"><a href="#" class="user-name">${sender?.is_bot ? `<i class="fa fa-robot"></i> ` : ``}${
                                    sender?.name ?? "-"
                                }</a> <span class="time">${time}</span></h5>
                                <p class="mb-0">
                                    ${generate_message(message ?? "")}
                                    ${attachments}
                                </p>
                            </div>
                        </div>
                    </div>
                </li>`;
}
