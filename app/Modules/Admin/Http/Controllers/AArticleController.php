<?php

namespace App\Modules\Admin\Http\Controllers;

use App\Modules\Base\Http\Controllers\ApiBaseController;
use App\Modules\Base\Models\Article;
use App\Modules\Base\Repositories\ArticleRepository;
use App\Modules\Base\Repositories\CategoryRepository;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AArticleController extends ApiBaseController
{
    protected $articleRepository;
    protected $categoryRepository;

    public function __construct(ArticleRepository $articleRepository, CategoryRepository $categoryRepository)
    {
        $this->articleRepository = $articleRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * 后台获取文章数据
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $options = $this->articleRepository->arindex($request);
        $users = getUser($request);
        $this->articleRepository = $this->articleRepository
            ->where([
                'userid' => $users['id'],
                'is_del' => Article::DEL_ON,
            ]);
        //是否推荐  1-推荐  2-不推荐
        if ((!empty($options['rec']) || $options['rec'] != 0) && in_array($options['rec'], [1, 2])) {
            $this->articleRepository = $this->articleRepository
                ->where(['is_rec' => $options['rec']]);
        }
        //是否发布 1-发布  2-不发布
        if ((!empty($options['pub']) || $options['pub'] != 0) && in_array($options['pub'], [1, 2])) {
            $this->articleRepository = $this->articleRepository
                ->where(['publish' => $options['pub']]);
        }
        //是否审核 1-审核  2-不审核
        if ((!empty($options['shen']) || $options['shen'] != 0) && in_array($options['shen'], [1, 2])) {
            $this->articleRepository = $this->articleRepository
                ->where(['status' => $options['pub']]);
        }
        //是否关键字查询
        if (!empty($options['words'])) {
            $this->articleRepository = $this->articleRepository
                ->where('content', 'like', '%' . $options['words'] . '%');
        }

        $articleRes = $this->articleRepository
            ->with(['article_cate' => function ($ac) {
                $ac->select(['id', 'name']);
            }])
            ->select(['id', 'content', 'cateid', 'publish', 'like', 'pv', 'is_rec', 'status'])
            ->orderBy('updated_at', $options['order'])
            ->paginate($options['line'])
            ->toArray();

        foreach ($articleRes['data'] as $k => $v) {
            $articleRes['data'][$k]['article_cate'] = $v['article_cate']['name'];
            $articleRes['data'][$k]['is_rec'] = $v['is_rec'] == 1 ? '已推荐' : '不推荐';
            $articleRes['data'][$k]['publish'] = $v['publish'] == 1 ? '已发布' : '未发布';
            $articleRes['data'][$k]['status'] = $v['status'] == 1 ? '已通过' : '未通过';
            $articleRes['data'][$k]['sxj'] = $v['publish'] == 1 ? '下架' : '上架';
        }

        return response_success(pageGo($articleRes));
    }

    /**
     * 删除文章
     * 支持多个删除
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delar(Request $request)
    {
        $user = getUser($request);
        $options = $this->articleRepository->ardel($request);
        $delRes = $this->articleRepository
            ->where(['userid' => $user['id']])
            ->whereIn('id', $options)
            ->update(['is_del' => -1]);
        if ($delRes) {
            return response_success(['message' => '删除成功']);
        }
        return response_failed('删除失败');
    }

    /**
     * 文章上下架
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sxjar(Request $request)
    {
        $user = getUser($request);
        $options = $this->articleRepository->arsxj($request);
        $sxjRes = $this->articleRepository
            ->where([
                'userid' => $user['id'],
                'is_del' => 1,
                'id' => $options,
            ])->first();
        if (!$sxjRes) {
            return response_failed(['message' => '数据有误']);
        }
        $update = $sxjRes->publish == Article::PUBLISH_ON ? Article::PUBLISH_OFF : Article::PUBLISH_ON;
        $updateRes = $sxjRes->update(['publish' => $update]);
        if ($updateRes) {
            return response_success([
                'sxj' => $update == Article::PUBLISH_ON ? '下架' : '上架',
                'pub' => $update == Article::PUBLISH_ON ? '已发布' : '未发布',
            ]);
        }
        return response_failed('修改失败');
    }

    /**
     * 添加发布内容
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pubar(Request $request)
    {
        $options = $this->articleRepository->getAddText($request);
        $cateArr = $this->getCates(1);
        //判断分类选择正确与否
        if (!in_array($options['cateid'], $cateArr)) {
            return response_failed('分类选择错误');
        }
        //判断是否发布的选择
        if (!in_array($options['publish'], [1, 2])) {
            return response_failed('数据参数错误');
        }
        //获取当前登陆用户
        $users = getUser($request);
        //整合需要插入的数据
        $options['userid'] = $users['id'];

        $createRes = $this->articleRepository->create($options);
        if (!$createRes) {
            return response_failed('添加失败');
        }
        return response_success(['message' => '添加成功']);
    }

    /**
     * 获取所有可用可输入的文本
     * @param int $type 1-获取id 2-获取name 其他获取id和name
     * @return array
     */
    private function getCates($type = 3)
    {
        $cateRes = $this->categoryRepository
            ->select(['id', 'name'])
            ->where(['is_del' => 1, 'type' => 1])
            ->get()->toArray();
        if ($type == 1) {
            return array_column($cateRes, 'id');
        } elseif ($type == 2) {
            return array_column($cateRes, 'name');
        }
        return $cateRes;
    }
}
