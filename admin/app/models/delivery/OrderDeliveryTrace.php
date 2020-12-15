<?php


namespace app\models\delivery;

use app\admin\model\distribution\Deliveryman;
use app\models\store\StoreDeliveryman;
use common\traits\ModelTrait;
use common\basic\BaseModel;
use think\Collection;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 门店自提 model
 * Class SystemStore
 * @package app\admin\model\system
 */
class OrderDeliveryTrace extends BaseModel
{
    use ModelTrait;

    /**
     * 数据表主键
     * @var string
     */
    protected $pk = 'id';

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'store_order_delivery_trace';


    /**
     * 关联的订单操作记录 配送员
     * @return \think\model\relation\BelongsTo
     */
    public function deliveryman(){
        return $this->belongsTo(Deliveryman::class,'deliveryman_id');
    }

}