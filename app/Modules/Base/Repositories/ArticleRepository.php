<?php
/**
 * Created by PhpStorm.
 * User: WhiteYuan
 * Date: 2018/1/12
 * Time: 13:47
 */

namespace App\Modules\Base\Repositories;


use App\Modules\Base\Http\Requests\ArticleRequest;
use App\Modules\Base\Models\Article;
use Illuminate\Http\Request;

class ArticleRepository extends BaseRepository
{
    public  function model()
    {
        return Article::class;
    }

    /**
     * 获取发布内容的信息
     * @param ArticleRequest $request
     * @return array
     */
    public function getAddText($request)
    {
        $options = [
            'cateid' => $request->get('cate',1),
            'content' => $request->get('text',''),
            'wordsnum' => $request->get('wordscount',0),
        ];
        //如果没传字数，则此处自己计算
        if ($options['wordsnum'] == 0) {
            $options['wordsnum'] = strlen($options['content']);
        }
        return $options;
    }

    /**
     * 删除传递参数   array()
     * @param Request $request
     * @return array()
     * @throws \Exception
     */
    public function ardel(Request $request)
    {
        $idArr = $request->get('idArr',[]);
        if (empty($idArr) || !is_array($idArr)) {
            throw new \Exception('请传入相关参数');
        }
        return $idArr;
    }

    /**
     * 上下架传递参数，仅允许传入单个
     * @param Request $request
     * @return int
     * @throws \Exception
     */
    public function arsxj(Request $request)
    {
        $id = (int)$request->get('id');
        if (!is_numeric($id)) {
            throw new \Exception('请传入正确相关参数');
        }
        return $id;
    }

}