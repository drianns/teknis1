(() => {
    const GroupThreadMenu = {
        template: `
      <div class="dropdown">
        <a href="#" class="btn btn-link text-gray-300 p-0 dropdown-toggle shadow-none" data-bs-toggle="dropdown">
          <i class="fa fas fa-ellipsis-h"></i>
        </a>
        <ul class="dropdown-menu bg-gray-800 border-0 dropdown-menu-end">
          <!-- MEMBER: Request Leave -->
          <template v-if="isMember">
          <li>
            <a class="dropdown-item hover:bg-gray-900"
               :class="{ 'disabled': !canRequestLeave, 'text-gray cursor-not-allowed': !canRequestLeave, 'text-danger cursor-pointer': canRequestLeave }"
               href="#"
               data-bs-toggle="modal" data-bs-target="#requestLeaveModal">
              Request Leave
            </a>
          </li>
          </template>
          <template v-else>
          <!-- ADMIN/OWNER: Leave -->
          <li><a class="dropdown-item text-white hover:bg-gray-900" href="#" @click.prevent="openInvite">Invite Member</a></li>
          <li><a class="dropdown-item text-white hover:bg-gray-900" href="#" @click.prevent="openManage">Manage Members</a></li>
          <li><hr class="dropdown-divider border-gray-700"></li>
          <li>
            <a class="dropdown-item hover:bg-gray-900"
               :class="{ 'disabled': !canLeaveNow, 'text-gray cursor-not-allowed': !canLeaveNow, 'text-danger cursor-pointer': canLeaveNow }"
               href="#"
               @click.prevent="leaveNow"
               :title="!canLeaveNow ? 'Cannot leave: there must be another admin/owner' : ''">
              Leave
            </a>
          </li>
          <li>
            <a class="dropdown-item text-danger hover:bg-gray-900"
               href="#"
               @click.prevent="confirmDisband">
              Disband Group
            </a>
          </li>          
          </template>     
        </ul>
      </div>
    `,
        computed: {
            gstore() {
                return useGroupStore();
            },
            uistore() {
                return useUIStore();
            },
            gid() {
                return this.gstore.activeGroupId;
            },
            detail() {
                return this.gstore.activeGroupDetail;
            },
            me() {
                return this.gstore.meId;
            },
            myMember() {
                const d = this.detail;
                if (!d || !d.members) return null;
                return (
                    d.members.find(
                        (m) => Number(m.user_id) === Number(this.me)
                    ) || null
                );
            },

            isMember() {
                return !!this.myMember && this.myMember.role === "member";
            },
            isAdminOrOwner() {
                return (
                    !!this.myMember && ["admin", "owner"].includes(this.myMember.role)
                );
            },

            canRequestLeave() {
                return (
                    this.isMember &&
                    this.myMember.status === "active" &&
                    !this.myMember.leave_requested_at
                );
            },
            canLeaveNow() {
                if (!this.isAdminOrOwner) return false;
                if (!this.detail) return false;

                const hasOther = (this.detail.members || [])
                    .filter(
                        (m) =>
                        m.status === "active" && ["admin", "owner"].includes(m.role)
                    )
                    .some((m) => Number(m.user_id) !== Number(this.me));
                return hasOther;
            },
        },
        methods: {
            openInvite() {
                this.uistore.openInvite();
            },
            openManage() {
                this.uistore.openManage();
            },

            async leaveNow() {
                if (!this.canLeaveNow) return;
                if (
                    !confirm(
                        "Leave this group now? You will no longer see this group."
                    )
                )
                    return;

                try {
                    await GIAPI.leaveNow(this.gid);

                    this.gstore.removeGroupFromList(this.gid);

                    const next = this.gstore.items[0]?.id;
                    if (next) {
                        await this.gstore.openGroup(next);
                    } else {
                        this.gstore.activeGroupId = null;
                        this.gstore.activeGroupDetail = null;
                    }
                } catch (e) {
                    const msg = ((e && e.message) || "").includes(
                            "NO_OTHER_ADMIN"
                        ) ?
                        "Cannot leave: there must be another admin/owner." :
                        e?.message || "Leave failed";
                    alert(msg);
                }
            },
            async confirmDisband() {
                if (
                    !confirm(
                        "Disband this group? You and other members will no longer see this group."
                    )
                ) return;
                try {
                    await GIAPI.disbandGroup(this.gid);
                    this.removeLocalGroup();
                } catch (e) {
                    alert("Disband failed: " + (e?.message || e));
                }
            },
            removeLocalGroup() {
                const gid = this.gid;
                this.gstore.removeGroupFromList(gid);
                const next = this.gstore.items[0]?.id;
                if (next) {
                    this.gstore.openGroup(next);
                } else {
                    this.gstore.activeGroupId = null;
                    this.gstore.activeGroupDetail = null;
                }
            },
        },
    };
    window.GroupThreadMenu = GroupThreadMenu;
})();