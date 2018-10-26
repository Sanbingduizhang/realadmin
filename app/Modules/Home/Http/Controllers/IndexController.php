<?php

namespace App\Modules\Home\Http\Controllers;

use App\Modules\Base\Models\Article;
use App\Modules\Base\Models\ArticleComment;
use App\Modules\Base\Models\Category;
use App\Modules\Base\Repositories\ArticleCommentRepository;
use App\Modules\Base\Repositories\ArticleReplyRepository;
use App\Modules\Base\Repositories\ArticleRepository;
use App\Modules\Base\Repositories\CategoryRepository;
use Illuminate\Http\Request;

use App\Modules\Base\Http\Controllers\ApiBaseController;

class IndexController extends ApiBaseController
{
    protected $categoryRepository;
    protected $articleRepository;
    protected $articleComRep;
    protected $articleReplyRep;


    public function __construct(
        CategoryRepository $categoryRepository,
        ArticleRepository $articleRepository,
        ArticleCommentRepository $articleComRep,
        ArticleReplyRepository $articleReplyRep
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->articleRepository = $articleRepository;
        $this->articleComRep = $articleComRep;
        $this->articleReplyRep = $articleReplyRep;

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
        $pageline = (int)$request->get('line', 10);
        $article = $this->articleRepository
            ->select(['id', 'userid', 'content', 'cateid', 'like', 'pv', 'is_rec', 'wordsnum', 'updated_at', 'created_at'])
            ->with(['article_cate' => function ($ac) {
                $ac->select(['id', 'name']);
            }])
            ->with((['article_user' => function ($au) {
                $au->select(['id', 'name']);
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
        $cate = (int)$request->get('recornew', 1); //1是显示推荐，2-是显示最新
        if (!in_array($cate, [1, 2])) {
            return response_failed('请输入正确的参数');
        }
        $this->articleRepository = $this->articleRepository
            ->select(['id', 'content', 'is_rec', 'created_at'])
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

    /**
     * 首页获取用户相关信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function myArticles(Request $request)
    {
        //获取当前用户
        $user = getUser($request);
        $articleRes = $this->articleRepository
            ->select(['id', 'content', 'updated_at'])
            ->where([
                'is_del' => Article::DEL_ON,
                'userid' => $user['id'],
            ])
            ->orderBy('updated_at', 'DESC')
            ->paginate(6)->toArray();

        return response_success(pageGo($articleRes));
    }

    /**
     * 获取单个article信息，包括作者和分类等信息
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function articleMsg($id)
    {
        //相关sql
        $id = (int)$id;
        $articeExist = $this->articleRepository
            ->with(['article_cate' => function ($ac) {
                $ac->select(['id', 'name']);
            }])
            ->with(['article_user' => function ($au) {
                $au->select(['id', 'name']);
            }])
            ->where([
                'publish' => Article::PUBLISH_ON,
                'status' => Article::STATUS_ON,
                'is_del' => Article::DEL_ON,
                'id' => $id
            ])
            ->select(['id', 'userid', 'cateid', 'content', 'like', 'pv', 'is_rec', 'wordsnum'])
            ->first();

        //判断
        if (empty($articeExist)) {
            return response_failed('数据获取失败');
        }
        //整合数据
        $returnArr = $articeExist->toArray();
        $returnArr['arcatename'] = $returnArr['article_cate']['name'];
        $returnArr['arusername'] = $returnArr['article_user']['name'];
        $returnArr['is_rec'] = $returnArr['is_rec'] == 1 ? '推荐' : '不推荐';
        unset($returnArr['article_cate']);
        unset($returnArr['article_user']);

        //返回数据
        return response_success($returnArr);
    }
    public function articleComent(Request $request,$id)
    {
        //获取相关数据
        $id = (int)$id;
        $pageline = (int)$request->get('line', 6);

        $findArRes = $this->articleRepository
            ->where([
                'publish' => Article::PUBLISH_ON,
                'status' => Article::STATUS_ON,
                'is_del' => Article::DEL_ON,
                'id' => $id
            ])->first();
        //判断
        if (empty($findArRes)) {
            return response_failed('数据获取失败');
        }
        //分页查找评论
        $comRes = $this->articleComRep
            ->with(['com_user' => function ($cu) {
                $cu->select(['id','name']);
            }])
            ->withCount(['com_reply'])
            ->where([
                'articleid' => $id,
                'is_del' => ArticleComment::IS_DEL_OFF,
            ])->toSql()
//            ->get()->toArray();

            ->paginate($pageline)->toArray();

//        dd(getUser($request));
        $is_me = 3;

        if (empty(getUser($request))) {

        }
        foreach ($comRes['data'] as $ck => $cv) {

        }


        dd($comRes);

    }
    public function articleReply($id)
    {

    }
}
