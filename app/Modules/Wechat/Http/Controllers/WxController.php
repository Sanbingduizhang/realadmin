<?php

namespace App\Modules\Wechat\Http\Controllers;

use EasyWeChat\Factory;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class WxController extends Controller
{

    protected $app;

    public function __construct()
    {
        $config = config('wechat.official_account.default');
        date_default_timezone_set('Asia/Shanghai');
        $this->app = Factory::officialAccount($config); // 公众号
    }

    /**
     * 微信使用验证token以及相关简单回复的消息
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function server()
    {
        $this->app->server->push(function ($message) {
            //用于查看返回消息
            Log::info($message);
            switch ($message['MsgType']) {
                case 'event':
                    if ($message['Event'] == 'subscribe') {
                        Log::info('_openid_' . $message['FromUserName'] .'_' . '关注公众号');
                        return '欢迎关注易录播公众号';
                    }

                    if($message['Event'] == 'unsubscribe') {
                        Log::info('_openid_' . $message['FromUserName'] .'_' . '取消关注公众号');
                        return '已经取消关注';
                    }
                    return '精品录播,开创新的学习';
                    break;
                case 'text':
                    //返回用户发送的消息
                    return $message['Content'];
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                case 'file':
                    return '收到文件消息';
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }
        });



        return $this->app->server->serve();
    }

    public function bindUser(Request $request)
    {
        Log::info('bind __ ');
        $respose = $this->app->oauth->scopes(['snsapi_userinfo'])->setRequest($request)->redirect();
        Log::info('url__' . $respose);
        return $respose;
//        $this->app->oauth->scopes(['snsapi_userinfo'])->setRequest($request);
//        $user = $this->app->oauth->user();
////        Log::info($user);
//        Log::info($user->getId());
    }

    /**
     * 设置相关按钮
     * @return \Illuminate\Http\JsonResponse
     */
    public function setButton()
    {
        $buttons = [
            [
                "type" => "view",
                "name" => "绑定授权",
//                "url"  => "http://148.70.67.47/shouquan.html",
                "url"  => "http://148.70.67.47/api/wx/bind-user",
            ],
            [
                "type" => "view",
                "name" => "我的微课",
                "url" => "http://148.70.67.47/mylubo.html"
            ],
        ];
        $setRes = $this->app->menu->create($buttons);
        return response_success($setRes);
    }

    public function userSet(Request $request)
    {
        $user = $this->app->oauth->user()->getId();
        Log::info($user);

        dd('成功绑定');
    }

}
