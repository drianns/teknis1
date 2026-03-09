class Chat {
    constructor() {
        this.lib = {
            indexDB : new IndexDB(currentAgent.id),
            http : new Http(),
        };

        this.var = {
            selected: null,
            chatHeaderHistories: [],
        };

        this.el = {
            loader: "#chat #loader",
            chatList: "#chat ul.chat-list#chat-list",
            chatListOpen: "#chat ul.chat-list#chat-list .chat-open",
            chatBody: "#chat .chat-body",
            chatHeader:".user-chat #header-datas",
            chatFooter: "#chat .chat-footer",
            chatInput: "#chat .chat-input",
            chatSend: "#chat .chat-send",
            servedCount: "#chat #served-count",
            resolvedCount: "#chat #resolved-count",
            historyList: "#placeHistories",
            chatItem : "#chat-list-:chat_header_id",
        }
    }

    elChatItem(selected) {
        return $(this.el.chatList).find(`#list-header-${selected}`);
    }

    async elChatItemRemove(id) {
        await $(this.el.chatList).find(`#list-header-${id}`).remove();
    }

    async elChatItemMoveToTop(id) {
        let header =  INSTANCE.storage.chatHeaders.find((header) => header.id == id);
        if(header == null) return;

        let headers_ui =  this.itemListHeader(header)
        if(this.elChatItem(id).length > 0) await this.elChatItem(id).remove();
        await $(this.el.chatList).prepend(headers_ui);
    }

    setChatSelected() {
        if(this.var.selected) {
            $(this.el.chatHeader).attr("data-chat-header-id", this.var.selected.id);

            $("a.chat-list-a").removeClass("chat-open");
            $("a#chat-list-" + this.var.selected.id).addClass("chat-open");
            $(".chat-open").focus();
        }
    }
    setChatHeader(chat_header) {
        this.var.selected = chat_header;

        this.setChatSelected();

        $("button.chat-send").prop("disabled", false);
        // if(chat_header.channel_id === 7) {
        //     $("#header-datas h5").text(chat_header.chat_from_name ?? "");
        // } else {
        //     $("#header-datas h5").text(channel_user.channel_user_name ?? "");
        // }
        $("#header-datas h5").text(chat_header.channel_user_name ?? "");
        $("#header-datas img").attr(
            "src",
            chat_header.channel_user_photo !== null && chat_header.channel_user_photo !== "" ? chat_header.channel_user_photo : "/assets/images/users/Profile.png"
        );
        $(".chat-conversation").attr("data-chat-id", chat_header.chat_id);
        $(".chat-conversation").attr("data-header-id", chat_header.id);
        $("form#reply-msg").attr("data-chat-id", chat_header.chat_id);
        $("form#reply-msg").attr("data-header-id", chat_header.id);
        $('.chat-conversation[data-chat-id="'+chat_header.chat_id+'"] ul').html(
            ""
        );

        // console.log("condition", chat_header.handle_by, currentAgent.id, (chat_header.handle_by != null && chat_header.handle_by != (currentAgent.id ?? "")) ||
        // chat_header.status == "close");
        if (
            (chat_header.handle_by != null && chat_header.handle_by != (currentAgent.id ?? "")) ||
            chat_header.status == "close"
        ) {
            $("#chat-input-section").hide();
            $("button.chat-end").prop("disabled", true);
            $("#nav-item-end-chat").addClass("d-none");
            $("#nav-item-end-chat-ticket").addClass("d-none");
        } else {
            $("#chat-input-section").show();
            $("button.chat-end").prop("disabled", false);
            $("#nav-item-end-chat").removeClass("d-none");
            $("#nav-item-end-chat-ticket").removeClass("d-none");
        }

    }

    async fetchListHeaders(response, selected = null, append_chat = false, targetList = this.el.chatList) {
        let headers_ui = ``;

        let response_data = await response.sort((a, b) => {
            return new Date(b.last_message_at || b.updated_at) - new Date(a.last_message_at || a.updated_at);
        });
        response_data.forEach((header) => {
            headers_ui += this.itemListHeader(header);
        });

        const listElement = $(targetList);

        // <div class="font-size-11">${new TimeAgo('id').format(moment(header.latest_message.created_at).toDate())}</div>
        if (!append_chat) {
            listElement.html('');
            listElement.html(headers_ui);
        } else {
            listElement.append(headers_ui);
        }

        listElement.find("a.chat-list-a").removeClass("chat-open");
        this.setChatSelected();
        console.log("selected", this.var.selected);
        $(this.el.loader).html("");
    }

    async loadChatHistories(chat_header) {
        this.var.chatHeaderHistories = await this.lib.http.send("/chat/v3/get-chat-header-histories?channel_user_id=" + chat_header.channel_user_id);
        $('#json_histories').val(JSON.stringify(this.var.chatHeaderHistories));

        $('#placeHistories .chat-list').html('');
        this.var.chatHeaderHistories.forEach((history) => {
            let after = history.last_message_at != null?  moment(history.last_message_at).format("DD MMM YYYY") : moment(history.updated_at).format("DD MMM YYYY");
            $('#placeHistories .chat-list').append(`<li><a href="javascript:openChatHeaderHistory('${history.id}')"><h5 class="font-size-14 mb-0">${moment(history.created_at).format("DD MMM YYYY")} - ${after ?? "NaN"}</h5></a></li>`)
        });
    }

    itemListHeader(header) {
        // //console.log(moment(header.latest_message.created_at).year() )
        // let time_ago = moment(header.latest_message.created_at)
        // //console.log(header.latest_message.created_at)
        console.log("ini header item list" + header.unseen_count)
        let status = "read";
        // if (typeof header.latest_message?.has_seen == 0) {
        // if (
        //     typeof header.latest_message == "undefined" ||
        //     (header.latest_message?.has_seen ?? 0) == 0
        // ) {
        //     status = "unread";
        // }
        if (header?.last_message_has_seen == 0) {
            status = "unread";
        }

        let chat_name = header.channel_source_name ?? "";

        let status_badge = "";
        // if(header.handle_by == null && header.started_at == null) {
        //     status_badge = `style="background-color: #f34e4e;"`;
        // } else {
        //     if(header.started_at != null) {
        //         status_badge = `style="background-color: #348feb;"`;
        //     }
        // }
        if (header.status == "close") {
            // Chat selesai dan ditutup
            status_badge = `style="background-color: #51d28c;"`; // 🟢 Hijau

        } else if (header.handle_by == null) {
            // Chat belum diambil oleh siapa pun
            status_badge = `style="background-color: #f34e4e;"`; // 🔴 Merah

        } else if (header.handle_by != null && header.started_at == null) {
            // Chat sudah diambil tetapi belum dimulai
            status_badge = `style="background-color: #ffc107;"`; // 🟡 Kuning

        } else if (header.handle_by != null && header.started_at != null) {
            // Chat sedang ditangani
            status_badge = `style="background-color: #348feb;"`; // 🔵 Biru

        }  else {
            // fallback jika semua kondisi tidak terpenuhi
            status_badge = `style="background-color: #ffffff;"`; // ⚪ Putih
        }

        let unread_badge = "";
        if (header?.unseen_count > 0) {
            unread_badge = `<span class="badge bg-success rounded-pill">${header?.unseen_count}</span>`;
        }

        return `<li class="${status} border-0 bg-gray-800 rounded" id="list-header-${header.id}">
                    <a href="javascript:openChatHeader('${header.id}')" class="chat-list-a border-0" id="chat-list-${header.id}">
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0 user-img online align-self-center me-3">
                                <div class="avatar-sm align-self-center">
                                    <img class="avatar-title rounded-circle bg-soft-primary" src="${header.channel_icon ?? ""}">
                                </div>
                                <span class="user-status" ${status_badge}></span>
                            </div>

                            <div class="flex-grow-1 overflow-hidden">
                                <h5 class="text-truncate text-white font-size-14 mb-1">[${chat_name}] ${ header.channel_user_name ?? "" }</h5>
                                <p class="text-truncate text-gray-300 mb-0">${(header.last_message_by == 'user') ? header.channel_user_name : ((header.channel_id == 2)? header.channel_account_name : header.channel_page_name)} : ${header.last_message ?? ""}</p>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="font-size-11">${moment(header.last_message_at ?? header.updated_at).fromNow()}</div>
                                <p class="text-truncate mb-0">${unread_badge}</p>
                            </div>
                        </div>
                    </a>
                </li>`;
    }
}

