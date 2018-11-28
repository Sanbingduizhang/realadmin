<?php

namespace App\Modules\Base\Models;

use Illuminate\Database\Eloquent\Model;

class Articlelike extends Model
{
    const IS_LIKEGO_ON = 1;    //点赞
    const IS_LIKEGO_OFF = 2;    //不点赞--直接删除
    const TYPE_ARTCILE = 1;        //类型---文章
    const TYPE_ARTCILE_COM = 2;         //类型---文章评论
    const TYPE_ARTCILE_REP = 3;         //类型---文章回复
    protected $table = 'article_likecount';
    protected $guarded = ['id'];
}
