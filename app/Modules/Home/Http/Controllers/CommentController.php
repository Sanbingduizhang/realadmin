<?php

namespace App\Modules\Home\Http\Controllers;

use App\Modules\Base\Http\Controllers\ApiBaseController;
use App\Modules\Base\Models\Article;
use App\Modules\Base\Models\ArticleComment;
use App\Modules\Base\Repositories\ArticleCommentRepository;
use App\Modules\Base\Repositories\ArticleRepository;
use App\Modules\Base\Repositories\CategoryRepository;
use Illuminate\Http\Request;


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
            $returnRes = $this->comId($request,$insertRes->id);
            return response_success($returnRes);
        }
        return response_failed('评论失败');
    }


    private function comId($request,$comid)
    {
        $comRes = $this->arComRepository
            ->with(['com_user' => function ($cu) {
                $cu->select(['id', 'name']);
            }])
            ->withCount(['com_reply'])
            ->where([
                'id' => $comid,
                'is_del' => ArticleComment::IS_DEL_OFF,
            ])->first();

        //定义是否时当前用户，默认不是
        $is_me = -1;
//dd($comRes);
        if (empty($comRes)) {
            return [];
        }
        $comRes = $comRes->toArray();
        //如果用户登录就去判断是否有评论时当前用户
        if (!empty(getUser($request))) {
            $is_me = getUser($request)['id'];
            $likeArr = app(IndexController::class)->myselfLike(getUser($request)['id'],2,[$comRes['id']]);
        }
        //数据整合
        $comRes['is_me'] = $comRes['userid'] == $is_me ? true : false;
        $comRes['is_like'] = isset($likeArr[$comRes['id']]) ? true : false;
        $comRes['replynum'] = $comRes['com_reply_count'];
        $comRes['com_user'] = $comRes['com_user']['name'];

        return $comRes;
    }

    /**
     * 首页评论删除，当前仅支持单个删除
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function arcomdel(Request $request)
    {
        //获取数据
        $id = $request->get('arcid','');
        if (empty($id)) {
            return response_failed('请输入相关参数');
        }
        //获取当前用户信息
        $users = getUser($request);
        //查找文章是否存在
        $findArcRes = $this->arComRepository
            ->where([
                'id' => $id,
                'is_del' => ArticleComment::IS_DEL_OFF,
                'userid' => $users['id'],
            ])->first();
        if (empty($findArcRes)) {
            return response_failed('数据有误');
        }
        //执行删除操作，更新字段
        $delRes = $findArcRes->update(['is_del' => ArticleComment::IS_DEL_ON]);
        if ($delRes) {
            return response_success(['message' => '删除成功']);
        }
        return response_failed('删除失败');
    }
}
