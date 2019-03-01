<?php

namespace App\Modules\Wechat\Repositories;

use App\Modules\Basic\Facade\OpenPlatform;
use App\Modules\Basic\Repositories\BaseRepository;
use App\Modules\Wechat\Models\WxBind;

class WxBindRepository extends BaseRepository
{

	public function model()
	{
		return WxBind::class;
	}

    /**
     * 获取用户信息
     * @param $openRes
     * @return array
     */
	public function getStuMsg($openRes)
    {
        $bindRes = $this->where([
            'wx_user_id' => $openRes->id,
            'status' => WxBind::STATUS_ON,
        ])->first();

        //获取姓名
        $userInfo = OpenPlatform::loginCheck($bindRes->user_code);
        //获取班级年级

        $user = OpenPlatform::get_stu_class_data($userInfo);

        return [
            'name' => $userInfo['name'],
            'grade_class' => $user[0]['GradeName'] . $user[0]['ClassName']
        ];

    }

}