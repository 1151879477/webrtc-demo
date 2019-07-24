<html>
<body>
<p>login</p>

<div>
    <form id="form">
        <input type="text" name="username">
        <input type="text" name="password">
        <button type="button" id="form-submit">提交</button>
    </form>
</div>

<div>
    <a href="/user/reg">注册</a>
</div>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $('#form-submit').on('click', function () {
        $.ajax({
            url: "/user/login",
            data: {
                username: $('input[name=username]').val(),
                password: $('input[name=password]').val()
            },
            type: "post",
            success: function (response) {
                if (response.result.code === 0) {
                    alert('登录成功');
                    let user = response.result.data;
                    localStorage.setItem('user', JSON.stringify(user));
                    localStorage.setItem('userId', user.id);
                    location.href = "/webrtc/index"
                } else {
                    alert('用户名或密码错误')
                }
            },
            error: function (xhr) {
                console.log(xhr);
            }
        })
    })
</script>
</html>
