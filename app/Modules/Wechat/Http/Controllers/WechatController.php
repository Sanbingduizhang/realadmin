<?php

namespace App\Modules\Wechat\Http\Controllers;


use EasyWeChat\Factory;
use EasyWeChatComposer\EasyWeChat;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

class WechatController extends Controller
{
    public function server()
    {

//        $app = Factory::officialAccount(config('wechat.official_account.default')); // 公众号
//        dd($app->access_token->getToken());
//        dd($officialAccount);
//        $app = app('wechat.official_account');
//        dd($app);
//        $app->server->push(function($message){
//            return "欢迎关注 overtrue！";
//        });
//
//        return $app->server->serve();
        $res = $this->sendAlertMsg();
        var_dump($res);
        exit();
    }


    public function sendAlertMsg()
    {
        $config = config('wechat.official_account.default');
//        dd($config);
        date_default_timezone_set('Asia/Shanghai');
        $app = Factory::officialAccount($config); // 公众号
        dd($app);


        $result = $app->template_message->send([
            'touser' => "oSAUb0lOEfQu8Q_up87ZsyAp_GUU",
            'template_id' => "WkyesmEZqKqxvfdglBWmxDyZy4SNweGyd2MfqJS9Pzg",
            'url' => "https://www.nblistener.com/video.php?videourl=",  //上边的域名
            'miniprogram' => [],
            'data' => [
                'first' => ['value' => '发布详情如下', 'color' => '#173177'],
                'keyword1' => ['value' => 'eee', 'color' => '#173177'],
                'keyword2' => ['value' => 'aaa', 'color' => '#173177'],
                'remark' => ['value' => '点击播放视频', 'color' => '#173177'],
            ]
        ]);;
        Log::info("template send result:", $result);
        return $result;

    }
}
