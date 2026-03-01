const INSTANCE = new Instance();
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
    await $(INSTANCE.lib.chat.el.chatList).html("");
    if (id == "resolved" && !INSTANCE.var.chatResolvedLoaded) {
        await INSTANCE.http_chatHeaderResolveds_get(
            "/chat/v3/get-chat-header-resolveds"
        );
        INSTANCE.var.chatResolvedLoaded = true;
    } else if (id == "served") {
        await INSTANCE.http_chatHeaders_get("/chat/v3/get-chat-headers");
    }
    await INSTANCE.ui_chatHeader_fetch();
}

function openChatHeaderHistory(id) {
    let chatHeaderJson = INSTANCE.lib.chat.var.chatHeaderHistories.find(
        (chatHeader) => chatHeader.id == id
    );
    if (!chatHeaderJson) return;
    openChatHeader(chatHeaderJson);
}

async function openChatHeader(chatHeader = null) {
    if (typeof chatHeader !== "object") {
        chatHeader = INSTANCE.storage.chatHeaders.find(
            (header) => header.id == chatHeader
        );
    }

    if (chatHeader) {
        INSTANCE.lib.chat.setChatHeader(chatHeader);
        await INSTANCE.lib.message.setChatHeader(chatHeader);

        $(INSTANCE.lib.message.el.chatMessages).html("");
        await INSTANCE.lib.message.loadChatMessages(chatHeader);
        await INSTANCE.lib.chat.loadChatHistories(chatHeader);
        await INSTANCE.lib.message.scrollSmoothlyToBottom(
            ".chat-conversation .simplebar-content-wrapper"
        );
    }
    await INSTANCE.lib.ticket.getCustomer(chatHeader);

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
    e.preventDefault();
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
    theme: "bootstrap-5",
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
            "<div class='select2-result-repository clearfix'>" +
            "<div class='select2-result-repository__meta'>" +
                "<div class='select2-result-repository__title'></div>" +
                "<div class='select2-result-repository__description'></div>" +
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
    var value = $(this).val()
    var uri = listUrls.ticketGetByCategory ?? '';
    uri = uri.replace(':variable', value);
    $.getJSON(uri, function(data) {
        $('#Form_Ticket_SubKategori').empty();
        $('#Form_Ticket_SubKategori').append('<option hidden>Sub Kategori</option>');
        $.each(data, function(key, val) {
            $('select[name="Form_Ticket_SubKategori"]').append('<option value="' + val.name + '">' + val.name + '</option>');
        });
    });
})

// $("#buttonEndChatAction").click(function (e) {
//     e.preventDefault();

//     // Disable button biar nggak double submit
//     $("button.chat-end").prop("disabled", true);

//     // Ambil CSRF token dari meta (penting buat Laravel)
//     const csrfToken = $('meta[name="csrf-token"]').attr('content');

//     // Buat FormData buat kirim data + file
//     var fd = new FormData();

//     // Append file upload
//     var files = document.getElementById('file-bc').files;
//     for (var x = 0; x < files.length; x++) {
//         fd.append("attachments[]", files[x]);
//     }

//     // Append data lainnya dari form
//     fd.append("chat_header_id", INSTANCE.lib.chat.var.selected.id);
//     fd.append("priority", $("#Form_Ticket_Priority").val());
//     fd.append("status", $("#Form_Ticket_Status").val());
//     fd.append("subject", $("#Form_Ticket_Subject").val());
//     fd.append("kategori", $("#Form_Ticket_Kategori option:selected").text());
//     fd.append("subkategori", $("#Form_Ticket_SubKategori").val());
//     fd.append("question", $("#Ticket_Complaints").val());
//     fd.append("answer", $("#Ticket_NoteAgent").val());

//     // Proses submit ke server (submitTicket adalah fungsi custom kamu kan?)
//     INSTANCE.lib.ticket.submitTicket(fd, csrfToken).then((res) => {
//         console.log("Response dari server:", res);

//         // Cek jika berhasil (ini asumsi response ada properti 'success' atau 'status')
//         if (res.success || res.status === "success") {
//             // Feedback ke user (optional bisa pakai sweetalert / toast)
//             alert("Ticket berhasil dikirim!");

//             // Sembunyikan chat input section
//             $(".chat-input-section").hide();

//             // Balik ke tab home
//             $('.nav-tabs a[href="#home2"]').tab("show");

//             // Reset form input + file + textarea
//             $("#formEndChat textarea").val("");
//             $("#placeNewAdditional").html("");
//             $('#file-bc').val("");

//             // Update chatHeaders status di local data kamu
//             let index = INSTANCE.storage.chatHeaders.findIndex(
//                 (chat_header) => chat_header.id == INSTANCE.lib.chat.var.selected.id
//             );

