<?php
namespace app\api\controller\user;

use app\http\validates\user\AddressValidate;
use app\models\user\UserAddressTag;
use think\exception\ValidateException;
use app\Request;
use common\services\ExpressService as Express;
use app\admin\model\system\SystemAttachment;
use app\models\routine\RoutineQrcode;
use app\models\user\UserLevel;
use app\models\system\SystemUserLevel;
use app\models\user\UserSign;
use app\models\routine\RoutineCode;
use app\models\routine\RoutineFormId;
use app\models\store\StoreBargain;
use app\models\store\StoreCombination;
use app\models\store\StoreCouponList;
use app\models\store\StoreCouponUser;
use app\models\store\StoreOrder;
use app\models\store\StoreOrderCartInfo;
use app\models\store\StoreProductRelation;
use app\models\store\StoreProductReply;
use app\models\store\StoreSeckill;
use app\models\user\User;
use app\models\user\UserAddress;
use app\models\user\UserBill;
use app\models\user\UserExtract;
use app\models\user\UserNotice;
use app\models\user\UserRecharge;
use common\services\CacheService;
use common\services\GroupDataService;
use common\services\SystemConfigService;
use common\services\UploadService;
use common\services\UtilService;
use think\facade\Cache;
use think\facade\Db;

/**
 * 用户类
 * Class UserController
 * @package app\api\controller\store
 */
class UserController
{

    /**
     * 获取用户信息
     * @param Request $request
     * @return mixed
     */
    public function userInfo(Request $request)
    {
        $info = $request->user()->hidden(['real_name','card_id','partner_id','group_id','now_money','brokerage_price','clean_time',
            'integral','sign_num','level','spread_uid','spread_time','user_type','is_promoter','pay_count','spread_count','account',
            'addres','adminid','login_type','is_vip','is_important','mark','add_time','add_ip','last_time','last_ip','status'])->toArray();
        $info['birthday'] = date('Y-m-d', $info['birthday']);
        $info['phone'] = $this->get_ciphertext_phone($info['phone']);
        $info['gender'] = $info['gender']? '男':'女';
        return app('json')->success($info);
    }

    /**
     * @param string $phone
     * 获取加密后的手机号
     */
    private function get_ciphertext_phone($phone){
        return substr($phone, 0, 3).'****'.substr($phone, 7);
    }

    /**
     * 用户资金统计
     * @param Request $request
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function balance(Request $request)
    {
        $uid = $request->uid();
        $user['now_money'] = User::getUserInfo($uid, 'now_money')['now_money'];//当前总资金
        $user['recharge'] = UserBill::getRecharge($uid);//累计充值
        $user['orderStatusSum'] = StoreOrder::getOrderStatusSum($uid);//累计消费
        return app('json')->successful($user);
    }

    /**
     * 密码修改
     * @param Request $request
     * @return mixed
     */
    public function editPwd(Request $request)
    {
        $uid = $request->uid();
        list($oldPwd, $newPwd) = UtilService::postMore([['oldPwd',''], ['newPwd','']],$request, true);
        $user = User::where('uid', $uid)->find();
        if($user) {
            if ($user->pwd !== md5($oldPwd))
                return app('json')->fail('旧密码错误');
            if ($newPwd === md5(123456))
                return app('json')->fail('请修改您的初始密码，再尝试登陆！');
            if ($oldPwd === $newPwd)
                return app('json')->fail('新密码不能与旧密码相同！');
        }

        if(strlen(trim($newPwd)) < 6 || strlen(trim($newPwd)) > 16)
            return app('json')->fail('密码必须是在6到16位之间');
        if($newPwd == '123456') return app('json')->fail('密码太过简单，请输入较为复杂的密码');
        $resetStatus = User::reset($user->account, $newPwd);
        if($resetStatus) return app('json')->success('修改成功');
        return app('json')->fail(User::getErrorInfo('修改失败'));
    }

