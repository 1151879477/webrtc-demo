<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        #userList a{
            cursor: pointer;
        }
    </style>
</head>
<body>
<div class="row">
    <div class="col-md-3">

    </div>
    <div class="col-md-3">

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
    let loginList = [];

    let routers = {
        "othUser.login": function (data) {
            console.log('router console', data);
            if(!loginList.includes(data.user.id)){
                $('#userList').append(`<a href="#" class="list-group-item disabled" data-id="${data.user.id}">${data.user.username}</a>`);
                loginList.push(data.user.id);
            }
        }
    };
    ws.onopen = function () {
        //登录
        wsLogin(userId, ws);
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
</html>