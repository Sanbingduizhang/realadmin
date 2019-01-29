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
    <link rel="stylesheet" href="./css/style.css">
    <!-- 本页样式文件 -->
    <link rel="stylesheet" href="./css/index.css">
</head>

<body>
  <header class="header">
    <a href="javascript: void(0);" onClick="javascript :history.go(-1);" class="icon icon_back"></a>
    <p class="title">我的微课</p>
    <p class="icon icon_share"></p>
  </header>

  <div class="root_wrap">
    <script type="text/html" id="tpl">
      <ul class="course_list">
        {{each data.subject v i}}
          <li class="course_item">
            {{if v.notify > 0 }}
            <p class="notice text_ellipsis"> {{v.notify}}</p>
            {{/if}}
            <a href="./video_list.blade.php?subid={{v.id}}">
              <img class="icon_cover" src="{{v.path}}" alt="{{v.name}}">
            </a>
            <p class="text_ellipsis">{{v.name}}</p>
          </li>
        {{/each}}
     </ul>
    </script>
  </div>

  <footer class="footer">
    <a href="javascript:void(0);">陈晓梅</a><a class="my_video_course" href="javascript:void(0)">我的微课</a>
  </footer>

  <script src="./js/zeptojs1.2.comzepto.min.js"></script>
  <script src="./js/index.js"></script>
<script src="./js/template-web.js"></script>
<script>
  $.ajax({
    type: 'GET',
    url: window.MAIN_CONFIG.USEFULL_API + '/api/wx/stu/index',
    dataType: 'json',
    beforeSend: function (request) {
      request.setRequestHeader('Authorization', window.MAIN_CONFIG.Authorization);
    },
    success: function (res) {
      if ('successful' === res.status) {
        render(res)
      } else {
        alert('网络错误！');
        return false;
      }
    }
  });

  // 渲染
  function render(res) {
    var htm = template('tpl', res);
    $('.root_wrap').html(htm)
  }
</script>

</body>

</html>