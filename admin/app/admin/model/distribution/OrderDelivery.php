<?php


namespace app\admin\model\distribution;


use app\admin\model\order\StoreOrder;
use app\admin\model\system\SystemStore;
use app\enum\DeliveryEnum;
use common\services\JsonService;
use common\traits\ModelTrait;


/**
 * 门店自提 model
 * Class SystemStore
 * @package app\admin\model\system
 */
class OrderDelivery extends \app\models\OrderDelivery
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
    protected $name = 'store_order_delivery';


    public function store()
    {
        return $this->belongsTo(SystemStore::class, 'store_id');
    }

    public function deliveryman()
    {
        return $this->belongsTo(Deliveryman::class, 'deliveryman_id');
    }

    public function user()
    {
        return $this->belongsTo(StoreOrder::class, 'user_id');//->field('uid,real_name,user_phone,user_address');
    }


    /**
     * 获取配送的订单列表  用于统计 配送订单
     * @param $where
     * @return array
     * @throws \think\db\exception\DbException
     */
    public static function orderDeliveryList($where)
    {
        $orderDeliveryModel = self::order('id', "DESC")->where('is_delete', 0);

        //todo
        //
        //        "data" => "today"
        //  "excel" => "0"


        //单号
        if (!empty($where['order_id'])) {
            $orderDeliveryModel = $orderDeliveryModel->where('order_id', $where['order_id']);
        }
        //配送员
        if (!empty($where['deliverymanId'])) {
            $orderDeliveryModel = $orderDeliveryModel->where('deliveryman_id', $where['deliverymanId']);
        }
        //配送状态
        if (isset($where['status'])) {
            $orderDeliveryModel = $orderDeliveryModel->where('delivery_status', $where['status']);
        }
        //门店站点
        if (!empty($where['storeId'])) {
            $orderDeliveryModel = $orderDeliveryModel->where('store_id', $where['storeId']);
        }
        //金额
        if (!empty($where['delivery_amount']) && isset($where['delivery_amount']['min']) && isset($where['delivery_amount']['max'])) {
            $orderDeliveryModel = $orderDeliveryModel->whereBetween('delivery_amount',implode(',',$where['delivery_amount']) );
        }


        $count = $orderDeliveryModel->count();
        $data = $orderDeliveryModel->with(['store', 'deliveryman', 'user'])
            ->limit(($where['page'] - 1) * $where['limit'], $where['limit'])
            ->select();
        //die(json_encode($data));
        foreach ($data as $index => &$item) {
            $item['delivery_status_str'] = isset(DeliveryEnum::DELIVERY_STATUS_MAP[$item['delivery_status']])
                ?
                DeliveryEnum::DELIVERY_STATUS_MAP[$item['delivery_status']] : '未知';
            $item['system_confirm'] = $item['system_confirm'] ? '已确认' : '未确认';
        }
        return compact('count', 'data');
    }

    /**
     * 重新配送  | 后台 手动分配配送任务
     *
     */
    public function reDelivery($orderId, $deliverymanId)
    {
        //修改订单记录
        $updateRes = self::where('order_id', $orderId)->update(['deliveryman_id' => $deliverymanId, 'update_time' => date('Y-m-d H:I:s')]);
        //todo 发信息给配送员
        if ($updateRes) {
            JsonService::success("分配配送任务成功");
        }
    }


}