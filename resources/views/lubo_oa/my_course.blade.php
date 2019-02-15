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
    <!-- <a href="javascript: void(0);" onClick="javascript :history.go(-1);" class="icon icon_back"></a> -->
    <p class="title">我的微课</p>
    <p class="icon icon_share"></p>
  </header>
  <p id="hasplay" style="display: none;"></p>

  <!-- 科目列表 START -->
  <div class="module_course_list module_show">
    <div class="course_list_wrap">
      <script type="text/html" id="tpl_course_list">
        <ul class="course_list">
          [[each data.subject v i]]
            <li class="course_item">
              [[if v.notify > 0 ]]
              <p class="notice text_ellipsis"> [[v.notify]]</p>
              [[/if]]

              <img class="icon_cover" src="[[v.path]]" alt="[[v.name]]" data-subid="[[v.id]]">

              <!-- <a href="./video_list.html?subid=[[v.id]]">
                <img class="icon_cover" src="[[v.path]]" alt="[[v.name]]">
              </a> -->
              <p class="text_ellipsis">[[v.name]]</p>
            </li>
          [[/each]]
       </ul>
      </script>
    </div>
  </div>
  <!-- 科目列表 END -->

  <!-- 视频列表 START -->
  <div class="module_video_list module_hide">
    <!-- <header class="header">
      <a href="javascript: void(0);" onClick="javascript :history.go(-1);" class="icon icon_back"></a>
      <p class="title">...</p>
      <p class="icon icon_share"></p>
    </header> -->
  
    <div class="video_list_wrap">
      
      <script type="text/html" id="tpl_video_list">
        [[if data.data && data.data.length > 0 ]]
          <ul class="video_list" id="video_list_content">
            [[each data.data v i]]
              <li class="item" data-vid="[[v.video_id]]">
                <video id="my_video_[[v.video_id]]" class="video_content" controls poster="[[v.img_path]]" videoid="[[v.video_id]]" onplay="begin_playing([[v.video_id]])">
                  <source src="[[v.path]]" type="video/mp4">
                </video>
                <div class="detail">
                  <p class="title text_ellipsis">[[v.video_title]]</p>
                  <p class="date">[[v.created_at]]</p>
                </div>
              </li>
            [[/each]]
          </ul>
        [[else]]
          <div class="no_data_text">暂无更多数据</div>
        [[/if]]
      </script>
    </div>
  </div>
  <!-- 视频列表 END -->
  <input type="text" value="{{ $yj_wx_token }}" id="yj_token_wx" style="display: none;">
  <input type="text" value="{{ $yj_wx_name }}" id="yj_token_user_name" style="display: none;">

  <footer class="footer">
    <a href="{{ URL::route('wx.bind-sucess',['openid' => $openid]) }}">陈晓梅</a><a class="my_video_course" href="{{ URL::route('wx.my-course',['openid' => $openid]) }}">我的微课</a>
  </footer>
  
<script src="{{ URL::asset('js/zeptojs1.2.comzepto.min.js') }}"></script>
<script src="{{ URL::asset('js/template-web.js') }}"></script>
<script>
  //重写js模板语法
  var rule = template.defaults.rules[1];
  rule.test = new RegExp(rule.test.source.replace('\{\{', '\\[\\[').replace('\}\}', '\\]\\]'));
</script>
  
<script src="{{ URL::asset('js/dropload.js') }}"></script>
<script src="{{ URL::asset('js/index.js') }}"></script>
<script>
  var getUrlParams = getSearchObj();
  $('#hasplay').attr('data-hasdata', '1');
  // 获取科目数据
  get_course_list();
</script>

</body>

</html>