<?php

namespace App\Modules\Base\Http\Requests;

use Illuminate\Contracts\Validation\Validator;

use Illuminate\Foundation\Http\FormRequest;
use Mockery\Exception;

class ApiBaseRequest extends FormRequest
{
    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @var array
     */
    protected $messages = [];

    /**
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     */
    public function messages(){
        return $this->messages;
    }

    /**
     * @return array
     */
    public function rules(){
        return $this->rules;
    }

    /**
     * @param Validator $validator
     * @throws \Exception
     */
    protected function failedValidation(Validator $validator)
    {
        if( $validator->failed() ){
//            $response_errors = json_encode($validator->getMessageBag()->all(),JSON_UNESCAPED_UNICODE);
            throw new \Exception($validator->getMessageBag()->first(),-100);
        }
    }
}
