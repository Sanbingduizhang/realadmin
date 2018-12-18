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
        $arcid = (int)$request->get('arcid',0);
        $type = (int)$request->get('type',1);    //1-评论的回复  2-回复的回复
        $option = $this->arReplyRepository->arcaoption($request);
        if (empty($option['arcid']) || empty($option['type']) || !in_array($option['type'],[1,2])) {
            return response_failed('请传入相关参数');
        }
        //查找数据是否存在
        $findRes = $this->arComRepository
            ->where([
                'is_del' => ArticleComment::IS_DEL_OFF,
                'id' => $arcid,
            ])->first();

        if (empty($findRes)) {
            return response_failed('数据有误');
        }
        //开始添加回复内容
        if ($type == 1) {
            //添加评论的回复
            $addRes = $this->arReplyRepository->create([
                'userid' => getUser($request)['id'],
                'content' => $option['content'],
                'acomid' => $arcid,
            ]);
        } else {
            if (empty($option['pid'])) {
                return response_failed('请输入回复的相关参数');
            }
            //添加回复的回复
            $addRes = $this->arReplyRepository->create([
                'userid' => getUser($request)['id'],
                'content' => $option['content'],
                'acomid' => $arcid,
                'pid' => $option['pid'],
            ]);
        }
        if ($addRes) {
            return response_success(['message' => '添加成功']);
        }
        return response_failed('添加失败');
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
