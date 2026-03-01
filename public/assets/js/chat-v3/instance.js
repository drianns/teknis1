class Instance {
    constructor() {
        this.lib = {
            chat: new Chat(),
            message: new Message(),
            indexDB: new IndexDB(currentAgent.id),
            http: new Http(),
            ticket: new Ticket(),
            botSession: window.botSessionInstance || new BotSession(),
        };

        this.var = {
            currentTab: 'served',
            chatResolvedLoaded: false,
        }

        this.storage = {
            chatHeaders: [],
            chatHeaderHistories: [],
            messages: {
                pending: [],
            },
            channelUsers: [],
        };
    }

    async init() {
        // await this.idb_chatHeaders_get();
        await this.http_chatHeaders_get('/chat/v3/get-chat-headers');

        await this.init_intvalSync();
    }

    storage_chatServedCount() {
        let count = this.storage.chatHeaders.filter(
            (header) => header.status == "open" && header.handle_by !== null
        );
        $(this.lib.chat.el.servedCount).html(count.length);
    }

    storage_chatResolvedCount() {
        let count = this.storage.chatHeaders.filter(
            (header) => header.status == "close"
        );
        $(this.lib.chat.el.resolvedCount).html(count.length);
    }

    async idb_chatHeaders_get() {
        let chatHeaders = await this.lib.indexDB.get("chat_header");
        this.storage.chatHeaders = chatHeaders === null ? [] : chatHeaders;
    }

    async idb_chatHeaders_sync() {
        await this.lib.indexDB.set("chat_header", this.storage.chatHeaders);
    }

    async idb_channelUser_sync() {
        await this.lib.indexDB.set("channel_user", this.storage.channelUsers);
    }

    async idb_channelUser_set(channel_user_id, datas = null) {
        let index = this.storage.channelUsers.findIndex(
            (channel_user) => channel_user.id == channel_user_id
        );

        if (datas !== null) {
            if (index < 0) {
                this.storage.channelUsers.push(datas);
            } else {
                this.storage.channelUsers[index] = datas;
            }
        } else {
            if (index < 0) {
                this.storage.channelUsers.push({
                    id: channel_user_id,
                    name: channel_user_id,
                });
            }
        }
        await this.idb_channelUser_sync();

        return this.storage.channelUsers.find(
            (channel_user) => channel_user.id == channel_user_id
        );
    }

    async init_intvalSync() {
        await this.idb_chatHeaders_sync();
        await this.idb_channelUser_sync();

        this.storage_chatServedCount();
        this.storage_chatResolvedCount();

        // await this.ui_chatHeader_fetch();
        setTimeout(async () => {
            await this.init_intvalSync();
        }, 5000);
    }

    async http_chatHeaders_get(url = listUrls.syncHeader) {
        let chatHeaders = await this.lib.http.send(url);
        console.log("http_chatHeaders_get", chatHeaders);

        let last_chatHeader_id = [];
        await chatHeaders.data.forEach((chatHeader) => {
            last_chatHeader_id.push(chatHeader.id);

            let old_chatHeader = this.storage.chatHeaders.findIndex(
                (chat) => chat.id == chatHeader.id
            );
            if (old_chatHeader >= 0) {
                this.storage.chatHeaders[old_chatHeader] = chatHeader;
            } else {
                this.storage.chatHeaders.push(chatHeader);
            }
        });

        // remove old chat header from storage
        // this.storage.chatHeaders = this.storage.chatHeaders.filter(
        //     (chat) => chat.status === "open" && last_chatHeader_id.includes(chat.id)
        // );

        // this.storage.chatHeaders = chatHeaders === null ? [] : chatHeaders;

        if (chatHeaders.next_page_url != null) {
            await this.http_chatHeaders_get(chatHeaders.next_page_url);
        }
    }

    async http_chatHeaderResolveds_get(url = listUrls.syncHeader) {
        let chatHeaders = await this.lib.http.send(url);
        console.log("http_chatHeaders_get", chatHeaders);

        let last_chatHeader_id = [];
        await chatHeaders.data.forEach((chatHeader) => {
            last_chatHeader_id.push(chatHeader.id);

            let old_chatHeader = this.storage.chatHeaders.findIndex(
                (chat) => chat.id == chatHeader.id
            );
            if (old_chatHeader >= 0) {
                this.storage.chatHeaders[old_chatHeader] = chatHeader;
            } else {
                this.storage.chatHeaders.push(chatHeader);
            }
        });

        // remove old chat header from storage
        // this.storage.chatHeaders = this.storage.chatHeaders.filter(
        //     (chat) => chat.status === "close" && last_chatHeader_id.includes(chat.id)
        // );

        // this.storage.chatHeaders = chatHeaders === null ? [] : chatHeaders;

        if (chatHeaders.next_page_url != null) {
            await this.http_chatHeaders_get(chatHeaders.next_page_url);
        }
    }

    async getStatusByTab(tab) {
        switch (tab) {
            case "served":
                return "open";
            case "resolved":
                return "close";
            default:
                return "open";
        }
    }

    async ui_chatHeader_fetch() {
        let status = await this.getStatusByTab(this.var.currentTab);
        console.log(status);
        let chatHeaders = this.storage.chatHeaders.filter(
            (header) => header.status == status
        );

        let targetList;
        if (this.var.currentTab === 'resolved') {
            targetList = '#chat-list-resolved';
        } else {
            targetList = '#chat-list';
        }

        console.log("chatHeaders", chatHeaders);
        await this.lib.chat.fetchListHeaders(chatHeaders, null, false, targetList);
    }
}
