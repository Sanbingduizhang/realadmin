<?php

namespace App\Modules\Wechat\Repositories;

use App\Modules\Basic\Repositories\BaseRepository;
use App\Modules\Wechat\Models\WxUser;

class WxUserRepository extends BaseRepository
{

	public function model()
	{
		return WxUser::class;
	}



}