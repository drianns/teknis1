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
                getCustomer: '/ticketing/ticket/customer/find',
                addCustomer: '/ticketing/ticket/customer/add',
                updateCustomer: '/ticketing/ticket/customer/update',
                getCustomerChannels: '/ticketing/ticket/customer/channels',
                deleteCustomerChannels: '/ticketing/ticket/customer/channels/delete',
                createTicket: '/ticketing/ticket/create',
                getHistoryTicket: '/ticketing/ticket/history',
                getCustomerByPhone: '/ticketing/ticket/customer/find/by-phone', // Tambahkan URL baru untuk mencari customer berdasarkan nomor telepon
            }
        };

        this.const = {
            whatsapp_channels: [4,8,9,10,13],
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
                editCustomerSideBar: $("#editCustomerButton"),
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
            this.el.panelProfile.editCustomerSideBar.hide();
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
            this.el.panelProfile.addCustomerSideBar.hide();
            this.el.panelProfile.editCustomerSideBar.show();
            this.var.chat_ticket_user = customer.data.channel_user.chat_ticket_user
            await this.getChannel();
            await this.setPanelProfile();
            await this.parseChannels();
            await this.setFormEditCustomer();
            await this.resetTicketHistory();
            await this.parseTicketHistory();
        }
    }

    async getCustomerByPhone(phoneNumber) {
        try {
            // Reset semua form dan panel sebelum memulai
            await this.resetFormAddCustomer();
            await this.resetPanelProfile();
            await this.resetChannels();
            await this.resetFormEditCustomer();

            // Kirim permintaan ke server untuk mencari customer
            let response = await this.lib.http.send(this.var.urls.getCustomerByPhone, {
                phone: phoneNumber
            });
            console.log("Response dari server:", response);

            // Handle jika fitur ticketing dinonaktifkan
            if (response.message === 'Ticket Disabled') {
                this.el.toast.fire({
                    icon: 'warning',
                    title: 'Fitur Ticket Dinonaktifkan'
                });
                return;
            }

            // Handle jika customer tidak ditemukan
            if (!response.success) {
                this.el.toast.fire({
                    icon: 'warning',
                    title: 'Customer tidak ditemukan'
                });

                // Tampilkan sidebar untuk menambah customer baru
                this.el.panelProfile.addCustomerSideBar.show();
                this.el.panelProfile.editCustomerSideBar.hide();

                // Isi nomor telepon di form tambah customer
                this.el.formAddCustomer.phone.val(phoneNumber);

                // Set nilai default untuk profile kosong
                this.el.panelProfile.photo.attr("src", "/assets/images/users/Profile.png");
                this.el.panelProfile.name.html("-");
                this.el.panelProfile.email.html("-");
                this.el.panelProfile.phone.html(phoneNumber);
                this.el.panelProfile.address.html("-");

                return;
            }

            // Handle jika customer ditemukan
            this.el.toast.fire({
                icon: 'success',
                title: 'Customer ditemukan'
            });

            // Sembunyikan sidebar tambah customer dan tampilkan sidebar edit customer
            this.el.panelProfile.addCustomerSideBar.hide();
            this.el.panelProfile.editCustomerSideBar.show();

            // Simpan data customer ke variabel global
            this.var.chat_ticket_user = response.data;

            // Update UI dengan data customer
            this.el.panelProfile.photo.attr("src", "/assets/images/users/Profile.png"); // Default photo
            this.el.panelProfile.name.html(response.data.name || "-");
            this.el.panelProfile.email.html(response.data.email || "-");
            this.el.panelProfile.phone.html(response.data.phone || "-");
            this.el.panelProfile.address.html(response.data.address || "-");

            // Ambil data channel dan update UI
            await this.getChannel();
            await this.setPanelProfile();
            await this.parseChannels();
            await this.setFormEditCustomer();
            await this.resetTicketHistory();
            await this.parseTicketHistory();
        } catch (error) {
            console.error("Error fetching customer data:", error);

            // Reset UI untuk kasus error
            this.el.panelProfile.addCustomerSideBar.show();
            this.el.panelProfile.editCustomerSideBar.hide();

            // Isi nomor telepon di form tambah customer
            this.el.formAddCustomer.phone.val(phoneNumber);

            // Set nilai default untuk profile kosong
            this.el.panelProfile.photo.attr("src", "/assets/images/users/Profile.png");
            this.el.panelProfile.name.html("-");
            this.el.panelProfile.email.html("-");
            this.el.panelProfile.phone.html(phoneNumber);
            this.el.panelProfile.address.html("-");

            // Tampilkan toast error
            this.el.toast.fire({
                icon: 'error',
                title: 'Error saat mencari customer'
            });
        }
    }

    async resetFormAddCustomer() {
        this.el.formAddCustomer.name.empty();
        this.el.formAddCustomer.email.empty();
        this.el.formAddCustomer.phone.empty();
        this.el.formAddCustomer.address.empty();
    }

    async resetFormEditCustomer() {
        this.el.formEditCustomer.name.val();
        this.el.formEditCustomer.email.val();
        this.el.formEditCustomer.phone.val();
        this.el.formEditCustomer.address.val();
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
            this.el.panelProfile.addCustomerSideBar.hide();
            this.el.panelProfile.editCustomerSideBar.show();
            this.var.chat_ticket_user = response.data
            await this.getChannel();
            await this.setPanelProfile();
            await this.parseChannels();
            await this.setFormEditCustomer();
            this.el.formAddCustomer.modal.modal('hide')
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

    async resetChannels() {
        this.el.channels.empty();
    }

    async parseChannels() {
        this.var.channels.forEach(element => {
            let html = `<div class="card border bg-gray-800 border-0 shadow-none">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0 avatar rounded-circle me-3">
                                    <div class="avatar-sm align-self-center"><span class="avatar-title rounded-circle bg-undefined text-light"><img src="${element.icon}" alt="" class="img-thumbnail d-block rounded-circle"></span></div>
                                </div>
                                <div class="flex-grow-1 overflow-hidden">
                                    <h5 class="font-size-15 mb-1 text-truncate" title="${element.text}"><a href="#" class="text-white">${element.text}</a></h5>
                                    <p class="text-muted text-truncate mb-0">${element.channel}</p>
                                </div>
                                <div class="dropdown text-dark"><a class="btn btn-light btn-sm dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true"><i class="fa fas fa-ellipsis-h"></i></a>
                                    <ul class="dropdown-menu bg-gray-900 border-0 dropdown-menu-end">
                                        <li><a class="dropdown-item text-white hover:bg-gray-800 border-0" id="btnDeleteChannel" href="#" onclick="deleteOtherChannel(${element.channel_user_id})">Delete</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>`;
            this.el.channels.append(html);
        });
    }

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
                    let html = `<li class="active">
                        <a href="#" class="mt-0" onclick="DirectHistory(${element.id})">
                            <div class="d-flex align-items-start">
                                <div class="flex-shrink-0 user-img online align-self-center me-3">
                                    <div class="avatar-sm align-self-center bg-danger text-danger rounded-circle font-size-22 text-center">
                                        <i class='bx bx-message text-light'></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="text-truncate font-size-14 mb-1">${element.ticket_number}</h5>
                                    <p class="text-truncate mb-0">${element.status}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="font-size-11">${element.created_at}</div>
                                </div>
                            </div>
                        </a>
                    </li>`
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

    // async submitTicket(data) {
    //     try {
    //         const response = await fetch(this.var.urls.createTicket, {
    //             method: 'POST',
    //             body: data,
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             }
    //         });

    //         const result = await response.json();
    //         return result;
    //     } catch (error) {
    //         console.error('Error submitting ticket:', error);
    //         return {
    //             success: false,
    //             message: 'Failed to submit ticket: ' + error.message
    //         };
    //     }
    // }
}