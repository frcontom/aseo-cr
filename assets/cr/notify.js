$(document).ready(function() {
    const token = "3e537989-76b2-4619-a4f0-bc5a1da5e8eb";

    $.fn.valite_notify = async function(query) {
        return this.each(function() {
            // const data = {id : query.id};
            // $(this).simple_call_text(data, 'url_notify', true);
        })
    }



    $.fn.send_notify = async function(title,message,tokens) {
        return this.each(async function () {
            // console.log(tokens)
            // const json = {"app_id": token,
            //     "name": {"en": "Clean"},
            //     "url": "https://challengebros.lat/aseo",
            //     "contents": {"en": message},
            //     "headings": {"en": title},
            //     "include_subscription_ids": tokens};
            // const res = await fetch('https://onesignal.com/api/v1/notifications', {
            //     method: 'POST',
            //     headers: {
            //         'Content-Type': 'application/json'
            //     },
            //     body: JSON.stringify(json)
            // });


        })
    }

})