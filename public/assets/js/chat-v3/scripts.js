const INSTANCE = new Instance();
// Inisialisasi BotSession instance global
window.botSessionInstance = window.botSessionInstance || new BotSession();
// Inisialisasi ChatBotMessage instance global
window.chatBotMessageInstance = window.chatBotMessageInstance || new ChatBotMessage();

$(document).ready(async () => {
    await INSTANCE.init();
    await openChatTab("served");

    $("#chat_ticket_category_tags").selectize({
        delimiter: ",",
        persist: false,
        create: function (input) {
            return {
                value: input,
                text: input,
            };
        },
    });

    $("#chat_ticket_category_tags").selectize({
        delimiter: ",",
        persist: false,
        create: function (input) {
            return {
                value: input,
                text: input,
            };
        },
    });

    // setTimeout(async () => {
    //     await INSTANCE.init_intvalSync(INSTANCE);
    // }, 5000);
});

async function openChatTab(id) {
    INSTANCE.var.currentTab = id;
    // Hide all lists and detail views first
    $('#chat-list, #chat-list-resolved, #chat-list-chatbot, #session-list').hide();
    $('.chat-conversation, .session-conversation').hide();

    if (id === "chatbot") {
        $('#session-list, .session-conversation').show();
        await botSessionInstance.fetchListSessionsV3();
        $("#chatbot-count").text(botSessionInstance.var.sessions.length);
        $("#header-datas h5").text("Chatbot");
        $(".chat-input-section").hide();
        // Chatbot tab doesn't need the generic fetch at the end.
        return;
    }

    // For human agent tabs, show the chat conversation area.
    $('.chat-conversation').show();

    if (id === "resolved") {
        $('#chat-list-resolved').show();
        $('#btn-show-meta-session').hide();
        // Only fetch data from the server if it hasn't been loaded before.
        if (!INSTANCE.var.chatResolvedLoaded) {
            await INSTANCE.http_chatHeaderResolveds_get(
                "/chat/v3/get-chat-header-resolveds"
            );
            INSTANCE.var.chatResolvedLoaded = true;
        }
    } else if (id === "served") {
        $('#chat-list').show();
        // Always get the latest "served" chats.
        await INSTANCE.http_chatHeaders_get("/chat/v3/get-chat-headers");
    }

    // This function will now render the chat headers into the correct, visible list.
    await INSTANCE.ui_chatHeader_fetch();
}

function openChatHeaderHistory(id) {
    let chatHeaderJson = INSTANCE.lib.chat.var.chatHeaderHistories.find(
        (chatHeader) => chatHeader.id == id
    );
    if (!chatHeaderJson) return;
    $('#btn-show-meta-session').hide();
    openChatHeader(chatHeaderJson);
}

// async function openChatHeader(chatHeader = null) {
//     if (typeof chatHeader !== "object") {
//         chatHeader = INSTANCE.storage.chatHeaders.find(
//             (header) => header.id == chatHeader
//         );
//     }

//     if (chatHeader) {
//         INSTANCE.lib.chat.setChatHeader(chatHeader);
//         await INSTANCE.lib.message.setChatHeader(chatHeader);

//         $(INSTANCE.lib.message.el.chatMessages).html("");
//         await INSTANCE.lib.message.loadChatMessages(chatHeader);
//         await INSTANCE.lib.chat.loadChatHistories(chatHeader);
//         await INSTANCE.lib.message.scrollSmoothlyToBottom(
//             ".chat-conversation .simplebar-content-wrapper"
//         );
//     }
//     await INSTANCE.lib.ticket.getCustomer(chatHeader);

// }

async function openChatHeader(chatHeader = null) {
    if (typeof chatHeader !== "object") {
        chatHeader = INSTANCE.storage.chatHeaders.find(
            (header) => header.id == chatHeader
        );
    }

    if (chatHeader) {
        INSTANCE.lib.chat.setChatHeader(chatHeader);
        await INSTANCE.lib.message.setChatHeader(chatHeader);
        // Langsung fetch history blast
        fetchBlastHistory(chatHeader.channel_user_account_id);

        $(INSTANCE.lib.message.el.chatMessages).html("");
        await INSTANCE.lib.message.loadChatMessages(chatHeader);
        await INSTANCE.lib.ticket.getCustomer(chatHeader);
         // Tandai semua pesan sebagai sudah dilihat
        window.ChatSocket?.sendEvent_ChatHasBeenViewed(chatHeader.id);
        await INSTANCE.lib.message.markAsSeen(chatHeader.id);
        await INSTANCE.lib.chat.loadChatHistories(chatHeader);
        await INSTANCE.lib.message.scrollSmoothlyToBottom(
            ".chat-conversation .simplebar-content-wrapper"
        );
        $("#inichatheaderid").val(chatHeader.id);
        $("#inichannelid").val(chatHeader.channel_id);
        $("#inichatphone").val(chatHeader.channel_user_account_id);
        $('.chat-conversation').show();
        $('.session-conversation').show();


        if (typeof window.resetDynamicFields === 'function') {
            window.resetDynamicFields();
        }
        const kategoriDropdown = document.getElementById('kategori_complaint_id');
        const jenisDropdown = document.getElementById('jenis_complaint_id');
        kategoriDropdown.value = '';
        jenisDropdown.value = '';

        //  ✅ Prioritas 1: Cek apakah chat sudah closed
        if (chatHeader.status === "close") {
            $(".chat-input-section").hide();
            $("button.chat-end").prop("disabled", true);
            $("#nav-item-end-chat").addClass("d-none");
            $("#nav-item-end-chat-ticket").addClass("d-none");
            return; // Stop eksekusi lebih lanjut
        }
        if (chatHeader.channel_id === 14) {
            $("#OutboundButton").show();
            $("#CallOutbound").show();
            $("#outbound-user-name").text(chatHeader.channel_user_name ?? "-");
            $("#outbound-user-phone").text(chatHeader.channel_user_email ?? "-");
            $("#callName").text(chatHeader.channel_user_name ?? "-");
            $("#callNumber").text(chatHeader.channel_user_email ?? "-");
        } else {
            $("#OutboundButton").hide();
            $("#CallOutbound").hide();
        }



        // Check if chat is started
        // if (chatHeader.handle_by && !chatHeader.started_at) {
        //     $('#chat-not-started').show();
        //     $('#chat-started').hide();
        // } else {
        //     $('#chat-not-started').hide();
        //     $('#chat-input-section').show();
        //     $('#chat-started').show();
        // }
        // Check if chat is started
        let isChatNotStarted = chatHeader.handle_by && !chatHeader.started_at;

        // Bersihkan elemen session sebelum apapun
        clearInterval(sessionInterval);
        // $('#btn-show-meta-session').hide();
        $('.wa-meta-session-timer').text('--:--:--');
        $('#meta-session-end').hide();

        // check whatsapp meta
        // if (chatHeader.channel_id == 14) {
        //     await checkWhatsAppMetaSession(chatHeader.id);
        // } else {
        //     $('#meta-session-end').hide();
        //     $('#chat-input-section').show();
        //     $('#chat-started').show();
        // }
        // Check WhatsApp Meta
        // Check WhatsApp Meta
        let sessionStatus = null;
        // if (chatHeader.channel_id == 14) {
        //     sessionStatus = await checkWhatsAppMetaSession(chatHeader.id);
        // } else {
        //     $('#meta-session-end').hide();
        //     sessionStatus = { status: "not_whatsapp_meta" };
        // }
        sessionStatus = await checkWhatsAppMetaSession(chatHeader.id);


        // Tentukan UI berdasarkan status sesi dan chat
        // if (sessionStatus.status === "expired") {
        //     $('#chat-not-started').hide();
        //     $('#chat-started').hide();
        //     $('#chat-input-section').hide();
            // #meta-session-end sudah ditampilkan oleh checkWhatsAppMetaSession
        // } else if (isChatNotStarted) {
        //     $('#chat-not-started').show();
        //     $('#chat-started').hide();
        //     $('#chat-input-section').hide();
        //     $('#meta-session-end').hide();
        // } else {
        //     $('#chat-not-started').hide();
        //     $('#chat-started').show();
        //     $('#chat-input-section').show();
        //     $('#meta-session-end').hide();
        // }
        if (sessionStatus.status === "expired") {
            if (sessionStatus.is_meta) {
                $('#chat-not-started').hide();
                $('#chat-started').hide();
                $('#chat-input-section').hide();
                $('#meta-session-end').show();
            } else {
                $('#chat-not-started').hide();
                $('#chat-started').show();
                $('#chat-input-section').show();
                $('#meta-session-end').hide();
            }
        } else if (isChatNotStarted) {
            $('#chat-not-started').show();
            $('#chat-started').hide();
            $('#chat-input-section').hide();
            $('#meta-session-end').hide();
        } else {
            $('#chat-not-started').hide();
            $('#chat-started').show();
            $('#chat-input-section').show();
            $('#meta-session-end').hide();
        }



    }

}

