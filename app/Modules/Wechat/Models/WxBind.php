<?php

namespace App\Modules\Wechat\Models;

use Illuminate\Database\Eloquent\Model;

class WxBind extends Model
{
    protected $table = 'wx_bind';
    protected $guarded = 'id';

    const STATUS_DEL = 2;
    const STATUS_ON = 1;
}
