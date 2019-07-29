<html>
<head>
    <style>
        .video-container {
            width: 400px;
            height: 300px;
            border: 1px solid #18bc9c;
        }

        #userList {
            position: absolute;
            top: 0;
            right: 0;
            height: 600px;
            width: 100px;
            border: 1px solid #cccccc;
        }

        #userList {
            cursor: pointer;
        }

    </style>
</head>
<body>
<div>
    <!--视频-->
    <div class="video-container">
        <!-- 好友-->
        <video src="" id="remote_video"></video>
    </div>
    <div class="video-container">
        <!--  自己-->
        <video src="" id="local_video"></video>
    </div>
</div>

<div>
    <ul id="userList"></ul>
</div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/web-socket-js/1.0.0/web_socket.min.js"></script>
<script>

    var userId = localStorage.getItem('userId');
    if (!userId) {
        location.href = "/user/login"
    }

    var localOffer = false;
    var localClient = null;
    var answerClient = null;
    var remoteUserId = null;

    function initWebRtc() {
        localClient = createPeerConnection();
        answerClient = createPeerConnection();



        answerClient.onicecandidate = function (e) {
            if (e.candidate) {
                ws.send('user.candidate:', JSON.stringify({
                    user_id: getUserId(),
                    candidateType: 'answerClient',
                    connectUserId: remoteUserId,
                    candidate: e.candidate
                }))
            }
            console.log('answerClient is on icecandidate');
        };

        localClient.onicecandidate = function (e) {
            if (e.candidate) {
                console.log('localClient onicecandidate event');
                console.log('send localClient');
                ws.send('user.candidate:', JSON.stringify({
                    user_id: getUserId(),
                    candidateType: 'offerClient',
                    connectUserId: remoteUserId,
                    candidate: e.candidate
                }))
            }
            console.log('answerClient is on icecandidate');
        };

        const remoteVideo = document.getElementById('remote_video');
        remoteVideo.onloadedmetadata = function (e) {
            remoteVideo.play();
        };

        answerClient.ontrack = function (e) {
            console.log('answerClient ontrack');
            remoteVideo.srcObject = e.streams[0];
        };

        localClient.ontrack = function(e){
            console.log('localClient ontrack');
            remoteVideo.srcObject = e.streams[0];
        };

        var mediaConstraints = {
            audio: true, // We want an audio track
            video: true // ...and we want a video track
        };
        navigator.mediaDevices.getUserMedia(mediaConstraints).then(localStream => {
            let localVideo = document.getElementById("local_video");
            localVideo.srcObject = localStream;
            localVideo.onloadedmetadata = function (e) {
                localVideo.play();
            };

            localVideo.volume = 0.0;
            localStream.getTracks().forEach(track => localClient.addTrack(track, localStream));
            localStream.getTracks().forEach(track => answerClient.addTrack(track, localStream));
        });
    }


    var ws = new WebSocket("wss://webrtc.dai-yu.net/user");
    $(function () {
        //user login
        initWebRtc();

        ws.onopen = function () {
            setInterval(webSocketLogin, 2000);
        };

        ws.onmessage = function (e) {
            try {
                const msg = JSON.parse(e.data);
                switch (msg.type) {
                    case "user.loginList":
                        $('#userList').empty();
                        msg.result.data.list.map(item => {
                            $('#userList').append(`<li><button data-id="${item.id}">${item.username}</button></li>`)
                        });
                        break;
                    case "user.offer":
                        const offer = msg.offer;
                        localOffer = false;
                        const sessionDescription = new RTCSessionDescription(offer);

                        console.log('get remote offer and save');
                        remoteUserId = msg.connectUserId;
                        answerClient.setRemoteDescription(sessionDescription);
                        answerClient.createAnswer()
                            .then(answer => {
                                answerClient.setLocalDescription(answer)
                                    .then(() => {
                                        console.log('send answer');
                                        ws.send('user.answer:' + JSON.stringify({
                                            user_id: getUserId(),
                                            answer: answer,
                                            connectUserId: msg.connectUserId
                                        }))
                                    })
                            });
                        break;
                    case "user.answer":
                        const answer = msg.answer
                        console.log('localCent set answer');
                        localClient.setRemoteDescription(new RTCSessionDescription(answer));
                        break;
                    case "user.candidate":
                        const candidate = msg.candidate;
                        if (candidate.candidateType === 'officeClient') {
                            console.log('get localClient candidate answerClient add');
                            answerClient.addIceCandidate(candidate)
                        } else {
                            console.log('get answerClient candidate localClient add');
                            localClient.addIceCandidate(candidate)
                        }
                }
            } catch (e) {
                return;
            }
        };

        ws.onclose = function () {
            console.log('websocket is close');
        };

        function webSocketLogin() {
            let userId = getUserId();
            ws.send("user.login:" + JSON.stringify({user_id: userId}))
            getUserList();
        }


        function getUserList() {
            ws.send('user.loginList:' + JSON.stringify({user_id: getUserId()}))
        }
    });

    function getUserId() {
        return localStorage.getItem('userId');
    }

    $(function () {
        $('#userList').on('click', 'button', function () {
            let $this = $(this);
            localOffer = true;
            remoteUserId = $this.data('id');
            console.log('localClient create Offer');
            localClient.createOffer()
                .then(offer => {
                    localClient.setLocalDescription(offer)
                        .then(() => {
                            console.log('save local description');
                            console.log('send ws offer');
                            ws.send('user.offer:' + JSON.stringify({
                                user_id: getUserId(),
                                connectUserId: $this.data('id'),
                                offer: offer
                            }))
                        })
                })
        })
    });

    function createPeerConnection() {
        var mediaConstraints = {};
        var string = "stun:stun1.l.google.com:19302\n" +
            "stun:stun2.l.google.com:19302\n" +
            "stun:stun3.l.google.com:19302\n" +
            "stun:stun4.l.google.com:19302\n" +
            "stun:23.21.150.121\n" +
            "stun:stun01.sipphone.com\n" +
            "stun:stun.ekiga.net\n" +
            "stun:stun.fwdnet.net\n" +
            "stun:stun.ideasip.com\n" +
            "stun:stun.iptel.org\n" +
            "stun:stun.rixtelecom.se\n" +
            "stun:stun.schlund.de\n" +
            "stun:stunserver.org\n" +
            "stun:stun.softjoys.com\n" +
            "stun:stun.voiparound.com\n" +
            "stun:stun.voipbuster.com\n" +
            "stun:stun.voipstunt.com\n" +
            "stun:stun.voxgratia.org\n" +
            "stun:stun.xten.com"
        let arr = string.split("\n")
        return new RTCPeerConnection({
            iceServers: [
                {"urls": arr}
            ]
        }, mediaConstraints);
    }
</script>
</html>
