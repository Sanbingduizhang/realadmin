<?php

namespace App\Modules\Home\Http\Controllers;

use App\Modules\Base\Http\Controllers\ApiBaseController;
use App\Modules\Base\Models\Article;
use App\Modules\Base\Repositories\ArticleCommentRepository;
use App\Modules\Base\Repositories\ArticleRepository;
use App\Modules\Base\Repositories\CategoryRepository;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class CommentController extends ApiBaseController
{
    protected $categoryRepository;
    protected $articleRepository;
    protected $arComRepository;
    public function __construct(
        CategoryRepository $categoryRepository,
        ArticleRepository $articleRepository,
        ArticleCommentRepository $arComRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->articleRepository = $articleRepository;
        $this->arComRepository = $arComRepository;
    }

    /**
     * 添加文章pingl
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function arcomadd(Request $request)
    {
        $users = getUser($request);
        $options = $this->arComRepository->addarcom($request);
        //查找文章是否存在
        $findArRes = $this->articleRepository
            ->where([
                'is_del' => Article::DEL_ON,
                'id'     => $options['id'],
            ])->first();
        if (empty($findArRes)) {
            return response_failed('数据查找有误');
        }
        $insertData = [
            'userid' => $users['id'],
            'fromuid' => $findArRes->userid,
            'articleid' => $findArRes->id,
            'content' => $options['content'],
        ];
        $insertRes = $this->arComRepository->create($insertData);
        if ($insertRes) {
            return response_success(['message' => '评论成功']);
        }
        return response_failed('评论失败');
    }

    public function arcomdel(Request $request)
    {
        dd('comdel');
    }
}
