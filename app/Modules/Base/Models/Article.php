<?php

namespace App\Modules\Base\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    const STATUS_ON = 1;  //审核通过
    const STATUS_OFF = 2;  //审核不通过
    const PV_USE_ALL = 1; //观看权限所有人
    const PV_USE_SOME = 2; //观看权限指定的人
    const PV_USE_ONLY = 3; //观看权限自己
    const DEL_ON = 1;   //不删除
    const DEL_OFF = 0;   //删除
    const PUBLISH_ON = 1;//发布
    const PUBLISH_OFF = 2;//不发布

    protected $table = 'article';
    protected $guarded = ['id'];

    /**
     * 文章和分类关联
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article_cate()
    {
        return $this->belongsTo(Category::class,'cateid','id');
    }

    /**
     * 文章和用户关联
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function article_user()
    {
        return $this->belongsTo(UserInfo::class,'userid','id');
    }
}
