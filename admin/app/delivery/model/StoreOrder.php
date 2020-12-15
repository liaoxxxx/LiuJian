<?php


namespace app\delivery\model;

use app\enum\DeliveryEnum;
use app\models\delivery\OrderDeliveryTrace;
use app\models\OrderDelivery;
use app\models\store\StoreDeliveryman;
use common\services\JsonService;
use common\traits\ModelTrait;
use common\basic\BaseModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 门店自提 model
 * Class SystemStore
 * @package app\admin\model\system
 */
class StoreOrder extends BaseModel
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
    protected $name = 'store_order';


    protected $orderDeliveryModel =null ;

    protected $DeliveryTraceModel =null;



    public function __construct(array $data = [])
    {
        parent::__construct($data);
        $this->orderDeliveryModel = new OrderDelivery();
        $this->DeliveryTraceModel = new OrderDeliveryTrace();
    }



    public static function getLatlngAttr($value, $data)
    {
        return $data['latitude'] . ',' . $data['longitude'];
    }


    /**
     * 配送员接单
     * @param int $orderId
     * @param int $deliverymanId
     * @param $lng
     * @param $lat
     * @return |null |null
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws \Exception
     */
    public function receiving(int $orderId, int $deliverymanId,$lng,$lat)
    {
        //查找配送信息
        $deliveryInfo=$this->orderDeliveryModel->where('order_id',$orderId)->find();
        if ($deliveryInfo['deliveryman_id']==$deliverymanId){
            return JsonService::fail('您已经负责配送此订单已，请勿重复操作');
        }
        if ($deliveryInfo['delivery_status']!='00'){
            return JsonService::fail('订单已分配给其他配送员');
        }
        //更新订单配送信息
        $date=date('Y-m-d H:i:s');
        //订单的信息
        $orderInfo=[
            'shipping_type'=>3,
            'delivery_man_id' => $deliverymanId,
            'update_time'=>$date,
        ];
        //配送信息
        $deliveryInfo = [
            'deliveryman_id' => $deliverymanId,
            'delivery_status'=>DeliveryEnum::DELIVERY_STEPS_MAP['ORDER_RECEIVING']
        ];
        //添加到配送轨迹信息
        $deliveryTraceInfo = [
            'order_id' => $orderId,
            'deliveryman_id' => $deliverymanId,
            'lat' =>$lat,
            'lng' =>$lng,
            'create_time'=>$date,
            'delivery_step'=> DeliveryEnum::DELIVERY_STEPS_MAP['ORDER_RECEIVING']
        ];
        BaseModel::beginTrans();
        //更新订单信息
        $orderRes= self::where('id', $orderId)->update($orderInfo); //更新订单信息
        $OrderDelivery = $this->orderDeliveryModel->where('order_id', $orderId)->update($deliveryInfo);
        $deliveryTrace = $this->DeliveryTraceModel->insertGetId($deliveryTraceInfo);
        BaseModel::checkTrans($orderRes&&$OrderDelivery&&$deliveryTrace);

        if( $orderRes&&$OrderDelivery&&$deliveryTrace){
            return JsonService::successful('接单成功', ['OrderDelivery' => $OrderDelivery]);
        } else{
            return JsonService::fail('接单失败');
        }
    }


    /**
     * 配送员  已到店
     * @param int $orderId
     * @param int $deliverymanId
     * @param $lng
     * @param $lat
     * @return  string
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function arriveStore(int $orderId, int $deliverymanId,$lng,$lat)
    {
        //更新订单配送信息
        $date=date('Y-m-d H:i:s');
        $deliveryInfo = [
            'update_time'=>$date,
            'delivery_status'=>DeliveryEnum::DELIVERY_STEPS_MAP['ARRIVE_STORE']
        ];
        //添加到配送轨迹信息
        $deliveryTraceInfo = [
            'order_id' => $orderId,
            'deliveryman_id' => $deliverymanId,
            'lat' =>$lat,
            'lng' =>$lng,
            'create_time'=>$date,
            'delivery_step'=> DeliveryEnum::DELIVERY_STEPS_MAP['ARRIVE_STORE']
        ];
        BaseModel::beginTrans();
        //更新订单信息
        $OrderDelivery = $this->orderDeliveryModel->where('order_id', $orderId)->update($deliveryInfo);
        $deliveryTrace = $this->DeliveryTraceModel->insertGetId($deliveryTraceInfo);
        BaseModel::checkTrans($OrderDelivery&&$deliveryTrace);

        if( $OrderDelivery&&$deliveryTrace){
            return JsonService::successful('操作(抵达门店)成功', ['OrderDelivery' => $OrderDelivery]);
        } else{
            return JsonService::fail('操作(抵达门店)失败');
        }
    }



    /**
     * 配送员  扫码出仓
     * @param int $orderId
     * @param int $deliverymanId
     * @param $lng
     * @param $lat
     * @return  string
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function outputStorage(int $orderId, int $deliverymanId,$lng,$lat)
    {
        //更新订单配送信息
        $date=date('Y-m-d H:i:s');
        $deliveryInfo = [
            'update_time'=>$date,
            'delivery_status'=>DeliveryEnum::DELIVERY_STEPS_MAP['OUTPUT_STORAGE']
        ];
        //添加到配送轨迹信息
        $deliveryTraceInfo = [
            'order_id' => $orderId,
            'deliveryman_id' => $deliverymanId,
            'lat' =>$lat,
            'lng' =>$lng,
            'create_time'=>$date,
            'delivery_step'=> DeliveryEnum::DELIVERY_STEPS_MAP['OUTPUT_STORAGE']
        ];
        BaseModel::beginTrans();
        //更新订单信息
        $OrderDelivery = $this->orderDeliveryModel->where('order_id', $orderId)->update($deliveryInfo);
        $deliveryTrace = $this->DeliveryTraceModel->insertGetId($deliveryTraceInfo);
        BaseModel::checkTrans($OrderDelivery&&$deliveryTrace);

        if( $OrderDelivery&&$deliveryTrace){
            return JsonService::successful('操作(扫码出仓)成功', ['OrderDelivery' => $OrderDelivery]);
        } else{
            return JsonService::fail('操作(扫码出仓)失败');
        }
    }


    /**
     * 配送员  开始配送
     * @param int $orderId
     * @param int $deliverymanId
     * @param $lng
     * @param $lat
     * @return  string
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function deliveryBegin(int $orderId, int $deliverymanId,$lng,$lat)
    {
        //更新订单配送信息
        $date=date('Y-m-d H:i:s');
        //订单的信息
        $orderInfo=[
            'order_status'=>'40',
        ];
        $deliveryInfo = [
            'update_time'=>$date,
            'delivery_status'=>DeliveryEnum::DELIVERY_STEPS_MAP['DELIVERY_BEGIN']
        ];
        //添加到配送轨迹信息
        $deliveryTraceInfo = [
            'order_id' => $orderId,
            'deliveryman_id' => $deliverymanId,
            'lat' =>$lat,
            'lng' =>$lng,
            'create_time'=>$date,
            'delivery_step'=> DeliveryEnum::DELIVERY_STEPS_MAP['DELIVERY_BEGIN']
        ];
        BaseModel::beginTrans();
        //更新订单信息
        $orderRes= $this->where('id', $orderId)->update($orderInfo); //更新订单信息
        $OrderDelivery = $this->orderDeliveryModel->where('order_id', $orderId)->update($deliveryInfo);
        $deliveryTrace = $this->DeliveryTraceModel->insertGetId($deliveryTraceInfo);
        BaseModel::checkTrans($orderRes&&$OrderDelivery&&$deliveryTrace);
        if( $orderRes&&$OrderDelivery&&$deliveryTrace){
            return JsonService::successful('操作成功，订单将开始配送', ['OrderDelivery' => $OrderDelivery]);
        } else{
            return JsonService::fail('操作失败');
        }
    }




    /**
     * 配送员  抵达目的
     * @param int $orderId
     * @param int $deliverymanId
     * @param $lng
     * @param $lat
     * @return  string
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function arriveDestination(int $orderId, int $deliverymanId,$lng,$lat)
    {
        //更新订单配送信息
        $date=date('Y-m-d H:i:s');

        $deliveryInfo = [
            'update_time'=>$date,
            'delivery_step'=>DeliveryEnum::DELIVERY_STEPS_MAP['ARRIVE_DESTINATION']
        ];
        //添加到配送轨迹信息
        $deliveryTraceInfo = [
            'order_id' => $orderId,
            'deliveryman_id' => $deliverymanId,
            'lat' =>$lat,
            'lng' =>$lng,
            'create_time'=>$date,
            'delivery_step'=> DeliveryEnum::DELIVERY_STEPS_MAP['ARRIVE_DESTINATION']
        ];
        BaseModel::beginTrans();
        //更新订单信息
        $OrderDelivery = $this->DeliveryTraceModel->where('order_id', $orderId)->update($deliveryInfo);
        $deliveryTrace = $this->DeliveryTraceModel->insertGetId($deliveryTraceInfo);
        BaseModel::checkTrans($OrderDelivery&&$deliveryTrace);

        if( $OrderDelivery&&$deliveryTrace){
            return JsonService::successful('接单成功', ['OrderDelivery' => $OrderDelivery]);
        } else{
            return JsonService::fail('接单失败');
        }
    }


    /**
     * 配送员  配送完成
     * @param int $orderId
     * @param int $deliverymanId
     * @param $lng
     * @param $lat
     * @return  string
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function deliveryComplete(int $orderId, int $deliverymanId,$lng,$lat)
    {
        //更新订单配送信息
        $date=date('Y-m-d H:i:s');

        $deliveryInfo = [
            'update_time'=>$date,
            'delivery_status'=>DeliveryEnum::DELIVERY_STEPS_MAP['DELIVERY_COMPLETE']
        ];
        //添加到配送轨迹信息
        $deliveryTraceInfo = [
            'order_id' => $orderId,
            'deliveryman_id' => $deliverymanId,
            'lat' =>$lat,
            'lng' =>$lng,
            'create_time'=>$date,
            'delivery_step'=> DeliveryEnum::DELIVERY_STEPS_MAP['DELIVERY_COMPLETE']
        ];
        BaseModel::beginTrans();
        //更新订单信息
        $OrderDelivery = $this->orderDeliveryModel->where('order_id', $orderId)->update($deliveryInfo);
        $deliveryTrace = $this->DeliveryTraceModel->insertGetId($deliveryTraceInfo);
        BaseModel::checkTrans($OrderDelivery&&$deliveryTrace);

        if( $OrderDelivery&&$deliveryTrace){
            return JsonService::successful('确认配送完成成功', ['OrderDelivery' => $OrderDelivery]);
        } else{
            return JsonService::fail('确认配送完成失败');
        }
    }



    /**
     * 配送员  取消配送
     * @param int $orderId
     * @param int $deliverymanId
     * @param $lng
     * @param $lat
     * @return  string
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function deliveryCancel(int $orderId, int $deliverymanId,$lng,$lat)
    {
        //更新订单配送信息
        $date=date('Y-m-d H:i:s');
        $deliveryInfo = [
            'update_time'=>$date,
            'delivery_status'=>DeliveryEnum::DELIVERY_STEPS_MAP['DELIVERY_CANCEL']
        ];
        //添加到配送轨迹信息
        $deliveryTraceInfo = [
            'order_id' => $orderId,
            'deliveryman_id' => $deliverymanId,
            'lat' =>$lat,
            'lng' =>$lng,
            'create_time'=>$date,
            'delivery_step'=> DeliveryEnum::DELIVERY_STEPS_MAP['DELIVERY_CANCEL']
        ];
        BaseModel::beginTrans();
        //更新订单信息
        $OrderDelivery = $this->orderDeliveryModel->where('order_id', $orderId)->update($deliveryInfo);
        $deliveryTrace = $this->DeliveryTraceModel->insertGetId($deliveryTraceInfo);
        BaseModel::checkTrans($OrderDelivery&&$deliveryTrace);

        if( $OrderDelivery&&$deliveryTrace){
            return JsonService::successful('申请取消配送成功，等待门店审核', ['OrderDelivery' => $OrderDelivery]);
        } else{
            return JsonService::fail('申请取消配送失败');
        }
    }



    /**
     * 获取所有的门店
     * @param array $where
     * @return array
     */
    public static function getAll($where = array())
    {
        $model = new self;
        $model = $model->order('id desc');
        return self::page($model, function ($item) {
            $item['store_name'] = $item->profile->store_name ?? '';
        }, $where);
    }


    /**
     * 获取所有的门店
     * @param $storeId
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function getDeliverymen($storeId)
    {
        $storeDeliverymanModel = new StoreDeliveryman();
        $deliverymen = $storeDeliverymanModel->alias('sd')
            ->field('d.id as deliveryman_id , d.real_name ,d.phone')
            ->join('Deliveryman d', 'd.id = sd .deliveryman_id')
            ->where('store_id', $storeId)
            ->select();
        return collect($deliverymen)->toArray();
    }

}