// Function untuk fetch blast history di profile tab/list (semua status, dengan message dan button)
async function fetchBlastHistory(phone, targetSelector = '#history-blast-list') {
    // Format nomor: jika mulai dengan 08, ubah ke 628...
    if (typeof phone === "string" && phone.startsWith('08')) {
        phone = '628' + phone.slice(2);
    }
    const listEl = $(targetSelector);
    if (!listEl.length) return;
    listEl.html('<div class="text-center text-gray-400 py-3">Loading blast history...</div>');

    try {
        const res = await $.ajax({
            url: `/chat/v3/get-blast/${phone}`,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        if (res && res.success && Array.isArray(res.data) && res.data.length > 0) {
            let html = '';
            res.data.forEach(item => {
                const isReplied = item.status == 2;
                const statusBadge = isReplied
                    ? '<span class="badge bg-green-600 text-white" style="font-size:13px;">Replied</span>'
                    : '<span class="badge bg-blue-600 text-white" style="font-size:13px;">Blast</span>';

                // Button hanya untuk yang belum replied
                const replyButton = isReplied
                    ? ''
                    : `<button class="btn btn-sm btn-success mt-2 mark-as-replied-btn" data-id="${item.id}" style="font-size:12px;">
                        <i class="fas fa-reply me-1"></i>Customer Reply
                       </button>`;

                // Tampilkan nama blast_schedule jika ada
                const scheduleName = item.blast_schedule_name
                    ? `<div class='text-gray-400 mb-2' style='font-size:13px;'>
                         <i class="fas fa-calendar-alt me-1"></i>
                         <strong>Schedule:</strong> ${item.blast_schedule_name}
                       </div>`
                    : '';

                html += `
<div class='mb-3 py-2 px-3 shadow-sm border-0 bg-gray-900 rounded-lg' style='overflow:hidden;'>
  <div class='d-flex align-items-center mb-1' style='gap:6px;'>
    <i class="fas fa-bullhorn text-blue-400 me-2 fs-5"></i>
    ${statusBadge}
    <span class='ms-auto text-gray-400 small' style='font-size:12px;'>${item.created_at}</span>
  </div>
  ${scheduleName}
  <div class='fw-semibold text-white' style='font-size:15px;line-height:1.6;word-break:break-word;'>
    ${item.message ? item.message.replace(/\n/g, '<br>') : '-'}
  </div>
  ${replyButton}
</div>`;
            });
            listEl.html(html);

            // Attach event listeners for reply buttons
            $('.mark-as-replied-btn').off('click').on('click', function() {
                const btn = $(this);
                const blastId = btn.data('id');
                markBlastAsReplied(blastId, btn);
            });
        } else {
            listEl.html('<div class="text-center text-gray-400 py-3">Tidak ada riwayat blast</div>');
        }
    } catch (err) {
        listEl.html('<div class="text-center text-red-400 py-3">Gagal load blast!</div>');
        console.error('Blast fetch error', err);
    }
}

// Function untuk fetch blast history di modal (hanya status 2, tampilkan nama blast_schedule dan flag saja)
async function fetchBlastHistoryForModal(phone, targetSelector = '#history-blast-list-modal') {
    // Format nomor: jika mulai dengan 08, ubah ke 628...
    if (typeof phone === "string" && phone.startsWith('08')) {
        phone = '628' + phone.slice(2);
    }
    const listEl = $(targetSelector);
    if (!listEl.length) return;
    listEl.html('<div class="text-center text-gray-400 py-3">Loading blast history...</div>');

    try {
        // Menggunakan route khusus untuk modal (hanya status 2 - replied)
        // Sertakan user_id bila tersedia agar filter lebih akurat
        const userId = ($('#inichatticketuser').val() || '').trim();
        const url = userId
            ? `/chat/v3/get-blast-for-modal/${phone}?user_id=${encodeURIComponent(userId)}`
            : `/chat/v3/get-blast-for-modal/${phone}`;
        const res = await $.ajax({
            url,
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        if (res && res.success && Array.isArray(res.data) && res.data.length > 0) {
            let html = '';
            const currentSelectedId = $('#selected_blast_queues_id').val();

            res.data.forEach(item => {
                // Untuk modal, semua item sudah status 2 (replied)
                const statusBadge = '<span class="badge bg-green-600 text-white" style="font-size:13px;">Replied</span>';
                const scheduleName = item.blast_schedule_name || 'N/A';
                const isSelected = currentSelectedId == item.id;

                // Modal: hanya nama blast_schedule dan flag, tidak ada message dan button
                // Tambahkan clickable dan visual feedback
                const selectedClass = isSelected ? 'border border-blue-500 bg-blue-900/20' : '';
                const selectedIcon = isSelected ? '<i class="fas fa-check-circle text-blue-400 me-1"></i>' : '';

                html += `
<div class='mb-3 py-2 px-3 shadow-sm border-0 bg-gray-900 rounded-lg blast-item-clickable ${selectedClass}'
     style='overflow:hidden; cursor:pointer; transition:all 0.2s;'
     data-blast-id='${item.id}'
     data-blast-schedule='${scheduleName.replace(/'/g, "\\'")}'
     onclick='selectBlastQueue(${item.id}, "${scheduleName.replace(/'/g, "\\'")}")'
     onmouseover='if(!$(this).hasClass("selected-blast")) this.style.backgroundColor="#374151"'
     onmouseout='if(!$(this).hasClass("selected-blast")) this.style.backgroundColor="#1F2937"'>
  <div class='d-flex align-items-center justify-content-between mb-1' style='gap:6px;'>
    <div class='d-flex align-items-center' style='gap:6px;'>
      <i class="fas fa-bullhorn text-blue-400 me-2 fs-5"></i>
      ${statusBadge}
      ${selectedIcon}
      <span class='text-white fw-semibold' style='font-size:14px;'>${scheduleName}</span>
    </div>
    <span class='text-gray-400 small' style='font-size:12px;'>${item.created_at}</span>
  </div>
</div>`;
            });
            listEl.html(html);

            // Update visual untuk item yang sudah dipilih
            if (currentSelectedId) {
                $(`.blast-item-clickable[data-blast-id="${currentSelectedId}"]`).addClass('selected-blast');
            }
        } else {
            listEl.html('<div class="text-center text-gray-400 py-3">Tidak ada riwayat blast</div>');
        }
    } catch (err) {
        listEl.html('<div class="text-center text-red-400 py-3">Gagal load blast!</div>');
        console.error('Blast fetch error', err);
    }
}

// Function untuk memilih blast queue dari modal
function selectBlastQueue(blastId, scheduleName) {
    // Simpan blast_queues_id dan schedule name ke hidden field
    $('#selected_blast_queues_id').val(blastId);
    $('#selected_blast_schedule_name').val(scheduleName);

    // Update visual: hapus selected dari semua item
    $('.blast-item-clickable').removeClass('selected-blast border border-blue-500 bg-blue-900/20');
    $('.blast-item-clickable .fa-check-circle').remove();

    // Tambahkan selected ke item yang diklik
    const clickedItem = $(`.blast-item-clickable[data-blast-id="${blastId}"]`);
    clickedItem.addClass('selected-blast border border-blue-500 bg-blue-900/20');
    clickedItem.css('backgroundColor', '#1e3a5f');

    // Tambahkan icon check
    const iconContainer = clickedItem.find('.d-flex.align-items-center').first();
    if (iconContainer.find('.fa-check-circle').length === 0) {
        iconContainer.prepend('<i class="fas fa-check-circle text-blue-400 me-1"></i>');
    }

    // Tampilkan indikator di form
    $('#selected-blast-schedule-display').text(scheduleName);
    $('#selected-blast-indicator').fadeIn();

    // Tampilkan notifikasi
    Swal.fire({
        icon: 'success',
        title: 'Blast Queue Selected',
        text: `Schedule: ${scheduleName}`,
        timer: 2000,
        showConfirmButton: false,
        toast: true,
        position: 'top-end'
    });

    // Tutup modal setelah 1.5 detik
    setTimeout(() => {
        $('#blastHistoryModal').modal('hide');
    }, 1500);
}

// Function untuk clear pilihan blast queue (global scope untuk bisa dipanggil dari ticket-form.js)
function clearSelectedBlastQueue() {
    $('#selected_blast_queues_id').val('');
    $('#selected_blast_schedule_name').val('');
    $('#selected-blast-schedule-display').text('');
    $('#selected-blast-indicator').fadeOut();

    // Update visual di modal (jika modal masih terbuka)
    $('.blast-item-clickable').removeClass('selected-blast border border-blue-500 bg-blue-900/20');
    $('.blast-item-clickable .fa-check-circle').remove();
    $('.blast-item-clickable').css('backgroundColor', '');

    // Tidak perlu notifikasi jika dipanggil dari reset form (bisa mengganggu)
    // Cek apakah ini dipanggil dari reset form atau user action
    const isFromReset = arguments[0] === true; // Parameter untuk skip notification

    if (!isFromReset) {
        Swal.fire({
            icon: 'info',
            title: 'Blast Queue Cleared',
            text: 'Pilihan blast queue telah dihapus',
            timer: 1500,
            showConfirmButton: false,
            toast: true,
            position: 'top-end'
        });
    }
}

// Pastikan function tersedia di global scope
window.clearSelectedBlastQueue = clearSelectedBlastQueue;

async function markBlastAsReplied(blastId, btnElement) {
    if (!blastId) {
        Swal.fire({ icon: 'error', title: 'Error', text: 'Blast ID tidak ditemukan' });
        return;
    }

    // Disable button while processing
    const btn = $(btnElement);
    const originalText = btn.html();
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i>Processing...');

    try {
        const res = await $.ajax({
            url: `/chat/v3/blast/${blastId}/mark-as-replied`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json'
            }
        });

        if (res && res.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: res.message || 'Status berhasil diubah menjadi replied',
                timer: 2000,
                showConfirmButton: false
            });

            // Reload blast history to reflect the change
            const phone = $('#inichatphone').val() || $('#Profile_NomorTelepon').text().trim();
            if (phone) {
                await fetchBlastHistory(phone);
            }
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: res.message || 'Gagal mengubah status'
            });
            btn.prop('disabled', false).html(originalText);
        }
    } catch (err) {
        console.error('Mark as replied error', err);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: err.responseJSON?.message || 'Gagal mengubah status'
        });
        btn.prop('disabled', false).html(originalText);
    }
}

