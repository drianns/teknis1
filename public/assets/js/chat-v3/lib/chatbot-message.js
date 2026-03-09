class ChatBotMessage {
    constructor() {
        this.session = {
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
            loader: "#loader",
            chatHeader: "#header-datas",
            chatBody: ".session-conversation",
            chatMessages: null,
        };
    }

    async setSessionHeader(session) {
        this.session.header = session;
        this.var.last_date = null;
    }

    async loadBotMessages(session) {
        this.setSessionHeader(session);
        let container = document.querySelector(this.el.chatBody);
        if (!container) return;
        // Hapus button/footer lama jika ada
        const oldFooter = document.getElementById('move-to-chat-footer');
        if (oldFooter) oldFooter.remove();
        // Pastikan parent .session-conversation position: relative
        container.style.position = 'relative';
        // SimpleBar hanya untuk area pesan, button di luar scrollable
        if (!container.SimpleBar) {
            this.el.chatMessages = new SimpleBar(container, {autoHide: false, scrollbarMinSize: 40});
        } else {
            this.el.chatMessages = container.SimpleBar;
        }
        let contentElement = this.el.chatMessages.getContentElement();
        contentElement.innerHTML = "";
        $(this.el.loader).show();
        try {
            const response = await this.lib.http.send(`/chat/v3/get-bot-interactions/${session.id}`, [], {method: 'GET'});
            if (response.status === "success") {
                let messages = response.data;
                let lastDate = null;
                for (const message of messages) {
                    // Tanggal hanya muncul sekali per hari
                    let with_separator = false;
                    const msgDate = moment(message.created_at).format('YYYY-MM-DD');
                    if (lastDate !== msgDate) {
                        with_separator = true;
                        lastDate = msgDate;
                    }
                    const html = await this.itemBotMessage(message, session, with_separator);
                    contentElement.insertAdjacentHTML('beforeend', html);
                }
                this.scrollSmoothlyToBottom();
            } else {
                contentElement.innerHTML = `<div class='text-danger'>Gagal memuat pesan bot.</div>`;
            }
        } catch (e) {
            contentElement.innerHTML = `<div class='text-danger'>Terjadi error: ${e.message}</div>`;
        }
        $(this.el.loader).hide();
        // Tambahkan footer di bawah area pesan (di luar scroll)
        let footer = document.getElementById('move-to-chat-footer');
        if (footer) footer.remove();
        footer = document.createElement('div');
        footer.id = 'move-to-chat-footer';
        footer.className = 'alert alert-info text-center';
        footer.innerHTML = `
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <p class="mb-0 font-poppins">
                        <b>Peringatan:</b>       Tekan tombol untuk assign chat
                    </p>
                </div>
                <button id="move-to-chat-btn" class="btn btn-success text-white rounded-lg font-poppins">
                    Pindahkan ke Chat Served
                </button>
            </div>
        `;
        // Sisipkan ke #session-footer jika ada, jika tidak fallback ke parent lama
        let sessionFooter = document.getElementById('session-footer');
        if (sessionFooter) {
            sessionFooter.innerHTML = '';
            sessionFooter.appendChild(footer);
        } else if (container.parentNode) {
            if (container.nextSibling) {
                container.parentNode.insertBefore(footer, container.nextSibling);
            } else {
                container.parentNode.appendChild(footer);
            }
        }
        document.getElementById('move-to-chat-btn').onclick = () => {
            // Modal konfirmasi
            if (document.getElementById('modal-move-bot-to-chat')) {
                document.getElementById('modal-move-bot-to-chat').remove();
            }
            const modal = document.createElement('div');
            modal.id = 'modal-move-bot-to-chat';
            modal.style.position = 'fixed';
            modal.style.top = '0';
            modal.style.left = '0';
            modal.style.width = '100vw';
            modal.style.height = '100vh';
            modal.style.background = 'rgba(0,0,0,0.4)';
            modal.style.display = 'flex';
            modal.style.alignItems = 'center';
            modal.style.justifyContent = 'center';
            modal.style.zIndex = '9999';
            modal.innerHTML = `
                <div style="background:#222;padding:24px 32px;border-radius:8px;max-width:90vw;min-width:300px;text-align:center;">
                    <h5 class='mb-3 text-white'>Konfirmasi</h5>
                    <p class='mb-3 text-white'>Yakin ingin memindahkan seluruh pesan bot ke chat utama (Served)?</p>
                    <button id='btn-confirm-move-bot' class='btn btn-success m-1'>Ya, Pindahkan</button>
                    <button id='btn-cancel-move-bot' class='btn btn-secondary m-1'>Batal</button>
                </div>
            `;
            document.body.appendChild(modal);
            document.getElementById('btn-cancel-move-bot').onclick = () => modal.remove();
            document.getElementById('btn-confirm-move-bot').onclick = () => {
                document.getElementById('btn-confirm-move-bot').disabled = true;
                document.getElementById('btn-confirm-move-bot').innerText = 'Memproses...';
                $.post('/chat/v3/bot-to-chat', {
                    bot_session_id: session.id,
                    _token: $('meta[name="csrf-token"]').attr('content')
                }, (res) => {
                    if (res.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Chat bot berhasil dipindahkan ke chat utama.',
                            timer: 1800,
                            showConfirmButton: false
                        });
                        setTimeout(() => window.location.reload(), 1800);
                    } else {
                        alert('Gagal memindahkan: ' + (res.msg || 'Unknown error'));
                        document.getElementById('btn-confirm-move-bot').disabled = false;
                        document.getElementById('btn-confirm-move-bot').innerText = 'Ya, Pindahkan';
                    }
                });
            };
        };
    }

    async itemBotMessage(message, session, with_separator = true) {
        const isUser = message.by_user === 1;
        const message_from = isUser ? (session.channel_user_name || 'User') : 'Bot';
        const message_pict = isUser
            ? (session.channel_user_photo && session.channel_user_photo !== '' ? session.channel_user_photo : '/assets/images/users/Profile.png')
            : '/assets/images/icons/agent.png';
        const message_time = moment(message.created_at).format('HH:mm');
        const message_content = this.generate_message(message.message);
        let separator = '';
        if (with_separator && this.var.last_date !== moment(message.created_at).format('YYYY-MM-DD')) {
            this.var.last_date = moment(message.created_at).format('YYYY-MM-DD');
            separator = this.itemMessageContentDateSeparator(message.created_at);
        }
        let extra_icon = '';
        if (!isUser) {
            extra_icon = ``;
        }
        return (
            separator +
            `<ul class="space-y-4">
    <li id="message-${message.id}" class="flex ${isUser ? 'justify-start' : 'justify-end'}">
        <div class="flex items-start space-x-3 max-w-[80%] md:max-w-[60%] lg:max-w-[50%]">
            <!-- Avatar -->
            <img src="${message_pict}" alt="avatar" class="w-10 h-10 rounded-full ml-2 object-cover ${isUser ? '' : 'order-2'}">

            <!-- Chat Bubble -->
            <div class="mb-2 p-3 rounded-lg ${isUser ? 'bg-gray-900 text-gray-200' : 'bg-gray-700 text-gray-200'} break-words" style="word-break: break-word;">
                <h5 class="text-sm font-semibold flex items-center space-x-1">
                    <span class="text-green-500">${message_from}</span>
                    ${extra_icon}
                    <span class="text-xs text-green-600 whitespace-nowrap">${message_time}</span>
                </h5>
                <div>${message_content}</div>
            </div>
        </div>
    </li>
</ul>`
        );
    }

    itemMessageContentDateSeparator(date) {
        let msg_date = moment(date).format("DD MMM YYYY");
        if (msg_date !== this.var.last_date) {
            this.var.last_date = msg_date;
            return `<div class="text-center mb-2">
                        <span class="badge bg-light text-dark p-1 px-3" style="font-size:12px;opacity:0.8;">${msg_date}</span>
                    </div>`;
        }
        return "";
    }

    generate_message(message) {
        if (!message) return '';
        // Check if message is JSON (like in Message.js)
        let isJSON = false;
        try {
            const parsed = JSON.parse(message);
            isJSON = typeof parsed === 'object' && parsed !== null;
        } catch (e) {}

        if (isJSON) {
            const json = JSON.parse(message);
            let msg = `<p style="margin-bottom:8px;">${this.nl2br(json.text) ?? ''}</p>`;
            if (typeof json.buttons !== 'undefined') {
                json.buttons.forEach((button) => {
                    if (button.type === 'call') {
                        msg += `<a href="call:+62${button.phone ?? ''}" class="btn btn-outline-secondary btn-sm mb-1 w-100" style="border-radius:12px;">${button.text ?? ''}</a>`;
                    } else if (button.type === 'url') {
                        msg += `<a href="${button.url ?? ''}" class="btn btn-outline-secondary btn-sm mb-1 w-100" style="border-radius:12px;">${button.text ?? ''}</a>`;
                    } else {
                        msg += `<button type="button" class="btn btn-outline-secondary btn-sm mb-1 w-100" style="border-radius:12px;">${button.text ?? ''}</button>`;
                    }
                });
            }
            return msg;
        } else {
            // Fallback: linkify URLs
            return message.replace(/(https?:\/\/[^\s]+)/g, '<a href="$1" target="_blank">$1</a>');
        }
    }

    nl2br(str, is_xhtml) {
        if (typeof str === 'undefined' || str === null) {
            return '';
        }
        var breakTag = is_xhtml || typeof is_xhtml === 'undefined' ? '<br />' : '<br>';
        return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
    }

    scrollSmoothlyToBottom() {
        if (!this.el.chatMessages) return;
        let chatContainer = this.el.chatMessages.getScrollElement();
        chatContainer.scrollTo({top: chatContainer.scrollHeight, behavior: "smooth"});
    }
}



