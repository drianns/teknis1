class Ticket {
    constructor() {
        this.lib = {
            http: new Http(),
        };

        this.var = {
            userBc: null,
            otherChannelUserBc: [],
            chatHeader: null,
            comments: null,
            chat_ticket_user: null,
            channelUser: null,
            channels: [],
            urls: {
                getCustomer: '/facebook/v3/customer/find',
                addCustomer: '/facebook/v3/customer/add',
                // updateCustomer: '/chat/v3/ticket/customer/update',
                // getCustomerChannels: '/chat/v3/ticket/customer/channels',
                // deleteCustomerChannels: '/chat/v3/ticket/customer/channels/delete',
                // createTicket: '/chat/v3/ticket/create',
                // getHistoryTicket: '/chat/v3/ticket/history'
            }
        };

        this.Toast = Swal.mixin({
            toast: true,
            position: "top",
            showConfirmButton: false,
            timer: 1000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            },
            customClass: {
                popup: 'custom-swal'  // Tambahkan custom class
            },
        });

        this.const = {
            whatsapp_channels: [4,8,9,10],
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
                name: $("#Profile_NamaCustomer"),
                phone: $("#Profile_NomorTelepon"),
                email: $("#Profile_Email_Customer"),
                address: $("#Profile_Alamat"),
                addCustomerSideBar: $("#addCustomerButton"),
                addExistingCustomerSideBar: $("#addCustomerExistingButton"),
                editCustomerSideBar: $("#editCustomerButton"),
            },
            // channels: $("#Div_CustomerChannel"),
            ticketHistory: $("#history-ticket-list"),
        }
    }

    // Di dalam class Ticket (file ticket.js)
    async setCommentForTicket(comments) {
        this.var.comments = comments;
        console.log("setcomments : ", this.var.comments); 
    }
    // Di dalam class Ticket (file ticket.js)
    async setChatHeaderForTicket(chatHeader) {
        this.var.chatHeader = chatHeader; 
        console.log("setchatheader : ", this.var.chatHeader);
    }

    async getCustomer() {
        await this.resetFormAddCustomer();
        await this.resetPanelProfile();
        // await this.resetChannels();
        await this.resetFormEditCustomer();
        if (!this.var.chatHeader) {
            console.error("Error: chatHeader belum diset!");
            return;
        }
        let customer = await this.lib.http.send(this.var.urls.getCustomer, {
            channel_user_id: this.var.chatHeader.channel_user_id,
        });
        
        this.var.channelUser = customer.data.channel_user
        $("#channel_user_id").val(this.var.channelUser.id);
        if (!this.var.channelUser.chat_ticket_user_id) {
            this.el.panelProfile.addCustomerSideBar.show();
            this.el.panelProfile.addExistingCustomerSideBar.show();
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
            // $("#inichatticketuser").val(this.var.chat_user.chat_ticket_user_id);
            this.el.panelProfile.addCustomerSideBar.hide();
            this.el.panelProfile.addExistingCustomerSideBar.hide();
            this.el.panelProfile.editCustomerSideBar.show();
            this.var.chat_ticket_user = customer.data.channel_user.chat_ticket_user
            // await this.getChannel();
            await this.setPanelProfile();
            // await this.parseChannels();
            await this.setFormEditCustomer();
            // await this.resetTicketHistory();
            // await this.parseTicketHistory();
        }
    }

    async saveCustomer(data) {
        let response = await this.lib.http.send(this.var.urls.addCustomer, data);
        if (response?.success) {
            this.el.formAddCustomer.modal.modal('hide');
            this.el.toast.fire({
                icon: 'success',
                title: 'Data Profil Customer berhasil dibuat'
            })
            this.el.panelProfile.addCustomerSideBar.hide();
            this.el.panelProfile.addExistingCustomerSideBar.hide();
            this.el.panelProfile.editCustomerSideBar.show();
            this.var.chat_ticket_user = response.data
            // $("#inichatticketuser").val(this.var.chat_ticket_user.id);
            console.log("response data user :", response.data);
            // await this.getChannel();
            await this.setPanelProfile();
            // await this.parseChannels();
            await this.setFormEditCustomer();
        } else {
            this.el.toast.fire({
                icon: 'error',
                title: 'Data Profil Customer gagal dibuat'
            })
        }
    }

    async resetFormAddCustomer() {
        this.el.formAddCustomer.name.empty();
        this.el.formAddCustomer.email.empty();
        this.el.formAddCustomer.phone.empty();
        this.el.formAddCustomer.address.empty();
    }

    async resetPanelProfile() {
        this.el.panelProfile.photo.attr("src","/assets/images/users/Profile.png");
        this.el.panelProfile.name.empty();
        this.el.panelProfile.email.empty();
        this.el.panelProfile.phone.empty();
        this.el.panelProfile.address.empty();
    }

    async resetFormEditCustomer() {
        this.el.formEditCustomer.name.empty();
        this.el.formEditCustomer.email.empty();
        this.el.formEditCustomer.phone.empty();
        this.el.formEditCustomer.address.empty();
    }

    async setFormAddCustomer() {
        this.el.formAddCustomer.name.val(this.var.channelUser.name);
        if (this.const.whatsapp_channels.includes(this.var.channelUser.channel_id)) {
            this.el.formAddCustomer.phone.val(this.var.channelUser.email);
        } else if (this.var.channelUser.channel_id == 7) {
            this.el.formAddCustomer.email.val(this.var.channelUser.email);
        } else if (this.const.sosmed_channels.includes(this.var.channelUser.channel_id)) {
            this.el.formAddCustomer.email.val(this.var.channelUser.email);
        }
    }

    async setPanelProfile() {
        this.el.panelProfile.photo.attr("src","/assets/images/users/Profile.png");
        this.el.panelProfile.name.html(this.var.chat_ticket_user.name);
        this.el.panelProfile.email.html(this.var.chat_ticket_user.email);
        this.el.panelProfile.phone.html(this.var.chat_ticket_user.phone);
        this.el.panelProfile.address.html(this.var.chat_ticket_user.address);
    }

    async setFormEditCustomer() {
        this.el.formEditCustomer.name.val(this.var.chat_ticket_user.name);
        this.el.formEditCustomer.email.val(this.var.chat_ticket_user.email);
        this.el.formEditCustomer.phone.val(this.var.chat_ticket_user.phone);
        this.el.formEditCustomer.address.val(this.var.chat_ticket_user.address);
    }

}