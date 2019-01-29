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
    <link rel="stylesheet" href="{{ URL::asset('css/dropload.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/plyr.css') }}">
    <script src="{{ URL::asset('js/plyr.js') }}"></script>
    <!-- 插件 -->
    <link rel="stylesheet" href="{{ URL::asset('css/style.css') }}">
    <!-- 项目页样式文件 -->
    <link rel="stylesheet" href="{{ URL::asset('css/index.css') }}">
</head>

<body>
<header class="header">
    <a href="javascript: void(0);" onClick="javascript :history.go(-1);" class="icon icon_back"></a>
    <p class="title">...</p>
    <p class="icon icon_share"></p>
</header>

<div class="root_wrap" id="tpl_parent">
    <p id="hasplay" style="display: none;"></p>
    <script type="text/html" id="tpl">
        {{if data.data && data.data.length > 0 }}
        <ul class="video_list">
            {{each data.data v i}}
            <li class="item" data-vid="{{v.video_id}}">
                <video id="my_video_{{v.video_id}}" class="video_content" controls poster="{{v.img_path}}"
                       videoid="{{v.video_id}}" onplay="begin_playing({{v.video_id}})">
                    <source src="{{v.path}}" type="video/mp4">
                </video>
                <div class="detail">
                    <p class="title text_ellipsis">{{v.video_title}}</p>
                    <p class="date">{{v.created_at}}</p>
                </div>
            </li>
            {{/each}}
        </ul>
        {{else}}
        <div class="no_data_text">暂无数据</div>
        {{/if}}
    </script>

    <footer class="footer">
        <a href="javascript:void(0);">陈晓梅</a><a class="my_video_course" href="./my_course.blade.php">我的微课</a>
    </footer>
</div>

<script src="{{ URL::asset('js/zeptojs1.2.comzepto.min.js') }}"></script>
<script src="{{ URL::asset('js/index.js') }}"></script>
<script src="{{ URL::asset('js/template-web.js') }}"></script>
<script src="{{ URL::asset('js/dropload.js') }}"></script>

<script>
    var getUrlParams = getSearchObj();
    $('#hasplay').attr('data-hasdata', '1');

    /*
    * 获取本页数据
    */
    function get_video_list(me) {
        $.ajax({
            type: 'GET',
            url: window.MAIN_CONFIG.USEFULL_API + '/api/wx/stu/sub-index/' + getUrlParams.subid + '?line=' + window.MAIN_CONFIG.PAGE_LINE + '&page=' + window.MAIN_CONFIG.CUR_PAGE,
            dataType: 'json',
            beforeSend: function (request) {
                request.setRequestHeader('Authorization', window.MAIN_CONFIG.Authorization);
            },
            success: function (res) {
                if ('successful' === res.status) {
                    render(res);
                    console.log(res.data.data.length);
                    if (res.data.data && res.data.data.length == 0) { // 没有数据时，置0
                        $('#hasplay').attr('data-hasdata', '0');
                    }
                    me.resetload();// 每次数据加载完，必须重置
                } else {
                    alert('网络错误！');
                    me.resetload();// 每次数据加载完，必须重置
                    return false;
                }
            }
        });
    }

    /*
    * 渲染模板
    */
    function render(res) {
        var subject = res.data.sub_name || '我的微课';
        $('.header>.title').text(subject);

        var htm = template('tpl', res);
        $('.root_wrap').append(htm); // 追加数据展示

    }

    /*
    * 上滑加载更多数据
    */
    $('#tpl_parent').dropload({
        scrollArea: window,
        domDown: {
            domClass: 'dropload-up',
            domRefresh: '<div class="dropload-refresh">↑上拉加载更多</div>',
            domLoad: '<div class="dropload-load"><span class="loading"></span>加载中...</div>',
            domNoData: '<div class="dropload-noData"></div>'
        },
        autoLoad: true,
        threshold: 2 / 3,
        loadDownFn: function (me) { // 上滑，继续加载数据
            if ($('#hasplay').attr('data-hasdata') == '0') {
                return false; // 没有数据时，不再发送请求
            }
            get_video_list(me);
            window.MAIN_CONFIG.CUR_PAGE++; // 页码++
        }

    });
</script>
</body>
</html>