<?php

namespace App\Modules\Home\Http\Controllers;

use App\Modules\Base\Models\Article;
use App\Modules\Base\Models\ArticleComment;
use App\Modules\Base\Models\Articlelike;
use App\Modules\Base\Models\ArticleReply;
use App\Modules\Base\Repositories\ArticleCommentRepository;
use App\Modules\Base\Repositories\ArticleLikeRepository;
use App\Modules\Base\Repositories\ArticleReplyRepository;
use App\Modules\Base\Repositories\ArticleRepository;
use App\Modules\Base\Repositories\CategoryRepository;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class LikeController extends Controller
{
    protected $categoryRepository;
    protected $articleRepository;
    protected $arComRepository;
    protected $arReplyRepository;
    protected $arLikeRepository;

    public function __construct(
        CategoryRepository $categoryRepository,
        ArticleRepository $articleRepository,
        ArticleCommentRepository $arComRepository,
        ArticleReplyRepository $arReplyRepository,
        ArticleLikeRepository $arLikeRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->articleRepository = $articleRepository;
        $this->arComRepository = $arComRepository;
        $this->arReplyRepository = $arReplyRepository;
        $this->arLikeRepository = $arLikeRepository;
    }

    /**
     * 点赞取消赞(关于文章,文章评论,文章回复)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function likego(Request $request)
    {
        $user = getUser($request);
        $arid = (int)$request->get('arid',0);
        $type = (int)$request->get('type',0);   //type=1是文章,2-评论,3-回复
        if (empty($arid) || empty($type) || !in_array($type,[1,2,3])) {
            return response_failed('请传入相关参数');
        }
        //查找此文章相关内容结果
        if ($type == 1) {
            $findArcrRes = $this->articleRepository
                ->where([
                    'id' => $arid,
                    'publish' => Article::PUBLISH_ON,
                    'status' => Article::STATUS_ON,
                    'is_del' => Article::DEL_ON,
                ])->first();
        } elseif ($type == 2) {
            $findArcrRes = $this->arComRepository
                ->where([
                    'id' => $arid,
                    'is_del' => ArticleComment::IS_DEL_OFF,
                ])->first();
        } else {
            $findArcrRes = $this->arReplyRepository
                ->where([
                    'id' => $arid,
                    'is_del' => ArticleReply::IS_DEL_OFF,
                ])->first();
        }

        if (empty($findArcrRes)) {
            return response_failed('数据有误');
        }
        //查找本人是否有本次对应的相关记录
        $findRes = $this->arLikeRepository
            ->where([
                'likego' => Articlelike::IS_LIKEGO_ON,
                'userid' => $user['id'],
                'type' => $type,
                'pid' => $arid
            ])->first();
        //如果查到，则取消赞，如果查不到，则进行点赞操作
            if (empty($findRes)) {
                $dzRes = $this->arLikeRepository
                    ->create([
                        'userid' => $user['id'],
                        'type' => $type,
                        'pid' => $arid,
                        'likego' => Articlelike::IS_LIKEGO_ON,
                        'fromuid' => $findArcrRes->userid,
                    ]);
                if ($type == 1) {
                    $this->articleRepository->update(['like' => $findArcrRes->like + 1],$arid);
                } elseif($type == 2) {
                    $this->arComRepository->update(['likecount' => $findArcrRes->likecount + 1],$arid);
                } else {
                    $this->arReplyRepository->update(['likecount' => $findArcrRes->likecount + 1],$arid);
                }

            } else {
                $dzRes = $findRes->delete();
                if ($type == 1) {
                    $this->articleRepository->update(['like' => $findArcrRes->like - 1],$arid);
                } elseif($type == 2) {
                    $this->arComRepository->update(['likecount' => $findArcrRes->likecount - 1],$arid);
                } else {
                    $this->arReplyRepository->update(['likecount' => $findArcrRes->likecount - 1],$arid);
                }
            }
            //判断结果是否正确
            if ($dzRes) {
                return response_success(['message' => '操作成功']);
            }
            return response_failed('操作失败');




    }
}
