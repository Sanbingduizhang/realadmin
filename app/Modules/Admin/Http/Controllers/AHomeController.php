<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Modules\Base\Http\Controllers\ApiBaseController;
use App\Modules\Base\Models\Article;
use App\Modules\Base\Models\ArticleComment;
use App\Modules\Base\Repositories\ArticleCommentRepository;
use App\Modules\Base\Repositories\ArticleReplyRepository;
use App\Modules\Base\Repositories\ArticleRepository;
use Illuminate\Http\Request;


class AHomeController extends ApiBaseController
{
    protected $articleRepository;
    protected $arComRepository;
    protected $arReplyRepository;
    public function __construct(
        ArticleRepository $articleRepository,
        ArticleCommentRepository $arComRepository,
        ArticleReplyRepository $arReplyRepository
    )
    {
        $this->articleRepository = $articleRepository;
        $this->arComRepository = $arComRepository;
        $this->arReplyRepository = $arReplyRepository;
    }
    public function tjshow(Request $request)
    {
//        dd(222);
        //获取用户所有文章数量
        $users = getUser($request);
        $arcount = $this->articleRepository
            ->where([
                'userid' => $users['id'],
                'is_del' => Article::DEL_ON,
            ])->count();
//        dd($arcount);
        //获取所有点赞数量
        $arcomcount = $this->arComRepository
            ->where([
                'fromuid' => $users['id'],
                'is_del' => ArticleComment::IS_DEL_OFF,
            ])->count();
        dd($arcomcount);
        //获取所有评论---不包括评论下方的回复
    }
}
