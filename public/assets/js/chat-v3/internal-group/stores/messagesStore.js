(() => {
    const {
        defineStore
    } = Pinia;

    window.useMessagesStore = defineStore("gi_messages", {
        state: () => ({
            byGroup: {},
        }),
        actions: {
            ensure(gid) {
                if (!this.byGroup[gid])
                    this.byGroup[gid] = {
                        items: [],
                        next_before: null,
                        loading: false,
                        sending: false,
                    };
                return this.byGroup[gid];
            },
            async loadLatest(gid) {
                const s = this.ensure(gid);
                s.loading = true;
                try {
                    const res = await GIAPI.history(gid, null, 50);
                    s.items = (res.items || []).reverse();
                    s.next_before = res.next_before || null;
                } finally {
                    s.loading = false;
                }
            },
            async loadMore(gid) {
                const s = this.ensure(gid);
                if (!s.next_before || s.loading) return;
                s.loading = true;
                try {
                    const res = await GIAPI.history(gid, s.next_before, 50);
                    const older = (res.items || []).reverse();
                    s.items = older.concat(s.items);
                    s.next_before = res.next_before || null;
                } finally {
                    s.loading = false;
                }
            },
            prependEcho(gid, msg) {
                this.ensure(gid).items.push(msg);
            },
            applyIncoming(gid, msg) {
                this.ensure(gid).items.push(msg);
            },
            async send(gid, payload) {
                const s = this.ensure(gid);
                if (s.sending) return;
                s.sending = true;
                try {
                    const res = await GIAPI.send(gid, payload);
                } finally {
                    s.sending = false;
                }
            },
            lastId(gid) {
                const s = this.ensure(gid);
                return s.items.length ? s.items[s.items.length - 1].id : null;
            },
        },
    });
})();