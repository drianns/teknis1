


$(document).ready(() => {

    $("#chat_ticket_category_tags").selectize({
        delimiter: ",",
        persist: false,
        create: function (input) {
            return {
                value: input,
                text: input,
            };
        },
    });

    // IFRAME EVENTS
    var result_filter;
    window.addEventListener("message", (event) => {
        // IMPORTANT: check the origin of the data!
        if (event.origin === "https://show.uidesk.id") {
            let data = event.data;

            if (data.event == "TicketID") {
            }
        } else {
            // The data was NOT sent from your site!
            // Be careful! Do not use it. This else branch is
            // here just for clarity, you usually shouldn't need it.
            return;
        }
    });
    window.addEventListener("new_message", (event) => {
        console.log(event.origin);
        console.log(event.data);
        // IMPORTANT: check the origin of the data!
        if (event.origin === "https://your-first-site.example") {
            // The data was sent from your site.
            // Data sent with postMessage is stored in event.data:
            console.log(event.data);
        } else {
            // The data was NOT sent from your site!
            // Be careful! Do not use it. This else branch is
            // here just for clarity, you usually shouldn't need it.
            return;
        }
    });

    document
    .addEventListener("keydown", async function(event) {
      if (event.ctrlKey && event.key === "m") {
        let conf = confirm("Are you sure to clear all data?");
        if(conf) {
            await clearDB();
        } else {
            alert("Cancelled");
        }
      }
  });
});



