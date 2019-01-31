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
    <p class="title">绑定成功</p>
</header>

<div class="root_wrap">
    <div class="bind_account_wrapper">
        <div class="bind_sucess">
            <div class="form_row">
                <i class="icon icon_user_active"></i>
                <p class="name">陈晓梅</p>
            </div>
            <div class="form_row">
                <i class="icon icon_pwd_active"></i>
                <p class="grade_class">高三（3）班</p>
            </div>
            <img class="move_book" src="{{ URL::asset('images/move_book.png') }}" alt="logo"/>
        </div>
        <p class="btn un_bind_submit">解除绑定</p>
    </div>
    <input type="text" value="{{ $yj_wx_token }}" id="yj_token_wx">
</div>

<footer class="footer">
    <a href="javascript:void(0);" class="stuname">陈晓梅</a><a class="my_video_course" href="{{ URL::route('wx.my-course',['openid' => $openid]) }}">我的微课</a>
</footer>

<script src="{{ URL::asset('js/zeptojs1.2.comzepto.min.js') }}"></script>
<script src="{{ URL::asset('js/index.js') }}"></script>
<script>
    //如果已经绑定，跳转到成功绑定的页面，设置token值
    var yj_token_wx = $("#yj_token_wx").val();
    console.log(yj_token_wx);
    console.log(!yj_token_wx);
    if (yj_token_wx) {
        window.localStorage.setItem('yj_wx_token',yj_token_wx);
    }
    $.ajax({
        type: 'GET',
        url: window.MAIN_CONFIG.USEFULL_API + '/api/wx/stu/stu-msg',
        dataType: 'json',
        beforeSend: function (request) {
            request.setRequestHeader('Authorization', "Bearer " + window.localStorage.getItem('yj_wx_token'));
        },
        success: function (res) {
            if ('successful' === res.status) {
                $('.name').text(res.data.name);
                $('.stuname').text(res.data.name);
                window.localStorage.setItem('yj_wx_user_name',res.data.name);
                $('.grade_class').text(res.data.grade_class);
                console.log(res);
            } else {
                alert('网络错误！');
                return false;
            }
        }
    });
    // 确定绑定
    $('.un_bind_submit').on('click', function () {
        alert("成功！");
        window.localStorage.removeItem('yj_wx_token');
        window.localStorage.removeItem('yj_wx_user_name');
        location.href = "{{ URL::route('wx.bind-acount',['openid' => $openid]) }}";

    })
</script>
</body>

</html>