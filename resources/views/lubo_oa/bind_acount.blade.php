<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>录播公众号</title>
    <meta name="viewport" content="width=640,user-scalable=no"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <!-- 插件 -->
    <link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">
    <!-- 项目页样式文件 -->
    <link rel="stylesheet" href="{{ URL::asset('css/index.css') }}">
</head>

<body>
<header class="header">
    <p class="title">绑定账号</p>
</header>

<div class="root_wrap">
    <div class="bind_account_wrapper">
        <img class="login_logo" src="{{ URL::asset('images/bind_acount/logo.png') }}" alt="logo"/>
        <div class="bind_form">
            <div class="form_row">
                <label for="name" class="label_name"><i class="icon icon_user"></i></label>
                <input type="text" required value="" id="name" class="input_style_update color_dbdbdb"
                       placeholder="请输入账号">
            </div>
            <div class="form_row">
                <label for="pwd" class="label_pwd"><i class="icon icon_pwd"></i></label>
                <input type="password" required id="pwd" class="input_style_update color_dbdbdb" placeholder="请输入密码">
            </div>
            <!-- openid隐示传递 -->
            <input type="text" id="openid" value="{{ $openid }}">
        </div>
        <p class="btn bind_submit">绑定</p>
    </div>
</div>

<footer class="footer">
    <a href="javascript: void(0);">账号绑定</a><a class="my_video_course" href="javascript:void(0)">我的微课</a>
</footer>

<script src="{{ URL::asset('js/zeptojs1.2.comzepto.min.js') }}"></script>
<script src="{{ URL::asset('js/template-web.js') }}"></script>
<script src="{{ URL::asset('js/index.js') }}"></script>
<script>
    // 确定绑定
    $('.bind_submit').on('click', function () {
        $.ajax({
            type: 'POST',
            url: 'http://10.10.10.167/api/wx/set-user?usercode=' + $('#name').val() + '&password=' + $('#pwd').val() + '&openid=' + $('#openid').val(),
            dataType: 'json',
            success: function (result) {
                if (result.code === 1) {
                    alert("成功！");
                    // 跳转至 我的课程
                    console.log(result);
                    window.MAIN_CONFIG.Authorization = "Bearer " + result.data.token;
                    console.log(window.MAIN_CONFIG.Authorization);
                    {{--location.href = "{{ URL::action('WxController@bindSucess') }}";--}}
                        location.href = "{{ URL::route('wx.bind-sucess') }}";
                } else {
                    alert("账号或密码错误！");
                }
            }
        })
    })

    // 设定定时器，检查账号密码，有值时，更改文字颜色和图标
    setInterval(function () {
// 账号
        if (!$('#name').val()) {
            $('#name').removeClass('color_333').addClass('color_dbdbdb');
            $('.label_name i').removeClass('icon_user_active').addClass('icon_user');
        } else { // 有值
            $('#name').removeClass('color_dbdbdb').addClass('color_333');
            $('.label_name i').removeClass('icon_user').addClass('icon_user_active');
        }
// 密码
        if (!$('#pwd').val()) {
            $('#pwd').removeClass('color_333').addClass('color_dbdbdb');
            $('.label_pwd i').removeClass('icon_pwd_active').addClass('icon_pwd');
        } else { // 有值
            $('#pwd').removeClass('color_dbdbdb').addClass('color_333');
            $('.label_pwd i').removeClass('icon_pwd').addClass('icon_pwd_active');
        }

    }, 500)


    // 我的课程
    $('.my_video_course').on('click', function () {
        alert('请先绑定账号！')
    })
</script>
</body>

</html>