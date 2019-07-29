<html>
<head>
    <style></style>
</head>
<body>

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/reconnecting-websocket/1.0.0/reconnecting-websocket.min.js"></script>

<script>
    let url = '127.0.0.1:9000';
    var ws = new ReconnectingWebSocket('ws://' + url + "/user");
    ws.open(function(){
        console.log('is open');
    })
</script>
</html>