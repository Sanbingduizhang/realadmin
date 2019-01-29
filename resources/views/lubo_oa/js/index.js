// 页面加载后执行
;(function (name, definition, context) {
  if ((typeof module !== 'undefined') && module.exports)
        module.exports = definition() // node 环境
  else if ((typeof context['define'] === 'function') && (context['define']['amd'] || context['define']['cmd']))
        define(definition)            // amd cmd 规范环境，如 seajs requirejs
  else
    context[name] = definition()  // 浏览器环境
})('MAIN_CONFIG', function () {

  return {
    USEFULL_API: 'http://10.10.10.167', // 接口开头数据
    Authorization: "Bearer eyJpdiI6IlhxRDRFaGxBN2FnWTl6XC85YXVWV2F3PT0iLCJ2YWx1ZSI6IjI1MkYzb1NNSEVkWnJETzU1NHI0T3lrc2RcL1Z4TnpnXC9sVkFyYnF0V2lKSys2UUg4VytuK1dabmNFTTh3NW1TZTBldUdmeXE2NzlEWWtZYVZYbEZyK2l1VHhzaFhpTklnZmZyS200ZlwvbzMyNE56V0JwR09tenl5Nk5NV041OXpOQ05yMFplY1hrQnVvR3N3aGtGQ005UlwvbHFVbEU1QklkZXJDWFlTcFkyT3JjSmRrWEpOZ0hYSVR3Q1BBcklqTzdNNDgyUzl0WmRxOVVWZnZEVTFnS2FnPT0iLCJtYWMiOiI3MWQ5NmVkNmI3NzQ2Njk1M2JlYzkxMmFmNjZhOWNhZTkyM2RhNzE3MzVlYWI5NWEwODIxNWEwOTczMWQ5ZjUzIn0=",
    PAGE_LINE: 10,
    CUR_PAGE: 1, // 记录视频列表页的页码，首次进入列表页，归1
  }

}, this);


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
        // render(res)
      } else {
        alert('网络错误！');
        return false;
      }
    }
  })
}

