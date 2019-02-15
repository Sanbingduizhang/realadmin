<?php

namespace App\Modules\Wechat\Http\Controllers;

use App\Support\OpenPlatform;
use EasyWeChat\Factory;
use GuzzleHttp\Client;
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


    /**
     * 视图跳转并传递openid
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bindSet()
    {
        $user = $this->app->oauth->user();
        Log::info('userSet__' . $user->getId());
        return view("lubo_oa.bind_acount",['openid' => $user->getId()]);
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
                "url"  => "http://148.70.67.47/api/wx/bind-acount",
            ],
            [
                "type" => "view",
                "name" => "我的微课",
                "url" => "http://148.70.67.47/api/wx/my-course"
            ],
            [
                "type" => "view",
                "name" => "搜索",
                "url" => "http://www.baidu.com"
            ],
        ];
        $setRes = $this->app->menu->create($buttons);
        return response_success($setRes);
    }






    /*****************页面的相关跳转控制************************************/
    /**
     * 返回绑定页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bindAcount()
    {
        $user = session('wechat.oauth_user.default');

        $bindRes = $this->openidAuth($user->getId());
        if ($bindRes == 2) {
            return view('lubo_oa.bind_acount',['openid' => $user->getId(),'yj_wx_token' => '']);
        }

        Log::info($user->getId());

        return view('lubo_oa.bind_sucess',[
            'openid' => $user->getId(),
            'yj_wx_token' => $bindRes['token'],
        ]);
    }

    /**
     * 返回绑定成功页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bindSucess()
    {
        $user = session('wechat.oauth_user.default');
        return view("lubo_oa.bind_sucess",['openid' => $user->getId(),'yj_wx_token' => '']);
    }

    /**
     * 返回我的课程页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function myCourse()
    {
        $user = session('wechat.oauth_user.default');

        $bindRes = $this->openidAuth($user->getId());
        if ($bindRes == 2) {
            return view('lubo_oa.bind_acount',['openid' => $user->getId()]);
        }

        Log::info($user->getId());

        return view('lubo_oa.my_course',[
            'openid' => $user->getId(),
            'yj_wx_token' => $bindRes['token'],
            'yj_wx_name' => $bindRes['name']
        ]);
    }

    /**
     * 返回视频列表页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function videoList()
    {
        $user = session('wechat.oauth_user.default');
        return view("lubo_oa.video_list",['openid' => $user->getId()]);
    }

    /**
     * 微信是否绑定，是否返回相关信息
     * @param $openid
     * @return int|string
     */
    private function openidAuth($openid)
    {

        $url = 'http://www.ischool365.com:16780/api/wx/remote/wxremote?openid=' . $openid;
        $client = new Client();
        $res = $client->request('GET',$url);

        $arr = json_decode($res->getBody(),true);
        if ($arr['data']['res'] == 2) {
            return 2;
        }
        return $arr['data']['res'];
        dd(json_decode($res->getBody(),true));
//        $openRes = $this->wxUserRepository->where([
//            'openid' => $openid,
//            'status' => WxUser::STATUS_ON,
//        ])->first();
//
//        if (!$openRes) {
//            //返回2，说明此账号没有绑定信息
//            return 2;
//        }
//
//        $bindRes = $this->where([
//            'wx_user_id' => $openRes->id,
//            'status' => WxBind::STATUS_ON,
//        ])->first();
//
//        $tokenstr = $bindRes->user_code . '+' . $bindRes->school_id . '+' . $bindRes->private_key . '+' .$openid;

        return [
            'token' => $tokenstr,
            'name' => $tokenstr,
        ];

    }




    public function remote()
    {
        $client = new Client();
        $res = $client->request('GET','http://10.10.10.167/api/wx/remote/wxremote?openid=oSAUb0lOEfQu8Q_up87ZsyAp_GUU');

        dd(json_decode($res->getBody(),true));
    }

}
