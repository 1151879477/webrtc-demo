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
<script src="https://cdnjs.cloudflare.com/ajax/libs/web-socket-js/1.0.0/web_socket.min.js"></script>
<script>
    $(function(){
        //user login
        var ws = new WebSocket("ws://192.168.10.252:9000/user");
        ws.onopen = function() {
            console.log('websocket is open');
            webSocketLogin();
        };
        ws.onmessage = function(e) {
            // Receives a message.
            //message
            console.log(e)
        };

        ws.onclose = function() {
            console.log('websocket is close');
        };


        function webSocketLogin(){
            let userId = localStorage.getItem('userId');
            ws.send("user.login:" + JSON.stringify({user_id:userId}))
        }
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