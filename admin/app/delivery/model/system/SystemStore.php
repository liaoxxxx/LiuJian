<?php


namespace app\delivery\model\system;

use app\models\delivery\DeliverymanWork;
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
class SystemStore extends BaseModel
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
    protected $name = 'system_store';


    public static function getLatlngAttr($value, $data)
    {
        return $data['latitude'] . ',' . $data['longitude'];
    }

    public static function verificWhere()
    {
        return self::where('is_show', 1)->where('is_del', 0);
    }

    /*
     * 获取门店信息
     * @param int $id
     * */
    public static function getStoreDispose($id = 0)
    {
        if ($id)
            $storeInfo = self::verificWhere()->where('id', $id)->find();
        else
            $storeInfo = self::verificWhere()->find();
        if ($storeInfo) {
            $storeInfo['latlng'] = self::getLatlngAttr(null, $storeInfo);
            $storeInfo['valid_time'] = $storeInfo['valid_time'] ? explode(' - ', $storeInfo['valid_time']) : [];
            $storeInfo['day_time'] = $storeInfo['day_time'] ? explode(' - ', $storeInfo['day_time']) : [];
            $storeInfo['address'] = $storeInfo['address'] ? explode(',', $storeInfo['address']) : [];
        } else {
            $storeInfo['latlng'] = [];
            $storeInfo['valid_time'] = [];
            $storeInfo['valid_time'] = [];
            $storeInfo['day_time'] = [];
            $storeInfo['address'] = [];
            $storeInfo['id'] = 0;
        }
        return $storeInfo;
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
     * 获取 某个配送员工作的所有门店
     * @param $deliverymanId   | 配送员的id
     * @param int $isStoreActive | 过滤激活的门店
     * @param int $isWorkActive  | 过滤激活的工作
     * @return array
     */
    public static function getWorkStores(int $deliverymanId ,int $isStoreActive =0,int $isWorkActive =0)
    {
        $workModel = new DeliverymanWork();
        $workModel = $workModel->alias('dw')
            ->field('ss.id')
            ->join('system_store ss','ss.id=dw.store_id')
            ->join('deliveryman d','d.id=dw.delivery_id')
            ->where('dw.delivery_id',$deliverymanId)
            ->order('id desc');
        if ($isStoreActive){
            $workModel= $workModel->where('ss.status',$isStoreActive);
        }

        if ($isWorkActive){
            $workModel= $workModel->where('dw.status',$isWorkActive);
        }

        return $workModel->select()->toArray();
    }


    /**
     * 获取所有的门店
     * @param $storeId
     * @return array|Collection
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function getDeliverymen($storeId)
    {
        $storeDeliverymanModel = new StoreDeliveryman();
        $deliverymen= $storeDeliverymanModel->alias('sd')
            ->field('d.id as deliveryman_id , d.real_name ,d.phone')
            ->join('Deliveryman d','d.id = sd .deliveryman_id')
            ->where('store_id', $storeId)
            ->select();
        return  collect($deliverymen)->toArray();
    }

}