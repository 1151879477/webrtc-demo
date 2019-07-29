<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        #userList a {
            cursor: pointer;
        }

        #messageBox {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            max-height: 500px;
            overflow-y: auto;
        }

    </style>
</head>
<body>
<div class="row" id="messageBox">
    <div class="col-md-12" id="messageContent">

    </div>
</div>
<div class="row">
    <div class="col-md-3">
        <video src="" id="localVideo"></video>
    </div>
    <div class="col-md-3">
        <video src="" id="remoteVideo"></video>
    </div>
    <div class="col-md-3">
    </div>
    <div class="col-md-3">
        <div class="list-group" id="userList">
            <a href="#" class="list-group-item disabled">用户列表</a>
        </div>
    </div>
</div>


</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/reconnecting-websocket/1.0.0/reconnecting-websocket.min.js"></script>
<script src="/user.js"></script>
<script>

    let url = '127.0.0.1:9000';
    var ws = new ReconnectingWebSocket('ws://' + url + "/user");
    let userId = getUserId();
    let remoteUserId = null;
    let loginIdList = [];
    let loginUserList = [];
    let localClient = createPeerConnection();
    localClient.onicecandidate = function(e){
        console.log('58', e);
        if (e.candidate) {
            console.log("remoteUserId", remoteUserId);
            ws.send('user.mail:' + JSON.stringify({
                to: remoteUserId,
                from: getUserId(),
                subject: 'icecandidate',
                data: e.candidate
            }));
        }
    };

    localClient.onaddstream = function(e){
        console.log('onaddstream', e);
    }

    localClient.ontrack = e => {
        let remoteVideo = document.getElementById("remoteVideo");
        remoteVideo.onloadedmetadata = function(){
            remoteVideo.play();
        };

        console.log('on track');

        // console.log(e.streams[0]);
        // console.log(e.streams, e.streams[0].remote);
        if(remoteVideo.srcObj !== e.streams[0]){
            // e.streams[0].onaddtrack = function(e){
                // remoteVideo.srcObj = e.streams[0]
            // }
            remoteVideo.srcObj = e.streams[0]
        }
    };
    function addAlert(userName, content, {type = 'success'} = {}) {
        $('#messageContent').append(`
        <div class="alert alert-${type} alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <strong>${userName}:</strong> <span>：${content}</span>
        </div>
        `);
    }

    let routers = {
        "othUser.login": function (data) {
            if (!loginIdList.includes(data.user.id)) {
                $('#userList').append(`<a href="javascript:;" class="list-group-item" data-id="${data.user.id}">${data.user.username}</a>`);
                loginIdList.push(data.user.id);
                loginUserList.push(data.user);
            }
        },
        "user.mail": function (data) {
            if (data.subject === "offer") {
                //offer
                localClient.setRemoteDescription(new RTCSessionDescription(data.data))
                    .then(async () => {
                        const answer = await localClient.createAnswer();
                        localClient.setLocalDescription(answer);
                        remoteUserId = data.fromUserId;
                        ws.send('user.mail:' + JSON.stringify({
                            to : data.fromUserId,
                            from: getUserId(),
                            subject: 'answer',
                            data: answer
                        }));
                    });
            } else if(data.subject === 'icecandidate') {
                localClient.addIceCandidate(new RTCIceCandidate(data.data));
            }
        },
        "user.loginList": function (data) {
            data.result.data.list.map(user => {
                routers["othUser.login"]({user: user});
            });
        }
    };

    ws.onopen = function () {
        //登录
        wsLogin(userId, ws);
        ws.send('user.loginList:' + JSON.stringify({user_id: getUserId()}))
    };

    ws.onmessage = function (e) {
        let message = {};

        try {
            message = JSON.parse(e.data)
        } catch (e) {
            return;
        }

        if (routers[message.type]) {
            const result = routers[message.type](message);
            if (result) {
                ws.send(JSON.stringify(result))
            }
        }
    };


</script>

<script>
    $(function () {
        $('#openIm').on('click', function () {
            $('#imModal').modal('show');
        });
        $('#userList').on('click', 'a', function () {
            const $this = $(this);
            const to = $this.data('id');

            var mediaConstraints = {
                audio: true, // We want an audio track
                video: true // ...and we want a video track
            };
            navigator.mediaDevices.getUserMedia(mediaConstraints)
                .then(localStream => {
                    let localVideo = document.getElementById("localVideo");
                    localVideo.srcObject = localStream;
                    localVideo.onloadedmetadata = function (e) {
                        localVideo.play();
                    };

                    localVideo.volume = 0.0;
                    // localStream.getTracks().forEach(track => localClient.addTrack(track, localStream));

                    const offer = localClient.createOffer();
                    localClient.setLocalDescription(offer);

                    ws.send('user.mail:' + JSON.stringify({
                        'to': to,
                        'from': getUserId(),
                        'subject': 'offer',
                        'data': offer
                    }));
                })

        });
    });
</script>
</html>