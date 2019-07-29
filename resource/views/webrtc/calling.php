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
        <div class="alert alert-success alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                        aria-hidden="true">&times;</span></button>
            <strong>没有风筝的线:</strong> <span>：在吗？</span>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3">

    </div>
    <div class="col-md-3">
        <button class="btn btn-primary" onclick="$('ImModel').modal('show');">Im</button>
    </div>
    <div class="col-md-3">
    </div>
    <div class="col-md-3">
        <div class="list-group" id="userList">
            <a href="#" class="list-group-item disabled">用户列表</a>
        </div>
    </div>

</div>

<!-- Im Modal -->
<div class="modal fade" id="ImModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                ...
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
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
    let loginIdList = [];
    let loginUserList = [];

    function addAlert(userName, content, {type='success'}={}) {
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
            addAlert(data.fromUser.username, data.data.content)
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