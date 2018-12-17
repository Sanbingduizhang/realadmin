<?php

namespace App\Modules\Base\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleReply extends Model
{
    const IS_DEL_ON = 2;    //删除
    const IS_DEL_OFF = 1;    //不删除
    protected $table = 'article_reply';
    protected $guarded = ['id'];
}
