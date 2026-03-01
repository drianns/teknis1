(() => {
    const {
        defineStore
    } = Pinia;
    window.useNotificationsStore = defineStore("gi_notifications", {
        state: () => ({
            byGroup: {},
        }),
        actions: {
            ensure(gid) {
                if (!this.byGroup[gid]) {
                    this.byGroup[gid] = {
                        items: [],
                        next_before: null,
                        loading: false,
                        unread: 0,
                    };
                }
                return this.byGroup[gid];
            },
            async fetch(gid) {
                const s = this.ensure(gid);
                s.loading = true;
                try {
                    const res = await GIAPI.notificationsList(gid, null, 20);
                    s.items = res.items || [];
                    s.next_before = res.next_before || null;
                    s.unread = res.unread || 0;
                } finally {
                    s.loading = false;
                }
            },
            async loadMore(gid) {
                const s = this.ensure(gid);
                if (!s.next_before || s.loading) return;
                s.loading = true;
                try {
                    const res = await GIAPI.notificationsList(
                        gid,
                        s.next_before,
                        20
                    );
                    s.items = s.items.concat(res.items || []);
                    s.next_before = res.next_before || null;
                } finally {
                    s.loading = false;
                }
            },
            prepend(gid, notif) {
                const s = this.ensure(gid);
                s.items.unshift(notif);
                s.unread = (s.unread || 0) + 1;
            },
            zeroUnread(gid) {
                const s = this.ensure(gid);
                s.unread = 0;
            },
        },
    });
})();