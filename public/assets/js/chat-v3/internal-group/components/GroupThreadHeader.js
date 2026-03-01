(() => {
    const RoleBadge = {
        props: ["role"],
        template: `<span class="badge ms-2"
                      :class="role==='owner' ? 'bg-pink-600' : role==='admin' ? 'bg-blue-600' : 'bg-gray-700'">
                {{ role }}
              </span>`,
    };

    const GroupThreadHeader = {
        components: {
            RoleBadge
        },
        template: `
      <div class="flex items-center gap-2 min-w-0">
        <h5 class="m-0 text-white truncate">{{ activeGroup?.name || '—' }}</h5>
        <role-badge v-if="myMember" :role="myMember.role"></role-badge>
        <div class="ml-auto text-sm text-gray-400 truncate" v-if="detail?.description">{{ detail.description }}</div>
      </div>
    `,
        computed: {
            gstore() {
                return useGroupStore();
            },
            activeGroup() {
                return this.gstore.activeGroup;
            },
            detail() {
                return this.gstore.activeGroupDetail;
            },
            myMember() {
                const d = this.detail;
                if (!d || !d.members) return null;
                return (
                    d.members.find((x) => x.user_id === this.gstore.meId) ||
                    null
                );
            },
        },
    };
    window.GroupThreadHeader = GroupThreadHeader;
})();