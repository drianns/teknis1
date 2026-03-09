(() => {
    const PendingInvites = {
        template: `
      <div>
        <div v-if="loading" class="text-gray-400">Loading...</div>
        <div v-else>
          <div v-if="!items.length" class="text-gray-400">No invitations</div>
          <ul class="list-group list-group-flush">
            <li v-for="it in items" :key="it.id"
                class="list-group-item bg-gray-900 text-gray-200 border-0 d-flex justify-content-between align-items-center">
              <div class="min-w-0">
                <div class="fw-semibold truncate">{{ it.group?.name }}</div>
                <small class="text-gray-400 truncate" v-if="it.group?.description">{{ it.group.description }}</small>
              </div>
              <div class="flex gap-2">
                <button class="btn btn-sm btn-outline-success" @click="accept(it)">Accept</button>
                <button class="btn btn-sm btn-outline-danger" @click="reject(it)">Reject</button>
              </div>
            </li>
          </ul>
        </div>
      </div>
    `,
        computed: {
            store() {
                return useInvitesStore();
            },
            items() {
                return this.store.items;
            },
            loading() {
                return this.store.loading;
            },
        },
        methods: {
            async accept(it) {
                await this.store.accept(it);
                await useGroupStore().fetchGroups();
            },
            async reject(it) {
                await this.store.reject(it);
            },
        },
        async mounted() {
            await this.store.fetch();
        },
    };
    window.PendingInvites = PendingInvites;
})();