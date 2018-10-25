<?php
/**
 * Created by PhpStorm.
 * User: WhiteYuan
 * Date: 2018/1/12
 * Time: 13:47
 */

namespace App\Modules\Base\Repositories;


use App\Modules\Base\Models\ArticleComment;

class ArticleCommentRepository extends BaseRepository
{
    public  function model()
    {
        return ArticleComment::class;
    }

}