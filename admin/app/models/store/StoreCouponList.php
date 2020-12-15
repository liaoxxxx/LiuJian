<?php
/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2018/01/18
 */

namespace app\models\store;


use common\basic\BaseModel;
use common\traits\ModelTrait;

/**
 * TODO 优惠券列表Model
 * Class StoreCouponIssue
 * @package app\models\store
 */
class StoreCouponList extends BaseModel
{
    /**
     * 数据表主键
     * @var string
     */
    protected $pk = 'id';

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'store_coupon_list';

    use ModelTrait;

    /**
     * @param string $prefix
     * @return $this
     */
    public static function validWhere($prefix = '')
    {
        $model = new self;
        if ($prefix) {
            $model->alias($prefix);
            $prefix .= '.';
        }
        $newTime = time();
        return $model->where("{$prefix}status", 0)
            ->where(function ($query) use ($newTime, $prefix) {
                $query->where(function ($query) use ($newTime, $prefix) {
                    $query->where("{$prefix}start_time", '<', $newTime)->where("{$prefix}expiry_time", '>', $newTime);
                })->whereOr(function ($query) use ($prefix) {
                    $query->where("{$prefix}start_time", 0)->where("{$prefix}expiry_time", 0);
                });
            })->where("{$prefix}is_del", 0)->where("{$prefix}is_receive", 0);
    }

    /**
     * 兑换优惠券
     * @param $couponCode
     * @param $uid
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function exchangeCoupon($couponCode, $uid)
    {
        $issueCouponInfo = self::validWhere()->where('coupon_code', $couponCode)->find();
        $issueCoupon = StoreCouponIssue::where('id', $issueCouponInfo['issue_coupon_id'])->find();
        if (!$issueCouponInfo) return self::setErrorInfo('兑换的优惠劵已领完或已过期!');
        if (StoreCouponIssueUser::be(['uid' => $uid, 'issue_coupon_id' => $issueCouponInfo['issue_coupon_id']]))
            return self::setErrorInfo('已领取过该优惠劵!');
        if ($issueCoupon['remain_count'] <= 0) return self::setErrorInfo('抱歉优惠卷已经领取完了！');
        self::beginTrans();
        $res1 = false != self::where('id',$issueCouponInfo['id'])->update(['is_receive'=>1,'receive_time'=>time(),'receive_uid'=>$uid]);
        $res2 = false != StoreCouponIssueUser::addUserIssue($uid, $issueCouponInfo['issue_coupon_id']);
        $res3 = true;
        if ($issueCoupon['total_count'] > 0) {
            $issueCoupon['remain_count'] -= 1;
            $res3 = false !== $issueCoupon->save();
        }
        $res = $res1 && $res2 && $res3;
        self::checkTrans($res);
        return $res;
    }

    /**
     * 处理过期的优惠券
     */
    public static function checkInvalidCoupon()
    {
        self::where('expiry_time','<',time())->where('status',0)->update(['status'=>4]);
    }

    /**
     * @param $couponList
     * @return mixed
     */
    public static function tidyCouponList($couponList)
    {
        $time = time();
        foreach ($couponList as $k=>$coupon){
            $coupon['_add_time'] = date('Y/m/d',$coupon['add_time']);
            $coupon['_expiry_time'] = date('Y/m/d',$coupon['expiry_time']);
            $coupon['use_min_price'] = number_format($coupon['use_min_price'],2);
            $coupon['coupon_price'] = number_format($coupon['coupon_price'],2);
            if($coupon['status'] == 3){
                $coupon['_type'] = 0;
                $coupon['_msg'] = '已失效';
            }else if ($coupon['status'] == 2){
                $coupon['_type'] = 0;
                $coupon['_msg'] = '已使用';
            }else if ($coupon['status'] == 4){
                $coupon['_type'] = 0;
                $coupon['_msg'] = '已过期';
            }else if($coupon['add_time'] > $time || $coupon['expiry_time'] < $time){
                $coupon['_type'] = 0;
                $coupon['_msg'] = '已过期';
            }else{
                $coupon['_type'] = 1;
                $coupon['_msg'] = '可使用';
            }
            $couponList[$k] = $coupon;
        }
        return $couponList;
    }

