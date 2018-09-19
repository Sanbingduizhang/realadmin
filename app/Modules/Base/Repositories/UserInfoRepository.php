<?php
/**
 * Created by PhpStorm.
 * User: WhiteYuan
 * Date: 2018/1/12
 * Time: 13:47
 */

namespace App\Modules\Base\Repositories;


use App\Modules\Base\Models\UserInfo;

class UserInfoRepository extends BaseRepository
{
    public  function model()
    {
        return UserInfo::class;
    }


}