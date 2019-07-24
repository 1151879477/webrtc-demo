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

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script>
    $('#form-submit').on('click', function(){
        $.ajax({
            url: "/user/login",
            data: {
                username: $('input[name=username]').val(),
                password: $('input[name=password]').val()
            },
            type: "post",
            success: function(result){
                if(result.code ===  0){
                    alert('登录成功')
                }else{
                    alert('用户名或密码错误')
                }
            },
            error: function(xhr){
                console.log(xhr);
            }
        })
    })
</script>
</html>
