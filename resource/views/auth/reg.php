<html>
<body>
<p>Reg</p>

<div>
    <form id="form">
        <input type="text" name="username">
        <input type="text" name="password">
        <button type="button" id="form-submit">注册</button>
    </form>
</div>

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $('#form-submit').on('click', function () {
        let user = {
            username: $('input[name=username]').val(),
            password: $('input[name=password]').val()
        };

        if (user.username === "" || user.password === "") {
            alert('用户名或密码不能为空!');
            return;
        }
        $.ajax({
            url: "/user/reg",
            data: {
                ...user
            },
            type: "post",
            success: function (response) {
                if (response.result.code === 0) {
                    alert('注册成功');
                    let user = response.result.data;
                    localStorage.setItem('user', JSON.stringify(user));
                    localStorage.setItem('userId', user.id);
                    location.href = "/webrtc/index"
                } else {
                    alert(response.result.msg || '注册失败， 用户名可能已经存在了')
                }
            },
            error: function (xhr) {
                console.log(xhr);
            }
        })
    })
</script>
</html>
