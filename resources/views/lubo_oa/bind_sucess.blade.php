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
</div>

<footer class="footer">
    <a href="javascript:void(0);">陈晓梅</a><a class="my_video_course" href="./my_course.blade.php">我的微课</a>
</footer>

<script src="{{ URL::asset('js/zeptojs1.2.comzepto.min.js') }}"></script>
<script>
    console.log({{  $user }});
    // 确定绑定
    $('.un_bind_submit').on('click', function () {
        alert("成功！");
        window.open('./bind_acount.html', '_self');
    })
</script>
</body>

</html>