<?php

namespace App\Modules\Wechat\Http\Controllers;

use EasyWeChat\Factory;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class WxController extends Controller
{
    protected $app;

    public function __construct()
    {
        $config = config('wechat.official_account.default');
        date_default_timezone_set('Asia/Shanghai');
        $this->app = Factory::officialAccount($config); // 公众号
    }

    public function test()
    {

        $res = $this->app->menu->list();
        dd($res);

    }

    /**
     * 发送消息
     * @param array $paramArr
     * @return bool
     */
    public function sendMsg($paramArr = [])
    {

//        if (empty($paramArr)) {
//            return true;
//        }
//
//        //获取列表
        $useOpenIdArr = $this->userList();
//dd($useOpenIdArr);
        if (empty($useOpenIdArr)) {
            return true;
        }
        $paramArr = [
            [
                'vUrl' => '32323232',
                'vDate' => date('Y-m-d H:i:s'),
                'vPlace' => '本机电脑',
            ]
        ];
        foreach ($paramArr as $k => $v) {

            //生成相关模板数据格式
//            $sendArr['template_id'] = "WkyesmEZqKqxvfdglBWmxDyZy4SNweGyd2MfqJS9Pzg";
//            $sendArr['url'] = "https://www.nblistener.com/video.php?videourl=" . "{$v['vUrl']}";

            $sendArr['template_id'] = env('WECHAT_TEMPALTE_ID','WkyesmEZqKqxvfdglBWmxDyZy4SNweGyd2MfqJS9Pzg');
            $sendArr['url'] = env('WECHAT_VIDEO_PALY',"https://www.nblistener.com/video.php?videourl=") . "{$v['vUrl']}";
            $sendArr['data'] = [
                'first' => ['value' => '发布详情如下', 'color' => '#173177'],
                'keyword1' => ['value' => "{$v['vDate']}", 'color' => '#173177'],
                'keyword2' => ['value' => "{$v['vPlace']}", 'color' => '#173177'],
                'remark' => ['value' => '点击播放视频', 'color' => '#173177'],
            ];
            foreach ($useOpenIdArr as $uk => $uv) {
                $sendArr['touser'] = $uv;
                //发送信息
                $result = $this->app->template_message->send($sendArr);
                \Log::info("template send result:", $result);
            }



        }
        //信息
//        $result = $this->app->template_message->send([
//            'touser' => "oSAUb0lOEfQu8Q_up87ZsyAp_GUU",
//            'template_id' => "WkyesmEZqKqxvfdglBWmxDyZy4SNweGyd2MfqJS9Pzg",
//            'url' => "https://www.nblistener.com/video.php?videourl=",  //上边的域名
//            'data' => [
//                'first' => ['value' => '发布详情如下', 'color' => '#173177'],
//                'keyword1' => ['value' => 'eee', 'color' => '#173177'],
//                'keyword2' => ['value' => 'aaa', 'color' => '#173177'],
//                'remark' => ['value' => '点击播放视频', 'color' => '#173177'],
//            ]
//        ]);
        return true;

    }

    /**
     * 设置相关按钮
     * @return \Illuminate\Http\JsonResponse
     */
    public function setButton()
    {
        $buttons = [
            [
                "name"       => "录播授权",
                "sub_button" => [
                    [
                        "type" => "view",
                        "name" => "搜索",
                        "url"  => "http://www.baidu.com/"
                    ],
                    [
                        "type" => "click",
                        "name" => "我的录播",
                        "key" => "my_lubo"
                    ],
                ],
            ],
        ];
        $setRes = $this->app->menu->create($buttons);
        return response_success($setRes);
    }

    /**
     * 获取用户列表的openid
     */
    public function userList()
    {
        $userArr = $this->app->user->list();
        $openidArr = [];
        if (is_array($userArr) && isset($userArr['data']) && $userArr['total'] != 0) {
            //此时是有openid的
            //todo 暂时获取所有的openid进行发送
            $openidArr = $userArr['data']['openid'];

        }
        return $openidArr;
    }
}
