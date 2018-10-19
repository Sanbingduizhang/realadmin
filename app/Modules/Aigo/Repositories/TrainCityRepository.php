<?php
/**
 * Created by PhpStorm.
 * User: WhiteYuan
 * Date: 2018/1/12
 * Time: 13:47
 */

namespace App\Modules\Aigo\Repositories;


use App\Modules\Aigo\Models\TrainCity;
use App\Modules\Base\Repositories\BaseRepository;

class TrainCityRepository extends BaseRepository
{
    public  function model()
    {
        return TrainCity::class;
    }
}