<html>
<head>
    <style></style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/css/bootstrap.min.css">
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
        <div class="list-group">
            <a href="#" class="list-group-item disabled">用户列表</a>
            <a href="#" class="list-group-item">Dapibus ac facilisis in</a>
            <a href="#" class="list-group-item">Morbi leo risus</a>
            <a href="#" class="list-group-item">Porta ac consectetur ac</a>
            <a href="#" class="list-group-item">Vestibulum at eros</a>
        </div>
    </div>
</div>

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/reconnecting-websocket/1.0.0/reconnecting-websocket.min.js"></script>
<script src="/user.js"></script>
<script>

    let url = '127.0.0.1:9000';
    var ws = new ReconnectingWebSocket('ws://' + url + "/user");
    let userId = getUserId();

    let routers = {
        "othUser.login": function(data){
            console.log(data);
        }
    };
    ws.onopen = function(){
        //登录
        wsLogin(userId, ws);
    };

    ws.onmessage = function(e){
        console.log(e.data);
        const message = JSON.parse(e.data)

        if(routers[e.type]){
            const result = routers[e.type](message);
            if(result){
                ws.send(JSON.stringify(result))
            }
        }
    };



</script>
</html>