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


}
