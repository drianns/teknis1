(() => {
    const existingSocket =
        (window.ChatV3Socket && window.ChatV3Socket.socket) ||
        (window.ChatSocket && window.ChatSocket.socket) ||
        window.socket ||
        null;

    const socket =
        existingSocket ||
        (typeof io !== "undefined" ?
            io(window.SOCKET_URL || undefined, {
                transports: ["websocket"],
            }) :
            null);

    if (!socket) {
        console.warn(
            "[InternalGroup] No socket instance found. Ensure chat-v3/lib/socket.js is loaded globally."
        );
    }

    function isForGroup(receiver, gid) {
        return (
            receiver &&
            receiver.scope === "group.internal" &&
            String(receiver.id) === String(gid)
        );
    }

    const REFRESH_TYPES = new Set([
        "invite_sent",
        "invite_accepted",
        "role_promoted",
        "role_demoted",
        "member_removed",
        "leave_requested",
        "leave_approved",
        "group_updated",
    ]);

    function isForUser(receiver, uid) {
        return (
            receiver &&
            receiver.scope === "user" &&
            String(receiver.id) === String(uid)
        );
    }

    function roomForGroup(id) {
        return {
            scope: "group.internal",
            id: String(id),
        };
    }

    document.addEventListener("DOMContentLoaded", () => {
        if (!socket) return;

        const mstore = window.useMessagesStore ? useMessagesStore() : null;
        const gstore = window.useGroupStore ? useGroupStore() : null;

        socket.on("message_created", (receiver, dto) => {
            if (!gstore || !mstore) return;

            if (dto.sender_id === gstore.meId) {
                gstore.decUnread(dto.group_id || gstore.activeGroupId);
                return;
            }

            const gidFromReceiver =
                receiver?.scope === "group.internal" ?
                receiver.id :
                dto?.group_id;
            const gid = gidFromReceiver || gstore.activeGroupId;

            if (gid) mstore.applyIncoming(Number(gid), dto);

            if (gid) gstore.bumpGroup(Number(gid), dto);

            if (gstore.activeGroupId !== Number(gid)) {
                gstore.incrementUnreadIfOtherGroup(Number(gid));
            } else {
                gstore.decUnread(Number(gid));
            }
        });

        socket.on("message_echo", (receiver, dto) => {
            if (!gstore || !mstore) return;

            const me = gstore.meId;
            if (isForUser(receiver, me)) {
                const gid = dto.group_id || gstore.activeGroupId;

                mstore.prependEcho(gid, dto);
                gstore.bumpGroup(Number(gid), dto);
                if (gstore.activeGroupId === Number(gid)) {
                    gstore.decUnread(Number(gid));
                }
            }
        });

        socket.on("member_removed", ({
            user_id,
            group_id
        }) => {
            if (gstore && gstore.meId && user_id === gstore.meId) {
                gstore.removeGroupFromList(group_id || gstore.activeGroupId);
            }
        });

        socket.on("leave_approved", ({
            user_id,
            group_id
        }) => {
            if (gstore && gstore.meId && user_id === gstore.meId) {
                gstore.removeGroupFromList(group_id || gstore.activeGroupId);
            }
        });
        socket.on("group_notification_created", (receiver, dto) => {
            const gstore = window.useGroupStore ? useGroupStore() : null;
            const nstore = window.useNotificationsStore ?
                useNotificationsStore() :
                null;
            if (!gstore || !nstore) return;

            const gid =
                receiver?.scope === "group.internal" ?
                Number(receiver.id) :
                Number(dto?.group_id);
            if (!gid) return;

            if (["member_removed", "leave_approved"].includes(dto?.type)) {
                if (String(dto.data.target_user_id) === String(gstore.meId)) {
                    gstore.removeGroupFromList(gid);
                    if (gstore.activeGroupId === gid) {
                        gstore.activeGroupId = null;
                        gstore.activeGroupDetail = null;
                    }
                    return;
                }
            }

            if (dto?.type === "group_disbanded") {
                const wasActive = gstore.activeGroupId === gid;
                gstore.removeGroupFromList(gid);
                if (wasActive) {
                    gstore.activeGroupId = null;
                    gstore.activeGroupDetail = null;
                }
                return;
            }

            if (REFRESH_TYPES.has(dto?.type)) {
                gstore.refreshIfActive(gid);
                window.dispatchEvent(
                    new CustomEvent("gi:active-group-updated", {
                        detail: {
                            groupId: gid,
                        },
                    })
                );
            }

            nstore.prepend(gid, dto);
            gstore.bumpGroup(gid, {
                id: 0,
                body: `[${dto.type}]`,
                created_at: dto.created_at,
                sender_id: dto.actor_id,
                sender_name: "System",
            });
        });

        socket.on("group_invitation_created", (receiver, dto) => {
            const gstore = window.useGroupStore ? useGroupStore() : null;
            const istore = window.useInvitesStore ? useInvitesStore() : null;
            if (!gstore || !istore) return;
            if (
                receiver?.scope === "user" &&
                String(receiver.id) === String(gstore.meId)
            ) {
                istore.prepend(dto.data);
            }
        });
    });

    window.GroupInternalSocket = {
        socket,
        roomForGroup,
    };
})();