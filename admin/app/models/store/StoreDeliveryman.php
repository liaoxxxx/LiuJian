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
 * TODO 发布优惠券Model
 * Class StoreCouponIssue
 * @package app\models\store
 */
class StoreDeliveryman extends BaseModel
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
    protected $name = 'system_store_deliveryman';


    public function deliveryman(){
        return $this->hasOne('Deliveryman','id','deliveryman_id');
    }

    public function store(){

    }

}