<?php

namespace App\Modules\Home\Http\Controllers;

use App\Modules\Base\Repositories\ArticleCommentRepository;
use App\Modules\Base\Repositories\ArticleLikeRepository;
use App\Modules\Base\Repositories\ArticleRepository;
use App\Modules\Base\Repositories\CategoryRepository;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class LikeController extends Controller
{
    protected $categoryRepository;
    protected $articleRepository;
    protected $arComRepository;
    public function __construct(
        CategoryRepository $categoryRepository,
        ArticleRepository $articleRepository,
        ArticleCommentRepository $arComRepository,
        ArticleLikeRepository $arLikeRepository)
    {
        $this->categoryRepository = $categoryRepository;
        $this->articleRepository = $articleRepository;
        $this->arComRepository = $arComRepository;
        $this->arLikeRepository = $arLikeRepository;
    }

    public function likego()
    {
        $collect = collect([1,2,3,'ll']);
        $res = $collect->combine();
        dd($res);
    }
}
