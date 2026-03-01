(() => {
    const {
        defineStore
    } = Pinia;

    window.useGroupStore = defineStore("gi_groups", {
        state: () => ({
            items: [],
            activeGroupId: null,
            activeGroupDetail: null,
            isAdmin: false,
            meId: null,
            me: null,
        }),
        getters: {
            activeGroup(state) {
                return (
                    state.items.find((g) => g.id === state.activeGroupId) ||
                    null
                );
            },
        },
        actions: {
            async fetchGroups() {
                const {
                    items
                } = await GIAPI.listGroups();
                this.items = items;
            },
            async createGroup(name, description) {
                const g = await GIAPI.createGroup({
                    name,
                    description
                });
                this.items.unshift({
                    ...g,
                    unread: 0
                });
                return g;
            },
            async openGroup(id) {
                this.activeGroupId = id;
                await this.fetchDetail(id);
            },
            async fetchDetail(id) {
                const detail = await GIAPI.showGroup(id);
                this.activeGroupDetail = detail;
                const my = (detail.members || []).find(
                    (m) => m.user_id === this.meId
                );
                this.isAdmin = !!my && ["owner", "admin"].includes(my.role) &&
                    my.status === "active";
            },
            decUnread(id) {
                const g = this.items.find((x) => x.id === id);
                if (g) g.unread = 0;
            },
            incrementUnreadIfOtherGroup(groupId) {
                if (this.activeGroupId !== groupId) {
                    const g = this.items.find((x) => x.id === groupId);
                    if (g) g.unread = (g.unread || 0) + 1;
                }
            },
            bumpGroup(groupId, dto) {
                const id = Number(groupId);
                const idx = this.items.findIndex((g) => Number(g.id) === id);
                if (idx === -1) {
                    return;
                }                
                const nowIso =
                    (dto && dto.created_at) || new Date().toISOString();
                const lastMsg = dto ?
                    {
                        id: dto.id,
                        body: dto.body,
                        created_at: dto.created_at || nowIso,
                        sender_id: dto.sender_id,
                        sender_name: dto.sender_name,
                    } :
                    this.items[idx]?.last_message || null;
                const updated = {
                    ...this.items[idx],
                    last_message: lastMsg
                };

                const arr = this.items.slice();
                arr.splice(idx, 1);
                arr.unshift(updated);
                this.items = arr;
            },
            removeGroupFromList(id) {
                this.items = this.items.filter((g) => g.id !== id);
                if (this.activeGroupId === id) {
                    this.activeGroupId = null;
                    this.activeGroupDetail = null;
                }
            },

            async refreshIfActive(gid) {
                gid = Number(gid);
                if (this.activeGroupId !== gid) return;
                if (this._refreshingDetail) return;
                this._refreshingDetail = true;
                try {
                    await this.fetchDetail(gid);
                } finally {
                    this._refreshingDetail = false;
                }
            },
        },
    });

    function hasAnotherAdmin(detail, meId) {
        if (!detail || !Array.isArray(detail.members)) return false;
        return detail.members
            .filter(
                (m) =>
                m.status === "active" && ["admin", "owner"].includes(m.role)
            )
            .some((m) => Number(m.user_id) !== Number(meId));
    }
    window.GI_hasAnotherAdmin = hasAnotherAdmin;
})();