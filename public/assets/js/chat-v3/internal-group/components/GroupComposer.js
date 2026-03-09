(() => {
    const GroupComposer = {
        template: `
      <div class="input-group">
        <input v-model="draft" class="form-control bg-gray-800 text-gray-200 border-0"
              placeholder="Type a message..." @keyup.enter="send">
        <button class="btn btn-success" :disabled="!canSend" @click="send">Send</button>
      </div>
    `,
        data() {
            return {
                draft: ""
            };
        },
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
            canSend() {
                return !!this.gid && this.draft.trim().length > 0;
            },
        },
        methods: {
            async send() {
                if (!this.canSend) return;
                await this.mstore.send(this.gid, {
                    body: this.draft.trim()
                });
                this.draft = "";
                this.$nextTick(async () => {
                    const last = this.mstore.lastId(this.gid);
                    if (last) await GIAPI.markRead(this.gid, last);
                    this.gstore.decUnread(this.gid);
                });
            },
        },
    };
    window.GroupComposer = GroupComposer;
})();