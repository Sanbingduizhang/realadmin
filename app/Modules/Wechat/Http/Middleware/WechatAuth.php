<?php

namespace App\Modules\Wechat\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class WechatAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //获取header头部，如果没有则则从地址栏获取
        $token = $request->header('Authorization');
        $token = trim(str_replace('Bearer','',$token));
        Log::info($token);
        try {
            $tokenArr = decrypt($token);
            if(count($tokenArr) != 4){
                return response_failed('sumit message is failed');
            }

//            $arr = [
//                'usercode' => $tokenArr['user_code'],
//                'school_id' => $tokenArr['school_id'],
//                'privatekey' => $tokenArr['private_key'],
//            ];
//            $user = Cache::rememberForever(
//                Cache::KEY_PAD_USERINFO . $arr['usercode'] . $arr['school_id'],
//                function() use ($arr){
//                    return OpenPlatform::get_stu_class_data($arr);
//                }
//            );
            $return = [
                'user_code' => $tokenArr['user_code'],
                'school_id' => $tokenArr['school_id'],
                'private_key' => $tokenArr['private_key'],
                'openid' => $tokenArr['openid'],
                'other' => [
                    "ClassId" => "106",
                    "GradeId" => "8",
                    "TermId" => "62",
                ],
            ];
            \Cache::store('array')->put('user', $return, 1);

        }catch (\UnexpectedValueException $e) {
            return response_failed('The Authorization field in the head does not exist');
        }catch (\Exception $e) {
            return response_failed($e->getMessage());
        }


        return $next($request);
    }
}
