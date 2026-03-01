(() => {
    const GroupList = {
        template: `
    <div class="h-100 d-flex flex-column">
      <div class="p-3 border-bottom border-gray-800" v-show="this.store.me?.user_type === 'owner' || this.store.me?.user_type === 'admin'">
        <div class="input-group gap-2">
          <input v-model="newName" class="form-control bg-gray-800 text-gray-200 border-0" placeholder="New group name">
          <button class="btn btn-success" @click="create">Create</button>
        </div>
      </div>

      <div class="flex-fill overflow-auto">
        <ul class="list-group list-group-flush">
          <li v-for="g in groups" :key="g.id"
              class="list-group-item bg-gray-900 text-gray-200 border-0 hover:bg-gray-800 cursor-pointer"
              :class="{ 'bg-gray-800': g.id === activeId }"
              @click="open(g.id)">

            <div class="d-flex align-items-start justify-content-between gap-2">
              <div class="min-w-0 flex-grow-1">
                <div class="fw-semibold truncate">{{ g.name }}</div>

                <!-- Row pesan terakhir -->
                <div class="d-flex align-items-center justify-content-between gap-2 mt-1">
                  <div class="min-w-0">
                    <small class="text-gray-400 d-block truncate"
                          v-if="g.last_message">
                      <span class="text-success fw-semibold">{{ g.last_message.sender_name || '—' }}</span>:
                      <span>{{ snippet(g.last_message.body) }}</span>
                    </small>
                    <small class="text-gray-500" v-else>—</small>
                  </div>
                  <small class="text-gray-500 ms-2 text-nowrap"
                        v-if="g.last_message">
                    {{ formatTime(g.last_message.created_at) }}
                  </small>
                </div>
              </div>

              <span v-if="g.unread" class="badge bg-danger rounded-pill align-self-start">{{ g.unread }}</span>
            </div>
          </li>
        </ul>
      </div>
    </div>`,
        data() {
            return {
                newName: "",
                newDesc: ""
            };
        },
        computed: {
            store() {
                return useGroupStore();
            },
            groups() {
                return this.store.items;
            },
            activeId() {
                return this.store.activeGroupId;
            },
        },
        methods: {
            snippet(txt) {
                if (!txt) return "";
                const s = String(txt).replace(/\s+/g, " ").trim();
                return s.length > 80 ? s.slice(0, 77) + "…" : s;
            },
            formatTime(iso) {
                if (!iso) return "";
                const d = new Date(iso);
                return d.toLocaleTimeString([], {
                    hour: "2-digit",
                    minute: "2-digit",
                });
            },
            async create() {
                if (!this.newName.trim()) return;
                const g = await this.store.createGroup(
                    this.newName,
                    this.newDesc
                );
                this.newName = "";
                this.newDesc = "";
                await this.open(g.id);
            },
            async open(id) {
                await this.store.openGroup(id);
                const m = useMessagesStore();
                await m.loadLatest(id);
                const last = m.lastId(id);
                if (last) await GIAPI.markRead(id, last);
                this.store.decUnread(id);
            },
        },
        async mounted() {
            await this.store.fetchGroups();
            if (this.groups[0]) await this.open(this.groups[0].id);
        },
    };

    window.GroupList = GroupList;
})();