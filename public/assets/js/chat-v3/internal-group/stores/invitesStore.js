(() => {
    const {
        defineStore
    } = Pinia;

    window.useInvitesStore = defineStore("gi_invites", {
        state: () => ({
            items: [],
            loading: false
        }),
        actions: {
            async fetch() {
                this.loading = true;
                try {
                    const {
                        items
                    } = await GIAPI.listMyInvites();
                    this.items = items;
                } finally {
                    this.loading = false;
                }
            },
            async accept(inv) {
                await GIAPI.respondInvite(inv.group_id, inv.id, "accept");
                this.items = this.items.filter((x) => x.id !== inv.id);
            },
            async reject(inv) {
                await GIAPI.respondInvite(inv.group_id, inv.id, "reject");
                this.items = this.items.filter((x) => x.id !== inv.id);
            },
            prepend(invite) {
                if (!invite) return;
                if (this.items.find((x) => String(x.id) === String(invite.id)))
                    return;
                this.items.unshift(invite);
            },
        },
    });
})();