class BotSession {
    constructor() {
        this.lib = {
            indexDB: new IndexDB(currentAgent.id),
            http: new Http(),
        };

        this.var = {
            selected: null,
            botSessionHistories: [],
            sessions: [], // simpan list session
        };

        this.el = {
            loader: "#loader", // loader global di sidebar
            sessionList: "#session-list",
            sessionListOpen: "#session-list .session-open",
            sessionBody: ".session-body",
            sessionHeader: "#header-datas",
            sessionFooter: ".session-footer",
            sessionVars: ".session-vars",
            sessionCount: "#session-count",
            historyList: "#placeSessionHistories",
            sessionItem: "#session-list-:bot_session_id",
        };
    }

    elSessionItem(selected) {
        return $(this.el.sessionList).find(`#list-session-${selected}`);
    }

    async elSessionItemRemove(id) {
        await $(this.el.sessionList).find(`#list-session-${id}`).remove();
    }

    async elSessionItemMoveToTop(id) {
        let session = INSTANCE.storage.botSessions.find((session) => session.id == id);
        if (session == null) return;

        let sessions_ui = this.itemListSession(session);
        if (this.elSessionItem(id).length > 0) await this.elSessionItem(id).remove();
        await $(this.el.sessionList).prepend(sessions_ui);
    }

