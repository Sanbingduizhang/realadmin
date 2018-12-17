<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Modules\Base\Http\Controllers\ApiBaseController;
use App\Modules\Base\Models\Article;
use App\Modules\Base\Models\ArticleComment;
use App\Modules\Base\Models\Articlelike;
use App\Modules\Base\Repositories\ArticleCommentRepository;
use App\Modules\Base\Repositories\ArticleLikeRepository;
use App\Modules\Base\Repositories\ArticleReplyRepository;
use App\Modules\Base\Repositories\ArticleRepository;
use Illuminate\Http\Request;


class AHomeController extends ApiBaseController
{
    protected $articleRepository;
    protected $arComRepository;
    protected $arReplyRepository;
    protected $arLikeRepository;
    public function __construct(
        ArticleRepository $articleRepository,
        ArticleCommentRepository $arComRepository,
        ArticleReplyRepository $arReplyRepository,
        ArticleLikeRepository $arLikeRepository
    )
    {
        $this->articleRepository = $articleRepository;
        $this->arComRepository = $arComRepository;
        $this->arReplyRepository = $arReplyRepository;
        $this->arLikeRepository = $arLikeRepository;
    }

    /**
     * 用户后台首页显示数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function tjshow(Request $request)
    {
        //获取用户所有文章数量
        $users = getUser($request);
        $arcount = $this->articleRepository
            ->where([
                'userid' => $users['id'],
                'is_del' => Article::DEL_ON,
            ])->count();

        //获取所有评论数量---不包括评论下方的回复
        $arcomcount = $this->arComRepository
            ->where([
                'fromuid' => $users['id'],
                'is_del' => ArticleComment::IS_DEL_OFF,
            ])->count();

        //获取所有点赞数量---仅限对文章的点赞数量
        $arlikecount = $this->arLikeRepository
            ->where([
                'fromuid' => $users['id'],
                'type' => Articlelike::IS_LIKEGO_ON,
            ])->count();

        return response_success([
            'arcount' => $arcount,
            'arcomcount' => $arcomcount,
            'arlikecount' => $arlikecount,
        ]);
    }
}
