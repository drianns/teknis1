class Ticket {
    constructor() {
        this.lib = {
            indexDB: new IndexDB(currentAgent.id),
            http: new Http(),
        };

        this.var = {
            chat_ticket_user: null,
            chat_header: null,
            chat_user: null,
            channels: [],
            urls: {
                getCustomer: '/chat/v3/ticket/customer/find',
                addCustomer: '/chat/v3/ticket/customer/add',
                updateCustomer: '/chat/v3/ticket/customer/update',
                getCustomerChannels: '/chat/v3/ticket/customer/channels',
                getAccountID:`/chat/v3/ticket/customer/accountid`,
                deleteCustomerChannels: '/chat/v3/ticket/customer/channels/delete',
                createTicket: '/chat/v3/ticket/create',
                getHistoryTicket: '/chat/v3/ticket/history'
            }
        };

        this.const = {
            whatsapp_channels: [4,8,9,10,13,14],
            sosmed_channels: [1,2,11,12]
        }

        this.el = {
            toast: Swal.mixin({
                toast: true,
                position: "top",
                showConfirmButton: false,
                timer: 1500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                },
                customClass: {
                    popup: 'custom-swal'  // Tambahkan custom class
                }
              }),
            formAddCustomer: {
                name: $("#AddCustomer_Name"),
                email: $("#AddCustomer_Email"),
                phone: $("#AddCustomer_HP"),
                address: $("#AddCustomer_Address"),
                modal: $("#addCustomerBC")
            },
            formEditCustomer: {
                name: $("#EditCustomer_Name"),
                email: $("#EditCustomer_Email"),
                phone: $("#EditCustomer_HP"),
                address: $("#EditCustomer_Address"),
                modal: $("#editCustomerBC")
            },
            panelProfile: {
                photo: $("#Profile_Image"),
                name: $("#Profile_Nama"),
                phone: $("#Profile_NomorTelepon"),
                email: $("#Profile_Email"),
                address: $("#Profile_Address"),
                addCustomerSideBar: $("#addCustomerButton"),
                addExistingCustomerSideBar: $("#addCustomerExistingButton"),
                editCustomerSideBar: $("#editCustomerButton"),
                switchProfileSideBar: $("#switchProfileButton"),
            },
            channels: $("#Div_CustomerChannel"),
            ticketHistory: $("#history-ticket-list"),
        }
    }

    async getCustomer(chatHeader) {
        await this.resetFormAddCustomer();
        await this.resetPanelProfile();
        await this.resetChannels();
        await this.resetFormEditCustomer();
        this.var.chat_header = chatHeader;
        let customer = await this.lib.http.send(this.var.urls.getCustomer, {
            channel_user_id: chatHeader.channel_user_id,
        });
        if (customer.message == 'Ticket Disabled') {
            return;
        }
        this.var.chat_user = customer.data.channel_user
        if (!this.var.chat_user.chat_ticket_user_id) {
            this.el.panelProfile.addCustomerSideBar.show();
            this.el.panelProfile.addExistingCustomerSideBar.show();
            this.el.panelProfile.editCustomerSideBar.hide();
            this.el.panelProfile.switchProfileSideBar.hide();
            await this.setFormAddCustomer();
            this.el.toast.fire({
                icon: 'warning',
                title: 'User tidak ditemukan, silahkan buat user terlebih dahulu'
            })
        } else {
            this.el.toast.fire({
                icon: 'success',
                title: 'User ditemukan'
            })
            $("#inichatticketuser").val(this.var.chat_user.chat_ticket_user_id);
            this.el.panelProfile.addCustomerSideBar.hide();
            this.el.panelProfile.editCustomerSideBar.show();
            this.el.panelProfile.addExistingCustomerSideBar.hide();
            this.el.panelProfile.switchProfileSideBar.show();
            this.var.chat_ticket_user = customer.data.channel_user.chat_ticket_user
            await this.getChannel();
            await this.getAccountid();
            await this.setPanelProfile();
            await this.parseChannels();
            await this.setFormEditCustomer();
            await this.resetTicketHistory();
            await this.parseTicketHistory();
        }
    }

    async resetFormAddCustomer() {
        this.el.formAddCustomer.name.val('');
        this.el.formAddCustomer.email.val('');
        this.el.formAddCustomer.phone.val('');
        this.el.formAddCustomer.address.val('');
    }

    async resetFormEditCustomer() {
        this.el.formEditCustomer.name.val('');
        this.el.formEditCustomer.email.val('');
        this.el.formEditCustomer.phone.val('');
        this.el.formEditCustomer.address.val('');
    }

    async setFormEditCustomer() {
        this.el.formEditCustomer.name.val(this.var.chat_ticket_user.name);
        this.el.formEditCustomer.email.val(this.var.chat_ticket_user.email);
        this.el.formEditCustomer.phone.val(this.var.chat_ticket_user.phone);
        this.el.formEditCustomer.address.val(this.var.chat_ticket_user.address);
    }

    async disableFormAddCustomer() {
        this.el.formAddCustomer.name.val(this.var.chat_ticket_user.name);
        this.el.formAddCustomer.email.val(this.var.chat_ticket_user.email);
        this.el.formAddCustomer.phone.val(this.var.chat_ticket_user.phone);
        this.el.formAddCustomer.address.val(this.var.chat_ticket_user.address);
        this.el.formAddCustomer.name.attr('disabled', 'disabled');
        this.el.formAddCustomer.email.attr('disabled', 'disabled');
        this.el.formAddCustomer.phone.attr('disabled', 'disabled');
        this.el.formAddCustomer.address.attr('disabled', 'disabled');
    }

    async clearSearchCustomer() {
        this.var.chat_ticket_user = null
        this.el.formAddCustomer.name.val('');
        this.el.formAddCustomer.email.val('');
        this.el.formAddCustomer.phone.val('');
        this.el.formAddCustomer.address.val('');
        this.el.formAddCustomer.name.removeAttr('disabled');
        this.el.formAddCustomer.email.removeAttr('disabled');
        this.el.formAddCustomer.phone.removeAttr('disabled');
        this.el.formAddCustomer.address.removeAttr('disabled');
    }

    async setFormAddCustomer() {
        this.el.formAddCustomer.name.val(this.var.chat_user.name);
        if (this.const.whatsapp_channels.includes(this.var.chat_user.channel_id)) {
            this.el.formAddCustomer.phone.val(this.var.chat_user.email);
        } else if (this.var.chat_user.channel_id == 7) {
            this.el.formAddCustomer.email.val(this.var.chat_user.email);
        }
    }

    async resetPanelProfile() {
        this.el.panelProfile.photo.attr("src","/assets/images/users/Profile.png");
        this.el.panelProfile.name.empty();
        this.el.panelProfile.email.empty();
        this.el.panelProfile.phone.empty();
        this.el.panelProfile.address.empty();
    }

    async setPanelProfile() {
        this.el.panelProfile.photo.attr("src","/assets/images/users/Profile.png");
        this.el.panelProfile.name.html(this.var.chat_ticket_user.name);
        this.el.panelProfile.email.html(this.var.chat_ticket_user.email);
        this.el.panelProfile.phone.html(this.var.chat_ticket_user.phone);
        this.el.panelProfile.address.html(this.var.chat_ticket_user.address);
    }


    async saveCustomer(data) {
        let response = await this.lib.http.send(this.var.urls.addCustomer, data);
        if (response?.success) {
            this.el.toast.fire({
                icon: 'success',
                title: 'Data Profil Customer berhasil dibuat'
            })
            this.var.chat_ticket_user = response.data
            $("#inichatticketuser").val(this.var.chat_ticket_user.id);
            this.el.panelProfile.addCustomerSideBar.hide();
            this.el.panelProfile.addExistingCustomerSideBar.hide();
            this.el.panelProfile.editCustomerSideBar.show();
            this.el.panelProfile.switchProfileSideBar.show();

            // Tunggu semua operasi selesai sebelum menutup modal
            await this.getChannel();
            await this.getAccountid();
            await this.setPanelProfile();
            await this.parseChannels();
            await this.setFormEditCustomer();

            // Tutup modal setelah semua operasi selesai
            this.el.formAddCustomer.modal.modal('hide');

            // Reset form untuk penggunaan selanjutnya
            await this.resetFormAddCustomer();
        } else {
            this.el.toast.fire({
                icon: 'error',
                title: 'Data Profil Customer gagal dibuat'
            })
        }
    }

    async updateCustomer(data) {
        let response = await this.lib.http.send(this.var.urls.updateCustomer, data);
        if (response?.success) {
            this.el.toast.fire({
                icon: 'success',
                title: 'Data Profil Customer berhasil diubah'
            })
            this.el.formEditCustomer.modal.modal('hide')
            this.var.chat_ticket_user = response.data
            $("#inichatticketuser").val(this.var.chat_ticket_user.id);
            await this.setPanelProfile()
        } else {
            this.el.toast.fire({
                icon: 'error',
                title: 'Data Profil Customer gagal diubah'
            })
        }
    }

    async getChannel() {
        let response = await this.lib.http.send(this.var.urls.getCustomerChannels, {
            chat_ticket_user_id: this.var.chat_ticket_user.id
        });
        this.var.channels = response.data;
    }

    async getAccountid(){
        let response = await this.lib.http.send(this.var.urls.getAccountID, {
            chat_ticket_user_id: this.var.chat_ticket_user.id
        });
        this.var.account_id = response.data;
    }

    async resetChannels() {
        this.el.channels.empty();
    }

    async parseChannels() {
        $("#Div_CustomerChannel").empty();
        if (this.var.channels && this.var.channels.length > 0) {
            this.var.channels.forEach(element => {
                let html = `<div class="col-xl-12 col-sm-12 mb-3">
                    <div class="card border bg-gray-800 border-0 shadow-none">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0 avatar rounded-circle me-3">
                                    <div class="avatar-sm align-self-center">
                                        <span class="avatar-title rounded-circle bg-primary text-light">
                                            <img src="${element.icon}" alt="${element.channel}" class="img-thumbnail d-block rounded-circle" style="width: 24px; height: 24px;">
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <h5 class="font-size-15 mb-1 text-truncate text-white" title="${element.text}">
                                        ${element.text}
                                    </h5>
                                    <p class="text-muted text-truncate mb-0">
                                        <i class="fas fa-circle text-success me-1" style="font-size: 8px;"></i>
                                        ${element.channel}
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="badge bg-success">Active</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
                $("#Div_CustomerChannel").append(html);
            });
        } else {
            $("#Div_CustomerChannel").append(`
                <div class="col-12">
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-info-circle mb-2"></i>
                        <p>No other channels found for this customer</p>
                    </div>
                </div>
            `);
        }
    }

    // async parseChannels() {
    //     this.var.channels.forEach(element => {
    //         let html = `<div class="card border bg-gray-800 border-0 shadow-none">
    //                     <div class="card-body p-4">
    //                         <div class="d-flex align-items-start">
    //                             <div class="flex-shrink-0 avatar rounded-circle me-3">
    //                                 <div class="avatar-sm align-self-center"><span class="avatar-title rounded-circle bg-undefined text-light"><img src="${element.icon}" alt="" class="img-thumbnail d-block rounded-circle"></span></div>
    //                             </div>
    //                             <div class="flex-grow-1 overflow-hidden">
    //                                 <h5 class="font-size-15 mb-1 text-truncate" title="${element.text}"><a href="#" class="text-white">${element.text}</a></h5>
    //                                 <p class="text-muted text-truncate mb-0">${element.channel}</p>
    //                             </div>
    //                             <div class="dropdown text-dark"><a class="btn btn-light btn-sm dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true"><i class="fa fas fa-ellipsis-h"></i></a>
    //                                 <ul class="dropdown-menu bg-gray-900 border-0 dropdown-menu-end">
    //                                     <li><a class="dropdown-item text-white hover:bg-gray-800 border-0" id="btnDeleteChannel" href="#" onclick="deleteOtherChannel(${element.channel_user_id})">Delete</a></li>
    //                                 </ul>
    //                             </div>
    //                         </div>
    //                     </div>
    //                 </div>`;
    //         this.el.channels.append(html);
    //     });
    // }

    async resetTicketHistory() {
        this.el.ticketHistory.empty();
    }

    async parseTicketHistory() {
        let response = await this.lib.http.send(this.var.urls.getHistoryTicket, {
            chat_ticket_user_id: this.var.chat_ticket_user.id
        })
        if (response.success) {
            if (response.data.length > 0) {
                response.data.forEach(element => {
                    // Determine status badge class
                    let status = (element.status || '').toLowerCase();
                    let statusClass = 'bg-gray-500';
                    switch (status) {
                        case 'open':
                            statusClass = 'bg-green-500';
                            break;
                        case 'closed':
                            statusClass = 'bg-red-500';
                            break;
                        case 'pending':
                            statusClass = 'bg-blue-500';
                            break;
                        case 'in_progress':
                        case 'progress':
                            statusClass = 'bg-yellow-500';
                            break;
                    }

                    let html = `
                    <div class="ticket-card mb-3 p-4 bg-gray-800/70 rounded-lg border border-gray-700 hover:bg-gray-700/70 transition-all duration-200 cursor-pointer group" onclick="DirectHistory(${element.id})">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-blue-500/20 rounded-full flex items-center justify-center group-hover:bg-blue-500/30 transition-colors">
                                    <i class="fas fa-ticket-alt text-blue-400"></i>
                                </div>
                                <div>
                                    <h5 class="text-white font-semibold text-sm mb-1">${element.ticket_number || 'N/A'}</h5>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="inline-block px-2 py-1 text-xs font-medium rounded-full ${statusClass} text-white">${element.status || 'Unknown'}</span>
                                <p class="text-gray-400 text-xs mt-1">${element.created_at || ''}</p>
                            </div>
                        </div>
                    </div>`;

                    this.el.ticketHistory.append(html);
                });
            }
        } else {
            await this.resetTicketHistory()

        }
    }

    async deleteChannel(data) {
        let response = await this.lib.http.send(this.var.urls.deleteCustomerChannels, data);
        if (response?.success) {
            this.el.toast.fire({
                icon: 'success',
                title: 'Channel berhasil dihapus'
            }).then((result) => {
                window.location.reload()
            })
        } else {
            this.el.toast.fire({
                icon: 'error',
                title: 'Channel gagal dihapus'
            })
        }
    }

    async submitTicket(data) {
        let response = await this.lib.http.sendFormData(this.var.urls.createTicket, data);
        console.log(response)
        return response
    }
}
