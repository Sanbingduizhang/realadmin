<?php

namespace App\Modules\Home\Http\Controllers;

use App\Modules\Base\Http\Requests\ArticleRequest;
use App\Modules\Base\Models\Article;
use App\Modules\Base\Models\Category;
use App\Modules\Base\Repositories\ArticleRepository;
use App\Modules\Base\Repositories\CategoryRepository;
use App\Modules\Base\Http\Controllers\ApiBaseController;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class ArticleController extends ApiBaseController
{
    protected $categoryRepository;
    protected $articleRepository;

    public function __construct(
        CategoryRepository $categoryRepository,
        ArticleRepository $articleRepository
    )
    {
        $this->categoryRepository = $categoryRepository;
        $this->articleRepository = $articleRepository;

    }

    /**
     * 添加发布内容
     * @param ArticleRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function pubText(ArticleRequest $request)
    {
        $options = $this->articleRepository->getAddText($request);
        $cateArr = $this->getCates(1);
        //判断分类选择正确与否
        if (!in_array($options['cateid'], $cateArr)) {
            return response_failed('分类选择错误');
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
