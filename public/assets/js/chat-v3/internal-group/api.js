(() => {
    const API_BASE = "/api-internal-groups";

    async function request(method, url, body) {
        const res = await fetch(url, {
            method,
            headers: {
                "Content-Type": "application/json",
                Accept: "application/json",
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            body: body ? JSON.stringify(body) : undefined,
        });
        if (!res.ok) {
            const t = await res.text();
            throw new Error(t || res.statusText);
        }
        return res.json();
    }

    window.GIAPI = {
        listGroups: () => request("GET", `${API_BASE}`),
        createGroup: (payload) => request("POST", `${API_BASE}`, payload),
        showGroup: (id) => request("GET", `${API_BASE}/${id}`),

        listMyInvites: () => request("GET", `${API_BASE}/invitations/me`),

        invite: (groupId, userId) =>
            request("POST", `${API_BASE}/${groupId}/invite`, {
                user_id: userId,
            }),
        respondInvite: (groupId, memberId, decision) =>
            request(
                "POST",
                `${API_BASE}/${groupId}/invitations/${memberId}/respond`, {
                    decision,
                }
            ),

        promote: (groupId, memberId) =>
            request(
                "POST",
                `${API_BASE}/${groupId}/members/${memberId}/promote`
            ),
        demote: (groupId, memberId) =>
            request(
                "POST",
                `${API_BASE}/${groupId}/members/${memberId}/demote`
            ),
        remove: (groupId, memberId) =>
            request(
                "POST",
                `${API_BASE}/${groupId}/members/${memberId}/remove`
            ),

        requestLeave: (groupId, reason) =>
            request("POST", `${API_BASE}/${groupId}/leave`, {
                reason
            }),
        approveLeave: (groupId, memberId) =>
            request("POST", `${API_BASE}/${groupId}/leave/${memberId}/approve`),
        rejectLeave: (groupId, memberId) =>
            request("POST", `${API_BASE}/${groupId}/leave/${memberId}/reject`),

        history: (groupId, beforeId, perPage = 50) => {
            const qs = new URLSearchParams();
            if (beforeId) qs.set("before_id", beforeId);
            qs.set("per_page", perPage);
            return request(
                "GET",
                `${API_BASE}/${groupId}/messages?${qs.toString()}`
            );
        },
        send: (groupId, payload) =>
            request("POST", `${API_BASE}/${groupId}/messages`, payload),
        markRead: (groupId, lastId) =>
            request("POST", `${API_BASE}/${groupId}/read`, {
                last_read_message_id: lastId,
            }),
        invite: (gid, uid) =>
            request("POST", `${API_BASE}/${gid}/invite`, {
                user_id: uid
            }),
        respondInvite: (gid, mid, decision) =>
            request("POST", `${API_BASE}/${gid}/invitations/${mid}/respond`, {
                decision,
            }),
        promote: (gid, mid) =>
            request("POST", `${API_BASE}/${gid}/members/${mid}/promote`),
        remove: (gid, mid) =>
            request("POST", `${API_BASE}/${gid}/members/${mid}/remove`),
        approveLeave: (gid, mid) =>
            request("POST", `${API_BASE}/${gid}/leave/${mid}/approve`),
        rejectLeave: (gid, mid) =>
            request("POST", `${API_BASE}/${gid}/leave/${mid}/reject`),
        searchUsers: (gid, q) =>
            request(
                "GET",
                `${API_BASE}/${gid}/users/search?q=${encodeURIComponent(q)}`
            ),
        notificationsList: (gid, before, per = 20) => {
            const qs = new URLSearchParams();
            if (before) qs.set("before_id", before);
            qs.set("per_page", per);
            return request(
                "GET",
                `${API_BASE}/${gid}/notifications?${qs.toString()}`
            );
        },
        notificationsMarkRead: (gid, upToId = null) =>
            request(
                "POST",
                `${API_BASE}/${gid}/notifications/read`,
                upToId ? {
                    up_to_id: upToId
                } : {}
            ),
        leaveNow: (gid) => request('POST', `${API_BASE}/${gid}/leave/now`),
        disbandGroup: (gid) => request('POST', `${API_BASE}/${gid}/disband`),
    };
})();