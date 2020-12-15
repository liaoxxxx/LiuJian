<?php


namespace app\admin\model\system;

use app\models\store\StoreDeliveryman;
use common\traits\ModelTrait;
use common\basic\BaseModel;
use think\Collection;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\model\concern\SoftDelete;

/**
 * 门店自提 model
 * Class SystemStore
 * @package app\admin\model\system
 */
class SystemStore extends \app\models\system\SystemStore
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

    /**
     * 获取门店信息
     * @param int $id
     * @param null $field |
     * @return array|\think\Model|null
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function getStoreDispose($id = 0, $field = null)
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
     * 获取所有的店员
     * @param $storeId
     * @return Collection
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


    public function getAddTimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }


    /**
     * 通过 管理员绑定的门店id
     * @param array $storeIds
     * @return false|\PDOStatement|string|Collection
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function getListByAdminBindStore($storeIds)
    {
        if (count($storeIds)) {
            $storeList = self::where('is_del', 0)->whereIn('id', $storeIds)->select();
        } else {
            $storeList = self::where('is_del', 0)->select();
        }
        return $storeList;
    }


    /**
     * 获取所有绑定的门店 下 的 省份
     * @param $storeIds
     * @return array
     */
    public static function getProvincesOfBindStore($storeIds)
    {
        $storeModel = self::field('province')->distinct(true)->where('is_del', 0);
        if (count($storeIds)) {
            $provinceList = $storeModel->whereIn('id', $storeIds)->select()->toArray();
        } else {
            $provinceList = $storeModel->select()->toArray();
        }

        $provinceListTemp = [];
        foreach ($provinceList as $item) {
            $provinceListTemp[] = $item['province'];
        }
        return $provinceListTemp;
    }


    /**
     * 通过 省份 province 获取所有绑定的门店下 的 城市
     * @param string $province
     * @return array
     */
    public static function getCityOfBindStore(string $province)
    {
        $cityList = self::field('city')
            ->distinct(true)
            ->where('province', $province)
            ->where('is_del', 0)
            ->select()
            ->toArray();

        $cityListTemp = [];
        foreach ($cityList as $item) {
            $cityListTemp[] = $item['city'];
        }
        return $cityListTemp;
    }


    /**
     * 通过 省份 && 城市 province 获取所有绑定的门店
     * @param string $province
     * @param array $city
     * @param array $storeIds
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function getStoreByCityAndProvince(string $province ,array $city ,array $storeIds)
    {
        $cityList=[];
        foreach ($city as $item){
           if ($item['check']==true){
               $cityList[]=$item['name'];
           }
        }
        $storeModel = self::alias('s')->field('s.*,sdc.max_distance')
            ->join('store_delivery_config sdc','sdc.store_id=s.id','left')
            ->where('province', $province)
            ->whereIn('city', $cityList)
            ->where('is_del', 0);

        if (count($storeIds)) {
            $storeList = $storeModel->whereIn('s.id', $storeIds)->select()->toArray();
        } else {
            $storeList = $storeModel->select()->toArray();
        }
        return $storeList;
    }


}