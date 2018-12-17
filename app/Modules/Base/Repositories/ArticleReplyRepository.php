<?php
/**
 * Created by PhpStorm.
 * User: WhiteYuan
 * Date: 2018/1/12
 * Time: 13:47
 */

namespace App\Modules\Base\Repositories;

use App\Modules\Base\Models\ArticleReply;
use Illuminate\Http\Request;

class ArticleReplyRepository extends BaseRepository
{
    public  function model()
    {
        return ArticleReply::class;
    }

    /**
     * 接收回复内容
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function arcaoption(Request $request)
    {
        $content = $request->get('content','');
        if (empty($content)) {
            throw new \Exception("请输入回复内容");
        }
        if (mb_strlen($content) < 5 || mb_strlen($content) > 100) {
            throw new \Exception("请输入5到100个相关字符");
        }
        $options = [
            'arcid' => (int)$request->get('arcid',0),
            'type' => (int)$request->get('type',0),
            'pid' => (int)$request->get('pid',0),
            'content' => $content,
        ];
        return $options;
    }

}