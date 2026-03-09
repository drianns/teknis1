if (!window.Notification) {
    console.log('Browser does not support notifications.');
} else {
    // check if permission is already granted
    if (Notification.permission === 'granted') {
        // show notification here
    } else {
        // request permission from user
        Notification.requestPermission().then(function(p) {
           if(p === 'granted') {
               // show notification here
           } else {
               console.log('User blocked notifications.');
           }
        }).catch(function(err) {
            console.error(err);
        });
    }
}

function desktopNotification(title="", body="", icon="") {
    if (!window.Notification) {
        console.log('Browser does not support notifications.');
    } else {
        // check if permission is already granted
        if (Notification.permission === 'granted') {
            // show notification here
            var notify = new Notification(title, {
                body: body,
                icon: icon,
            });

            // close the notification after 10 seconds
            setTimeout(() => {
                notify.close();
            }, 10 * 1000);

            // navigate to a URL when clicked
            notify.addEventListener('click', () => {
                window.open('https://multichat-2.uidesk.id/chat/view', '_blank');
            });

        } else {
            // request permission from user
            Notification.requestPermission().then(function (p) {
                if (p === 'granted') {
                    // show notification here
                    var notify = new Notification(title, {
                        body: body,
                        icon: icon,
                    });
                } else {
                    console.log('User blocked notifications.');
                }
            }).catch(function (err) {
                console.error(err);
            });
        }
    }
}
