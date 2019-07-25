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
        <video src=""></video>
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

        localClient.onicecandidate = function (e) {
            if (!e || !e.candidate) return
            ws.send('user:candidate:', {
                user_id: getUserId(),
                candidateType: 'officeClient',
                connectUserId: remoteUserId,
                candidate: e.candidate
            })
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
            localStream.getTracks().forEach(track => localClient.addTrack(track, localStream));
        });
    }


    var ws = new WebSocket("ws://192.168.10.252:9000/user");
    $(function () {
        //user login
        initWebRtc();

        ws.onopen = function () {
            setInterval(webSocketLogin, 5000);
        };

        ws.onmessage = function (e) {
            try {
                const msg = JSON.parse(e.data)
                switch (msg.type) {
                    case "user.loginList":
                        $('#userList').empty();
                        msg.result.data.list.map(item => {
                            $('#userList').append(`<li data-id="${item.id}">${item.username}</li>`)
                        });
                        break;
                    case "user.offer":
                        const offer = msg.offer;
                        localOffer = false;

                        answerClient.setRemoteDescription(offer);
                        answerClient.createAnswer()
                            .then(answer => {
                                answerClient.setLocalDescription(answer)
                                    .then(() => {
                                        ws.send('user.answer:'.JSON.stringify({
                                            user_id: getUserId(),
                                            answer: answer
                                        }))
                                    })
                            });
                        break;
                    case "user.answer":
                        const answer = msg.answer;
                        localClient.setRemoteDescription(answer);
                        break;
                    case "user.candidate":
                        const candidate = msg.candidate;
                        if (candidate.candidateType === 'officeClient') {
                            answerClient.addIceCandidate(candidate)
                        } else {
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
        $('#userList').on('click', 'li', function () {
            let $this = $(this);
            localOffer = true;
            remoteUserId = $this.data('id');
            localClient.createOffer()
                .then(offer => {
                    localClient.setLocalDescription(offer)
                        .then(() => {
                            console.log($this.data('id'));
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
        return new RTCPeerConnection({
            iceServers: [     // Information about ICE servers - Use your own!
                {
                    urls: "turn://47.90.123.45:3479",
                    username: "daiyu",
                    credential: "adcfd1+.+"
                }
            ]
        }, mediaConstraints);
    }
</script>
</html>