document.addEventListener('alpine:init', () => {
    const menuHierarchy = {
        "Master Data": { "Data Type": [], "Data Category": [], "Data Meta": [] },
        "Apps": { "Ticketing Department": [], "Taskboard": [], "Thread System": [], "Ticketing": [], "History Ticketing": [] },
        "Dashboard": [],
        "Management User": { "Data User Application": [], "Data Access Application": [] },
        "Master Customer": { "Data Table Customer": [], "Data Customer": [] },
        "File Manager": [],
        "Setup Channel Email": { "Dashboard Email": [], "Monitoring Email Response": [] },
        "Channel": { "Email": { "Inbox Email": [], "History Email": [] } },
        "Report": []
    };

    window.Alpine.data('accessTableData', () => {
        return {
            accesses: [],
            pagination: { total: 0, from: 0, to: 0, prev_page_url: null, next_page_url: null },
            search: '',
            filterLevel: '',
            limit: 10,
            open: false,
            previewOpen: false,
            formData: { agentRole: '', menuLevel1: '', menuLevel2: '', menuLevel3: '', description: '' },
            previewData: {},
            menuLevel2Options: [],
            menuLevel3Options: [],

            init() {
                this.loadTable();
                this.$watch('search', () => this.loadTable());
                this.$watch('filterLevel', () => this.loadTable());
                this.$watch('limit', () => this.loadTable());
            },

            async loadTable(url = null) {
                const config = document.getElementById('data-access-config');
                if(!config) return;
                const endpoint = url || config.dataset.get;

                try {
                    const response = await fetch(`${endpoint}${endpoint.includes('?') ? '&' : '?'}search=${this.search}&level_user=${this.filterLevel}&limit=${this.limit}`);
                    const data = await response.json();
                    this.accesses = data.data;
                    this.pagination = { total: data.total, from: data.from, to: data.to, prev_page_url: data.prev_page_url, next_page_url: data.next_page_url };
                } catch (error) {
                    console.error('Error loading accesses:', error);
                }
            },

            openSettingModal() {
                this.resetForm();
                this.open = true;
            },

            resetForm() {
                this.formData = { agentRole: '', menuLevel1: '', menuLevel2: '', menuLevel3: '', description: '' };
                this.menuLevel2Options = [];
                this.menuLevel3Options = [];
            },

            updateMenuLevel2Options() {
                const l1 = this.formData.menuLevel1;
                this.formData.menuLevel2 = '';
                this.formData.menuLevel3 = '';
                this.menuLevel2Options = (l1 && !Array.isArray(menuHierarchy[l1])) ? Object.keys(menuHierarchy[l1]) : [];
                this.menuLevel3Options = [];
            },

            updateMenuLevel3Options() {
                const l1 = this.formData.menuLevel1;
                const l2 = this.formData.menuLevel2;
                this.formData.menuLevel3 = '';
                const sub = menuHierarchy[l1][l2];
                this.menuLevel3Options = Array.isArray(sub) ? sub : (sub ? Object.keys(sub) : []);
            },

            async saveMenuSetting() {
                if (!this.formData.agentRole || !this.formData.menuLevel1) return alert("Please fill required fields");
                const config = document.getElementById('data-access-config');
                try {
                    const response = await fetch(config.dataset.store, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                        body: JSON.stringify(this.formData)
                    });
                    const result = await response.json();
                    if (response.ok) { alert(result.message); this.open = false; this.loadTable(); }
                    else alert("Error: " + result.message);
                } catch (error) { console.error('Error saving access:', error); }
            },

            openPreview(item) {
                this.previewData = item;
                this.previewOpen = true;
            },

            async deleteAccess(id) {
                if (!confirm('Are you sure you want to delete this access?')) return;
                try {
                    const response = await fetch(`/management-user/data-access-application/${id}`, {
                        method: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                    });
                    const result = await response.json();
                    if (response.ok) { alert(result.message); this.loadTable(); }
                    else alert("Error: " + result.message);
                } catch (error) { console.error('Error deleting access:', error); }
            }
        };
    });
});

