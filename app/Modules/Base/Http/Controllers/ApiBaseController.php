<?php

namespace App\Modules\Base\Http\Controllers;

use App\Modules\Base\Repositories\UserInfoRepository;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class ApiBaseController extends Controller
{

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
        }

    }

}
