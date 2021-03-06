<?php

/**
 * 获取用户信息
 * @return mixed
 */

if (!function_exists('response_success')) {
    /**
     * @param array $params
     * @param string $status
     * @return \Illuminate\Http\JsonResponse
     */
    function response_success(Array $params = [], $status = 'successful', $code = 1)
    {
        return response()->json([
            'status' => $status,
            'code' => $code,
            'data' => $params
        ]);
    }
}
if (!function_exists('response_failed')) {
    /**
     * @param string $message
     * @param integer $code
     * @return \Illuminate\Http\JsonResponse
     */
    function response_failed($message = 'Response Failed', $code = -1)
    {
        return response()->json(['status' => 'failed', 'code' => $code, 'message' => $message]);
    }
}

if (!function_exists('pageGo')) {
    /**
     * 返回分页数据
     * @param $data
     * @return array
     */
    function pageGo($data)
    {
        return [
            'data' => $data['data'],
            'pagination' => [
                'current' => $data['current_page'],
                'total_page' => $data['last_page'],
                'per_page' => $data['per_page'],
            ],
        ];
    }
}


function getUser(\Illuminate\Http\Request $request)
{
    return $request->get('user_msg');
}

if (!function_exists('get_or_check_captcha')) {
    /**
     * 获取/检测验证码
     * @param $params
     * $param = [
     * 'width' => 250,
     * 'height' => 79,
     * 'font'  => null,
     * 'usercode' => '',
     * 'minu' => 5,
     * 'cachekey' => 'captcha_',
     * 'check' => '',
     * ];
     * @param $type  string get/check  ---get(获取) ---check(检测)
     * @return bool|string
     */
    function get_or_check_captcha($params, $type)
    {
        $param = [
            'width' => 250,
            'height' => 79,
            'font' => null,
            'usercode' => '',
            'minu' => 5,
            'cachekey' => 'captcha_',
            'check' => '',
        ];

        $paramarr = array_merge($param, $params);
        //根据传递的$type类型进行获取或者检测验证码
        if ($type == 'get') {
            $builder = new Gregwar\Captcha\CaptchaBuilder();
            //生成验证码图片的Builder对象，配置相应属性
            //可以设置图片宽高及字体
            $builder->build($width = 250, $height = 70, $font = null);
            //获取验证码的内容
            $phrase = $builder->getPhrase();
            //把内容存入session
            Illuminate\Support\Facades\Cache::put($paramarr['cachekey'] . $paramarr['usercode'], $phrase, $paramarr['minu']);
            //保存路径
            $filename = storage_path() . '/captchaimg/' . $paramarr['cachekey'] . $paramarr['usercode'] . '.jpg';
            $builder->save($filename);
            return $filename;

        } else if ($type = 'check') {
            //如果输入的验证码阿赫存储的验证码相等，返回true并删除对应验证码的缓存
            if (Illuminate\Support\Facades\Cache::get($paramarr['cachekey'] . $paramarr['usercode']) == $paramarr['check']) {
                Illuminate\Support\Facades\Cache::forget($paramarr['cachekey'] . $paramarr['usercode']);
                //用户输入验证码正确
                return true;
            }
            //用户输入验证码错误
            return false;
        }

        return false;
    }
}

if (!function_exists('uploadsFile')) {
    function uploadsFile($request, $arr = array())
    {
        $option = $arr;             //['jpg','png','jpeg','gif']
        //判断文件是否上传成功
        if (!($request->hasFile('file') && $request->file('file'))) {

            return 0;  //Error in the process of uploading files or uploading
        }
        //获取上传文件
        $file = $request->file('file');

        if (!is_array($file)) {
            return uploadsOne($file, $option);
        }
        return uploadsMore($file, $option);
    }

    /**
     * 上传一个时
     * @param $file
     * @param $option
     * @return array|int
     */
    function uploadsOne($file, $option)
    {
        $ext = strtolower($file->getClientOriginalExtension()); //文件扩展名
//        var_dump($ext);
        $originName = strtolower($file->getClientOriginalName());  //文件原名
        $type = $file->getClientMimeType();     // image/jpeg(真实文件名称)
        //判断文件类型是否符合
        if (!empty($option) && !in_array(strtolower($ext), $option)) {

            return 1; //'Please upload the specified type of picture:jpg,png,jpeg,gif';
        }
        //替换后的文件名称及路径
//        $course['img_path'] ? pathinfo($course['img_path'], PATHINFO_FILENAME) . '.' . $ext : '';
        $path1 = date('YmdHis') . '-' . uniqid() . '.' . $ext;
        $filesave = $file->storeAs('local', $path1, 'local');
        if (!$filesave) {
            return 2;   //'save is failed';
        }

        return $options = [
            'ext' => $ext,
            'originName' => $originName,
//            'path' => 'http://photo.heijiang.top/uploads/' . $path1,
            'path' => storage_path('app/local/') . $path1,
            'name' => $path1,
            'type' => $type,
        ];
    }

    /**
     * 上传多个时
     * @param $file
     * @param $option
     * @return array|int
     */
    function uploadsMore($file, $option)
    {
        $options = [];
        foreach ($file as $k => $v) {

            $ext = strtolower($v->getClientOriginalExtension()); //文件扩展名
            $originName = strtolower($v->getClientOriginalName());  //文件原名
            $type = $v->getClientMimeType();     // image/jpeg(真实文件名称)
            //判断文件类型是否符合
            if (!empty($option) && !in_array(strtolower($ext), $option)) {

                return 1; //'Please upload the specified type of picture:jpg,png,jpeg,gif';
            }
            //替换后的文件名称及路径
//        $course['img_path'] ? pathinfo($course['img_path'], PATHINFO_FILENAME) . '.' . $ext : '';
            $path1 = date('YmdHis') . '-' . uniqid() . '.' . $ext;
            $filesave = $v->storeAs('local', $path1, 'local');
            if (!$filesave) {
                return 2;   //'save is failed';
            }

            $options[] = [
                'ext' => $ext,
                'originName' => $originName,
//            'path' => 'http://photo.heijiang.top/uploads/' . $path1,
                'path' => storage_path('app/local/') . $path1,
                'name' => $path1,
                'type' => $type,
            ];
        }
        return $options;
    }
}

if (!function_exists('datetime_gap')) {
    /**
     * 获取时间间隔差值的分钟数
     * @param $ss   2018-01-01 00:00:00
     * @param $ee   2018-01-01 00:00:00
     * @return int -1或者分钟数
     */
    function datetime_gap($ss,$ee)
    {
        if (strtotime($ss) >= strtotime($ee)) {
            return intval(ceil(abs(strtotime($ss) - strtotime($ee)) / 60));
        }
        return -1;
    }
}