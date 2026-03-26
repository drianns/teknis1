document.addEventListener('alpine:init', () => {
    window.Alpine.data('userModalData', () => {
        return {
            open: false,
            profileOpen: false,
            isEdit: false,
            isPreview: false,
            users: [],
            pagination: { total: 0, from: 0, to: 0, prev_page_url: null, next_page_url: null },
            search: '',
            limit: 10,
            
            formData: {
                id: null,
                userName: '',
                name: '',
                email: '',
                password: '',
                levelUser: '',
                department: '',
                groupAgent: '',
                site: '',
                status: 'Aktif',
                channelAgent: {
                    email: false, wa: false, inbound: false, outbound: false,
                    instagram: false, facebook: false, twitter: false, telegram: false
                },
                description: '',
                photoUrl: ''
            },

            channelOptions: {
                email: 'Email', wa: 'WA', inbound: 'Inbound', outbound: 'Outbound',
                instagram: 'Instagram', facebook: 'Facebook', twitter: 'Twitter', telegram: 'Telegram'
            },

            init() {
                this.loadTable();
                this.$watch('search', () => this.loadTable());
                this.$watch('limit', () => this.loadTable());
            },

            async loadTable(url = null) {
                const config = document.getElementById('data-user-config');
                if(!config) return;
                const endpoint = url || config.dataset.get;
                
                try {
                    const response = await fetch(`${endpoint}${endpoint.includes('?') ? '&' : '?'}search=${this.search}&limit=${this.limit}`);
                    const data = await response.json();
                    this.users = data.data;
                    this.pagination = {
                        total: data.total,
                        from: data.from,
                        to: data.to,
                        prev_page_url: data.prev_page_url,
                        next_page_url: data.next_page_url
                    };
                } catch (error) {
                    console.error('Error loading users:', error);
                }
            },

            toggleAllChannels(value) {
                if (this.isPreview) return;
                for (let key in this.formData.channelAgent) {
                    this.formData.channelAgent[key] = value;
                }
            },

            get isDepartmentActive() {
                return ['Supervisor', 'layer3'].includes(this.formData.levelUser);
            },

            get isGroupAgentActive() {
                return ['layer1', 'layer2'].includes(this.formData.levelUser);
            },

            get modalTitle() {
                if (this.isPreview) return 'Preview User Application';
                if (this.isEdit) return 'Edit User Application';
                return 'Form Add User Application';
            },

            resetForm() {
                this.isEdit = false;
                this.isPreview = false;
                this.formData = {
                    id: null,
                    userName: '',
                    name: '',
                    email: '',
                    password: '',
                    levelUser: '',
                    department: '',
                    groupAgent: '',
                    site: '',
                    status: 'Aktif',
                    channelAgent: {
                        email: false, wa: false, inbound: false, outbound: false,
                        instagram: false, facebook: false, twitter: false, telegram: false
                    },
                    description: '',
                    photoUrl: ''
                };
            },

            handleLevelUserChange() {
                if (!this.isDepartmentActive) this.formData.department = '';
                if (!this.isGroupAgentActive) this.formData.groupAgent = '';
            },

            openAddUserModal() {
                this.resetForm();
                this.open = true;
            },

            editUser(user) {
                this.resetForm();
                this.isEdit = true;
                this.formData = {
                    id: user.id,
                    userName: user.user_name,
                    name: user.name,
                    email: user.email,
                    password: '',
                    levelUser: user.level_user,
                    department: user.department || '',
                    groupAgent: user.group_agent || '',
                    site: user.site || '',
                    status: user.status,
                    channelAgent: user.channels || {
                        email: false, wa: false, inbound: false, outbound: false,
                        instagram: false, facebook: false, twitter: false, telegram: false
                    },
                    description: user.description || '',
                    photoUrl: user.photo_url || ''
                };
                this.open = true;
            },

            previewUser(user) {
                this.editUser(user);
                this.isPreview = true;
            },

            openProfile(user) {
                this.editUser(user);
                this.profileOpen = true;
            },

            async saveUser() {
                if (this.isPreview) {
                    this.open = false;
                    return;
                }

                const config = document.getElementById('data-user-config');
                const storeUrl = config ? config.dataset.store : '';
                
                const url = this.isEdit 
                    ? `/management-user/data-user-application/${this.formData.id}` 
                    : storeUrl;
                
                const method = this.isEdit ? 'PUT' : 'POST';

                try {
                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(this.formData)
                    });

                    const result = await response.json();
                    if (response.ok) {
                        alert(result.message);
                        this.open = false;
                        this.loadTable();
                    } else {
                        alert("Error: " + (result.message || "Failed to save user"));
                    }
                } catch (error) {
                    console.error('Error saving user:', error);
                    alert("Network error occurred");
                }
            },

            async deleteUser(id) {
                if (!confirm('Are you sure you want to delete this user?')) return;

                try {
                    const response = await fetch(`/management-user/data-user-application/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });

                    const result = await response.json();
                    if (response.ok) {
                        alert(result.message);
                        this.loadTable();
                    } else {
                        alert("Error: " + result.message);
                    }
                } catch (error) {
                    console.error('Error deleting user:', error);
                }
            }
        };
    });
});

