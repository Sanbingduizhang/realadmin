<?php

namespace App\Modules\Wechat\Models;

use Illuminate\Database\Eloquent\Model;

class WxUser extends Model
{
    protected $table = 'wx_user';
    protected $guarded = 'id';

    const STATUS_DEL = -1;
    const STATUS_ON = 1;
    const STATUS_BAN = 2;

    public function WxUserBind()
    {
        return $this->hasMany(WxBind::class,'wx_user_id','id');
    }
}