//             if (index >= 0) {
//                 let chat_header = INSTANCE.storage.chatHeaders[index];
//                 chat_header.status = "close";
//                 chat_header.ended_at = new Date().toISOString();

//                 // Update array chatHeaders lokal
//                 INSTANCE.storage.chatHeaders[index] = chat_header;

//                 // Kalau lagi di tab 'served', hapus chat dari list UI
//                 if (INSTANCE.var.currentTab == "served") {
//                     INSTANCE.lib.chat.elChatItemRemove(chat_header.id);
//                 }
//             }
//         } else {
//             alert("Gagal kirim ticket. Coba lagi!");
//             $("button.chat-end").prop("disabled", false);
//         }
//     }).catch((err) => {
//         console.error("Error submit ticket:", err);
//         alert("Ada masalah koneksi, coba lagi.");
//         $("button.chat-end").prop("disabled", false);
//     });
// });


// FormEndPhone handling
document.addEventListener('DOMContentLoaded', function() {
    // Initialize form elements
    const formElements = {
        agentName: document.getElementById('Form_Ticket_Agent_Name'),
        priority: document.getElementById('Form_Ticket_Priority'),
        status: document.getElementById('Form_Ticket_Status'),
        subject: document.getElementById('Form_Ticket_Subject'),
        category: document.getElementById('Form_Ticket_Kategori'),
        subCategory: document.getElementById('Form_Ticket_SubKategori'),
        complaints: document.getElementById('Ticket_Complaints'),
        noteAgent: document.getElementById('Ticket_NoteAgent'),
        fileInput: document.getElementById('file-bc'),
        sourceType: document.getElementById('Form_Ticket_Source_Type')
    };

    // Handle form submission
    document.getElementById('buttonEndChatAction').addEventListener('click', async function(e) {
        e.preventDefault();

        // Validate required fields
        if (!validateForm(formElements)) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please fill in all required fields',
                width: '300px',
                customClass: {
                    popup: 'small-swal'
                }
            });
            return;
        }

        try {
            const formData = new FormData();

            // Add form fields to FormData
            formData.append('agent_name', formElements.agentName.value);
            formData.append('priority', formElements.priority.value);
            formData.append('status', formElements.status.value);
            formData.append('subject', formElements.subject.value);
            formData.append('kategori', formElements.category.options[formElements.category.selectedIndex].text);
            formData.append('subcategory_id', formElements.subCategory.value);
            formData.append('complaints', formElements.complaints.value);
            formData.append('note_agent', formElements.noteAgent.value);
            formData.append('source_type', formElements.sourceType.value);

            // Handle file uploads
            const files = formElements.fileInput.files;
            for (let i = 0; i < files.length; i++) {
                formData.append('files[]', files[i]);
            }

            // Disable submit button while processing
            const submitButton = document.getElementById('buttonEndChatAction');
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';

            // Send request to server
            const response = await fetch('/ticketing/ticket/save', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const result = await response.json();

            if (result.status) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Ticket has been created successfully',
                    width: '300px',
                    customClass: {
                        popup: 'small-swal'
                    }
                }).then(() => {
                    // Reset form and refresh necessary components
                    resetForm(formElements);
                    if (typeof refreshTicketList === 'function') {
                        refreshTicketList();
                    }
                });
            } else {
                throw new Error(result.message || 'Failed to create ticket');
            }

        } catch (error) {
            console.error('Error creating ticket:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Failed to create ticket',
                width: '300px',
                customClass: {
                    popup: 'small-swal'
                }
            });
        } finally {
            // Re-enable submit button
            const submitButton = document.getElementById('buttonEndChatAction');
            submitButton.disabled = false;
            submitButton.innerHTML = '<i class="fa fa-save"></i>&nbsp;Save & Closed';
        }
    });
});

// Form validation function
function validateForm(elements) {
    const requiredFields = [
        { element: elements.priority, name: 'Priority' },
        { element: elements.status, name: 'Status' },
        { element: elements.subject, name: 'Subject' },
        { element: elements.category, name: 'Category' },
        { element: elements.subCategory, name: 'Sub Category' },
        { element: elements.complaints, name: 'Complaints' },
        { element: elements.noteAgent, name: 'Agent Note' },
        { element: elements.sourceTypeAgent, name: 'Source Type' }
    ];

    for (const field of requiredFields) {
        if (!field.element.value.trim()) {
            field.element.focus();
            return false;
        }
    }

    return true;
}

// Form reset function
function resetForm(elements) {
    elements.priority.value = '';
    elements.status.value = '';
    elements.subject.value = '';
    elements.category.value = '';
    elements.subCategory.value = '';
    elements.complaints.value = '';
    elements.noteAgent.value = '';
    elements.fileInput.value = '';
    elements.sourceType.value = '';
}
