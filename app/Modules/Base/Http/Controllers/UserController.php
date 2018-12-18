<?php

namespace App\Modules\Base\Http\Controllers;

use App\Modules\Base\Models\UserInfo;
use App\Modules\Base\Repositories\UserInfoRepository;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class UserController extends ApiBaseController
{
    protected $userInfoRepository;
    public function __construct(UserInfoRepository $userInfoRepository)
    {
        $this->userInfoRepository = $userInfoRepository;
    }

    /**
     * 用户获取个人数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsers(Request $request)
    {
        $userArr = getUser($request);
        $users = $this->userInfoRepository
            ->select(['id','usercode','img_path','email','iphone','birthday','sex','status','name','type','desc'])
            ->where(['id' => $userArr['id'],'status' => UserInfo::STATUS_SHIYONG])
            ->first();

        if (empty($users)) {
            return response_failed('暂时查询不到当前用户');
        }
        $userRes = $users->toArray();
        //男女变化
        if ($userRes['sex'] == 1) {
            $userRes['sex'] = '男';
        } elseif ($userRes['sex'] == 2) {
            $userRes['sex'] = '女';
        } else {
            $userRes['sex'] = '未知';
        }


        return response_success($userRes);

    }
}
