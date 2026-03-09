(() => {
    const GroupThread = {
        template: `
      <div ref="scrollBox" class="h-full overflow-y-auto">
        <!-- Separator tanggal (optional example) -->
        <div v-if="items.length" class="text-center mb-3">
          <span class="badge bg-danger text-white p-2">
            {{ items[0]?.created_at ? new Date(items[0].created_at).toLocaleDateString() : '' }}
          </span>
        </div>

        <ul class="space-y-4">
          <li v-for="m in items" :key="m.id"
              :class="bubbleClass(m)">
            <div class="flex items-start space-x-3 max-w-[80%] md:max-w-[60%] lg:max-w-[50%]">
              <!-- Avatar -->
              <img :src="avatar(m)" alt="avatar"
                  class="w-10 h-10 rounded-full ml-2 object-cover"
                  :class="isMe(m) ? 'order-2' : ''">

              <!-- Bubble -->
              <div class="mb-2 p-3 rounded-lg break-words"
                  :class="isMe(m) ? 'bg-gray-800 text-white' : 'bg-gray-700 text-gray-200'">
                <h5 class="text-sm font-semibold flex items-center space-x-1">
                  <span :class="isMe(m) ? 'text-white' : 'text-green-500'">{{ m.sender_name || 'Agent' }}</span>
                  <span class="text-xs text-green-600 whitespace-nowrap">{{ time(m.created_at) }}</span>
                </h5>
                <div v-if="m.body">{{ m.body }}</div>
              </div>
            </div>
          </li>
        </ul>
      </div>
    `,
        computed: {
            gstore() {
                return useGroupStore();
            },
            mstore() {
                return useMessagesStore();
            },
            gid() {
                return this.gstore.activeGroupId;
            },
            items() {
                const b = this.gid ? this.mstore.byGroup[this.gid] : null;
                return b ? b.items : [];
            },
        },
        methods: {
            isMe(m) {
                return m.sender_id === this.gstore.meId;
            },
            bubbleClass(m) {
                return this.isMe(m) ?
                    "flex justify-end mt-4" :
                    "flex justify-start mt-4";
            },
            time(ts) {
                return ts ?
                    new Date(ts).toLocaleTimeString([], {
                        hour: "2-digit",
                        minute: "2-digit",
                    }) :
                    "";
            },
            avatar(m) {
                return m.avatar;
            },
            async maybeLoadMore() {
                const el = this.$refs.scrollBox;
                if (el.scrollTop <= 0) {
                    const oldH = el.scrollHeight;
                    await this.mstore.loadMore(this.gid);
                    this.$nextTick(() => {
                        el.scrollTop = el.scrollHeight - oldH;
                    });
                }
            },
            scrollToBottom() {
                const el = this.$refs.scrollBox;
                if (el) el.scrollTop = el.scrollHeight;
            },
        },
        watch: {
            items: {
                deep: true,
                handler() {
                    this.$nextTick(() => this.scrollToBottom());
                },
            },
            gid: {
                immediate: true,
                async handler(nv) {
                    if (!nv) return;
                    const el = this.$refs.scrollBox;
                    await this.mstore.loadLatest(nv);
                    this.$nextTick(() => this.scrollToBottom());
                },
            },
        },
        mounted() {
            this.$refs.scrollBox.addEventListener("scroll", this.maybeLoadMore);
            this.scrollToBottom();
        },
        beforeUnmount() {
            this.$refs.scrollBox.removeEventListener(
                "scroll",
                this.maybeLoadMore
            );
        },
    };
    window.GroupThread = GroupThread;
})();