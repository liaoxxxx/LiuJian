<?php
/**
 * @author: xaboy<365615158@qq.com>
 * @day: 2018/01/17
 */

namespace app\admin\model\ump;

use common\basic\BaseModel;
use common\traits\ModelTrait;

class StoreCouponIssue extends BaseModel
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
    protected $name = 'store_coupon_issue';

    use ModelTrait;

    protected $insert = ['add_time'];

//    public static function stsypage($where){
//        $model = self::alias('A')->field('A.*,B.title')->join('store_coupon B', 'A.gid=B.id', 'LEFT')->where('A.is_del',0)->order('A.add_time DESC');
//        if(isset($where['status']) && $where['status']!=''){
//            $model=$model->where('A.status',$where['status']);
//        }
//        if(isset($where['coupon_title']) && $where['coupon_title']!=''){
//            $model=$model->where('B.title','LIKE',"%$where[coupon_title]%");
//        }
//        return self::page($model);
//    }

    public static function stsypage($where){
        $model = self::alias('A')->field('A.*,B.title')->join('store_coupon_group B', 'A.gid=B.id', 'LEFT')->where('A.is_del',0)->order('A.add_time DESC');
        if(isset($where['status']) && $where['status']!=''){
            $model=$model->where('A.status',$where['status']);
        }
        if(isset($where['coupon_title']) && $where['coupon_title']!=''){
            $model=$model->where('B.title','LIKE',"%$where[coupon_title]%");
        }
        return self::page($model);
    }

    protected function setAddTimeAttr()
    {
        return time();
    }

    public static function setIssue($gid,$total_count = 0,$start_time = 0,$end_time = 0,$remain_count = 0,$grant_type = 0,$status=0)
    {
        $add_time = time();
        $coupon_issue = self::create(compact('gid','start_time','end_time','total_count','remain_count','status','grant_type','add_time'));
        if ($coupon_issue) {
            $couponGroupInfo = StoreCouponGroup::where('id', $gid)->find();
            $CouponList = new StoreCouponList();
            return $CouponList->generateCoupon($coupon_issue['id'],$coupon_issue['total_count'],$coupon_issue['start_time'],$coupon_issue['end_time'],$grant_type,$status,$couponGroupInfo);
        }
        return false;
    }

    /**
     * 发布优惠券
     * @param $gid
     * @param int $total_count
     * @param int $start_time
     * @param int $end_time
     * @param int $remain_count
     * @param int $status
     * @param int $grant_type
     */
//    public static function setIssue($gid, $total_count = 0, $start_time = 0, $end_time = 0, $remain_count = 0, $status = 0, $grant_type = 0)
//    {
//        $add_time = time();
//        $coupon_issue = self::create(compact('gid', 'start_time', 'end_time', 'total_count', 'remain_count', 'status','grant_type','add_time'));
//        if ($coupon_issue) {
//            $couponGroupInfo = StoreCoupon::where('id', $gid)->find();
//            $CouponList = new StoreCouponList();
//            return $CouponList->generateCoupon($coupon_issue['id'],$coupon_issue['total_count'],$coupon_issue['start_time'],$coupon_issue['end_time'],$grant_type,$status,$couponGroupInfo);
//        }
//        return false;
//    }
}