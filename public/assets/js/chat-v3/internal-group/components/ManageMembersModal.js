(() => {
    const ManageMembersModal = {
        template: `
      <div v-if="ui.showManage" class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,.6);">
        <div class="modal-dialog modal-xl modal-dialog-centered">
          <div class="modal-content bg-gray-900 text-gray-200 border-0">
            <div class="modal-header border-0">
              <h5 class="modal-title">Manage Members</h5>
              <button type="button" class="btn-close btn-close-white" @click="close"></button>
            </div>
            <div class="modal-body">
              <div class="d-flex align-items-center mb-3 gap-2">
                <div class="text-sm text-gray-400">Promote, remove, or handle leave requests.</div>
                <div class="ms-auto">
                  <select v-model="filter" class="form-select bg-gray-800 text-gray-200 border-0">
                    <option value="all">All</option>
                    <option value="active">Active</option>
                    <option value="pending">Pending</option>
                    <option value="left">Left</option>
                    <option value="removed">Removed</option>
                  </select>
                </div>
              </div>

              <div v-if="loading" class="text-gray-400">Loading...</div>
              <div v-else>
                <div class="mb-2">
                  <strong class="text-white">{{ detail?.name }}</strong>
                  <div class="text-sm text-gray-400" v-if="detail?.description">{{ detail.description }}</div>
                </div>

                <div class="table-responsive">
                  <table class="table table-dark table-borderless align-middle">
                    <thead>
                      <tr class="text-gray-400">
                        <th>User</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th v-if="anyLeaveRequested">Leave Reason</th>
                        <th class="text-end">Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="m in filtered" :key="m.id">
                        <td>
                          <div class="fw-semibold text-white">{{ m.user?.name }}</div>
                          <div class="text-xs text-gray-400">{{ m.user?.email }}</div>
                        </td>
                        <td class="text-capitalize">{{ m.role }}</td>
                        <td class="text-capitalize">
                          {{ m.status }}
                          <span v-if="m.leave_requested_at" class="badge bg-warning text-dark ms-2">leave requested</span>
                        </td>
                                          <td v-if="anyLeaveRequested">
                    <span v-if="m.leave_reason">{{ m.leave_reason }}</span>
                    <span v-else>-</span>
                  </td>
                        <td class="text-end">
                          <div class="btn-group">
                            <button class="btn btn-sm btn-outline-primary" :disabled="acting || !canPromote(m)"
                                    @click="promote(m)">Promote to admin</button>
                            <button class="btn btn-sm btn-outline-primary" :disabled="acting || !canDemote(m)"
                                    @click="demote(m)">Demote admin</button>                                    
                            <button class="btn btn-sm btn-outline-warning" :disabled="acting || !canApproveLeave(m)"
                                    @click="approveLeave(m)">Approve leave</button>
                            <button class="btn btn-sm btn-outline-secondary" :disabled="acting || !canRejectLeave(m)"
                                    @click="rejectLeave(m)">Reject leave</button>
                            <button class="btn btn-sm btn-outline-danger" :disabled="acting || !canRemove(m)"
                                    @click="remove(m)">Remove</button>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="modal-footer border-0">
              <button class="btn btn-light" @click="close">Close</button>
            </div>
          </div>
        </div>
      </div>
    `,
        data() {
            return {
                detail: null,
                loading: false,
                acting: false,
                filter: "all",
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
            filtered() {
                if (!this.detail?.members) return [];
                if (this.filter === "all") return this.detail.members;
                return this.detail.members.filter(
                    (m) => m.status === this.filter
                );
            },
            anyLeaveRequested() {
                return (this.detail?.members || []).some((m) => m.leave_requested_at);
            },
        },
        methods: {
            async load() {
                if (!this.gid) return;
                this.loading = true;
                try {
                    this.detail = await GIAPI.showGroup(this.gid);
                } finally {
                    this.loading = false;
                }
            },
            canPromote(m) {
                return m.status === "active" && m.role === "member";
            },
            canDemote(m) {
                return m.status === "active" && m.role === "admin";
            },
            canRemove(m) {
                return ["pending", "active"].includes(m.status);
            },
            canApproveLeave(m) {
                return m.status === "active" && !!m.leave_requested_at;
            },
            canRejectLeave(m) {
                return m.status === "active" && !!m.leave_requested_at;
            },
            async promote(m) {
                this.acting = true;
                try {
                    await GIAPI.promote(this.gid, m.id);
                    await this.load();
                } catch (e) {
                    alert("Failed to promote: " + (e?.message || e));
                } finally {
                    this.acting = false;
                }
            },
            async demote(m) {
                this.acting = true;
                try {
                    await GIAPI.demote(this.gid, m.id);
                    await this.load();
                } catch (e) {
                    alert("Failed to demote: " + (e?.message || e));
                } finally {
                    this.acting = false;
                }
            },
            async remove(m) {
                if (!confirm("Remove this member from the group?")) return;
                this.acting = true;
                try {
                    await GIAPI.remove(this.gid, m.id);
                    await this.load();
                } catch (e) {
                    alert("Failed to remove: " + (e?.message || e));
                } finally {
                    this.acting = false;
                }
            },
            async approveLeave(m) {
                this.acting = true;
                try {
                    await GIAPI.approveLeave(this.gid, m.id);
                    await this.load();
                } catch (e) {
                    alert("Failed to approve leave: " + (e?.message || e));
                } finally {
                    this.acting = false;
                }
            },
            async rejectLeave(m) {
                this.acting = true;
                try {
                    await GIAPI.rejectLeave(this.gid, m.id);
                    await this.load();
                } catch (e) {
                    alert("Failed to reject leave: " + (e?.message || e));
                } finally {
                    this.acting = false;
                }
            },
            close() {
                this.ui.closeManage();
            },
            async onGroupUpdated(e) {
                if (
                    this.ui.showManage &&
                    Number(e.detail?.groupId) === Number(this.gid)
                ) {
                    await this.load();
                }
            },
        },
        mounted() {
            window.addEventListener(
                "gi:active-group-updated",
                this.onGroupUpdated
            );
        },
        beforeUnmount() {
            window.removeEventListener(
                "gi:active-group-updated",
                this.onGroupUpdated
            );
        },
        watch: {
            "ui.showManage": {
                immediate: true,
                async handler(v) {
                    if (v) await this.load();
                },
            },
        },
    };

    window.ManageMembersModal = ManageMembersModal;
})();