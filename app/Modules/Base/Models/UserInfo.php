<?php

namespace App\Modules\Base\Models;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    const STATUS_SHIYONG = 1;   //使用中
    const STATUS_JINYONG = 2;   //禁用中
    const STATUS_DEL = 3;   //删除
    protected $table = 'userinfo';
    protected $guarded = ['id'];
}
