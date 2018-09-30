<?php

namespace App\Modules\Base\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends ApiBaseRequest
{
    protected $rules = [
        'cate' => 'numeric',
        'text' => 'string|min:1|max:200',
    ];

    protected $messages = [
        'cate.*' => '请选择正确的分类类型',
        'text.*' => '请输入最多200字以内的内容',
    ];
}
