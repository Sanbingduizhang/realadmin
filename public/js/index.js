/*------------------------------------- 页面加载后立即执行部分 ------------------------------------------------*/
;(function (name, definition, context) {
  if ((typeof module !== 'undefined') && module.exports)
        module.exports = definition() // node 环境
  else if ((typeof context['define'] === 'function') && (context['define']['amd'] || context['define']['cmd']))
        define(definition)            // amd cmd 规范环境，如 seajs requirejs
  else
    context[name] = definition()  // 浏览器环境
})('MAIN_CONFIG', function () {

  return {
    //USEFULL_API: 'http://10.10.10.167', // 接口开头数据
    USEFULL_API: 'http://www.ischool365.com:16780', // 接口开头数据
    Authorization: "100141101+16+gZK6aaZUf6+oSAUb0lOEfQu8Q_up87ZsyAp_GUU",
    PAGE_LINE: 10,
    CUR_PAGE: 0, // 记录视频列表页的页码，首次进入列表页，归1
    CUR_SUBID: -1, // 当前选择的科目id
  }

}, this);

/*------------------------------------- mixin  ------------------------------------------------*/
/**
 * 页头分享事件
 */
$('.icon_share').on('click', function() {
  alert('分享');
});

/**
 * 获取地址栏参数
 */
function getSearchObj() {
  var arr = decodeURI(location.search).slice(1).split("?");
  var obj = {};
  arr.forEach(function (v) {
      return obj[v.split("=")[0]] = v.split("=")[1];
  });
  return obj;
}

/*------------------------------------- 账号绑定与解绑页  ------------------------------------------------*/

//如果已经绑定，跳转到成功绑定的页面，设置token值

function setUserMsg() {
    var yj_token_wx = $("#yj_token_wx").val();
    if (yj_token_wx) {
        window.localStorage.setItem('yj_wx_token',yj_token_wx);
        $('.module_bind_account').removeClass('module_show').addClass('module_hide');
        $('.module_bind_success').removeClass('module_hide').addClass('module_show');
        var userName = getUserMsg();
        var str = '<a href="javascript:void(0);">'+userName+'</a><a class="my_video_course" href="{{ URL::route(\'wx.my-course\') }}">我的微课</a>';
        $('.footer').html(str)
    }
}
setUserMsg();

/**
 * 确定 绑定
 */
$('.bind_submit').on('click', function() {
  $.ajax({
    type: 'POST',
    // url: window.MAIN_CONFIG.USEFULL_API + '/api/admin/login?login_name='+ $('#name').val()+'&password='+ $('#pwd').val(),
    url: window.MAIN_CONFIG.USEFULL_API + '/api/wx/set-user?usercode=' + $('#name').val() + '&password=' + $('#pwd').val() + '&openid=' + $('#openid').val(),
    dataType: 'json',
    success: function(result) {
      if (result.code === 1) {

        $('.header>.title').text('绑定成功');
        setUserMsg();
        // $('.module_bind_account').removeClass('module_show').addClass('module_hide');
        // $('.module_bind_success').removeClass('module_hide').addClass('module_show');
        // getUserMsg();
        // var str = '<a href="javascript:void(0);">'+result.data.name+'</a><a class="my_video_course" href="./my_course.html">我的微课</a>';
        // $('.footer').html(str)
      } else{
        alert("账号或密码错误！");
      }
    }
  })
})


/**
 * 获取用户信息
 */
function getUserMsg() {
    var userName = '';
    $.ajax({
        type: 'GET',
        url: window.MAIN_CONFIG.USEFULL_API + '/api/wx/stu/stu-msg',
        dataType: 'json',
        beforeSend: function (request) {
            request.setRequestHeader('Authorization', "Bearer " + window.localStorage.getItem('yj_wx_token'));
        },
        success: function (res) {
            if (1 === res.code) {
                window.localStorage.setItem('yj_wx_user_name', res.data.name);
                userName = res.data.name;
                $('.name').text(res.data.name);
                $('.stuname').text(res.data.name);
                $('.grade_class').text(res.data.grade_class);
            } else {
                alert('网络错误！');
                return false;
            }
        }
    });
    return userName;
}

/**
 * 取消 绑定
 */
$('.un_bind_submit').on('click', function() {
  $('.header>.title').text('绑定账号');
  var str = '<a href="javascript: void(0);">绑定账号</a><a class="my_video_course" href="javascript:void(0)">我的微课</a>';
  $('.footer').html(str);
  $('.module_bind_account').removeClass('module_hide').addClass('module_show');
  $('.module_bind_success').removeClass('module_show').addClass('module_hide');
})

