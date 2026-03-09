(() => {
    const {
        defineStore
    } = Pinia;
    window.useUIStore = defineStore("gi_ui", {
        state: () => ({
            showInvite: false,
            showManage: false,
        }),
        actions: {
            openInvite() {
                this.showInvite = true;
            },
            closeInvite() {
                this.showInvite = false;
            },
            openManage() {
                this.showManage = true;
            },
            closeManage() {
                this.showManage = false;
            },
        },
    });
})();