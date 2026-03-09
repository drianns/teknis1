class Message {
    constructor() {
        this.chat = {
            header: null,
        };

        this.lib = {
            indexDB: new IndexDB(currentAgent.id),
            http: new Http(),
        };

        this.var = {
            last_date: null,
        };

        this.el = {
            loader: ".user-chat #loader",
            chatHeader: ".user-chat #header-datas",
            chatBody: ".user-chat .chat-conversation",
            // chatMessages: ".user-chat .chat-conversation .simplebar-wrapper .simplebar-content ul",
            chatMessages: new SimpleBar(
                document.querySelector(".chat-conversation")
            ),
        };
    }

    async setChatHeader(chat_header) {
        this.chat.header = chat_header;
        this.var.last_date = null;
    }

    async createChatBody(chat_header, message, sender_type, created_at) {
        return {
            id: new Date().getTime() + Math.floor(Math.random() * 1000),
            chat_header_id: chat_header.id,
            sender_id:
                sender_type == "user"
                    ? chat_header.channel_user_id
                    : chat_header.channel_page_id,
            sender_type: sender_type,
            user_handle_id: null,
            message: message,
            has_attachment: 0,
            attachment: null,
            attachment_info: null,
            created_at: moment(created_at).format("YYYY-MM-DD HH:mm:ss"),
            updated_at: moment(created_at).format("YYYY-MM-DD HH:mm:ss"),
            message_id: null,
            has_seen: 1,
            reply_time_lapsed: 0,
            sender_name:
                sender_type == "user"
                    ? chat_header.channel_user_name
                    : chat_header.channel_page_name,
        };
    }

    async loadChatMessages(chat_header, create_before=true) {
        let chatMessages = await this.httpGetMessages(chat_header);
        await this.fetchMessages(chatMessages, create_before);
    }
    async httpGetMessages(chat_header) {
        return await this.lib.http.send(
            `/chat/v3/get-chat-bodies?chat_header_id=${chat_header.id}`
        );
    }

    async fetchMessages(chatMessages, create_before=true) {
        // $(this.el.chatMessages).html("");
        var contentElement = this.el.chatMessages.getContentElement();
        if(create_before) contentElement.innerHTML = "";

        let chat_messages = await chatMessages.sort(function (a, b) {
            return a.id - b.id;
        });
        await chat_messages.forEach(async (message, key) => {
            if (
                (await $(contentElement).find(`#message-${message.id}`).length) == 0
            ) {
                // await $(this.el.chatMessages).append(
                //     await this.itemMessage(message)
        // $(this.el.chatMessages).html(messages_ui);

                let itemMessage = await this.itemMessage(message);
                contentElement.innerHTML += itemMessage;

                // If response is not ok, the image is not valid
                // console.log("fetchMessages", contentElement.innerHTML);

                // Cari semua gambar yang belum dimuat
                var images = contentElement.querySelectorAll("img");
                // console.log("images", images);
                var loadedCount = 0;

                images.forEach(function (image) {
                    console.log(image, loadedCount);
                    if (image.complete) {
                        // Jika gambar sudah ter-load, tambahkan ke counter
                        loadedCount++;
                    } else {
                        // Jika gambar belum selesai di-load, tambahkan event listener
                        image.addEventListener("load", async function () {
                            loadedCount++;
            // Check if the content type is an image
                            if (loadedCount === images.length) {
                                // await Message.scrollSmoothlyToBottom(null);

                                let chatContainer = document.querySelector('.chat-conversation').SimpleBar.getScrollElement();

            // Any error (e.g., network issues) means the image is not valid
                                chatContainer.scrollTo({
                                    top: chatContainer.scrollHeight,
                                    behavior: "smooth", // Menambahkan scroll halus
                                });
        }
                        });
    }
                });

    // async fetchMessages(chatMessages) {
                if (images.length === 0) {
                    await Message.scrollSmoothlyToBottom(null);
                }
            }
        });
        // $(this.el.chatMessages).html(messages_ui);
    }
    // async fetchMessages(chatMessages) {    //     var beforeMe = null;
    //     let chat_messages =  await chatMessages.sort(function(a, b){return a.id - b.id});
    //     await chat_messages.forEach(async (message, key) => {
    //         console.log(message.id, beforeMe);
    //         let item = await this.itemMessage(message);
    //         console.log(key, message, item);
    //         if(await $(this.el.chatMessages).find(`#message-${message.id}`).length == 0 ) {
    //             console.log(1)
    //             console.log(message.id, message.updated_at, beforeMe);
    //             if(key > 0 && beforeMe !== null) {
    //                 console.log(2)
    //                 // let _beforeMe = chatMessages.sort(function(a, b){return a.id - b.id})[key-1];
    //                 // let _beforeMe = chatMessages.sort(function(a, b){return a.id - b.id})[key-1];

    //                 if(message.updated_at.slice(0, 10) != beforeMe.updated_at.slice(0, 10)) {
    //                     console.log(3)
    //                     if($(`li#separator-${message.updated_at.slice(0, 10)}`).length > 0) {
    //                         console.log(4)
    //                         item = await this.itemMessage(message, false);
    //                         await $(item).insertAfter($(`li#separator-${message.updated_at.slice(0, 10)}`));
    //                     } else {
    //                         console.log(5, message.id, beforeMe.id)
    //                         await $(this.el.chatMessages).append(await this.itemMessage(message));
    //                         // await $(item).insertAfter($(`li#message-${beforeMe.id}`));
    //                     }
    //                     beforeMe = message;
    //                 } else {
    //                     console.log(6)
    //                     await $(item).insertAfter($(`li#message-${beforeMe.id}`));
    //                     await $(this.el.chatMessages).append(await this.itemMessage(message));

    //                     beforeMe = message;
    //                 }
    //             } else {
    //                 console.log(7)
    //                 await $(this.el.chatMessages).append(await this.itemMessage(message));
    //                 beforeMe = message;
    //             }
    //         } else {
    //             console.log(8)
    //         }

    //     });
    //     // $(this.el.chatMessages).html(messages_ui);
    // }

    // this message from me
    async itemMessage(message, with_separator = true) {
        let message_from = message.sender_name;
        let agent_from = message.agent_name ? message.agent_name : message_from;
        let message_pict =
            message.sender_type == "user"
                ? this.chat.header.channel_user_photo !== null && this.chat.header.channel_user_photo !== "" ? this.chat.header.channel_user_photo : "/assets/images/users/Profile.png"
                : "/assets/images/icons/agent.png";
        let message_time = moment(message.created_at).format("HH:mm");
        let message_content = await this.itemMessageContent(message);
        let message_attachment =
            (await this.itemMessageContentAttachment(message)) ?? "";
        let separator = with_separator
            ? await this.itemMessageContentDateSeparator(message.created_at)
            : "";
        let extra_icon = ``;
        if (message.is_bot) {
            extra_icon = `<i class="fa fa-robot"></i>`;
        }
        return (
            separator +
            `<ul class="space-y-4">
    <li id="message-${message.id}" class="flex ${
            message.sender_type == 'user' ? 'justify-start' : 'justify-end'
        } ${message.previous_sender !== message.sender_type ? 'mt-4' : 'mb-4'}">
        <div class="flex items-start space-x-3 max-w-[80%] md:max-w-[60%] lg:max-w-[50%]">
            <!-- Avatar -->
            <img src="${message_pict}" alt="avatar" class="w-10 h-10 rounded-full ml-2 object-cover ${
                message.sender_type == 'user' ? '' : 'order-2'
            }">

            <!-- Chat Bubble -->
            <div class="mb-2 p-3 rounded-lg ${
                message.sender_type == 'user'
                    ? 'bg-gray-900 text-gray-200'
                    : 'bg-gray-700 text-gray-200'
            } break-words" style="word-break: break-word;">
                <h5 class="text-sm font-semibold flex items-center space-x-1">
                    <span class="text-green-500">${message.sender_type == 'user' ? message_from : agent_from}</span>
                    ${extra_icon}
                    <span class="text-xs text-green-600 whitespace-nowrap">${message_time}</span>
                </h5>
                <div>${message_content}</div>
                ${message_attachment ? `<div class="mt-2">${message_attachment}</div>` : ''}
            </div>
        </div>
    </li>
</ul>`
        );
    }

    async itemMessageContent(message) {
        return this.generate_message(message.message);
    }

    async itemMessageContentDateSeparator(date) {
        let msg_date = await moment(date).format("DD MMM YYYY");
        if (msg_date !== this.var.last_date) {
            console.log(this.var.last_date, msg_date);

            this.var.last_date = msg_date;
            return `<div class="text-center mb-3">
                        <span class="badge bg-danger text-white p-2">${msg_date}</span>
                    </div>`;
        }

        return "";  // Tidak ada pemisah jika tanggalnya sama
    }


    async itemMessageContentAttachment(message) {
        let attachment_datas = message.attachment_info;
        let msg = ``;

        attachment_datas =
            typeof attachment_datas == "string"
                ? JSON.parse(attachment_datas)
                : attachment_datas;
        console.log(typeof attachment_datas);
        if(typeof attachment_datas == "object") {
            if(attachment_datas != null) {
                attachment_datas.forEach((attachment_info) => {
                    msg += this.attachment_file_message(attachment_info);
                });
            }
        }

        return msg;
    }

    // async scrollSmoothlyToBottom(id) {
    //     const element = $(`${id}`);
    //     element.animate(
    //         {
    //             scrollTop: element.prop("scrollHeight"),
    //         },
    //         500
    //     );
    //     //console.log(element.prop("scrollHeight"));
    // };
    // async scrollSmoothlyToBottom(id) {
    //     var chatContainer = document.querySelector(id);
    //     chatContainer.scrollTop = chatContainer.scrollHeight;
    // }    async scrollSmoothlyToBottom(id) {

    async scrollSmoothlyToBottom(id) {
        //console.log(element.prop("scrollHeight"));
        var chatContainer = this.el.chatMessages.getScrollElement();

        // Scroll ke bagian paling bawah dengan smooth
        chatContainer.scrollTo({
            top: chatContainer.scrollHeight,
            behavior: "smooth", // Menambahkan scroll halus
        });
    }
    Linkify(inputText) {
        let replacedText = inputText;

        // Google Maps URLs with embedded Leaflet map (process this first)
        const replacePattern4 = /(https?:\/\/maps\.google\.com\/maps\?[^"\s]+)/gim;
        replacedText = replacedText.replace(
            replacePattern4,
            function (match, url) {
                try {
                    const urlParams = new URL(url);
                    const coords = urlParams.searchParams.get('q');
                    if (coords) {
                        const coordParts = coords.split(',');
                        if (
                            coordParts.length === 2 &&
                            !isNaN(coordParts[0]) &&
                            !isNaN(coordParts[1])
                        ) {
                            const [lat, lng] = coordParts;
                            const mapId = `map_${Math.random().toString(36).substr(2, 9)}`;
                            setTimeout(() => {
                                const map = L.map(mapId).setView([lat, lng], 17);
                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '&copy; OpenStreetMap contributors'
                                }).addTo(map);
                                L.marker([lat, lng]).addTo(map);
                            }, 100);

                            return `<div class="mt-2">
                                <div id="${mapId}" style="width: 250px; height: 250px; border-radius: 8px; overflow: hidden;"></div>
                                <a href="${url}" target="_blank" rel="noopener noreferrer" class="text-blue-500 underline mt-2 inline-block">Lihat di Google Maps</a>
                            </div>`;
                        }
                    }
                } catch (e) {
                    console.warn('Invalid map URL:', e);
                }
                return `<a href="${url}" target="_blank" rel="noopener noreferrer">${url}</a>`;
            }
        );

        // URLs starting with http, https, or ftp (but exclude already processed Google Maps URLs)
        const replacePattern1 = /(\b(https?|ftp):\/\/(?!maps\.google\.com\/maps)[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim;
        replacedText = replacedText.replace(
            replacePattern1,
            '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>'
        );

        // URLs starting with www.
        const replacePattern2 = /(^|[^\/])(www\.[\S]+(\b|$))/gim;
        replacedText = replacedText.replace(
            replacePattern2,
            '$1<a href="http://$2" target="_blank" rel="noopener noreferrer">$2</a>'
        );

        // Email addresses
        const replacePattern3 = /([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/gim;
        replacedText = replacedText.replace(
            replacePattern3,
            '<a href="mailto:$1">$1</a>'
        );

        return replacedText;
    }


    attachment_file_message(attachment_info) {
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
                                                <p class="text-muted text-truncate mb-0"><a href="${this.image_url(
                                                    attachment_info.payload
                                                        .local_url
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
                                                <p class="text-muted text-truncate mb-0"><a href="${this.image_url(
                                                    attachment_info.payload
                                                        .local_url
                                                )}" target="_blank">Download</a></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>`;
        } else if (attachment_info.type == "sticker") {
            attachments += `<img class="img-fluid" src="${this.image_url(
                attachment_info.payload.local_url
            )}">`;
        } else if (attachment_info.type == "image") {
            attachments += `<img class="img-fluid" src="${this.image_url(
                attachment_info.payload.local_url
            )}">`;
        } else if (attachment_info.type == "share") {
            attachments += `<img class="img-fluid" src="${this.image_url(
                attachment_info.payload.local_url
            )}">`;
        } else if (attachment_info.type == "video") {
            attachments += `<video
        id="msg-${new Date().getMilliseconds()}"
        class="video-js"
        controls
        preload="auto"
        width="300"
        height="164"
        data-setup="{}"
      >
        <source src="${this.image_url(
            attachment_info.payload.local_url
        )}" type="video/mp4" />
      </video>`;
        }
        return attachments;
    }



    image_url(url) {
        return this.isValidUrl(url) ? url : listUrls.baseUrl + url;
    }

    isValidUrl(urlString) {
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
    }

    isJSON(str) {
        try {
            let check = JSON.parse(str);
            return check && typeof check == "object";
        } catch (e) {
            return false;
        }
    }

    nl2br(str, is_xhtml) {
        if (typeof str === "undefined" || str === null) {
            return "";
        }
        var breakTag =
            is_xhtml || typeof is_xhtml === "undefined" ? "<br />" : "<br>";
        return (str + "").replace(
            /([^>\r\n]?)(\r\n|\n\r|\r|\n)/g,
            "$1" + breakTag + "$2"
        );
    }

    generate_message(message) {
        if (this.isJSON(message)) {
            let json = JSON.parse(message);
            let msg = `<p>${this.nl2br(json.text) ?? ""}</p>`;

            if (typeof json.buttons !== "undefined") {
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
            }

            return msg;
        } else {
            return this.Linkify(message ?? "");
        }
    }
}
