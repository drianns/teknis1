(() => {
    const TypeIcon = {
        props: ["type"],
        template: `<i :class="klass"></i>`,
        computed: {
            klass() {
                switch (this.type) {
                    case "invite_sent":
                        return "fas fa-paper-plane text-info";
                    case "invite_accepted":
                        return "fas fa-user-check text-success";
                    case "invite_rejected":
                        return "fas fa-user-times text-danger";
                    case "role_promoted":
                        return "fas fa-user-shield text-primary";
                    case "role_demoted":
                        return "fas fa-user-shield text-secondary";
                    case "member_removed":
                        return "fas fa-user-minus text-danger";
                    case "leave_requested":
                        return "fas fa-door-open text-warning";
                    case "leave_approved":
                        return "fas fa-door-open text-success";
                    case "leave_rejected":
                        return "fas fa-door-open text-secondary";
                    case "group_updated":
                        return "fas fa-edit text-info";
                    default:
                        return "fas fa-bell text-muted";
                }
            },
        },
    };

    const GroupBell = {
        components: {
            TypeIcon
        },
        template: `
      <div class="dropdown" v-if="gid">
        <a class="btn btn-link text-gray-300 p-0 position-relative" href="#" data-bs-toggle="dropdown"
           @click="onOpen">
          <i class="fas fa-bell"></i>
          <span v-if="unread" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            {{ unread }}
          </span>
        </a>
        <div class="dropdown-menu dropdown-menu-end bg-gray-900 border-0 p-0"
             style="min-width: 360px; max-height: 420px; overflow:auto;">
          <div class="p-2 border-bottom border-gray-800 d-flex align-items-center">
            <strong class="text-white">Group notifications</strong>
            <button class="btn btn-sm btn-outline-light ms-auto" @click="markAllRead">Mark all read</button>
          </div>

          <div v-if="loading" class="p-3 text-gray-400">Loading…</div>
          <div v-else-if="!items.length" class="p-3 text-gray-400">No notifications</div>
          <ul v-else class="list-group list-group-flush">
            <li v-for="n in items" :key="n.id"
                class="list-group-item bg-gray-900 text-gray-200 border-0 d-flex align-items-start gap-2">
              <type-icon :type="n.type" class="mt-1"></type-icon>
              <div class="min-w-0">
                <div class="small">{{ renderText(n) }}</div>
                <div class="text-xs text-gray-500">{{ time(n.created_at) }}</div>
              </div>
              <span v-if="!n.read" class="badge bg-primary ms-auto">new</span>
            </li>
          </ul>

          <div v-if="next" class="p-2 border-top border-gray-800 text-center">
            <button class="btn btn-sm btn-outline-light" @click="loadMore">Load more</button>
          </div>
        </div>
      </div>
    `,
        computed: {
            gstore() {
                return useGroupStore();
            },
            nstore() {
                return useNotificationsStore();
            },
            gid() {
                return this.gstore.activeGroupId;
            },
            bucket() {
                return this.gid ? this.nstore.byGroup[this.gid] : null;
            },
            items() {
                return this.bucket ? this.bucket.items : [];
            },
            unread() {
                return this.bucket ? this.bucket.unread : 0;
            },
            loading() {
                return this.bucket ? this.bucket.loading : false;
            },
            next() {
                return this.bucket ? this.bucket.next_before : null;
            },
        },
        methods: {
            async onOpen() {
                if (!this.gid) return;
                if (!this.items.length) await this.nstore.fetch(this.gid);
                if (this.items.length) {
                    const upTo = this.items[0].id;
                    await GIAPI.notificationsMarkRead(this.gid, upTo);
                    this.nstore.zeroUnread(this.gid);
                    this.items.forEach((x) => (x.read = true));
                }
            },
            async loadMore() {
                await this.nstore.loadMore(this.gid);
            },
            async markAllRead() {
                if (!this.items.length) return;
                const upTo = this.items[0].id;
                await GIAPI.notificationsMarkRead(this.gid, upTo);
                this.nstore.zeroUnread(this.gid);
                this.items.forEach((x) => (x.read = true));
            },
            time(iso) {
                return iso ? new Date(iso).toLocaleString() : "";
            },
            renderText(n) {
                const d = n.data || {};
                switch (n.type) {
                    case "invite_sent":
                        return `${d.actor_name || "Someone"} invited ${
                            d.target_user_name || "a user"
                        }`;
                    case "invite_accepted":
                        return `${
                            d.target_user_name || "User"
                        } accepted the invite`;
                    case "invite_rejected":
                        return `${
                            d.target_user_name || "User"
                        } rejected the invite`;
                    case "role_promoted":
                        return `${d.target_user_name || "User"} is now admin`;
                    case "role_demoted":
                        return `${d.target_user_name || "User"} is no longer admin`;
                    case "member_removed":
                        return `${d.target_user_name || "User"} was removed`;
                    case "leave_requested":
                        return `${
                            d.target_user_name || "User"
                        } requested to leave`;
                    case "leave_approved":
                        return `${
                            d.target_user_name || "User"
                        }'s leave approved`;
                    case "leave_rejected":
                        return `${
                            d.target_user_name || "User"
                        }'s leave rejected`;
                    case "group_updated":
                        return `Group updated: ${d.field || "info"} changed`;
                    default:
                        return `Group activity`;
                }
            },
        },
        watch: {
            gid: {
                immediate: true,
                async handler(nv) {
                    if (!nv) return;
                    await this.nstore.fetch(nv);
                },
            },
        },
    };

    window.GroupBell = GroupBell;
})();