    setSessionSelected() {
        if (this.var.selected) {
            $(this.el.sessionHeader).attr("data-bot-session-id", this.var.selected.id);

            $("a.session-list-a").removeClass("session-open");
            $("a#session-list-" + this.var.selected.id).addClass("session-open");
            $(".session-open").focus();
        }
    }

    setSessionHeader(bot_session) {
        this.var.selected = bot_session;

        this.setSessionSelected();

        const displayName = bot_session.channel_user_name || bot_session.user_unique_id || `Session #${bot_session.id}`;
        let photo = '/assets/images/users/Profile.png';
        if (bot_session.channel_user_channel_id && bot_session.channel_icon) {
            if (bot_session.channel_icon.includes('/')) {
                photo = bot_session.channel_icon;
            } else {
                photo = '/assets/images/icons/' + bot_session.channel_icon;
            }
        } else if (bot_session.channel_user_photo && bot_session.channel_user_photo !== '') {
            photo = bot_session.channel_user_photo;
        }

        $("#header-datas h5").text(displayName);
        $("#header-datas img").attr("src", photo);
        $(".session-conversation").attr("data-session-id", bot_session.id);
        $(".session-conversation").attr("data-bot-id", bot_session.bot_id);
        $("form#session-vars").attr("data-session-id", bot_session.id);
        $('.session-conversation[data-session-id="' + bot_session.id + '"] ul').html("");

        if (bot_session.vars) {
            $(this.el.sessionVars).html(JSON.stringify(bot_session.vars, null, 2));
        } else {
            $(this.el.sessionVars).html("No variables available");
        }
    }

    async fetchListSessions(response, selected = null, append_session = false) {
        let sessions_ui = ``;

        let response_data = await response.sort((a, b) => {
            return new Date(b.updated_at) - new Date(a.updated_at);
        });
        response_data.forEach((session) => {
            sessions_ui += this.itemListSession(session);
        });

        if (!append_session) {
            $(this.el.sessionList).html('');
            $(this.el.sessionList).html(sessions_ui);
        } else {
            $(this.el.sessionList).append(sessions_ui);
        }

        $(this.el.sessionList).find("a.session-list-a").removeClass("session-open");
        this.setSessionSelected();
        $(this.el.loader).html("");
    }

