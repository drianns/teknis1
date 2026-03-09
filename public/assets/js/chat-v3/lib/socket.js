class ChatSocket {
    constructor() {
        this.lib = {
            http: new Http(),
            notification: new WindowNotification(),
        };

        this.socket = {
            chat_id: null,
            datas: null,
        };
    }

    async init(chat_id, datas) {
        this.socket = {
            chat_id: chat_id,
            datas: datas,
        };

        console.log("init socket", chat_id, datas);

        if (this.socket.chat_id == null) return;

        console.log(1);
        if (this.socket.chat_id == "chat-has-handled") {
            await this.event_chatHasBeenHandledOther();
            return;
        }
        console.log(2);

        if (this.socket.datas == null) return;
        console.log(3);
        if (this.socket.datas.type == "inbox") {
            console.log(4);
            if (typeof this.socket.datas.chat_header === "undefined") {
                // if(typeof this.socket.datas.chat_header !== "undefined" && this.socket.datas.chat_header.company_id != $('meta[name="company-id"]').attr('content')) {
                return;
            }
            console.log("init");
            await this.event_inboxChat();
            return;
        } else if (this.socket.datas.type == "outbox") {
            await this.event_outboxChat();
            return;
        } else if (this.socket.datas.type == "close") {
            await this.event_closeChat();
            return;
        }
        // 👇 Tambahkan case ini
        else if (this.socket.datas.type == "chat_started") {
            await this.event_chatStarted();
            return;
        }
    }

    async event_chatStarted() {
        let chat_header_id = this.socket.datas.chat_header_id;

        console.log("Received chat_started event for chat header ID:", chat_header_id);

        // Update lokal di INSTANCE.storage.chatHeaders
        let index = INSTANCE.storage.chatHeaders.findIndex(h => h.id === chat_header_id);
        if (index >= 0) {
            INSTANCE.storage.chatHeaders[index].started_at = new Date().toISOString();

            // Panggil openChatHeader untuk refresh UI
            openChatHeader(chat_header_id);
        }
    }

    addZero(i) {
        if (i < 10) {
            i = "0" + i;
        }
        return i;
    }

    sendEventToParent(data = {}) {
        if (this.isAgent()) {
            data.username = currentAgent.user_agent.username;
            window.parent.postMessage(data, "https://iframe-datakelola.test/");
            window.parent.postMessage(
                data,
                "https://iframe-datakelola.test/folder"
            );
            window.parent.postMessage(data, "https://cloud.uidesk.id/AHUOMNI");
        }

        return;
    }

    isAgent() {
        let is_agent = false;

        if (currentAgent != null && currentAgent.user_agent != null) {
            is_agent = true;
        }

        return is_agent;
    }
    async event_chatHasBeenHandledOther() {
        if (typeof INSTANCE === "undefined") return;

        let index = INSTANCE.storage.chatHeaders.findIndex(
            (chat_header) => chat_header.id == this.socket.datas.chat_header_id
        );

        if (index >= 0) {
            // remove chat header
            INSTANCE.storage.chatHeaders.splice(index, 1);

            // remove chat item
            await INSTANCE.lib.chat.elChatItemRemove(this.socket.datas.chat_header_id);
        }
    }

    async event_inboxChat() {
        console.log("event_incomingChat", this.socket.datas);
        let datas = this.socket.datas;

        let currDate = new Date();
        let date =
            currDate.getFullYear() +
            "-" +
            this.addZero(currDate.getMonth() + 1) +
            "-" +
            this.addZero(currDate.getDate());
        let time =
            currDate.getHours() +
            ":" +
            currDate.getMinutes() +
            ":" +
            currDate.getSeconds();

        this.sendEventToParent({
            chat: {
                type: datas.type,
                chat_header_id: datas.chat_header.id,
                message: datas.message,
                created_at: date + " " + time,
            },
        });

        // cegah proses jika tidak ada variabel instance
        if (typeof INSTANCE === "undefined") return;

        let chat_header_id =
            datas.chat_header_id ?? datas.chat_header?.id ?? null;
        if (chat_header_id == null) return;

        let index = INSTANCE.storage.chatHeaders.findIndex(
            (chat_header) => chat_header.id == datas.chat_header.id
        );

        console.log("index", index);

        let chatHeaderData = null;

        if (typeof index === "undefined" || index < 0) {
            if (datas.chat_header.handle_by != $('meta[name="user-id"]').attr("content")) {
                return;
            }

            let chatHeader = await INSTANCE.lib.http.send(
                "/chat/v3/get-chat-header-by-id",
                {
                    id: datas.chat_header.id,
                }
            );

            chatHeaderData = chatHeader;
            INSTANCE.storage.chatHeaders.push(chatHeader);
        } else {
            let chatHeader = INSTANCE.storage.chatHeaders[index];

            chatHeader.status = datas.chat_header.status;
            chatHeader.updated_at = datas.chat_header.updated_at;
            chatHeader.last_message = datas.chat_header.latest_message.message;
            chatHeader.last_message_at =
                datas.chat_header.latest_message.created_at;
            chatHeader.last_message_by =
                datas.chat_header.latest_message.sender_type;

            INSTANCE.storage.chatHeaders[index] = chatHeader;
            chatHeaderData = chatHeader;
        }

        if (chatHeaderData == null || chatHeaderData.length == 0) return;

        if (datas.chat_header.handle_by == $('meta[name="user-id"]').attr("content")) {
            // show notification
            let notification_title =
                "New message from " +
                (datas.chat_header?.chat_from_name ?? datas.sender?.name ?? "-");
            let notification_msg = datas.message ?? "";
            console.log("event_inboxChat", notification_title, notification_msg);
            await this.lib.notification.showNotification(
                notification_title,
                notification_msg
            );
        }
        INSTANCE.lib.chat.elChatItemMoveToTop(chatHeaderData.id);

        if (
            $(INSTANCE.lib.chat.el.chatHeader).attr("data-chat-header-id") ==
            chatHeaderData.id
        ) {
            await INSTANCE.lib.message.loadChatMessages(chatHeaderData, false);
            await INSTANCE.lib.message.scrollSmoothlyToBottom();
            // ✅ Cek ulang sesi jika channel_id adalah WhatsApp Meta (ID = 14)
            if (chatHeaderData.channel_id == 14) {
                console.log("🔄 Refresh WA META session timer for chat", chatHeaderData.id);
                await checkWhatsAppMetaSession(chatHeaderData.id);
            }
        }
    }

    async event_outboxChat() {
        console.log("event_incomingChat", this.socket.datas);
        let datas = this.socket.datas;

        let currDate = new Date();
        let date =
            currDate.getFullYear() +
            "-" +
            this.addZero(currDate.getMonth() + 1) +
            "-" +
            this.addZero(currDate.getDate());
        let time =
            currDate.getHours() +
            ":" +
            currDate.getMinutes() +
            ":" +
            currDate.getSeconds();

        // cegah proses jika tidak ada variabel instance
        if (typeof INSTANCE === "undefined") return;

        let chat_header_id =
            datas.chat_header_id ?? datas.chat_header?.id ?? null;
        if (chat_header_id == null) return;

        let index = INSTANCE.storage.chatHeaders.findIndex(
            (chat_header) => chat_header.id == datas.chat_header.id
        );

        console.log("index", index);

        let chatHeaderData = null;

        if (typeof index === "undefined" || index < 0) {
            if (datas.chat_header.handle_by != $('meta[name="user-id"]').attr("content")) {
                return;
            }

            let chatHeader = await INSTANCE.lib.http.send(
                "/chat/v3/get-chat-header-by-id",
                {
                    id: datas.chat_header.id,
                }
            );

            chatHeaderData = chatHeader;
            INSTANCE.storage.chatHeaders.push(chatHeader);
        } else {
            let chatHeader = INSTANCE.storage.chatHeaders[index];

            chatHeader.status = datas.chat_header.status;
            chatHeader.updated_at = datas.chat_header.updated_at;
            chatHeader.last_message = datas.chat_header.latest_message.message;
            chatHeader.last_message_at =
                datas.chat_header.latest_message.created_at;
            chatHeader.last_message_by =
                datas.chat_header.latest_message.sender_type;

            INSTANCE.storage.chatHeaders[index] = chatHeader;
            chatHeaderData = chatHeader;
        }

        if (chatHeaderData == null || chatHeaderData.length == 0) return;

        INSTANCE.lib.chat.elChatItemMoveToTop(chatHeaderData.id);

        if (
            $(INSTANCE.lib.chat.el.chatHeader).attr("data-chat-header-id") ==
            chatHeaderData.id
        ) {
            await INSTANCE.lib.message.loadChatMessages(chatHeaderData, false);
            await INSTANCE.lib.message.scrollSmoothlyToBottom();
        }
    }

    async event_closeChat() {
        console.log("event_closeChat", this.socket.datas);
        let datas = this.socket.datas;

        // cegah proses jika tidak ada variabel instance
        if (typeof INSTANCE === "undefined") return;

        let chat_header_id =
            datas.chat_header_id ?? datas.chat_header?.id ?? null;
        if (chat_header_id == null) return;

        let index = INSTANCE.storage.chatHeaders.findIndex(
            (chat_header) => chat_header.id == datas.chat_header.id
        );

        let chatHeaderData = null;

        if (typeof index === "undefined" || index < 0) {
            if (datas.chat_header.handle_by != $('meta[name="user-id"]').attr("content")) {
                return;
            }

            let chatHeader = await INSTANCE.lib.http.send(
                "/chat/v3/get-chat-header-by-id",
                {
                    id: datas.chat_header.id,
                }
            );

            chatHeaderData = chatHeader;
            INSTANCE.storage.chatHeaders.push(chatHeader);
        } else {
            let chatHeader = INSTANCE.storage.chatHeaders[index];

            chatHeader.status = datas.chat_header.status;
            chatHeader.updated_at = datas.chat_header.updated_at;

            INSTANCE.storage.chatHeaders[index] = chatHeader;
            chatHeaderData = chatHeader;
        }

        if (chatHeaderData == null || chatHeaderData.length == 0) return;

        INSTANCE.lib.chat.elChatItemRemove(chatHeaderData.id);
    }

    async init(chat_id, datas) {
        this.socket = {
            chat_id: chat_id,
            datas: datas,
        };

        if (this.socket.chat_id == null) return;

        if (this.socket.chat_id == "chat-has-handled") {
            await this.event_chatHasBeenHandledOther();
            return;
        }

        if (this.socket.datas == null) return;

        if (this.socket.datas.type == "inbox") {
            await this.event_inboxChat();
        } else if (this.socket.datas.type == "outbox") {
            await this.event_outboxChat();
        } else if (this.socket.datas.type == "close") {
            await this.event_closeChat();
        } else if (this.socket.datas.type == "chat_started") {
            await this.event_chatStarted();
        } else if (this.socket.datas.type == "chat_viewed") {
            await this.event_chatViewed();
        }
    }

    // Untuk triggre menghilangkan jumlah notif
    async sendEvent_ChatHasBeenViewed(chat_header_id) {
        const data = {
            type: 'chat_viewed',
            chat_header_id: chat_header_id,
            user_id: $('meta[name="user-id"]').attr("content")
        };

        // Kirim event via custom socket
        this.sendEventToParent(data);
    }
    async event_chatViewed() {
        let chat_header_id = this.socket.datas.chat_header_id;

        console.log("Received chat_viewed event for ID:", chat_header_id);

        // Hapus badge notifikasi di UI
        $(`.chat-item[data-id="${chat_header_id}"] .unread-badge`).remove();
    }
}
