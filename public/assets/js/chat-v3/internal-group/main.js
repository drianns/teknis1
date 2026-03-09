(() => {
    const {
        createApp
    } = Vue;
    const {
        createPinia
    } = Pinia;

    const app = createApp({
        components: {
            "group-list": window.GroupList,
            "group-thread-header": window.GroupThreadHeader,
            "group-thread-menu": window.GroupThreadMenu,
            "group-bell": window.GroupBell,
            "group-thread": window.GroupThread,
            "group-composer": window.GroupComposer,
            "pending-invites": window.PendingInvites,
            "invite-members-modal": window.InviteMembersModal,
            "manage-members-modal": window.ManageMembersModal,
            'request-leave-modal': window.RequestLeaveModal,
        },
    });

    app.use(createPinia());

    const gs = useGroupStore();
    gs.meId = window.AUTH_USER_ID || null;
    gs.me = window.AUTH_USER || null;

    app.mount("#chat-v3-internal");
})();