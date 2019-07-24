<html>
<head>
    <style>
        .video-container{
            width: 800px;
            height: 600px;
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
        <video src=""></video>
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
    if(!userId){
        location.href="/user/login"
    }

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
            let userId = getUserId();
            ws.send("user.login:" + JSON.stringify({user_id:userId}))
            getUserList()
        }
        function getUserId(){
            return localStorage.getItem('userId');
        }
        function getUserList(){
            $.ajax({
                url: "/user/loginList",
                type: 'get',
                data: {
                    user_id: getUserId()
                },
                success: function(response){
                    if(response.result.code !== 0){
                        alert(response.result.msg || '接口返回异常: \n' + JSON.stringify(response))
                        return;
                    }

                    let data = response.result.data;

                    $('#userList').empty()
                    data.list.map(item => {
                        $('#userList').append(`<li>${item.username}</li>`)
                    })
                }
            })
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