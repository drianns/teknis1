(() => {
    const UserPicker = {
        props: ["modelValue"],
        emits: ["update:modelValue"],
        data() {
            return {
                query: "",
                results: [],
                loading: false,
                error: null,
                selectedUser: {
                    id: null,
                    name: null,
                },
            };
        },
        computed: {
            ui() {
                return useUIStore();
            },
            gstore() {
                return useGroupStore();
            },
            gid() {
                return this.gstore.activeGroupId;
            },
        },
        template: `
      <div>
        <div class="input-group mb-2">
          <input v-model="query" class="form-control bg-gray-800 text-gray-200 border-0" placeholder="Search user by name/email">
          <button class="btn btn-outline-light" :disabled="loading" @click="search">Search</button>
        </div>
        <div v-if="error" class="alert alert-danger py-1 px-2">{{ error }}</div>
        <ul class="list-group list-group-flush" v-if="results.length">
          <li v-for="u in results" :key="u.id"
              class="list-group-item bg-gray-900 text-gray-200 border-0 d-flex justify-content-between align-items-center">
            <div class="min-w-0">
              <div class="fw-semibold truncate">{{ u.name }}</div>
              <small class="text-gray-400 truncate">{{ u.email }}</small>
            </div>
            <button class="btn btn-sm btn-success" @click="setSelectedUser(u)" v-show="selectedUser.id !== u.id">Select</button>
            <button class="btn btn-sm btn-danger" @click="cancelSelectUser" v-show="selectedUser.id === u.id">Cancel</button>
          </li>
        </ul>
      </div>
    `,
        methods: {
            async search() {
                this.loading = true;
                this.error = null;
                try {
                    const d = await GIAPI.searchUsers(this.gid, this.query);
                    this.results = d || [];
                } catch (e) {
                    this.error = (e && e.message) || "Search failed";
                } finally {
                    this.loading = false;
                }
            },
            async setSelectedUser(user) {
                this.selectedUser = user;
                this.$emit("update:modelValue", user);
                console.log("selectedUser", this.selectedUser);
            },
            async cancelSelectUser() {
                this.selectedUser = {
                    id: null,
                    name: null,
                }
                this.$emit("update:modelValue", null);
                console.log("selectedUser", this.selectedUser);
            },            
        },
    };

    const InviteMembersModal = {
        components: { UserPicker },
        template: `
      <div v-if="ui.showInvite" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,.6);">
        <div class="modal-dialog modal-lg modal-dialog-centered">
          <div class="modal-content bg-gray-900 text-gray-200 border-0">
            <div class="modal-header border-0">
              <h5 class="modal-title">Invite Members</h5>
              <button type="button" class="btn-close btn-close-white" @click="close"></button>
            </div>
            <div class="modal-body">
              <div class="row g-3">
                <div class="col-md-6">
                  <h6 class="text-white mb-2">Pick a user</h6>
                  <user-picker v-model="selectedUser"></user-picker>
                </div>
                <div class="col-md-6">
                  <h6 class="text-white mb-2">Group overview</h6>
                  <div v-if="loading" class="text-gray-400">Loading...</div>
                  <div v-else>
                    <div class="mb-2"><strong>{{ detail?.name }}</strong></div>
                    <div class="text-sm text-gray-400 mb-2" v-if="detail?.description">{{ detail.description }}</div>
                    <div class="text-sm text-gray-400">Members (active + pending):</div>
                    <ul class="list-group list-group-flush">
                      <li v-for="m in detail?.members || []" :key="m.id"
                          class="list-group-item bg-gray-900 text-gray-200 border-0 d-flex justify-content-between">
                        <div>
                          <div class="fw-semibold">{{ m.user?.name }}</div>
                          <small class="text-gray-400">role: {{ m.role }} • status: {{ m.status }}</small>
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer border-0">
              <button class="btn btn-light" @click="close">Close</button>
              <button class="btn btn-success" :disabled="!selectedUser || inviting" @click="invite">
                {{ inviting ? 'Inviting...' : 'Invite' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    `,
        data() {
            return {
                selectedUser: null,
                inviting: false,
                detail: null,
                loading: false,
            };
        },
        computed: {
            ui() {
                return useUIStore();
            },
            gstore() {
                return useGroupStore();
            },
            gid() {
                return this.gstore.activeGroupId;
            },
        },
        methods: {
            async loadDetail() {
                if (!this.gid) return;
                this.loading = true;
                try {
                    this.detail = await GIAPI.showGroup(this.gid);
                } finally {
                    this.loading = false;
                }
            },
            async invite() {
                if (!this.selectedUser || !this.gid) return;
                this.inviting = true;
                try {
                    await GIAPI.invite(this.gid, this.selectedUser.id);
                    await this.loadDetail();
                    this.selectedUser = null;
                } catch (e) {
                    alert("Invite failed: " + (e?.message || e));
                } finally {
                    this.inviting = false;
                }
            },
            close() {
                this.ui.closeInvite();
            },
        },
        watch: {
            "ui.showInvite": {
                immediate: true,
                async handler(v) {
                    if (v) await this.loadDetail();
                },
            },
            selectedUser(v) {
                if (v) alert("Ready to invite " + v.name);
            },
        },
    };

    window.InviteMembersModal = InviteMembersModal;
})();
