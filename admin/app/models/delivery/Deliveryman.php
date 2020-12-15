<?php

/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/11/02
 */

namespace app\models\delivery;

use app\admin\model\store\StoreProduct;
use app\admin\model\system\SystemAdmin;
use app\models\OrderDelivery;
use app\models\system\SystemStore;
use app\models\user\User;
use common\traits\ModelTrait;
use common\basic\BaseModel;
use think\facade\Db;

/**
 * 图文管理 Model
 * Class WechatNews
 * @package app\admin\model\wechat
 */
class Deliveryman extends BaseModel
{

    use ModelTrait;

    protected $pk = 'id';

    protected $name = 'deliveryman';





    public function profile()
    {
        return $this->hasOne(StoreProduct::class, 'id', 'product_id')->field('store_name');
    }


    /**
     * @param $mobile
     * @param $checkCode
     * @param $password
     * @throws \Exception
     */
    public function resetPassword($mobile,$checkCode,$password){

        if (!$mobile) {
            return app('json')->fail('mobile(手机号)参数不存在');
        }
        if (!$checkCode) {
            return app('json')->fail('checkCode(验证码)参数不存在');
        }
        if (!$password) {
            return app('json')->fail('password(密码)参数不存在');
        }

        //查找用户
        $deliveryman=$this->where('phone',$mobile)->find();
        if ($deliveryman){
            return app('json')->fail('用户不存在，请确认手机号码');
        }
        //判断账号可用
        if (!$deliveryman['status']){
            return app('json')->fail('账户不可用，请联系管理员');
        }

        //TODO 校验验证码

        $data=['pwd'=> $this->getPasswordByEncrypt($password)];
        $res= $this->where('phone',$mobile)->update($data);
        if ($res){
            return app('json')->successful('重置密码成功');
        }
        return app('json')->fail('重置密码失败，系统错误');

    }
    /**
     * 获取配置分类
     * @param array $where
     * @return array
     */
    public static function getAll($where = array())
    {
        $model = new self;
        $model = $model->order('id desc');
        return self::page($model, function ($item) {
            if (!$item['mer_id']) $item['admin_name'] = '总后台管理员---》' . SystemAdmin::where('id', $item['admin_id'])->value('real_name');
            else $item['admin_name'] = Merchant::where('id', $item['mer_id'])->value('mer_name') . '---》' . MerchantAdmin::where('id', $item['admin_id'])->value('real_name');
            $item['content'] = Db::name('ArticleContent')->where('nid', $item['id'])->value('content');
            $item['catename'] = Db::name('ArticleCategory')->where('id', $item['cid'])->value('title');
            $item['store_name'] = $item->profile->store_name ?? '';
        }, $where);
    }

    /**
     * 切换工作状态
     * @param int $deliverymanId
     * @param int $startTime
     * @param int $endTime
     * @param int $storeId
     */
    public function workStat($deliverymanId = 0, $startTime = 0, $endTime = 0, $storeId = 0)
    {

        $orderDeliveryM = new OrderDelivery();
        $orderDeliveryM = $orderDeliveryM->alias('od')->where('od.deliveryman_id', $deliverymanId);
        //時間
        if ($startTime == 0) {
            $startTime = strtotime(date("Y-m-d 00:00:00"));
        } else {
            if (!is_numeric($startTime)) {  //不是時間戳
                $startTime = strtotime($startTime);
            }
        }
        if ($endTime == 0) {
            $endTime = strtotime(date("Y-m-d 23:59:59"));
        } else {
            if (!is_numeric($endTime)) {  //不是時間戳
                $endTime = strtotime($endTime);
            }
        }
        $orderDeliveryM = $orderDeliveryM->whereBetweenTime('od.create_time', $startTime, $endTime);
        if ($storeId) {
            $orderDeliveryM = $orderDeliveryM->join('store_order so', 'so.id = od.order_id')->where('so.store_id', $storeId);
        }

        $count = $orderDeliveryM->count();
        $amount = $orderDeliveryM->sum('delivery_amount');
        //echo $orderDeliveryM->getLastSql();die;
        $res = new \stdClass();
        $res->count = $count;
        $res->amount = $amount;

        return $res;
    }



    /**
     * H5用户注册
     * @param $account
     * @param $password
     * @param null $userName
     * @param int $spread
     * @return User|bool|\think\Model
     * @throws \Exception
     */
    public static function register($account, $password, $realName = null, $spread = 0)
    {
        if (self::be(['phone' => $account])) return self::setErrorInfo('用户已存在');
        $phone = $account;
        $data['pwd'] = md5($password);
        $data['phone'] = $phone;
        if ($spread) {
            $data['spread_uid'] = $spread;
            $data['spread_time'] = time();
        }
        if ($realName) {
            $data['real_name'] = $realName;
        }
        $data['real_name'] = '配送员:' . $account;
        $data['birthday'] = 0;
        $data['card_id'] = '';
        $data['mark'] = '';
        $data['addres'] = '';
        $data['user_type'] = 'h5';
        $data['add_time'] = time();
        $data['add_ip'] = app('request')->ip();
        $data['last_time'] = time();
        $data['last_ip'] = app('request')->ip();
        $data['nickname'] = substr(md5($account . time()), 0, 12);
        $data['avatar'] = $data['headimgurl'] = sys_config('h5_avatar');
        $data['city'] = '';
        $data['language'] = '';
        $data['province'] = '';
        $data['country'] = '';
        self::beginTrans();
        //$res2 = WechatUser::create($data);
        //$data['uid'] = $res2->uid;
        $res1 = self::create($data);
        $res = $res1;//&& $res2;
        self::checkTrans($res);
        return $res;
    }

    /**
     * 生成加密后的密码
     * @param $originPwd  string \未加密的密码
     * @return string
     */
    public static function getPasswordByEncrypt($originPwd)
    {
        return md5($originPwd);
    }



    /**
     * 申请门店的配送员 工作
     * @param $storeId | 门店的id
     * @param $deliverymanId | 配送员的id
     * @param string $deliverymanMark
     * @return array|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function applyWorkStore($storeId, $deliverymanId, $deliverymanMark = '申请配送工作')
    {
        $applyWorkModel= new DeliverymanApplywork();

        //判断参数
        if (!$storeId){
            return app('json')->fail('参数storeId(门店id) 无效');
        }
        //寻找有效的门店;
        $store = (new SystemStore())->where('id', $storeId)
            ->where('status', 1)
            ->where('is_del', 0)
            ->find();
        if (!$store || $store['status'] != 1) {
            return app('json')->fail('门店不存在,或未启用');
        }
        //已有记录
        $neverHandle= $applyWorkModel->where(['store_id'=>$storeId,'deliveryman_id'=>$deliverymanId,'is_handled'=>0])->count();
        if ($neverHandle){
            return app('json')->fail('您有申请记录还未审核，请稍后再次提交');
        }

        //写入申请记录
        $applyWorkMsg = [
            'deliveryman_mark' => $deliverymanMark,
            'store_id' => $storeId,
            'deliveryman_id' => $deliverymanId,
            'create_time' => date("Y-m-d H:i:s")
        ];
        $res = $applyWorkModel->insertGetId($applyWorkMsg);
        if ($res) {
            return app('json')->successful('申请成功，等待门店管理员处理');
        }
        return app('json')->fail('申请失败:系统错误');
    }
}