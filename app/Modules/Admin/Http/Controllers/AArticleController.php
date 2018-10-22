<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Modules\Base\Http\Controllers\ApiBaseController;
use App\Modules\Base\Models\Article;
use App\Modules\Base\Repositories\ArticleRepository;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class AArticleController extends ApiBaseController
{
    protected $articleRepository;
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * 后台获取文章数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $line = $request->get('line',15);
        $order = $request->get('order','DESC');
        //相关条件
        $rec = $request->get('rec',0);
        $pub = $request->get('pub',0);
        $sh = $request->get('sh',0);
        $words = $request->get('words','');
        $users = getUser($request);
        $this->articleRepository = $this->articleRepository
            ->where([
                'userid' => $users['id'],
                'is_del' => Article::DEL_ON,
            ]);
        //是否推荐  1-推荐  2-不推荐
        if ((!empty($rec) || $rec != 0) && in_array($rec,[1,2])) {
            $this->articleRepository = $this->articleRepository
                ->where(['is_rec' => $rec]);
        }
        //是否发布 1-发布  2-不发布
        if ((!empty($pub) || $pub != 0) && in_array($pub,[1,2])) {
            $this->articleRepository = $this->articleRepository
                ->where(['publish' => $pub]);
        }
        //是否审核 1-审核  2-不审核
        if ((!empty($sh) || $sh != 0) && in_array($sh,[1,2])) {
            $this->articleRepository = $this->articleRepository
                ->where(['status' => $sh]);
        }
        //是否关键字查询
        if (!empty($words)) {
            $this->articleRepository = $this->articleRepository
                ->where('content','like',"%$words%");
        }

        $articleRes = $this->articleRepository
            ->with(['article_cate' => function ($ac) {
                $ac->select(['id','name']);
            }])
            ->select(['id','content','cateid','publish','like','pv','is_rec','status'])
            ->orderBy('updated_at',$order)
            ->paginate($line)
            ->toArray();

        foreach ($articleRes['data'] as $k => $v) {
            $articleRes['data'][$k]['article_cate'] = $v['article_cate']['name'];
            $articleRes['data'][$k]['is_rec'] = $v['is_rec'] == 1 ? '已推荐' : '不推荐';
            $articleRes['data'][$k]['publish'] = $v['publish'] == 1 ? '已发布' : '未发布';
            $articleRes['data'][$k]['status'] = $v['status'] == 1 ? '已通过' : '未通过';
            $articleRes['data'][$k]['sxj'] = $v['publish'] == 1 ? '下架' : '上架';
        }

        return response_success(pageGo($articleRes));
    }
}