    /**
     * 个人中心
     * @param Request $request
     * @return mixed
     */
    public function userCenter(Request $request)
    {
        $user = $request->user()->hidden(['pwd','birthday','gender','real_name','card_id','partner_id','group_id','now_money','brokerage_price',
            'clean_time','sign_num','level','spread_uid','spread_time','user_type','is_promoter','pay_count','spread_count','account',
            'addres','adminid','login_type','is_vip','is_important','mark','add_time','add_ip','last_time','last_ip','status'])->toArray();
//        $user['couponCount'] = StoreCouponList::getUserValidCouponCount($user['uid']);  //获取优惠券数量
        $user['couponCount'] = StoreCouponUser::getUserValidCouponCount($user['uid']);
        $user['like'] = StoreProductRelation::getUserIdCollect($user['uid']);  //获取收藏商品数量
        $user['foot'] = StoreProductRelation::getUserFootCount($user['uid']);  //获取足迹商品数量
        $user['orderStatusNum'] = StoreOrder::getOrderData($user['uid']);  //获取订单状态
//        $user['notice'] = UserNotice::getNotice($user['uid']);  //获取用户通知
//        $user['brokerage'] = UserBill::getBrokerage($user['uid']);//获取总佣金
//        $user['recharge'] = UserBill::getRecharge($user['uid']);//累计充值
//        $user['orderStatusSum'] = StoreOrder::getOrderStatusSum($user['uid']);//累计消费
//        $user['statu'] = (int)sysConfig('store_brokerage_statu');
        $vip=UserLevel::getUserVip($user['uid']);
        $user['level']=$vip['grade'] ?? 0;
        $user['growth']=$vip['growth'] ?? 0;
        $user['is_vip']=$vip['grade']? 1:0;
        $user['vip_info'] = '';
        if ($user['is_vip']) {
            $vip_info=SystemUserLevel::getLevelInfo($user['uid']);
            $vip_info['add_time'] = date('Y-m-d',$vip_info['add_time']);
            $vip_info['valid_time'] = date('Y-m-d',$vip_info['valid_time']);
            unset($vip_info['is_pay']);
            unset($vip_info['id']);
            $user['vip_info'] = $vip_info;
        }

        return app('json')->successful($user);
    }

    /**
     * 地址 获取单个
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function address(Request $request, $id)
    {
        $addressInfo = [];
        if($id && is_numeric($id) && UserAddress::be(['is_del'=>0,'id'=>$id,'uid'=>$request->uid()])){
            $addressInfo = UserAddress::find($id)->toArray();
        }
        return app('json')->successful($addressInfo);
    }

    /**
     * 地址列表
     * @param Request $request
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function address_list(Request $request)
    {
        list($page, $limit) = UtilService::getMore([['page',0], ['limit',20]],$request, true);
        $list = UserAddress::getUserValidAddressList($request->uid(),$page,$limit,'id,real_name,phone,province,city,district,detail,is_default,tag_id');
        $tag_list = UserAddressTag::whereIn('uid', [0,$request->uid()])->select()->toArray();
        $tag_arr = array_column($tag_list, 'name', 'id');
        foreach ($list as $k=>$v) {
            if (in_array($v['tag_id'],array_column($tag_list,'id'))) {
                $list[$k]['tag_name'] = $tag_arr[$v['tag_id']];
            } else {
                $list[$k]['tag_name'] = '';
            }
        }
        return app('json')->successful($list);
    }

    /**
     * 设置默认地址
     *
     * @param Request $request
     * @return mixed
     */
    public function address_default_set(Request $request)
    {
        list($id) = UtilService::getMore([['id',0]],$request, true);
        if(!$id || !is_numeric($id)) return app('json')->fail('参数错误!');
        if(!UserAddress::be(['is_del'=>0,'id'=>$id,'uid'=>$request->uid()]))
            return app('json')->fail('地址不存在!');
        $res = UserAddress::setDefaultAddress($id,$request->uid());
        if(!$res)
            return app('json')->fail('地址不存在!');
        else
            return app('json')->successful();
    }

    /**
     * 获取默认地址
     * @param Request $request
     * @return mixed
     */
    public function address_default(Request $request)
    {
        $defaultAddress = UserAddress::getUserDefaultAddress($request->uid(),'id,real_name,phone,province,city,district,detail,is_default');
        if($defaultAddress) {
            $defaultAddress = $defaultAddress->toArray();
            return app('json')->successful('ok',$defaultAddress);
        }
        return app('json')->successful('empty',[]);
    }

