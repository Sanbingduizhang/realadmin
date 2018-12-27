<?php

namespace App\Modules\Home\Http\Controllers;

use App\Modules\Base\Http\Controllers\ApiBaseController;
use App\Modules\Base\Models\ArticleComment;
use App\Modules\Base\Models\ArticleReply;
use App\Modules\Base\Repositories\ArticleCommentRepository;
use App\Modules\Base\Repositories\ArticleReplyRepository;
use App\Modules\Base\Repositories\ArticleRepository;
use Illuminate\Http\Request;


class ReplyController extends ApiBaseController
{
    protected $articleRepository;
    protected $arReplyRepository;
    protected $arComRepository;

    public function __construct(
        ArticleRepository $articleRepository,
        ArticleReplyRepository $arReplyRepository,
        ArticleCommentRepository $arComRepository
    )
    {
        $this->articleRepository = $articleRepository;
        $this->arReplyRepository = $arReplyRepository;
        $this->arComRepository = $arComRepository;
    }

    /**
     * 回复的添加(评论的回复以及评论回复的回复)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function arrepadd(Request $request)
    {
        $option = $this->arReplyRepository->arcaoption($request);
        if (empty($option['arcid']) || empty($option['type']) || !in_array($option['type'],[1,2])) {
            return response_failed('请传入相关参数');
        }
        //查找数据是否存在
        $findRes = $this->arComRepository
            ->where([
                'is_del' => ArticleComment::IS_DEL_OFF,
                'id' => $option['arcid'],
            ])->first();

        if (empty($findRes)) {
            return response_failed('数据有误');
        }
        //开始添加回复内容
        if ($option['type'] == 1) {
            //添加评论的回复
            $addRes = $this->arReplyRepository->create([
                'userid' => getUser($request)['id'],
                'content' => $option['content'],
                'acomid' => $option['arcid'],
            ]);
        } else {
            if (empty($option['pid'])) {
                return response_failed('请输入回复的相关参数');
            }
            //添加回复的回复
            $addRes = $this->arReplyRepository->create([
                'userid' => getUser($request)['id'],
                'content' => $option['content'],
                'acomid' => $option['arcid'],
                'pid' => $option['pid'],
            ]);
        }
        if (!$addRes) {
            return response_failed('添加失败');
        }
        //添加成功的话返回其他数据
        $replyRes = $this->replyId($request,$addRes->id);

        return response_success($replyRes);
    }


    /**
     * 自调用格式化返回回复内容
     * @param $request
     * @param $replyid
     * @return array
     */
    private function replyId($request,$replyid)
    {
        $repRes = $this->arReplyRepository
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
                'id' => $replyid,
                'is_del' => ArticleReply::IS_DEL_OFF,
            ])->first();

        //定义是否时当前用户，默认不是
        $is_me = -1;

        if (empty($repRes)) {
            return [];
        }
        $repRes = $repRes->toArray();
        //如果用户登录就去判断是否有评论时当前用户
        if (!empty(getUser($request))) {
            $is_me = getUser($request)['id'];
            $likeArr = app(IndexController::class)->myselfLike(getUser($request)['id'],3,[$repRes['id']]);
        }
        //数据整合
        $repRes['is_me'] = $repRes['userid'] == $is_me ? true : false;
        $repRes['is_like'] = isset($likeArr[$repRes['id']]) ? true : false;
        if (!empty($repRes['pid_arply'])) {
            $repRes['pid_arply'] = $repRes['pid_arply']['reply_user'];
        }

        return $repRes;
    }

    /**
     * 删除回复
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function arrepdel(Request $request)
    {
        $user = getUser($request);
        $arrid = (int)$request->get('arrid',0);
        if (empty($arrid)) {
            return response_failed('请传入相关参数');
        }
        $findRes = $this->arReplyRepository
            ->where([
                'id' => $arrid,
                'userid' => $user['id'],
                'is_del' => ArticleReply::IS_DEL_OFF,
            ])->first();
        if (empty($findRes)) {
            return response_failed('数据有误');
        }
        $delRes = $findRes->delete();
        if ($delRes) {
            return response_success(['message' => '删除成功']);
        }
        return response_failed('删除失败');
    }
}
