class WindowNotification
{
    async showNotification(title, message) {
        console.log("showNotification", title, message);
        // Cek apakah browser mendukung notifikasi
        if (!("Notification" in window)) {
            alert("Browser kamu tidak mendukung notifikasi.");
        }
        // Cek apakah izin notifikasi sudah diberikan
        else if (Notification.permission === "granted") {
            // Jika sudah diberikan, tampilkan notifikasi
            new Notification(title, { body: message });
        }
        // Jika belum diberikan izin, minta izin kepada pengguna
        else if (Notification.permission !== "denied") {
            Notification.requestPermission().then(permission => {
                if (permission === "granted") {
                    new Notification(title, { body: message });
                }
            });
        }
    }

}
