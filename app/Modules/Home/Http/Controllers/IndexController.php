<?php

namespace App\Modules\Home\Http\Controllers;

use App\Modules\Base\Models\Article;
use App\Modules\Base\Models\ArticleComment;
use App\Modules\Base\Models\ArticleReply;
use App\Modules\Base\Models\Category;
use App\Modules\Base\Repositories\ArticleCommentRepository;
use App\Modules\Base\Repositories\ArticleLikeRepository;
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
    protected $arLikeRepository;


    public function __construct(
        CategoryRepository $categoryRepository,
        ArticleRepository $articleRepository,
        ArticleCommentRepository $articleComRep,
        ArticleReplyRepository $articleReplyRep,
        ArticleLikeRepository $arLikeRepository
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->articleRepository = $articleRepository;
        $this->articleComRep = $articleComRep;
        $this->articleReplyRep = $articleReplyRep;
        $this->arLikeRepository = $arLikeRepository;

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
                'status' => Article::STATUS_ON,
                'is_pv_use' => Article::PV_USE_ALL,
                'is_del' => Article::DEL_ON,
                'publish' => Article::PUBLISH_ON,
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
            ->select(['id', 'userid', 'cateid', 'content', 'like', 'pv', 'is_rec', 'wordsnum', 'created_at', 'updated_at'])
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

    /**
     * 获取单个article下方的评论(如果用户登录且有其评论则显示删除)
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function articleComent(Request $request, $id)
    {
        //获取相关数据
        $id = (int)$id;
        $pageline = (int)$request->get('line', 6);

        $findArRes = $this->articleRepository
            ->where([
                'publish' => Article::PUBLISH_ON,
                'status' => Article::STATUS_ON,
                'is_del' => Article::DEL_ON,
                'id' => $id,
            ])->first();
        //判断
        if (empty($findArRes)) {
            return response_failed('数据获取失败');
        }
        //分页查找评论
        $comRes = $this->articleComRep
            ->with(['com_user' => function ($cu) {
                $cu->select(['id', 'name']);
            }])
            ->withCount(['com_reply'])
            ->where([
                'articleid' => $id,
                'is_del' => ArticleComment::IS_DEL_OFF,
            ])
            ->orderBy('created_at','desc')
            ->paginate($pageline)->toArray();
        //定义是否时当前用户，默认不是
        $is_me = -1;

        if (empty($comRes['data'])) {
            return response_success(pageGo($comRes));
        }
        //如果用户登录就去判断是否有评论时当前用户
        if (!empty(getUser($request))) {
            $is_me = getUser($request)['id'];
            $idArr = array_column($comRes['data'],'id');
            $likeArr = $this->myselfLike(getUser($request)['id'],2,$idArr);
        }
        //数据整合
        foreach ($comRes['data'] as $ck => $cv) {
            $comRes['data'][$ck]['is_me'] = $cv['userid'] == $is_me ? true : false;
            $comRes['data'][$ck]['is_like'] = isset($likeArr[$cv['id']]) ? true : false;
            $comRes['data'][$ck]['com_user'] = $cv['com_user']['name'];
            $comRes['data'][$ck]['replynum'] = $cv['com_reply_count'];
        }

        return response_success(pageGo($comRes));

    }

    public function articleReply(Request $request,$id)
    {
        //获取相关数据
        $id = (int)$id;
        $pageline = (int)$request->get('line', 10);

        $findComRes = $this->articleComRep
            ->where([
                'is_del' => ArticleComment::IS_DEL_OFF,
                'id' => $id,
            ])->first();
        //判断
        if (empty($findComRes)) {
            return response_failed('数据获取失败');
        }
        //分页查找评论
        $repRes = $this->articleReplyRep
            ->with(['reply_user' => function ($cu) {
                $cu->select(['id', 'name']);
            }])
            ->with(['pid_arply' => function ($cu) {
                $cu->select(['id', 'userid'])
                    ->with(['reply_user' => function ($cu) {
                        $cu->select(['id', 'name']);
                    }]);
            }])
            ->where([
                'acomid' => $id,
                'is_del' => ArticleReply::IS_DEL_OFF,
            ])
            ->orderBy('created_at','desc')
            ->paginate($pageline)->toArray();
        //定义是否时当前用户，默认不是
        $is_me = -1;

        if (empty($repRes['data'])) {
            return response_success(pageGo($repRes));
        }
        //如果用户登录就去判断是否有评论时当前用户
        if (!empty(getUser($request))) {
            $is_me = getUser($request)['id'];
            $idArr = array_column($repRes['data'],'id');
            $likeArr = $this->myselfLike(getUser($request)['id'],3,$idArr);
        }
        //数据整合
        foreach ($repRes['data'] as $ck => $cv) {
            $repRes['data'][$ck]['is_me'] = $cv['userid'] == $is_me ? true : false;
            $repRes['data'][$ck]['is_like'] = isset($likeArr[$cv['id']]) ? true : false;
            if (!empty($cv['pid_arply'])) {
                $repRes['data'][$ck]['pid_arply'] = $cv['pid_arply']['reply_user'];
            }
//            $repRes['data'][$ck]['com_user'] = $cv['com_user']['name'];
        }

        return response_success(pageGo($repRes));

    }

    /**
     * 自调用格式化回复内容
     * @param $userid
     * @param int $type
     * @param $idArr
     * @return array
     */
    private function myselfLike($userid,$type = 1,$idArr)
    {
        $findRes = $this->arLikeRepository
            ->where([
                'type' => $type,
                'userid' => $userid,
            ])->whereIn('pid',$idArr)->get();
        if (count($findRes) == 0) {
            return [];
        }
        //遍历格式化结果
        $return = [];
        foreach ($findRes->toArray() as $k => $v) {
            $return[$v['pid']] = true;
        }
        return $return;
    }
}
