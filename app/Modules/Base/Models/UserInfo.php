<?php

namespace App\Modules\Base\Models;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $table = 'userinfo';
    protected $guarded = ['id'];
}
