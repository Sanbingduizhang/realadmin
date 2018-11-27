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
     * @param Request $request
     * @return array
     */
    public function getAddText(Request $request)
    {
        $options = [
            'cateid' => $request->get('cate',1),
            'publish' => $request->get('pub',2),
            'content' => $request->get('text',''),
            'wordsnum' => $request->get('wordscount',0),
        ];
        //如果没传字数，则此处自己计算
        if ($options['wordsnum'] == 0) {
            $options['wordsnum'] = mb_strlen($options['content']);
            if ($options['wordsnum'] < 10) {
                throw new \Exception("您最少输入10个字符");
            }
        }
        return $options;
    }

    /**
     * 显示获取
     * @param Request $request
     * @return array
     */
    public function arindex(Request $request)
    {
        return [
            'line' => $request->get('line', 15),
            'order' => $request->get('order', 'DESC'),
            //相关条件
            'rec' => $request->get('rec', 0),
            'pub' => $request->get('pu', 0),
            'shen' => $request->get('shen', 0),
            'words' => rawurldecode($request->get('words', '')),
        ];
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