<?php
/**
 * @author: xaboy<365615158@qq.com>
 * @day: 2018/01/17
 */

namespace app\admin\model\ump;

use common\basic\BaseModel;
use common\traits\ModelTrait;

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

    protected $insert = ['add_time'];

    private $coupon_array = [];

    public static function getCouponPage($where){
        $model = self::alias('A')->field('A.*')->where('A.is_del',0)->order('A.add_time DESC');
        if(isset($where['issue_coupon_id']) && $where['issue_coupon_id']!=''){
            $model=$model->where('A.issue_coupon_id',$where['issue_coupon_id']);
        }
        if(isset($where['status']) && $where['status']!=''){
            $model=$model->where('A.status',$where['status']);
        }
        if(isset($where['coupon_code']) && $where['coupon_code']!=''){
            $model=$model->where('A.coupon_code',$where['coupon_code']);
        }
        return self::page($model);
    }

    public static function getReceivePage($where){
        $model = self::alias('A')->field('A.*')->where('A.is_del',0)->order('A.add_time DESC');
        $model=$model->where('A.is_receive',1);
        if(isset($where['status']) && $where['status']!=''){
            $model=$model->where('A.status',$where['status']);
        }
        if(isset($where['coupon_code']) && $where['coupon_code']!=''){
            $model=$model->where('A.coupon_code',$where['coupon_code']);
        }
        return self::page($model);
    }

    protected function setAddTimeAttr()
    {
        return time();
    }

    /**
     * 生成优惠券
     * @param int $issue_coupon_id
     * @param int $total_count
     * @param int $start_time
     * @param int $expiry_time
     * @param int $grant_type
     * @param int $is_show
     * @param array $couponGroupInfo
     * @return bool
     */
    public function generateCoupon($issue_coupon_id = 0,$total_count = 0,$start_time = 0,$expiry_time = 0,$grant_type = 0,$is_show = 0,$couponGroupInfo=[])
    {
        $title = $couponGroupInfo['title'];
        $coupon_price = $couponGroupInfo['coupon_price'];
        $add_time = time();
        $use_min_price = $couponGroupInfo['limit_amount'];
        $type = $couponGroupInfo['type'];
        self::beginTrans();
        for($i = 0;$i < $total_count;$i++){
            $coupon_code=$this->generate_code($issue_coupon_id);
            $res[$i] = self::create(compact('coupon_code','issue_coupon_id','start_time','expiry_time','grant_type','is_show','title','coupon_price','add_time','use_min_price','type'));
            if(!$res[$i]){
                self::rollbackTrans();
                Json::fail('生成兑换码失败！');
            }
        }

        self::commitTrans();
        return true;
    }

    /**
     * 优惠券兑换码生成规则:YHQ123456789012 前三位为识别代码，后十二位为数字，其中4位为优惠券组id
     * @param $group_id
     * @return string
     */
    private function generate_code($group_id){
        $group_id = sprintf('%04d', $group_id);//优惠券组id占4位
        if(!$this->coupon_array){
            $res = self::where('is_del', 0)->select()->toArray();
            if ($res) {
                $this->coupon_array = array_column($res,'coupon_code');
            }
        }
        $random_str = $this->_get_random_str($group_id);
        $coupon_code = 'YHQ';
        for ($i = 0; $i < strlen($random_str); $i++) {
            $coupon_code .= $random_str[$i];
        }
        return $coupon_code;
    }


    /**
     * 获取12位随机数
     * @param $group_id
     * @return string
     */
    private function _get_random_str($group_id){
        $random_str = '';
        while(strlen($random_str) < 12){
            $char = mt_rand(0,9);
            $str_length = strlen($random_str);
            //产品需求:不允许出现连续出现 4 个相同数字
            if($str_length >= 3 && $char == $random_str[$str_length - 1]
                && $char == $random_str[$str_length - 2] && $char == $random_str[$str_length - 3]){
                continue;
            }

            $random_str .= $char;
            if($str_length == 2) $random_str .= $group_id;   //四位优惠券组id 放在 3-6位
        }

        if(in_array($random_str,$this->coupon_array)){
            $random_str = $this->_get_random_str($group_id);
        }

        return $random_str;
    }
}