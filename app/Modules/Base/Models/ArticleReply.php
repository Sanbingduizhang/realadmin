<?php

namespace App\Modules\Base\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleReply extends Model
{
    const IS_DEL_ON = 2;    //删除
    const IS_DEL_OFF = 1;    //不删除
    protected $table = 'article_reply';
    protected $guarded = ['id'];
    /**
     * 评论的作者
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reply_user()
    {
        return $this->belongsTo(UserInfo::class,'userid','id');
    }

    /**
     * 获取回复中的pid
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function pid_arply()
    {
        return $this->belongsTo(ArticleReply::class,'pid','id');
    }
}
