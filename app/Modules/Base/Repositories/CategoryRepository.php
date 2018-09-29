<?php
/**
 * Created by PhpStorm.
 * User: WhiteYuan
 * Date: 2018/1/12
 * Time: 13:47
 */

namespace App\Modules\Base\Repositories;


use App\Modules\Base\Models\Category;

class CategoryRepository extends BaseRepository
{
    public  function model()
    {
        return Category::class;
    }


}