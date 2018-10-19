<?php

namespace App\Modules\Aigo\Http\Controllers;

use App\Modules\Base\Http\Controllers\ApiBaseController;
use App\Modules\Aigo\Repositories\TrainCityRepository;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

class TrainController extends ApiBaseController
{
    protected $tcRepository;
    public function __construct(TrainCityRepository $tcRepository)
    {
        $this->tcRepository = $tcRepository;
    }
}