async function closeChatHeader() {
    INSTANCE.lib.chat.setChatHeader(null);
}

function onSearchHistory(el) {
    let val = el.value.toUpperCase();

    let HISTORIES = JSON.parse($("#json_histories").val());
    let filter = HISTORIES.filter((history) => {
        return (
            String(history.id).toUpperCase().search(val) != -1 ||
            history.created_at.toUpperCase().search(val) != -1 ||
            history.channel_page_name.toUpperCase().search(val) != -1 ||
            history.channel_user_name.toUpperCase().search(val) != -1
        );
    });

    let html = "";
    filter.forEach((search) => {
        html += `<li><a href="javascript:openChatHeaderHistory('${search.id}')"><h5 class="font-size-14 mb-0">${search.created_at}</h5></a></li>`;
    });

    $("#placeHistories ul.chat-list").html(html);
}

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
function addZero(i) {
    if (i < 10) {
        i = "0" + i;
    }
    return i;
}

$("#formEndChat").submit((e) => {
    e.preventDefault();
    let form = $("#formEndChat");
    let data = new FormData(form[0]);
    // data.append("chat_header_id", form.attr('data-header-id'))
    // //console.log("chat_header_id", form.attr('data-header-id'));


    $.ajax({
        type: "POST",
        url: listUrls.chatEnd.replaceAll(":header_id",
            INSTANCE.lib.chat.var.selected.id
        ),
        data: form.serialize(),
        beforeSend: function () {
            // disabled the submit button
            $("button.chat-end").prop("disabled", true);
        },
        success: function (result) {
            $(".chat-input-section").hide();
            $('.nav-tabs a[href="#home2"]').tab("show");
            $("#formEndChat textarea").val("");
            $("#placeNewAdditional").html("");


            // UPDATE IDB CHAT HEADER
            let index = INSTANCE.storage.chatHeaders.findIndex(
                (chat_header) =>
                    chat_header.id == INSTANCE.lib.chat.var.selected.id
            );
            if (index >= 0) {
                let chat_header = INSTANCE.storage.chatHeaders[index];
                chat_header.status = "close";
                chat_header.ended_at = new Date().toISOString();

                // Update chat_header
                //TODO: Masih kurang paham next nya gimana
                INSTANCE.storage.chatHeaders[index] = chat_header;
                if (INSTANCE.var.currentTab == "served") {
                    INSTANCE.lib.chat.elChatItemRemove(chat_header.id);
                }
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
    });
});
// $("#formEndChat").submit(async (e) => {
//     e.preventDefault();
//     let form = $("#formEndChat");
//     let data = {
//         _token: $("meta[name=csrf-token]").attr("content"),
//         note: form.find("textarea[name=note]").val(),
//     };

//     await INSTANCE.lib.http
//         .send(`/chat/${INSTANCE.lib.chat.var.selected.id}/close`, data)
//         .then((res) => {
//             $(".chat-input-section").hide();
//             $('.nav-tabs a[href="#home2"]').tab("show");
//             $("#formEndChat textarea").val("");
//             $("#placeNewAdditional").html("");

//             // UPDATE IDB CHAT HEADER
//             let index = INSTANCE.storage.chatHeaders.findIndex(
//                 (chat_header) =>
//                     chat_header.id == INSTANCE.lib.chat.var.selected.id
//             );
//             if (index >= 0) {
//                 let chat_header = INSTANCE.storage.chatHeaders[index];
//                 chat_header.status = "close";
//                 chat_header.ended_at = new Date().toISOString();

//                 // Update chat_header
//                 //TODO: Masih kurang paham next nya gimana
//                 INSTANCE.storage.chatHeaders[index] = chat_header;
//                 if (INSTANCE.var.currentTab == "served") {
//                     INSTANCE.lib.chat.elChatItemRemove(chat_header.id);
//                 }
//             }

//             if (res.data.is_create_ticket == 1) {
//                 createTicket(res.data);
//             } else {
//                 Swal.fire({
//                     icon: "success",
//                     title: "Success",
//                     text: "Chat has been closed",
//                 });
//             }
//         });
// });

$("#formEndChatTicket").submit(async (e) => {
    e.preventDefault();
    let form = $("#formEndChatTicket");
    let data = new FormData(form[0]);

    // disabled the submit button
    $("button.chat-end-ticket").prop("disabled", true);

    await INSTANCE.lib.http
        .send(
            `/chat/${INSTANCE.lib.chat.var.selected.id}/close-with-ticket`,
            form.serialize()
        )
        .then((res) => {
            $(".chat-input-section").hide();
            $('.nav-tabs a[href="#home2"]').tab("show");
            $("#formEndChat textarea").val("");
            $("#placeNewAdditional").html("");

            // UPDATE IDB CHAT HEADER
            let index = INSTANCE.storage.chatHeaders.findIndex(
                (chat_header) =>
                    chat_header.id == INSTANCE.lib.chat.var.selected.id
            );
            if (index >= 0) {
                let chat_header = INSTANCE.storage.chatHeaders[index];
                chat_header.status = "close";
                chat_header.ended_at = new Date().toISOString();

                // Update chat_header
                //TODO: Masih kurang paham next nya gimana
                INSTANCE.storage.chatHeaders[index] = chat_header;
                if (INSTANCE.var.currentTab == "served") {
                    INSTANCE.lib.chat.elChatItemRemove(chat_header.id);
                }
            }

            Swal.fire({
                icon: "success",
                title: "Success",
                text: "Chat has been closed",
            });
        });
});

$("#reply-msg").submit(async (e) => {
    e.preventDefault();
    let form = $("#reply-msg");
    var data = form.serializeArray();
    data.push({
        chat_header_id: "hours",
        value: INSTANCE.lib.chat.var.selected.id,
    });
    // data.append("chat_header_id", INSTANCE.lib.chat.var.selected.id);
    //console.log("chat_header_id", form.attr("data-header-id"));

    let messageText = $("#reply-msg textarea[name=message]").val();

    // disabled the submit button
    $("button.chat-send").prop("disabled", true);

    await INSTANCE.lib.http
        .send(`/chat/send/text`, {
            message: messageText,
            chat_header_id: INSTANCE.lib.chat.var.selected.id,
        })
        .then(async (result) => {
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
            let index = INSTANCE.storage.chatHeaders.findIndex(
                (chat_header) =>
                    chat_header.id == INSTANCE.lib.chat.var.selected.id
            );
            if (index >= 0) {
                let chat_header = INSTANCE.storage.chatHeaders[index];

                // if(typeof chat_header.latest_message == "undefined") {
                //     chat_header.latest_message = {}
                // }
                chat_header.last_message = messageText;
                chat_header.last_message_at = date + " " + time;
                chat_header.updated_at = date + " " + time;
                chat_header.last_message_by = "page";

                // Update chat_header on IDB
                INSTANCE.storage.chatHeaders[index] = chat_header;

                // let chatBodies = await INSTANCE.lib.message.createChatBody(chat_header, messageText, date, time);

                // await $(INSTANCE.lib.message.el.chatMessages).append(await INSTANCE.lib.message.itemMessage(chatBodies));

                INSTANCE.lib.chat.elChatItemMoveToTop(chat_header.id);
                await INSTANCE.lib.message.loadChatMessages(chat_header, false);
                await INSTANCE.lib.message.scrollSmoothlyToBottom(
                    ".chat-conversation .simplebar-content-wrapper"
                );
            }
        });
});

// Allow sending message in reply form using Enter (Shift+Enter for newline)
$(document).on("keydown", "#reply-msg textarea[name=message], .emojionearea-editor", function (e) {
    if (e.key === "Enter" && !e.shiftKey) {
        e.preventDefault();
        $("#reply-msg").submit();
    }
});
function onSubmitEndChat(e) {
    e.preventDefault();
    let form = $("#reply-msg");
    let data = new FormData(form[0]);
    data.append("chat_header_id", form.attr("data-header-id"));

    console.log(data);
}

$(".chooseTemplate").click(function() {
    var templateId = $(this).data("template-id")
    console.log($("#"+templateId).text())
    $("#chat-input-text").val($("#"+templateId).text())
    // $("#chat-input-text").val($("#"+templateId).text())
    $("#listTemplateChat").modal("hide")
})

/**
 * Ticket
 */

$("#SimpanCustomerModal").click(function(e) {
    e.preventDefault();
    let data = {
        name: $("#AddCustomer_Name").val(),
        phone: $("#AddCustomer_HP").val(),
        email: $("#AddCustomer_Email").val(),
        address: $("#AddCustomer_Address").val(),
        channel_user_id: INSTANCE.lib.chat.var.selected.channel_user_id,
    }

    INSTANCE.lib.ticket.saveCustomer(data);
})

$("#EditCustomerModal").click(function(e) {
    e. preventDefault();
    let data = {
        name: $("#EditCustomer_Name").val(),
        phone: $("#EditCustomer_HP").val(),
        email: $("#EditCustomer_Email").val(),
        address: $("#EditCustomer_Address").val(),
        chat_ticket_user_id: INSTANCE.lib.ticket.var.chat_ticket_user.id,
    }

    INSTANCE.lib.ticket.updateCustomer(data);
})

$( '#search-customer' ).select2( {
    theme: "bootstrap -5",
    width: $( this ).data( 'width' ) ? $( this ).data( 'width' ) : $( this ).hasClass( 'w-100' ) ? '100%' : 'style',
    placeholder: $( this ).data( 'placeholder' ),
    closeOnSelect: false,
    dropdownParent: $("#addCustomerBC"),
    allowClear: true,
    ajax: {
        url: `/chat/v3/ticket/customer/find`,
        dataType: 'json',
        delay: 250,
        data: function (params) {
            return {
                keyword: params.term, // search term
            };
        },
        // Additional AJAX parameters go here; see the end of this chapter for the full code of this example
        processResults: function (data, params) {
            return {
                results: data.data.search_result,
            };
        },
        cache: true
    },
    placeholder: 'Search for a repository',
    minimumInputLength: 3,
    templateResult: formatRepo,
    templateSelection: formatRepoSelection
});
// Bind an event
$('#search-customer').on('select2:select', function (e) {
    var data = e.params.data;
    INSTANCE.lib.ticket.var.chat_ticket_user = data
    INSTANCE.lib.ticket.disableFormAddCustomer()
});
$('#search-customer').on('select2:clear', function (e) {
    console.log("clear")
    INSTANCE.lib.ticket.clearSearchCustomer()
});
function formatRepoSelection (repo) {
    return repo.name;
  }
function formatRepo (repo) {
    console.log("repo", repo)
    var $container = $(
            "<div class='select2-result-repo sitory clearfix'>" +
            "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title'></div>" +
                "<div class='select2-resu lt-repository__description'></div>" +
                "<div class='select2-result-repository__phone'></div>" +
            "</div>" +
            "</div>"
        );

    $container.find(".select2-result-repository__title").text(repo.name);
    $container.find(".select2-result-repository__description").text(repo.email);
    $container.find(".select2-result-repository__phone").text(repo.phone);

    return $container;
}

function deleteOtherChannel(channel_user_id) {
    INSTANCE.lib.ticket.deleteChannel({
        channel_user_id: channel_user_id
    })
    return
}

$("#Form_Ticket_Kategori").on('change', function() {
    var selectedOption = $(this).find('option:selected');
    var dataId = selectedOption.data('id'); // Ambil data-id dari option yang dipilih
    var value = $(this).val();

    // Jika tidak ada data-id, gunakan value sebagai fallback
    var categoryId = dataId || value;

    var uri = listUrls.ticketGetByCategory ?? '';
    uri = uri.replace(':variable', categoryId);

    $.getJSON(uri, function(response) {
        $('#Form_Ticket_SubKategori').empty();
        $('#Form_Ticket_SubKategori').append('<option hidden>Sub Kategori</option>');

        // Handle both direct array and JSON response formats
        const data = response.data || response;

        $.each(data, function(key, val) {
            $('select[name="Form_Ticket_SubKategori"]').append(
                '<option value="' + val.name + '" data-id="' + (val.id || val.data_id || '') + '">' + val.name + '</option>'
            );
        });
    }).fail(function(error) {
        console.error('Error loading subcategories:', error);
        $('#Form_Ticket_SubKategori').empty()
            .append('<option value="">Error loading subcategories</option>');
    });
});

$("#buttonEndChatAction").click(function (e) {
    e.preventDefault();
    $("button.chat-end").prop("disabled", true);
    var fd = new FormData();
    var files = document.getElementById('file-bc').files;
    for (var x = 0; x < files.length; x++) {
        fd.append("attachments[]", files[x]);
    }
    fd.append("chat_header_id",  INSTANCE.lib.chat.var.selected.id)
    fd.append("priority",  $("#Form_Ticket_Priority").val())
    fd.append("km_article_id",  $("#article-id-field").val())
    fd.append("status",  $("#Form_Ticket_Status").val())
    fd.append("subject",  $("#Form_Ticket_Subject").val())
    fd. append("kategori",  $("#Form_Ticket_Kategori option:selected").text())
    fd.append("subkategori",  $("#Form_Ticket_SubKategori").val())
    fd.append("question",  $("#Ticket_Complaints").val())
    fd.append("answer",  $("#Ticket_NoteAgent").val())
    INSTANCE.lib.ticket.submitTicket(fd).then((res) => {
        $(".chat-input-section").hide();
        $("#meta-session-end").hide();
        $("#chat-not-started").hide();
        $("#CallOutbound").hide();
        $("#OutboundButton").hide();
        $('.nav-tabs a[href="#home2"]').tab("show");
        $("#formEndChat textarea").val("");
        $("#placeNewAdditional").html("");


        // UPDATE IDB CHAT HEADER
        let index = INSTANCE.storage.chatHeaders.findIndex(
            (chat_header) =>
                chat_header.id == INSTANCE.lib.chat.var.selected.id
        );
        if (index >= 0) {
            let chat_header = INSTANCE.storage.chatHeaders[index];
            chat_header.status = "close";
            chat_header.ended_at = new Date().toISOString();

            // Update chat_header
            //TODO: Masih kurang paham next nya gimana
            INSTANCE.storage.chatHeaders[index] = chat_header;
            if (INSTANCE.var.currentTab == "served") {
                INSTANCE.lib.chat.elChatItemRemove(chat_header.id);
            }
        }

        Swal.fire({
            icon: "success",
            title: "Success",
            text: "Chat has been closed",
        });
    });
})

// BUTTON MULAI SAAT ACTIVE SESSION EXPIRED DAN MODAL NYA MASIH DUMMY
    $(document).ready(function () {
        $('#start-template-hsm').on('click', function () {
            $('#modalPilihTemplate').modal('show');
            $('#defaultPreview').show();
            $('#selectedTemplatePreview').hide();
        });

        $('#selectTemplateType').on('change', function () {
            $('#selectTemplateCategory').prop('disabled', false);
            $('#templateListWrapperDummy').hide();
        });

        $('#selectTemplateCategory').on('change', function () {
            const selectedCategory = $(this).val();
            $('#templateListWrapperDummy').show();

            $('.template-row').each(function () {
                const rowCategory = $(this).data('category');
                if (selectedCategory === 'all' || selectedCategory === rowCategory) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        $('.select-template-btn').on('click', function () {
            const $button = $(this);
            const templateId = $button.data('template-id');
            const $previewHeader = $('#previewHeader');
            const $previewBody = $('#previewBody');
            const $defaultPreview = $('#defaultPreview');
            const $selectedPreview = $('#selectedTemplatePreview');

            if ($button.hasClass('btn-success')) {
                // Unselect: revert to original state and show default preview
                $button.removeClass('btn-success').addClass('btn-primary').html('Select');
                $selectedPreview.hide();
                $defaultPreview.show();
            } else {
                // Select: change to checkmark and update preview
                $('.select-template-btn').removeClass('btn-success').addClass('btn-primary').html('Select');
                $button.removeClass('btn-primary').addClass('btn-success').html('<i class="bx bx-check"></i>');

                // Update preview based on template ID
                if (templateId === 'SS-001') {
                    // Shoe Sale Offer preview
                    $previewHeader.html(`
                        <p class="mb-0 font-poppins text-gray-500">Get 20% off on blue sneakers! Shop now.</p>
                        <p class="text-xs text-gray-500 text-right mt-1">16:01</p>
                    `);
                    $previewBody.html(`
                        <div class="carousel-wrapper" style="display: flex; overflow-x: auto; gap: 1rem; padding: 0.5rem; max-width: 100%; box-sizing: border-box;">
                            <div class="card" style="min-width: 160px; border: none; border-radius: 0.5rem; overflow: hidden; background-color: #1e293b;">
                                <img src="https://images.unsplash.com/photo-1506748686214-e9df14d4d9d0?w=300&h=200&fit=crop" class="card-img-top" alt="Blue Shoes">
                            </div>
                        </div>
                        <p class="mb-1 text-gray-500 text-center">Blue Shoes</p>

                        <div class="row text-center">
                            <a href="#" class="text-blue-400 text-sm">
                                <i class="bx bx-link-external"></i> Buy Now
                            </a>
                        </div>
                        <p class="text-xs text-gray-500 text-right mt-1">16:01</p>
                    `);
                } else if (templateId === 'OC-002') {
                    // Select Service Option preview
                    $previewHeader.html(`
                        <p class="mb-0 font-poppins text-gray-500">Selamat datang (username) di layanan contact center FII. Silakan pilih menu tujuan anda.</p>
                        <p class="text-xs text-gray-500 text-right mt-1">16:01</p>
                    `);
                    $previewBody.html(`
                        <p class="mb-0 font-poppins text-gray-500">Silakan pilih menu berikut :</p>
                        <div class="mt-2">
                            <button class="btn btn-outline-dark mb-2 w-100 !text-gray-500">Layanan</button>
                            <button class="btn btn-outline-dark mb-2 w-100 !text-gray-500">Collection</button>
                            <button class="btn btn-outline-dark mb-2 w-100 !text-gray-500">Kredit</button>
                        </div>
                        <p class="text-xs text-gray-500 text-right mt-1">16:01</p>
                    `);
                }
                $defaultPreview.hide();
                $selectedPreview.show();
            }
        });
    });
// END

// START CHAT UNTUK MEMBUAT STARTED_AT YANG TADINYA NULL DIMULAI NOW
    $(document).on('click', '#start-chat-btn-null', async function () {
        const chat_header_id = INSTANCE.lib.chat.var.selected.id;

        try {
            const response = await $.ajax({
                url: `/chat/v3/start-chat/${chat_header_id}`,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            if (response.success) {
                const index = INSTANCE.storage.chatHeaders.findIndex(h => h.id === chat_header_id);
                if (index >= 0) {
                    INSTANCE.storage.chatHeaders[index].started_at = new Date().toISOString();
                }

                // Hide all possible not-started alerts
                $('#chat-not-started').hide();
                $('#meta-session-end').hide();
                // Show started section
                $('#chat-started').show();
                $('#chat-input-section').show();

                // Refresh UI state to ensure everything is in sync
                await openChatHeader(chat_header_id);

                // Swal.fire('Berhasil!', 'Chat telah dimulai.', 'success');
            }
        } catch (e) {
            Swal.fire('Gagal!', e.responseJSON?.message || 'Tidak dapat memulai chat.', 'error');
        }
    });
// END

// BUTTON UNTUK MELIHAT ACTIVE SESSION
    $(document).ready(function () {
        $('#btn-show-meta-session').on('click', function () {
            $('#meta-session-cover')
                .removeClass('translate-x-full opacity-0 pointer-events-none')
                .addClass('translate-x-0 opacity-100 pointer-events-auto');
        });

        $('#btn-hide-meta-session').on('click', function () {
            $('#meta-session-cover')
                .removeClass('translate-x-0 opacity-100 pointer-events-auto')
                .addClass('translate-x-full opacity-0 pointer-events-none');
        });
    });
// END


// LOGIC JAVASCRIPT UNTUK ACTIVE SESSION

    let sessionInterval = null;

    // async function checkWhatsAppMetaSession(chat_header_id) {
    //     const response = await fetch(`/chat/v3/check-session?chat_header_id=${chat_header_id}`);
    //     const result = await response.json();

    //     clearInterval(sessionInterval); // 💡 reset countdown sebelumnya

    //     // Ambil chat header dari storage atau API
    //     const chatHeader = INSTANCE.storage.chatHeaders.find(h => h.id == chat_header_id);

    //     // ❗Pastikan chat ini belum dalam status close sebelum melanjutkan
    //     if (chatHeader?.status === "close") {
    //         return; // jangan ubah UI karena status close memiliki prioritas tertinggi
    //     }



    //     if (!result.is_whatsapp_meta) {

    //         $('#btn-show-meta-session').hide();
    //         $('#meta-session-end').hide();
    //         $('#chat-input-section').show();
    //         return;
    //     }


    //     if (!result.session_active) {
    //         clearInterval(sessionInterval);

    //         $('#btn-show-meta-session').show();
    //         $('.wa-meta-session-timer').text("Session Expired"); // ← Ubah isi teks

    //         // Hitung waktu expired = last_user_message + 24 jam
    //         let expireAt = new Date(result.last_message_time);
    //         expireAt.setHours(expireAt.getHours() + 24);

    //         let options = { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' };
    //         let expireLabel = `Last Session at ${expireAt.toLocaleString('en-US', options)}`;

    //         $('.wa-meta-session-label').text(expireLabel); // ← Tambahkan info expired-nya

    //         $('#meta-session-end').show();
    //         $('#chat-input-section').hide();
    //         $('#chat-started').hide(); // Tambahkan ini untuk memastikan konsistensi
    //         return { status: "expired" }; // Kembalikan status expired
    //     }

    //     // Session aktif
    //     let expireAt = new Date(result.last_message_time);
    //     expireAt.setHours(expireAt.getHours() + 24);

    //     let options = { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' };
    //     let expireLabel = `Active Session until ${expireAt.toLocaleString('en-US', options)}`;
    //     // 💡 Default: hide timer box
    //     $('.wa-meta-session-timer').text('--:--:--');
    //     // Session aktif
    //     $('#btn-show-meta-session').show();
    //     $('#meta-session-end').hide();
    //     $('#chat-input-section').show();
    //     $('#chat-started').show();

    //     $('.wa-meta-session-label').text(expireLabel);

    //     // Jika masih aktif, jalankan countdown
    //     startSessionCountdown(result.last_message_time);
    //     return { status: "active" }; // Kembalikan status aktif
    // }
    async function checkWhatsAppMetaSession(chat_header_id) {
        const response = await fetch(`/chat/v3/check-session?chat_header_id=${chat_header_id}`);
        const result = await response.json();

        clearInterval(sessionInterval);

        const chatHeader = INSTANCE.storage.chatHeaders.find(h => h.id == chat_header_id);
        if (chatHeader?.status === "close") {
            $('#btn-show-meta-session').hide();
            return;
        }

        const isMeta = result.is_whatsapp_meta;
        const isActive = result.session_active;

        const expireAt = new Date(result.last_message_time);
        expireAt.setHours(expireAt.getHours() + 24);
        const options = { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' };
        const expireLabel = isActive
            ? `Active Session until ${expireAt.toLocaleString('en-US', options)}`
            : `Last Session at ${expireAt.toLocaleString('en-US', options)}`;

        $('.wa-meta-session-label').text(expireLabel);
        $('#btn-show-meta-session').show(); // ✅ SELALU tampilkan tombolnya

        if (!isActive) {
            $('.wa-meta-session-timer').text("Session Expired");

            if (isMeta) {
                $('#chat-input-section').hide();
                $('#chat-started').hide();
                $('#meta-session-end').show();
            } else {
                $('#chat-input-section').show();
                $('#chat-started').show();
                $('#meta-session-end').hide();
            }

            return { status: "expired", is_meta: isMeta };
        }


        // Session masih aktif
        $('#chat-input-section').show();
        $('#chat-started').show();
        $('#meta-session-end').hide();
        $('.wa-meta-session-timer').text('--:--:--');

        // if (isMeta) {
        //     $('#btn-show-meta-session').show();
        //     startSessionCountdown(result.last_message_time);
        // }
        // if (isMeta) {
        //     startSessionCountdown(result.last_message_time);
        // }
        startSessionCountdown(result.last_message_time); // Tanpa if isMeta

        return { status: "active", is_meta: isMeta };
    }


    function startSessionCountdown(lastMessageTime) {
        clearInterval(sessionInterval); // clear previous countdown if any

        const sessionDuration = 24 * 60 * 60 * 1000; // 24 jam in ms
        const lastUserMessageTime = new Date(lastMessageTime).getTime();
        const expireTime = lastUserMessageTime + sessionDuration;

        sessionInterval = setInterval(() => {
            const now = new Date().getTime();
            const diff = expireTime - now;

            if (diff <= 0) {
                clearInterval(sessionInterval);
                $('#meta-session-end').show();
                $('#chat-input-section').hide();
                $('.wa-meta-session-timer').text("00:00:00");
                return;
            }

            const hours = Math.floor(diff / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);

            const formatted = `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;
            $('.wa-meta-session-timer').text(formatted);
        }, 1000);
    }

    function pad(num) {
        return num < 10 ? "0" + num : num;
    }
// END

// UNTUK JUMLAH NOTIFIKASI/MARK AS SEEN
    if (!INSTANCE.lib.message) INSTANCE.lib.message = {}; // Pastikan namespace tersedia

    INSTANCE.lib.message.markAsSeen = async function(chat_header_id) {
        try {
            const response = await this.lib.http.send("/chat/v3/mark-as-seen", {
                chat_header_id: chat_header_id
            });
            console.log("Marked as seen:", response);
        } catch (error) {
            console.error("Failed to mark as seen:", error);
        }
    };

    // Event listener untuk klik sesi bot pada #session-list
    $(document).on('click', '#session-list .session-list-a', function() {
        const sessionId = $(this).data('session-id');
        const session = botSessionInstance.var.sessions.find(s => s.id == sessionId);
        if (session) {
            botSessionInstance.openSessionV3(sessionId);
            // Render pesan bot ke .session-conversation
            window.chatBotMessageInstance.loadBotMessages(session);
        }
    });
// END

let allTemplates = [];         // semua template dari API (master)
let outboundTemplates = [];    // template hasil filter
let selectedTemplateId = null;

// Load dan render ke table
function loadOutboundTemplates(data) {
    outboundTemplates = data;

    $('#templatesListSelect').empty();

    if (data.length === 0) {
        $('#templatesListSelect').html(`<tr><td colspan="4" class="text-center text-gray-300 py-4">No templates found.</td></tr>`);
        return;
    }

    data.forEach(template => {
        let row = `
            <tr class="template-row" data-template-id="${template.id}" data-template-name="${template.name}" data-language="${template.language}">
                <td class="px-6 py-4 text-white">${template.name}</td>
                <td class="px-6 py-4 text-white">${template.category ?? '-'}</td>
                <td class="px-6 py-4 text-white">${template.language}</td>
                <td class="px-6 py-4">
                    <button class="btn btn-sm btn-outline-info select-outbound-template" data-template-id="${template.id}" id="select-template-${template.id}">
                        <i class="fas fa-plus"></i> Select
                    </button>
                </td>
            </tr>
        `;
        $('#templatesListSelect').append(row);
    });
}

// Filter kategori
$('#selectOutboundTemplateCategory').on('change', function () {
    const selectedCategory = $(this).val();

    // Show loading in table
    $('#templatesListSelect').html(`
        <tr>
            <td colspan="4" class="text-center text-white py-4">
                <div class="spinner-border text-light" role="status"></div>
                <div class="mt-2">Filtering templates...</div>
            </td>
        </tr>
    `);

    // Simulate short delay (for UX)
    setTimeout(() => {
        const filtered = selectedCategory === 'all'
        ? [...allTemplates]
        : allTemplates.filter(t => t.category?.toLowerCase() === selectedCategory.toLowerCase());

        loadOutboundTemplates(filtered);
    }, 300);
});

// Select handler
$(document).on('click', '.select-outbound-template', function () {
    const templateId = $(this).data('template-id');
    const button = $(`#select-template-${templateId}`);
    const template = outboundTemplates.find(t => String(t.id) === String(templateId));

    if (selectedTemplateId === templateId) {
        selectedTemplateId = null;
        button.html('<i class="fas fa-plus"></i> Select')
              .removeClass('btn-success')
              .addClass('btn-outline-info');
        $('#defaultOutboundPreview').show();
        $('#selectedOutboundTemplatePreview').hide().empty();
    } else {
        if (selectedTemplateId !== null) {
            $(`#select-template-${selectedTemplateId}`)
                .html('<i class="fas fa-plus"></i> Select')
                .removeClass('btn-success')
                .addClass('btn-outline-info');
        }
        selectedTemplateId = templateId;
        button.html('<i class="fas fa-check"></i> Selected')
              .removeClass('btn-outline-info')
              .addClass('btn-success');
        $('#defaultOutboundPreview').hide();
        $('#selectedOutboundTemplatePreview').show().html(renderTemplatePreview(template));
    }
});

// Render preview WhatsApp-like
function renderTemplatePreview(template) {
    if (!template.components || !Array.isArray(template.components)) {
        return '<p class="text-muted">No components available.</p>';
    }

    const header = template.components.find(c => c.type === 'HEADER');
    const body = template.components.find(c => c.type === 'BODY');
    const footer = template.components.find(c => c.type === 'FOOTER');
    const callPermission = template.components.find(c => c.type === 'CALL_PERMISSION_REQUEST');

    let html = `
        <div class="whatsapp-preview p-3 rounded-lg" style="background-color: #d9fdd3; min-height: 200px; font-family: system-ui; max-width: 300px; min-width: 300px;">
    `;

    if (callPermission) {
        html += `
            <div class="mb-3 d-flex align-items-center" style="color: black; background-color:rgb(211, 250, 205); padding: 10px; border-radius: 6px; box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;">
                <i class="fas fa-phone-alt me-2"></i>
                <span><strong>Bolehkah {{ nama_pengirim }} menelepon Anda?</strong></span>
            </div>
        `;
    }

    if (header?.text) {
        html += `<div class="mb-2 fw-bold" style="font-size: 16px; color: #111;">${header.text}</div>`;
    }

    if (body?.text) {
        html += `<div class="mb-2" style="font-size: 15px; color: #111; white-space: pre-line;">${body.text}</div>`;
    }

    if (footer?.text) {
        html += `<div class="mt-3 text-muted small">${footer.text}</div>`;
    }

    html += `</div>`;
    return html;
}

// Fetch awal saat modal dibuka
function fetchOutboundTemplates() {
    $('#templateLoader').show();
    $('#templateListWrapper').hide();
    $('#selectOutboundTemplateCategory').prop('disabled', true);

    $.get('/chat/v3/GetOutboundTemplates', function (res) {
        $('#templateLoader').hide();

        if (res.templates || res.data) {
            const data = res.templates ?? res.data;

            // ✅ Simpan salinan data master (allTemplates) & yang akan di-load
            allTemplates = [...data];              // <== simpan data asli
            outboundTemplates = [...data];         // <== data yang bisa difilter

            loadOutboundTemplates(outboundTemplates);

            $('#selectOutboundTemplateCategory').prop('disabled', false);
            $('#templateListWrapper').show();
        } else {
            $('#templateListWrapper').hide();
        }
    }).fail(function () {
        $('#templateLoader').hide();
        $('#templateListWrapper').hide();
        alert("Failed to load templates.");
    });
}


$('#OutboundTemplateModal').on('show.bs.modal', function () {
    fetchOutboundTemplates();
    $('#defaultOutboundPreview').show();
    $('#selectedOutboundTemplatePreview').hide().empty();
});

$(document).on('click', '#btnSendSelectedTemplate', function () {
    const selectedRow = $('.template-row').filter(function () {
        return $(this).find('button.select-outbound-template').hasClass('btn-success');
    });

    const templateId = selectedRow.data('template-id');
    const templateName = selectedRow.data('template-name');
    const templateLanguage = selectedRow.data('language');

    // Cari template yang sesuai dari allTemplates
    const template = allTemplates.find(t => String(t.id) === String(templateId));

    if (!template) {
        alert("Template not found.");
        return;
    }

    const chatHeader = INSTANCE.lib.chat.var.selected;

    if (!chatHeader || chatHeader.channel_id !== 14) {
        alert("Outbound only available for WhatsApp Meta (channel_id = 14).");
        return;
    }

    let bodyParameters = [];
    if (templateName === "pay_reminder") {
        bodyParameters = [
            "CS Mutual Credit Plus",
            "1234",
            "Mar 22, 2024"
        ];
    }

    // Ambil komponen template untuk ChatBody
    const components = {};
    if (template.components && Array.isArray(template.components)) {
        template.components.forEach(comp => {
            if (comp.type === 'HEADER' && comp.text) components.header = comp.text;
            if (comp.type === 'BODY' && comp.text) components.body = comp.text;
            if (comp.type === 'FOOTER' && comp.text) components.footer = comp.text;
        });
    }

    const requestData = {
        template_name: templateName,
        language: templateLanguage,
        phone: chatHeader.channel_user_email,
        name: chatHeader.channel_user_name,
        channel_page_id: chatHeader.channel_page_id,
        body_parameters: bodyParameters,
        chat_header_id: chatHeader.id,
        template_components: components // 👈 Kirim ke backend
    };

    $.ajax({
        url: '/chat/v3/send-outbound-template',
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: requestData,
        beforeSend: function () {
            $('#btnSendSelectedTemplate').prop('disabled', true).text('Sending...');
        },
        success: function (res) {
            if (res.error) {
                const errorCode = res.error.code;
                const errorMessage = res.error.message;

                if (errorCode === 138009) {
                    // alert("❌ You have reached the 24-hour call permission limit for this customer. Try again tomorrow.");
                    Swal.fire({
                        icon: 'error',
                        title: 'Limit Reached',
                        text: 'You have reached the 24-hour call permission limit for this customer. Try again tomorrow.'
                    });
                } else {
                    // alert(`❌ ${errorMessage || 'Failed to send template.'}`);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: errorMessage || 'Failed to send template.'
                    });
                }

                return; // keluar agar tidak eksekusi alert sukses
            }

            // alert("✅ Template sent successfully!");
            Swal.fire({
                icon: 'success',
                title: 'Template Sent!',
                text: 'Your outbound template has been successfully sent.'
            });
            $('#OutboundTemplateModal').modal('hide');
        },
        error: function (xhr) {
            let message = "❌ Failed to send template.";

            if (xhr.responseJSON?.error?.code === 138009) {
                message = "❌ You have reached the 24-hour call permission limit for this customer. Try again tomorrow.";
            } else if (xhr.responseJSON?.error?.message) {
                message = `❌ ${xhr.responseJSON.error.message}`;
            }

            alert(message);
            $('#OutboundTemplateModal').modal('hide');
        },
        complete: function () {
            $('#btnSendSelectedTemplate').prop('disabled', false).text('Send Template');
        }
    });
});



// async function handleMakeCall() {
//     const callNumber = document.getElementById('callNumber').textContent.trim();

//     if (!callNumber || callNumber === "-") {
//         Swal.fire("Error", "Nomor telepon tidak tersedia.", "error");
//         return;
//     }

//     // Hapus semua karakter non-digit
//     const cleanedNumber = callNumber.replace(/\D+/g, '');

//     if (!cleanedNumber) {
//         Swal.fire("Error", "Format nomor telepon tidak valid.", "error");
//         return;
//     }

//     // Tambahkan '000' di depan nomor (sebelum kode negara)
//     const formattedNumber = '000' + cleanedNumber;

//     // Contoh: http://localhost:60024/dial/telp=0006292483749278
//     const url = `http://localhost:60024/dial/telp=${encodeURIComponent(formattedNumber)}`;

//     try {
//         const response = await fetch(url);

//         if (!response.ok) {
//             throw new Error(`HTTP error! status: ${response.status}`);
//         }

//         const result = await response.text();

//         console.log("Response dari API:", result);

//         if (result.toLowerCase().includes("success") || result.toLowerCase().includes("ok")) {
//             Swal.fire("Berhasil", "Permintaan panggilan telah dikirim.", "success");
//         } else {
//             Swal.fire("Gagal", "Terjadi kesalahan saat memulai panggilan.", "error");
//         }

//         // Tutup modal
//         const modalEl = document.getElementById('confirmCallModal');
//         const modalInstance = bootstrap.Modal.getInstance(modalEl);
//         modalInstance.hide();

//     } catch (error) {
//         console.error("Error calling API:", error);
//         Swal.fire("Error", "Gagal terhubung ke server.", "error");

//         // Tutup modal meskipun error
//         const modalEl = document.getElementById('confirmCallModal');
//         const modalInstance = bootstrap.Modal.getInstance(modalEl);
//         modalInstance.hide();
//     }
// }

async function handleMakeCall() {
    const callNumber = document.getElementById('callNumber').textContent.trim();

    if (!callNumber || callNumber === "-") {
        Swal.fire("Error", "Nomor telepon tidak tersedia.", "error");
        return;
    }

    const cleanedNumber = callNumber.replace(/\D+/g, '');

    if (!cleanedNumber) {
        Swal.fire("Error", "Format nomor telepon tidak valid.", "error");
        return;
    }

    const formattedNumber = '000' + cleanedNumber;
    const url = `http://localhost:60024/dial/telp=${encodeURIComponent(formattedNumber)}`;

    // console.log(`Mencoba memanggil: ${url}`);

    try {
        // Cukup kirim requestnya. Jika tidak ada error jaringan, kita anggap berhasil.
        await fetch(url);

        // Karena kita bisa mencapai baris ini, berarti request telah terkirim.
        // Server softphone Anda yang akan menangani sisanya.
        console.log(`Panggilan ke ${formattedNumber} berhasil diinisiasi!`);
        Swal.fire("Berhasil", "Call di inisiasi.", "success");

        const modalEl = document.getElementById('confirmCallModal');
        const modalInstance = bootstrap.Modal.getInstance(modalEl);
        modalInstance.hide();

    } catch (error) {
        // Blok ini hanya akan terjadi jika ada error jaringan yang sebenarnya,
        // misalnya aplikasi softphone Anda tidak berjalan sama sekali.
        console.error('Terjadi kesalahan saat mencoba memanggil:', error);
        Swal.fire("Gagal memulai panggilan", "Pastikan aplikasi softphone Anda berjalan dan bisa diakses.", "error");

        const modalEl = document.getElementById('confirmCallModal');
        const modalInstance = bootstrap.Modal.getInstance(modalEl);
        modalInstance.hide();
    }

    // try {
    //     const response = await fetch(url);

    //     if (!response.ok) {
    //         throw new Error(`HTTP error! status: ${response.status}`);
    //     }

    //     const result = await response.json();

    //     console.log("Response dari API:", result);

    //     if (result.Message === "1") {
    //         Swal.fire("Berhasil", "Permintaan panggilan telah dikirim.", "success");
    //     } else {
    //         Swal.fire("Gagal", "Panggilan tidak berhasil diproses.", "error");
    //     }

    //     const modalEl = document.getElementById('confirmCallModal');
    //     const modalInstance = bootstrap.Modal.getInstance(modalEl);
    //     modalInstance.hide();

    // } catch (error) {
    //     console.error("Error calling API:", error);
    //     Swal.fire("Error", "Gagal terhubung ke server.", "error");

    //     const modalEl = document.getElementById('confirmCallModal');
    //     const modalInstance = bootstrap.Modal.getInstance(modalEl);
    //     modalInstance.hide();
    // }
}