    /**
     * 获取用户优惠券（全部）
     * @param $uid
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getUserAllCoupon($uid)
    {
        self::checkInvalidCoupon();
        $couponList = self::where('receive_uid',$uid)->order('status ASC,add_time DESC')->select()->toArray();
        return self::tidyCouponList($couponList);
    }


    /**
     * 获取商品可用优惠券
     * @param $uid
     * @param array $productInfo
     * @return mixed
     */
    public static function getUsableCouponList($uid, $productInfo = [])
    {
        self::checkInvalidCoupon();
        $couponList = self::alias('A')
            ->field('A.*,C.product_range_type,C.product_range_value')
            ->join('store_coupon_issue B','A.issue_coupon_id = B.id')
            ->join('store_coupon_group C','B.gid = C.id')
            ->where('A.receive_uid',$uid)->select()->toArray();
        if (!$couponList) return [];
        foreach ($couponList as &$coupon){
            $err = 0;
            foreach ($productInfo as &$product){
                //满减型优惠券
                if ($coupon['type'] == 1) {

                    //所有类型商品满减
                    if ($coupon['product_range_type'] == 0) {
                        if ($coupon['limit_amount'] > $product['price']) {
                            $err++;
                            continue 1;
                        }
                    }

                    //指定分类商品满减
                    if ($coupon['product_range_type'] == 1){
                        if (!in_array($product['cate_id'],json_decode($coupon['product_range_value'],true))) {
                            $err++;
                            continue 1;
                        }
                        if ($coupon['limit_amount'] > $product['price']) {
                            $err++;
                            continue 1;
                        }
                    }

                    //指定商品满减
                    if ($coupon['product_range_type'] == 2){
                        if (!in_array($product['id'],json_decode($coupon['product_range_value'],true))) {
                            $err++;
                            continue 1;
                        }
                        if ($coupon['limit_amount'] > $product['price']) {
                            $err++;
                            continue 1;
                        }
                    }
                }

                //无门槛型优惠券
                if ($coupon['type'] == 0){

                    //指定分类商品无门槛
                    if ($coupon['product_range_type'] == 1){
                        if (!in_array($product['cate_id'],json_decode($coupon['product_range_value'],true))) {
                            $err++;
                            continue 1;
                        }
                    }

                    //指定商品无门槛
                    if ($coupon['product_range_type'] == 2){
                        if (!in_array($product['id'],json_decode($coupon['product_range_value'],true))) {
                            $err++;
                            continue 1;
                        }
                    }
                }
            }
            if ($err == count($productInfo)) unset($coupon);
        }
        unset($coupon);
        return self::tidyCouponList($couponList);
    }

    /**
     * 获取用户优惠券个数
     * @param $uid
     * @return int
     */
    public static function getUserValidCouponCount($uid)
    {
        self::checkInvalidCoupon();
        return self::where('receive_uid',$uid)->where('status',0)->order('status ASC,add_time DESC')->count();
    }
    /**
     * 获取用户优惠券（未使用）
     * @return \think\response\Json
     */
    public static function getUserValidCoupon($uid)
    {
        self::checkInvalidCoupon();
        $couponList = self::where('receive_uid',$uid)->where('status',0)->order('status ASC,add_time DESC')->select()->toArray();
        return self::tidyCouponList($couponList);
    }
    /**
     * 获取用户优惠券（已使用）
     * @return \think\response\Json
     */
    public static function getUserAlreadyUsedCoupon($uid)
    {
        self::checkInvalidCoupon();
        $couponList = self::where('receive_uid',$uid)->where('status',2)->order('status ASC,add_time DESC')->select()->toArray();
        return self::tidyCouponList($couponList);
    }
    /**
     * 获取用户优惠券（已过期）
     * @return \think\response\Json
     */
    public static function getUserBeOverdueCoupon($uid)
    {
        self::checkInvalidCoupon();
        $couponList = self::where('receive_uid',$uid)->where('status',4)->order('status ASC,add_time DESC')->select()->toArray();
        return self::tidyCouponList($couponList);
    }
    /**
     * 获取用户优惠券（即将过期）
     * @return \think\response\Json
     */
    public static function getUserOverdueCoupon($uid)
    {
        self::checkInvalidCoupon();
        $couponList = self::where('receive_uid',$uid)->where('expiry_time','<',time()+3600*24)->where('expiry_time','>',time())->order('status ASC,add_time DESC')->select()->toArray();
        return self::tidyCouponList($couponList);
    }
}