window.ChatBotMessage = ChatBotMessage;

// function handleBubbleButtonClick(buttonText, payload, messageId) {
//     console.log('Button clicked:', { buttonText, payload, messageId });

//     const replyArea = document.querySelector(ChatBotMessage.el.chatInput);
//     if (replyArea) {
//         replyArea.value = payload || buttonText;
//         document.querySelector("#reply-msg").dispatchEvent(new Event('submit'));
//     }
// }

// function openImagePreview(imageUrl) {
//     const modal = document.createElement('div');
//     modal.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50';
//     modal.innerHTML = `
//         <div class="relative max-w-4xl max-h-screen p-4">
//             <img src="${imageUrl}" alt="Preview" class="max-w-full max-h-full rounded-lg">
//             <button onclick="this.parentNode.parentNode.remove()" class="absolute top-2 right-2 text-white text-xl">&times;</button>
//         </div>`;
//     document.body.appendChild(modal);
// }

// // Add new function for handling bot assignment
// function assignBotToAgent(chatHeaderId) {
//     // Get list of available agents
//     $.get("/spv/user-agents", (result) => {
//         $('#table-select-agents tbody').html('');

//         let tbody = "";
//         result.forEach(row => {
//             tbody += `<tr>
//                         <td>${row.username ?? ""}</td>
//                         <td>${row.user.name ?? ""}</td>
//                         <td>
//                             <button class="btn btn-sm btn-warning m-1" type="button" 
//                                     onclick="assignAgent(${chatHeaderId}, ${row.user_id})">
//                                 Assign
//                             </button>
//                         </td>
//                     </tr>`
//         });

//         $('#table-select-agents tbody').html(tbody);
//         $('#selectAgents').modal('show');
//     });
// }

// function assignAgent(chatHeaderId, userId) {
//     $.post("/spv/assign", {
//         chat_header_id: chatHeaderId, 
//         user_id: userId,
//         "_token": $('meta[name="csrf-token"]').attr('content')
//     }, (result) => {
//         if(result.status) {
//             $('#selectAgents').modal('hide');
//             Swal.fire('Success', result.msg, 'success');
//             } else {
//             Swal.fire('Error', 'Failed to assign agent', 'error');
//         }
//     });
// } 