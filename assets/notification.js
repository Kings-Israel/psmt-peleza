function notifyMe() {

    // var name = document.getElementById('userName').value;

    if (!window.Notification) {
        console.log('Browser does not support notifications.');
    } else {
        // check if permission is already granted
        if (Notification.permission === 'granted') {
            // show notification here

            var notify = new Notification('name', {
                body: 'Has submitted a request',
                icon: 'https://bit.ly/2DYqRrh',
            });
        } else {
            // request permission from user
            Notification.requestPermission().then(function (p) {
                if (p === 'granted') {
                    // show notification here

                    var notify = new Notification('name', {
                        body: 'Has submitted a request',
                        icon: 'https://bit.ly/2DYqRrh',
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

    function JSalert(){
        swal("Congrats!", ", Your account is created!", "success");
    }