    /**
     * 修改 添加地址
     * @param Request $request
     * @return mixed
     */
    public function address_edit(Request $request)
    {
        $addressInfo = UtilService::postMore([
            ['province',''],
            ['city',''],
            ['district',''],
            ['is_default',false],
            ['real_name',''],
            ['tag_id',0],
            ['tag_name',''],
            ['phone',''],
            ['detail',''],
            ['id',0]
        ], $request);
        if(!isset($addressInfo['province'])) return app('json')->fail('收货地址格式错误!');
        if(!isset($addressInfo['city'])) return app('json')->fail('收货地址格式错误!');
        if(!isset($addressInfo['district'])) return app('json')->fail('收货地址格式错误!');
        $addressInfo['post_code'] = 0;
        $addressInfo['uid'] = $request->uid();

        if (!$addressInfo['tag_id'] && $addressInfo['tag_name']) {
            $addressInfo['tag_id'] = $this->get_tag_id($addressInfo['uid'],$addressInfo['tag_name']);
        }
        unset($addressInfo['tag_name']);
        try {
            validate(AddressValidate::class)->check($addressInfo);
        } catch (ValidateException $e) {
            return app('json')->fail($e->getError());
        }
        if($addressInfo['id'] && UserAddress::be(['id'=>$addressInfo['id'],'uid'=>$request->uid(),'is_del'=>0])){
            $id = $addressInfo['id'];
            unset($addressInfo['id']);
            if(UserAddress::edit($addressInfo,$id,'id')){
                if($addressInfo['is_default'])
                    UserAddress::setDefaultAddress($id,$request->uid());
                return app('json')->successful();
            }else
                return app('json')->fail('编辑收货地址失败!');
        }else{
            $addressInfo['add_time'] = time();
            if($address_id = UserAddress::insertGetId($addressInfo))
            {
                if($addressInfo['is_default'])
                {
                    UserAddress::setDefaultAddress($address_id,$request->uid());
                }
                return app('json')->successful();
            }else{
                return app('json')->fail('添加收货地址失败!');
            }
        }
    }

    /**
     * 地址标签列表
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function tag_list(Request $request)
    {
        $list = UserAddressTag::whereIn('uid', [0,$request->uid()])->select()->toArray();
        return app('json')->successful($list);
    }

    /**
     * 获取地址标签id
     * @param $uid
     * @param $tag_name
     * @return int|mixed|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function get_tag_id($uid, $tag_name)
    {
        $tag_info = UserAddressTag::where('uid', $uid)->find();
        $UserAddressTagModel = new UserAddressTag();
        $data = [];
        $data['name'] = $tag_name;
        if(!$tag_info) {
            $data['uid'] = $uid;
            $tag_id = $UserAddressTagModel->insertGetId($data);
            return $tag_id? $tag_id:0;
        } else {
            $res1 = UserAddressTag::where('uid',$uid)->update($data);
            return $res1? $tag_info['id']:0;
        }
    }

    /**
     * 删除地址
     *
     * @param Request $request
     * @return mixed
     */
    public function address_del(Request $request)
    {
        list($id) = UtilService::postMore([['id',0]], $request, true);
        if(!$id || !is_numeric($id)) return app('json')->fail('参数错误!');
        if(!UserAddress::be(['is_del'=>0,'id'=>$id,'uid'=>$request->uid()]))
            return app('json')->fail('地址不存在!');
        if(UserAddress::edit(['is_del'=>'1'],$id,'id'))
            return app('json')->successful();
        else
            return app('json')->fail('删除地址失败!');
    }


    /**
     * 获取收藏产品
     *
     * @param Request $request
     * @return mixed
     */
    public function collect_user(Request $request)
    {
        list($page, $limit) = UtilService::getMore([
            ['page',0],
            ['limit',0]
        ], $request, true);
        if(!(int)$limit) return  app('json')->successful([]);
        $productRelationList = StoreProductRelation::getUserCollectProduct($request->uid(), (int)$page, (int)$limit);
        return app('json')->successful($productRelationList);
    }

