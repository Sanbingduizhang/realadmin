<?php

namespace App\Modules\Base\Http\Controllers;

use App\Modules\Base\Repositories\UserInfoRepository;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class BaseController extends Controller
{
    protected $timestamp;
    protected $userInfoRepository;
    public function __construct(UserInfoRepository $userInfoRepository)
    {
        $this->timestamp = time();
        $this->userInfoRepository = $userInfoRepository;
    }

    /**
     * 用户网页端登陆接口
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $usercode = htmlspecialchars($request->get('usercode',null));
        $pwd = htmlspecialchars($request->get('password',null));

        if (empty($usercode) || empty($pwd)) {
            return response_failed('请输入用户名或密码');
        }
        //入库查询
        $searchRes = $this->userInfoRepository
            ->where(['usercode' => $usercode,'status' => 1])
            ->first();
        if (!$searchRes) {
            return response_failed('用户不存在');
        }
        //hash检测密码是否正确
        $checkRes = Hash::check($pwd,$searchRes->password);
        if (!$checkRes) {
            return response_failed('账号或密码错误');
        }
        $token = $this->issueJwtToken([
            'uid'=>$searchRes->id,
            'usercode'=>$searchRes->usercode,
        ]);
        return response_success(['token' => $token]);

    }

    /**
     * 注册接口
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $usercode = htmlspecialchars($request->get('usercode',null));
        $pwd = htmlspecialchars($request->get('password',null));

        if (empty($usercode) || empty($pwd)) {
            return response_failed('请输入用户名或密码');
        }
        //正则匹配账号
        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]{4,15}$/',$usercode)) {
            return response_failed('账号由字母/数字/下划线组成(4~16),以字母开头');
        }
        //密码正则匹配
        if (!preg_match('/^[a-zA-Z][a-zA-Z0-9_]{5,17}$/',$pwd)) {
            return response_failed('密码有字母/数字/下划线组成(6~18),以字母开头');
        }
        //入库查询
        $searchRes = $this->userInfoRepository
            ->where(['usercode' => $usercode])
            ->first();
        if ($searchRes) {
            //此处放回code=-2，则提醒是账号已经存在，
            return response_failed('此账号已经存在',-2);
        }
        //进行入库操作
        $create = $this->userInfoRepository->create([
            'usercode' => $usercode,
            'password' => Hash::make($pwd),
        ]);
        if ($create) {
            return response_success(['message' => '注册成功']);
        }
        return response_failed('注册失败');

    }

    /**
     * @description 签发jwt token
     * @param array $userInfo 教学平台用户信息
     *
     * @Payload
     * |- iss (Issuer) Token签发者
     * |- sub (Subject) 主题
     * |- aud (Audience) 接收者
     * |- exp (Expiration Time) 过期时间-UNIX时间戳，必须大于签发时间
     * |- nbf (Not Before) 指定一个UNIX时间戳之前，此TOKEN是不可用的
     * |- iat (Issued At) 签发时间-UNIX时间戳
     * |- jti (JWT ID) Token唯一身份标识
     * |- uuid 教学平台用户标识
     * @return string
     */
    protected function issueJwtToken($userInfo)
    {
        $payload  = [
            'iss'=>config('jwt.iss'),
            'sub'=>config('jwt.sub'),
            'aud'=>config('jwt.aud'),
            'exp'=>$this->timestamp + config('jwt.exp'),
            'iat'=>$this->timestamp,
            'uid'=>$userInfo['uid'],
            'usercode'=>$userInfo['usercode'],
        ];
        $token = JWT::encode($payload,config('jwt.key'),config('jwt.alg'));
        return $token;
    }

    /**
     * todo 修改路径问题
     * 生成验证码
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function captcha(Request $request) {
        //获取验证码
        $res = get_or_check_captcha([
                    'usercode' => $request->get('usercode','111111'),
                    'cachekey' => 'login_',
                ],'get');
        //返回验证码图片的路径

        return response_success(['src' => $res]);
    }

    /**
     * 验证码的正确与否
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyCaptcha(Request $request) {
        //检测验证码正确与否
        $res = get_or_check_captcha([
                    'usercode' => $request->get('usercode',''),
                    'cachekey' => 'login_',
                    'check' => $request->get('captcha',''),
                ],'check');

        if ($res == true) {
            return response_success(['message' => '验证通过']);
        }
        return response_failed('验证码错误');
    }
}
