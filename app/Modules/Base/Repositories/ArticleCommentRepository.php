<?php
/**
 * Created by PhpStorm.
 * User: WhiteYuan
 * Date: 2018/1/12
 * Time: 13:47
 */

namespace App\Modules\Base\Repositories;


use App\Modules\Base\Models\ArticleComment;
use Illuminate\Http\Request;

class ArticleCommentRepository extends BaseRepository
{
    public  function model()
    {
        return ArticleComment::class;
    }

    /**
     * 文章评论获取内容
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function addarcom(Request $request)
    {
        $option = [
            'id' => $request->get('arid',''),
            'content' => $request->get('content',''),
        ];
        //判断是否为空
        if (empty($option['id']) || empty($option['content'])) {
            throw new \Exception("请传入相关参数");
        }
        return $option;
    }

}