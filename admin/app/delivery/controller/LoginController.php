<?php


namespace app\delivery\controller;


use app\models\delivery\Deliveryman;
use app\models\user\User;
use think\facade\App;
use common\basic\BaseController;
use common\jwt\JwtTool;
use Firebase\JWT\JWT;
use think\Response;

/**
 * 微信公众号
 * Class WechatController
 * @package app\api\controller\wechat
 */
class LoginController extends BaseController
{

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->jwtTool = new JwtTool();
    }

    protected $jwtTool;


    /**
     * 登陆
     * @return Response
     */
    public function index()
    {

        if ($this->request->isPost()) {
            $type = input("post.type", 'password');
            $mobile = input("post.mobile", '');
            $password = input("post.password", '');
            $checkCode = input("post.checkCode", '');
            $isRegister=false;
            //判断登陆方式
            if ($type == 'checkCode') {
                if ($checkCode != 888888) {
                    return app('json')->fail('验证码错误,请输入 888888');
                }
                $user = Deliveryman::get(['phone' => $mobile]);
                if (!$user) {
                    $isRegister=true;
                    Deliveryman::register($mobile,$password);
                   $user= Deliveryman::get(['phone' => $mobile]);
                }
            } elseif ($type == 'password') {
                $user = Deliveryman::where(['phone' => $mobile])->find();
                if (empty($user)) {
                    return app('json')->fail('用户不存在');
                }
                if ($user->pwd != Deliveryman::getPasswordByEncrypt($password)) {
                    return app('json')->fail('请输入正确的密码');
                }
            } else {
                return app('json')->fail('未知的登录方式');
            }



            $key = $this->jwtTool->getKey();
            $expireTime = $this->jwtTool->getExpireTime();
            $issue = $this->jwtTool->getIssue();
            //封装额外信息
            $extendInfo = [];
            $extendInfo['id'] = $user->id;
            $extendInfo['realName'] = $user->real_name;
            $extendInfo['nickname'] = $user->nickname;
            $extendInfo['avatar'] = $user->avatar;
            $extendInfo['userType'] = $user->user_type;
            $extendInfo['phone'] = $user->phone;
            $extendInfo['isRegister'] =$isRegister;
            $extendInfo['expireTime']=time() + $expireTime - 300;

            $token = $this->genToken($expireTime, $issue, $key, $extendInfo);

            return app('json')->successful(['token' => $token]);
        }
        return app('json')->fail('错误的访问方法');

    }


    /**
     * 获取用户信息
     * @param string token
     * @return mixed
     */
    public function userInfo()
    {
        if ($this->request->isPost()) {
            $token = input("post.token", null);
            if (!$token) {
                return app('json')->fail('token 参数不存在');
            }
            $key = $this->jwtTool->getKey();
            try {
                $decodeRes = Jwt::decode($token, $key, array('HS256'));
            } catch (\Exception $exception) {
                $msg = $exception->getMessage();
                return app('json')->fail($msg);
            }

            $extendInfo = $decodeRes->ext;
            $user= Deliveryman::get(['id' => $extendInfo->id])->toArray();
            unset($user['pwd']);
            return app('json')->successful(['userinfo' => $user]);
        }
        return app('json')->fail('错误的访问方法');
    }


    /**
     * 获取用户信息
     * @param string token
     * @return mixed
     * @throws \Exception
     */
    public function resetPassword()
    {
        if ($this->request->isPost()) {
            $mobile = $this->request->post("mobile", null);
            $checkCode=$this->request->post("checkCode", null);
            $password=$this->request->post("password", null);
            $deliverymanModel=new Deliveryman();
            $deliverymanModel->resetPassword($mobile,$checkCode,$password);
            return app('json')->fail('未知的错误');
        }
        return app('json')->fail('错误的访问方法');
    }


    //交换 token
    public function transToken()
    {
        if ($this->request->isPost()) {
            $token = input("post.token", null);
            if (!$token) {
                return app('json')->fail('token 参数不存在');
            }
            $key = $this->jwtTool->getKey();
            try {
                $decodeRes = Jwt::decode($token, $key, array('HS256'));
            } catch (\Exception $exception) {
                $msg = $exception->getMessage();
                return app('json')->fail($msg);
            }
            //寻找用户
            $extendInfo = $decodeRes->ext;
            $user = Deliveryman::get(['id'=>$extendInfo->id]);
            if (!$user) {
                return app('json')->fail('用户不存在');
            }
            $key = $this->jwtTool->getKey();
            $expireTime = $this->jwtTool->getExpireTime();
            $issue = $this->jwtTool->getIssue();
            //封装额外信息
            $extendInfo = [];
            $extendInfo['uid'] = $user->uid;
            $extendInfo['realName'] = $user->real_name;
            $extendInfo['nickname'] = $user->nickname;
            $extendInfo['avatar'] = $user->avatar;
            $extendInfo['userType'] = $user->user_type;
            $extendInfo['phone'] = $user->phone;
            $extendInfo['expireTime']=time() + $expireTime - 300;
            $token = $this->genToken($expireTime, $issue, $key, $extendInfo);
            return app('json')->successful(['token' => $token]);
        }
        return app('json')->fail('错误的访问方法');
    }




    /**
     * 生成 token
     * @param $expireTime
     * @param $issue
     * @param $key
     * @param array $extendInfo
     * @return string
     */
    protected function genToken($expireTime, $issue, $key, $extendInfo = [])
    {
        $payload = $this->jwtTool->genPayload($expireTime, $extendInfo, $issue);
        return JWT::encode($payload, $key);
    }

    /**
     * decode token ,获取 payload
     * @param $token
     * @param $key
     * @return object|null
     */
    protected function decodeToken($token, $key)
    {
        //这里是解码  注意参数   ’HS256‘  https://github.com/firebase/php-jwt/issues/144
        return Jwt::decode($token, $key, array('HS256'));
    }


}