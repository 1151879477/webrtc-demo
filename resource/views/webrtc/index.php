<html>
<body>
<div>
<!--视频-->
    <div>
<!-- 好友-->
        <video src="" height="500px"></video>
    </div>
    <div>
<!--  自己-->
        <video src="" height="300px;"></video>
    </div>
</div>

<div>
    <ul id="userList"></ul>
</div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.2.0/socket.io.js"></script>

<script>
    $(function(){

    });

    function createPeerConnection() {
        var mediaConstraints = {

        };
        return new RTCPeerConnection({
            iceServers: [     // Information about ICE servers - Use your own!
                {
                    urls: "turn://47.90.123.45:3479",
                    username: "ling2",
                    credential: "ling1234"
                }
            ]
        }, mediaConstraints);
    }
</script>
</html>