    /**
     * 获取足记商品
     *
     * @param Request $request
     * @return mixed
     */
    public function foot_user(Request $request)
    {
        list($page, $limit) = UtilService::getMore([
            ['page',0],
            ['limit',0]
        ], $request, true);
        if(!(int)$limit) return  app('json')->successful([]);
        $productRelationList = StoreProductRelation::getUserFootProduct($request->uid(), (int)$page, (int)$limit);
        return app('json')->successful($productRelationList);
    }

    /**
     * 添加收藏
     * @param Request $request
     * @param $id
     * @param $category
     * @return mixed
     */
    public function collect_add(Request $request)
    {
        list($id, $category) = UtilService::postMore([['id',0], ['category','product']], $request, true);
        if(!$id || !is_numeric($id)) return app('json')->fail('参数错误');
        $res = StoreProductRelation::productRelation($id, $request->uid(),'collect', $category);
        if(!$res) return app('json')->fail(StoreProductRelation::getErrorInfo());
        else return app('json')->successful();
    }

    /**
     * 取消收藏
     *
     * @param Request $request
     * @return mixed
     */
    public function collect_del(Request $request)
    {
        list($id, $category) = UtilService::postMore([['id',0], ['category','product']], $request, true);
        if(!$id || !is_numeric($id)) return app('json')->fail('参数错误');
        $res = StoreProductRelation::unProductRelation($id, $request->uid(),'collect', $category);
        if(!$res) return app('json')->fail(StoreProductRelation::getErrorInfo());
        else return app('json')->successful();
    }

    /**
     * 批量收藏
     * @param Request $request
     * @return mixed
     */
    public function collect_all(Request $request)
    {
        $collectInfo = UtilService::postMore([
            ['id',[]],
            ['category','product'],
        ], $request);
        if(!count($collectInfo['id'])) return app('json')->fail('参数错误');
        $productIdS = $collectInfo['id'];
        $res = StoreProductRelation::productRelationAll($productIdS, $request->uid(),'collect', $collectInfo['category']);
        if(!$res) return app('json')->fail(StoreProductRelation::getErrorInfo());
        else return app('json')->successful('收藏成功');
    }

    /**
     * 添加点赞
     *
     * @param Request $request
     * @return mixed
     */
//    public function like_add(Request $request)
//    {
//        list($id, $category) = UtilService::postMore([['id',0], ['category','product']], $request, true);
//        if(!$id || !is_numeric($id))  return app('json')->fail('参数错误');
//        $res = StoreProductRelation::productRelation($id,$request->uid(),'like',$category);
//        if(!$res) return  app('json')->fail(StoreProductRelation::getErrorInfo());
//        else return app('json')->successful();
//    }

    /**
     * 取消点赞
     *
     * @param Request $request
     * @return mixed
     */
//    public function like_del(Request $request)
//    {
//        list($id, $category) = UtilService::postMore([['id',0], ['category','product']], $request, true);
//        if(!$id || !is_numeric($id)) return app('json')->fail('参数错误');
//        $res = StoreProductRelation::unProductRelation($id, $request->uid(),'like',$category);
//        if(!$res) return app('json')->fail(StoreProductRelation::getErrorInfo());
//        else return app('json')->successful();
//    }

    /**
     * 签到 配置
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function sign_config()
    {
       $signConfig = GroupDataService::getData('sign_day_num') ?? [];
       return app('json')->successful($signConfig);
    }

    /**
     * 签到 列表
     * @param Request $request
     * @param $page
     * @param $limit
     * @return mixed
     */
    public function sign_list(Request $request)
    {
        list($page, $limit) = UtilService::getMore([
            ['page',0],
            ['limit',0]
        ], $request, true);
        if(!$limit) return  app('json')->successful([]);
        $signList = UserSign::getSignList($request->uid(),(int)$page,(int)$limit);
        if($signList) $signList = $signList->toArray();
        return app('json')->successful($signList);
    }

