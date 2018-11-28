<?php

namespace App\Modules\Base\Http\Controllers;

use App\Modules\Base\Models\UserInfo;
use App\Modules\Base\Repositories\UserInfoRepository;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class ApiBaseController extends Controller
{
    /**
     * 根据用户的type来判断用户的身份
     * @param $type
     * @return string
     */
    public function typeUser($type)
    {
        switch ($type) {
            case 1 :
                return '管理员';
                break;
            case 2 :
                return '高级会员';
                break;
            case 3 :
                return '超级会员';
                break;
            case 4 :
                return '顶级会员';
                break;
            case 5 :
                return '会员';
                break;
            default :
                return '游客';
                break;
        }
    }

    /**
     * 账号状态
     * @param Request $request
     * @throws \Exception
     */
    public function userStatus(Request $request)
    {
        $user = getUser($request);
        $userRes = app(UserInfoRepository::class)
            ->where('status','<>', UserInfo::STATUS_DEL)
            ->where('id','=',$user['id'])
            ->first();
        if (empty($userRes)) {
            throw new \Exception("此账号不存在",-3);
        }
        //判断此账号的状态相关信息
        /**
         * 判断是否禁用账号
         * 如果被禁用，则提醒解除
         */
        //被永久封禁
        if ($userRes->status == -1) {
            throw new \Exception("您的账号被永久封停，当前只能浏览相关内容",-4);
        }
        //被禁用
        if ($userRes->status == 2) {
            throw new \Exception("您的账号被封禁，请先解禁进行解禁，当前只能浏览相关内容",-5);
        }

        /**
         * 判断是否被警告过
         */
        //被警告一次
        if ($userRes->caveat == 1) {
            //获取分钟数
            $gapm = datetime_gap(date('Y-m-d H:i:s'),$userRes->caveat_at);
            if ($gapm < 60) {
                $cha = 60 - $gapm;
                throw new \Exception("您的账号于{$userRes->caveat_at},请再等待{$cha}分钟，您当前仅可以浏览相关内容",-6);
            }
        }
        //被警告两次
        if ($userRes->caveat == 2) {
            //获取分钟数
            $gapm = datetime_gap(date('Y-m-d H:i:s'),$userRes->caveat_at);
            if ($gapm < 600) {
                $cha = 600 - $gapm;
                throw new \Exception("您的账号于{$userRes->caveat_at},请再等待{$cha}分钟，您当前仅可以浏览相关内容",-6);
            }
        }
        //被警告三次
        if ($userRes->caveat == 3) {
            //获取分钟数
            $gapm = datetime_gap(date('Y-m-d H:i:s'),$userRes->caveat_at);
            if ($gapm < 1440) {
                $cha = 1440 - $gapm;
                throw new \Exception("您已被警告三次，请注意相关言论,等待{$cha}分钟，您当前仅可以浏览相关内容",-6);
            }
        }
    }


}
