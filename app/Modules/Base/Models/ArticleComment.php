<?php

namespace App\Modules\Base\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleComment extends Model
{
    const IS_DEL_ON = 2;    //删除
    const IS_DEL_OFF = 1;    //不删除

    protected $table = 'article_comment';
    protected $guarded = ['id'];

    /**
     * 评论下方的回复
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function com_reply()
    {
        return $this->hasMany(ArticleReply::class,'acomid','id');
    }

    /**
     * 评论的作者
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function com_user()
    {
        return $this->belongsTo(UserInfo::class,'userid','id');
    }
}
