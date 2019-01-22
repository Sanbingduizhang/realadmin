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

        $officialAccount = Factory::officialAccount(config('wechat.official_account.default')); // 公众号

//dd($officialAccount);
//        $app = app('wechat.official_account');
//        dd($app);
//        $app->server->push(function($message){
//            return "欢迎关注 overtrue！";
//        });
//
//        return $app->server->serve();
        $res = $this->sendAlertMsg(1,2,3,4,5);
        echo $res;exit();
    }


    public function sendAlertMsg($title, $service, $status, $message, $remark)
    {
        $config = config('wechat.official_account.default');
//        dd($config);
        date_default_timezone_set('Asia/Shanghai');
        $app = Factory::officialAccount($config); // 公众号
        dd($app->access_token->getToken());
        $templateId = "12335454";   //这里是模板ID，自行去公众号获取
        $currentTime = date('Y-m-d H:i:s', time());
        $host = "baidu123.com";   //你的域名

        $openids = ["1256456965252"];   //关注微信公众号的openid，前往公众号获取
        foreach ($openids as $v) {
            $result = $app->template_message->send([
                'touser' => $v,
                'template_id' => $templateId,
                'url' => 'baidu.com',  //上边的域名
                'data' => [
                    'first' => $title,
                    'keyword1' => $currentTime,
                    'keyword2' => $host,
                    'keyword3' => $service,
                    'keyword4' => $status,
                    'keyword5' => $message,
                    'remark' => $remark,
                ]
            ]);
            Log::info("template send result:", $result);
        }
        return 11;
    }
}