    async loadSessionHistories(bot_session) {
        this.var.botSessionHistories = await this.lib.http.send(
            "/bot/v1/get-bot-session-histories?channel_user_id=" + bot_session.channel_user_id
        );
        $('#json_histories').val(JSON.stringify(this.var.botSessionHistories));

        $('#placeSessionHistories .session-list').html('');
        this.var.botSessionHistories.forEach((history) => {
            let after = history.updated_at
                ? moment(history.updated_at).format("DD MMM YYYY")
                : "N/A";
            $('#placeSessionHistories .session-list').append(
                `<li><a href="javascript:openBotSessionHistory('${history.id}')"><h5 class="font-size-14 mb-0">${moment(history.created_at).format("DD MMM YYYY")} - ${after}</h5></a></li>`
            );
        });
    }

    itemListSession(session) {
        let status = session.bot_interaction_id ? "active" : "inactive";
        let status_badge = session.bot_interaction_id
            ? `style=\"background-color: #51d28c;\"`
            : `style=\"background-color: #f34e4e;\"`;

        const displayName = session.channel_user_name || session.user_unique_id || `Session #${session.id}`;
        let photo = '/assets/images/users/Profile.png';
        if (session.channel_user_channel_id && session.channel_icon) {
            if (session.channel_icon.includes('/')) {
                photo = session.channel_icon;
            } else {
                photo = '/assets/images/icons/' + session.channel_icon;
            }
        } else if (session.channel_user_photo && session.channel_user_photo !== '') {
            photo = session.channel_user_photo;
        }

        return `<li class="session-list-item ${status} border-0 bg-gray-800 rounded mb-1" id="list-session-${session.id}" style="transition:background 0.2s;">
                    <a href="javascript:openBotSession('${session.id}')" class="session-list-a border-0 d-flex align-items-center p-2 rounded" id="session-list-${session.id}" data-session-id="${session.id}" style="text-decoration:none;">
                        <div class="flex-shrink-0 user-img online align-self-center me-3">
                            <div class="avatar-sm align-self-center">
                                <img class="avatar-title rounded-circle bg-soft-primary" src="${photo}">
                            </div>
                            <span class="user-status" ${status_badge}></span>
                        </div>
                        <div class="flex-grow-1 overflow-hidden">
                            <h5 class="text-truncate text-white font-size-14 mb-1">${displayName}</h5>
                            <p class="text-truncate text-gray-300 mb-0">Channel User ID: ${session.channel_user_id}</p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="font-size-11">${moment(session.updated_at ?? session.created_at).fromNow()}</div>
                        </div>
                    </a>
                </li>`;
    }

    /**
     * Ambil list bot_sessions dari endpoint baru dan render ke UI
     */
    async fetchListSessionsV3() {
        try {
            $(this.el.loader).html("Loading...");
            const response = await this.lib.http.send('/chat/v3/get-bot-sessions-v3', [], {method: 'GET'});
            if (response.status === "success") {
                this.var.sessions = response.data;
                this.renderSessionListV3();
            } else {
                $(this.el.sessionList).html("<div class='text-danger'>Gagal memuat sesi bot.</div>");
            }
            $(this.el.loader).html("");
        } catch (error) {
            $(this.el.loader).html("");
            $(this.el.sessionList).html("<div class='text-danger'>Terjadi kesalahan saat memuat sesi bot.</div>");
        }
    }

    /**
     * Render daftar sesi bot ke UI (mirip chatlist)
     */
    renderSessionListV3() {
        let html = "";
        this.var.sessions.forEach(session => {
            html += this.itemListSession(session);
        });
        $(this.el.sessionList).html(html);

        // Event listener klik pada session
        $(this.el.sessionList).find("a.session-list-a").on("click", (e) => {
            const sessionId = $(e.currentTarget).data("session-id");
            this.openSessionV3(sessionId);
        });
    }

    /**
     * Pilih session dan tampilkan detail (bisa dikembangkan untuk load pesan dsb)
     */
    openSessionV3(sessionId) {
        this.var.selected = this.var.sessions.find(s => s.id == sessionId);
        this.setSessionHeader(this.var.selected);
        // TODO: tambahkan load pesan jika perlu
    }
}

// Fungsi global agar bisa dipanggil dari HTML
window.openBotSession = function(sessionId) {
    const session = botSessionInstance.var.sessions.find(s => s.id == sessionId);
    if (session) {
        botSessionInstance.openSessionV3(sessionId);
        window.chatBotMessageInstance.loadBotMessages(session);
    }
};
