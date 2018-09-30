<?php

namespace App\Modules\Home\Http\Controllers;

use App\Modules\Base\Models\Article;
use App\Modules\Base\Models\Category;
use App\Modules\Base\Repositories\ArticleRepository;
use App\Modules\Base\Repositories\CategoryRepository;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Modules\Base\Http\Controllers\ApiBaseController;

class IndexController extends ApiBaseController
{
    protected $categoryRepository;
    protected $articleRepository;


    public function __construct(
        CategoryRepository $categoryRepository,
        ArticleRepository $articleRepository
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->articleRepository = $articleRepository;

    }

    /**
     * 返回前六条分类
     * @return \Illuminate\Http\JsonResponse
     */
    public function cate()
    {
        $cateRes = $this->categoryRepository
            ->where(['is_del' => Category::IS_DEL_ON])
            ->select(['id as cateid', 'name as catename'])
            ->orderBy('id', 'ASC')
            ->limit(6)
            ->get()->toArray();
        return response_success($cateRes);
    }

    /**
     * 获取首页的数据显示
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $pageline = (int)$request->get('line',10);
        $article = $this->articleRepository
            ->select(['id','userid','content','cateid','like','pv','is_rec','wordsnum','updated_at','created_at'])
            ->with(['article_cate' =>function ($ac) {
                $ac->select(['id','name']);
            }])
            ->with((['article_user' => function ($au) {
                $au->select(['id','name']);
            }]))
            ->where([
                'status' => Article::STATUS_ON,
                'is_pv_use' => Article::PV_USE_ALL,
                'is_del' => Article::DEL_ON,
                'publish' => Article::PUBLISH_ON,
            ])
            ->orderBy('like', 'desc')
            ->orderBy('pv', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate($pageline)->toArray();

        return response_success(pageGo($article));
    }

    /**
     * 展示推荐和最新
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function articleOther(Request $request)
    {
//        $pageline = (int)$request->get('line',6);
        $cate = (int)$request->get('recornew',1); //1是显示推荐，2-是显示最新
        if (!in_array($cate,[1,2])) {
            return response_failed('请输入正确的参数');
        }
        $this->articleRepository = $this->articleRepository
            ->select(['id','content','is_rec','created_at'])
            ->where([
                'status' => Article::STATUS_ON,
                'is_pv_use' => Article::PV_USE_ALL,
                'is_del' => Article::DEL_ON,
                'publish' => Article::PUBLISH_ON,
            ]);
        if ($cate == 1) {
            $this->articleRepository = $this->articleRepository->where(['is_rec' => 1]);
        }
        $articleRes = $this->articleRepository
            ->orderBy('created_at', 'DESC')
            ->paginate(6)->toArray();

        return response_success(pageGo($articleRes));
    }
}