/*------------------------------------- 科目列表页  ------------------------------------------------*/
function get_course_list() {
  $.ajax({
    type: 'GET',
    url: window.MAIN_CONFIG.USEFULL_API + '/api/wx/stu/index',
    dataType: 'json',
    beforeSend: function (request) {
      request.setRequestHeader('Authorization', window.MAIN_CONFIG.Authorization);
    },
    success: function (res) {

      if ('successful' === res.status) {
        course_list_render(res)
      } else {
        alert('网络错误！');
        return false;
      }
    }
  });
}

// 渲染
function course_list_render(res) {
  var htm = template('tpl_course_list', res);
  $('.course_list_wrap').html(htm)

/**
 * 查看某个科目的视频列表
 * @param subid 科目id
 */
  $('.icon_cover').on('click', function() {
    var subid = $(this).attr('data-subid');
    window.MAIN_CONFIG.CUR_SUBID = subid;

    $('.module_course_list').removeClass('module_show').addClass('module_hide');
    $('.module_video_list').removeClass('module_hide').addClass('module_show');
    $('#hasplay').attr('data-hasdata', 1);

    // 获取科目对应视频数据
    dropload_video_list();
  });
}

/*------------------------------------- 视频列表页  ------------------------------------------------*/

/*
* 上滑加载更多数据
*/
function dropload_video_list() {
  $('#tpl_video_list').dropload({
    scrollArea : $('#video_list_content'),
    autoLoad: true,
    threshold: 2/3,
    loadDownFn: function(me) { // 上滑，继续加载数据
      window.MAIN_CONFIG.CUR_PAGE++; // 页码++

      if (parseInt($('#hasplay').attr('data-hasdata')) === 0) {
        return false; // 没有数据时，不再发送请求
      }
      setTimeout(function() {
        get_video_list(me);
      }, 300)
    }
  });
}

/*
* 获取本页数据
*/
function get_video_list(me) {
  if (parseInt(window.MAIN_CONFIG.CUR_SUBID) === -1) { // 科目id不存在，退出
    return;
  }
  if (parseInt($('#hasplay').attr('data-hasdata')) === 0) {
    return false; // 没有数据时，不再发送请求
  }
  $.ajax({
    type: 'GET',
    url: window.MAIN_CONFIG.USEFULL_API + '/api/wx/stu/sub-index/' + window.MAIN_CONFIG.CUR_SUBID +'?line=' + window.MAIN_CONFIG.PAGE_LINE +'&page=' + window.MAIN_CONFIG.CUR_PAGE,
    dataType: 'json',
    beforeSend: function (request) {
      request.setRequestHeader('Authorization', window.MAIN_CONFIG.Authorization);
    },
    success: function (res) {
      if ('successful' === res.status) {
        
        video_list_render(res);
        me.resetload();// 每次数据加载完，必须重置

        if (res.data.data && res.data.data.length === 0) { // 没有数据时，置0
          $('#hasplay').attr('data-hasdata', 0);
        }
      } else {

        me.lock();
        me.noData();
        return false;
      }
    },
    error: function(xhr, type){
      alert('网络错误！');
      me.resetload();
    }
  });
}

/*
* 渲染模板
*/
function video_list_render(res) {
  var subject = res.data.sub_name || '我的微课';
  $('.header>.title').text(subject);

  var htm = template('tpl_video_list', res);
  $('.video_list_wrap').append(htm); // 追加数据展示

}
/**
 * 点击播放某个视频，向后台发送该视频id
 * 当前列表页多次点击播放仅发送一次
 */
function begin_playing(vid) {
  var vids = $('#hasplay').attr('data-played') || '';
  var vids_arr = vids.split(',');
  // 当前页中，已存在，播放过，则不再向后台发送
  if (vids_arr.indexOf(vid + '') > -1) {
    return false;
  }

  vids += vid + ','
  $('#hasplay').attr('data-played', vids);
  $.ajax({
    type: 'get',
    url: window.MAIN_CONFIG.USEFULL_API + '/api/wx/stu/play-video/'+ vid,
    beforeSend: function (request) {
      request.setRequestHeader('Authorization', window.MAIN_CONFIG.Authorization);
    },
    success: function (res) {
      if ('successful' === res.status) {

      } else {
        return false;
      }
    }
  })
}

