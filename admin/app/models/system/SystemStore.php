<?php


namespace app\models\system;

use app\admin\model\store\StoreDeliveryConfig;
use app\admin\model\system\SystemConfig;
use app\enum\DeliveryEnum;
use app\models\delivery\Deliveryman;
use common\traits\ModelTrait;
use common\basic\BaseModel;

/**
 * 门店自提 model
 * Class SystemStore
 * @package app\model\system
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

    public function config()
    {
        return $this->hasOne(StoreDeliveryConfig::class,'store_id');
    }

    /*
     * 获取门店信息
     * @param int $id
     * */
    public static function getStoreDispose($id = 0, $felid = '')
    {
        if ($id)
            $storeInfo = self::verificWhere()->where('id', $id)->find();
        else
            $storeInfo = self::verificWhere()->find();
        if ($storeInfo) {
            $storeInfo['latlng'] = self::getLatlngAttr(null, $storeInfo);
            $storeInfo['valid_time'] = $storeInfo['valid_time'] ? explode(' - ', $storeInfo['valid_time']) : [];
            $storeInfo['_valid_time'] = str_replace('-', '/', ($storeInfo['valid_time'][0] ?? '') . ' ~ ' . ($storeInfo['valid_time'][1] ?? ""));
            $storeInfo['day_time'] = $storeInfo['day_time'] ? str_replace(' - ', ' ~ ', $storeInfo['day_time']) : [];
            $storeInfo['_detailed_address'] = $storeInfo['address'] . ' ' . $storeInfo['detailed_address'];
            $storeInfo['address'] = $storeInfo['address'] ? explode(',', $storeInfo['address']) : [];
            if ($felid) return $storeInfo[$felid] ?? '';
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
     * @param $storeId
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getWorkingDeliveryman($storeId){
       return $deliverymanList=  (new Deliveryman())->where('work_store_id',$storeId)
            ->where('is_receiving',1)
            ->select();
    }


    /**
     * @return array
     */
    public static function getHeadStore(){
        //1 从缓存中读取
        $store=\think\facade\Cache::get(DeliveryEnum::HEADER_STORE_KEY);
        if (!$store){ //不存在
            $id=SystemConfig::getConfigValue(DeliveryEnum::HEADER_STORE_KEY);
            if (!isset($id)||$id==null){
                $e=new \think\exception\DataNotFoundException(DeliveryEnum::HEADER_STORE_KEY." NOT FOUND ,SystemConfig::getConfigValue`s result not found,param: menu =>".DeliveryEnum::HEADER_STORE_KEY);
                throw  $e;
            }
            //2.从数据库中读取
            $store = (new \app\admin\model\system\SystemStore())->with("config")->where('id', $id)->find()->toArray();
            \think\facade\Cache::set(DeliveryEnum::HEADER_STORE_KEY,json_encode( $store));
        }
        $store=json_decode($store,true);
        return $store;
    }

    /**
     * @return bool
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public static function delHeadStore(){
      $res=  \think\facade\Cache::set(DeliveryEnum::HEADER_STORE_KEY,null);
      if ($res){
          return true;
      }else{
          return false;
        }

    }


}