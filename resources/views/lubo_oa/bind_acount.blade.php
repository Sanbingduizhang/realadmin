<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
  <title>录播公众号</title>
    <meta name="viewport" content="width=640,user-scalable=no" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
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

  <!-- 绑定账号显示 START -->
  <div class="module_bind_account module_show">
    <div class="root_wrap">
      <div class="bind_account_wrapper">
        <img class="login_logo" src="{{ URL::asset('images/bind_acount/logo.png') }}" alt="logo" />
        <div class="bind_form">
          <div class="form_row">
            <label for="name" class="label_name"><i class="icon icon_user"></i></label>
            <input type="text" required value="" id="name" class="input_style_update color_dbdbdb" placeholder="请输入账号">
          </div>
          <div class="form_row">
            <label for="pwd" class="label_pwd"><i class="icon icon_pwd"></i></label>
            <input type="password" required id="pwd" class="input_style_update color_dbdbdb" placeholder="请输入密码">
          </div>
          <!-- openid隐示传递 -->
          <input type="text" id="openid" value="{{ $openid }}"  style="display: none;">
        </div>
        <p class="btn bind_submit">绑定</p>
      </div>
    </div>
  </div>
  <!-- 绑定账号显示 END -->


  <!-- 绑定账号成功 START -->
  <div class="module_bind_success module_hide">
    <div class="root_wrap">
      <div class="bind_account_wrapper">
        <div class="bind_success">
          <div class="form_row">
            <i class="icon icon_user_active"></i>
            <p class="name">陈晓梅</p>
          </div>
          <div class="form_row">
            <i class="icon icon_pwd_active"></i>
            <p class="grade_class">高三（3）班</p>
          </div>
          <img class="move_book" src="{{ URL::asset('images/move_book.png') }}" alt="logo" />
        </div>
        <p class="btn un_bind_submit">解除绑定</p>
      </div>
    </div>

    <input type="text" value="{{ $yj_wx_token }}" id="yj_token_wx" checkpage="true" style="display: none;">

  </div>
  <!-- 绑定账号成功 END -->

  <footer class="footer">
    <a href="javascript: void(0);">绑定账号</a><a class="my_video_course unbind" href="javascript:void(0)" >我的微课</a>
  </footer>
  <a href="{{ URL::route('wx.my-course') }}" style="display: none;" class="coursego"></a>



<script src="{{ URL::asset('js/zeptojs1.2.comzepto.min.js') }}"></script>
<script src="{{ URL::asset('js/template-web.js') }}"></script>
<script src="{{ URL::asset('js/index.js') }}"></script>
<script>


// 设定定时器，检查账号密码，有值时，更改文字颜色和图标
setInterval(function(){
  // 账号
  if ( ! $('#name').val() ) {
    $('#name').removeClass('color_333').addClass('color_dbdbdb');
    $('.label_name i').removeClass('icon_user_active').addClass('icon_user');
  } else { // 有值
    $('#name').removeClass('color_dbdbdb').addClass('color_333');
    $('.label_name i').removeClass('icon_user').addClass('icon_user_active');
  }
  // 密码
  if ( ! $('#pwd').val() ) {
    $('#pwd').removeClass('color_333').addClass('color_dbdbdb');
    $('.label_pwd i').removeClass('icon_pwd_active').addClass('icon_pwd');
  } else { // 有值
    $('#pwd').removeClass('color_dbdbdb').addClass('color_333');
    $('.label_pwd i').removeClass('icon_pwd').addClass('icon_pwd_active');
  }

}, 500)


  // 我的课程
  $('.my_video_course').on('click', function() {
    if ($('.my_video_course').hasClass('unbind')) {
      alert('请先绑定账号！');
      return ;
    }
  })
</script>
</body>

</html>