    /**
     * 签到
     * @param Request $request
     * @return mixed
     */
    public function sign_integral(Request $request)
    {
        $signed = UserSign::getIsSign($request->uid());
        if($signed) return app('json')->fail('已签到');
        if(false !== ($integral = UserSign::sign($request->uid())))
            return app('json')->successful('签到获得'.floatval($integral).'积分',['integral'=>$integral]);
        return app('json')->fail(UserSign::getErrorInfo('签到失败'));
    }

    /**
     * 签到用户信息
     * @param Request $request
     * @return mixed
     */
    public function sign_user(Request $request)
    {
        list($sign,$integral,$all) = UtilService::postMore([
            ['sign',0],
            ['integral',0],
            ['all',0],
        ],$request,true);
        $user = $request->user();
        //是否统计签到
        if($sign || $all){
            $user['sum_sgin_day'] = UserSign::getSignSumDay($user['uid']);
            $user['is_day_sgin'] = UserSign::getIsSign($user['uid']);
            $user['is_YesterDay_sgin'] = UserSign::getIsSign($user['uid'],'yesterday');
            if(!$user['is_day_sgin'] && !$user['is_YesterDay_sgin']){ $user['sign_num'] = 0;}
        }
        //是否统计积分使用情况
        if($integral || $all){
            $user['sum_integral'] = (int)UserBill::getRecordCount($user['uid'],'integral','sign,system_add,gain');
            $user['deduction_integral'] = (int)UserBill::getRecordCount($user['uid'],'integral','deduction') ?? 0;
            $user['today_integral'] = (int)UserBill::getRecordCount($user['uid'],'integral','sign,system_add,gain','today');
        }
        unset($user['pwd']);
        if(!$user['is_promoter']){
            $user['is_promoter']=(int)sysConfig('store_brokerage_statu') == 2 ? true : false;
        }
        return app('json')->successful($user->hidden(['account','real_name','birthday','card_id','mark','partner_id','group_id','add_time','add_ip','phone','last_time','last_ip','spread_uid','spread_time','user_type','status','level','clean_time','addres'])->toArray());
    }

    /**
     * 签到列表（年月）
     *
     * @param Request $request
     * @return mixed
     */
    public function sign_month(Request $request)
    {
        list($page, $limit) = UtilService::getMore([
            ['page',0],
            ['limit',0]
        ], $request, true);
        if(!$limit) return  app('json')->successful([]);
        $userSignList = UserSign::getSignMonthList($request->uid(), (int)$page, (int)$limit);
        return app('json')->successful($userSignList);
    }

    /**
     * 获取活动状态
     * @return mixed
     */
    public function activity()
    {
        $data['is_bargin'] = StoreBargain::validBargain() ? true : false;
        $data['is_pink'] = StoreCombination::getPinkIsOpen() ? true : false;
        $data['is_seckill'] = StoreSeckill::getSeckillCount() ? true : false;
        return app('json')->successful($data);
    }

    /**
     * 用户修改信息
     * @param Request $request
     * @return mixed
     */
    public function edit(Request $request)
    {
        $data = UtilService::getMore([
            ['avatar',''],
            ['nickname',''],
            ['gender',0],
            ['birthday','']
        ],$request);
        $data['birthday'] = !empty($data['birthday'])? $data['birthday']:'';
        foreach ($data as $k => $v) {
            if (!$data[$k] && $data[$k]!=="0") unset($data[$k]);
        }
        if(User::editUser($data,$request->uid())) return app('json')->successful('修改成功');
        return app('json')->fail('修改失败');
    }

    /**
     * 推广人排行
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function rank(Request $request){
        $data = UtilService::getMore([
            ['page',''],
            ['limit',''],
            ['type','']
        ],$request);
        $users = User::getRankList($data);
        return app('json')->success($users);
    }

    /**
     * 佣金排行
     * @param Request $request
     * @return mixed
     */
    public function brokerage_rank(Request $request){
        $data = UtilService::getMore([
            ['page',''],
            ['limit'],
            ['type']
        ],$request);
        return app('json')->success([
            'rank'    => User::brokerageRank($data),
            'position'=> User::currentUserRank($data['type'],$request->user()['brokerage_price'])
        ]);

    }

}