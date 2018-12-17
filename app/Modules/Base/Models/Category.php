<?php

namespace App\Modules\Base\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'category';
    protected $guarded = ['id'];
    const IS_DEL_ON = 1;
    const IS_DEL_OFF = -1;
}
