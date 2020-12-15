<?php
/**
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/11/11
 */

namespace app\admin\model\store;

use app\admin\model\system\SystemStore;
use common\traits\ModelTrait;
use common\basic\BaseModel;
use common\services\UtilService;
use think\facade\Cache;

/**
 * Class StoreDeliveryConfig |  门店 的  配送配置
 * @package app\admin\model\store
 */
class StoreDeliveryConfig extends BaseModel
{

    protected static $GlobalConfigKey="GlobalStoreDeliveryConfig";


    /**
     * 数据表主键
     * @var string
     */
    protected $pk = 'id';

    /**
     * 模型名称
     * @var string
     */
    protected $name = 'store_delivery_config';

    use ModelTrait;


    public function store(){
        return $this->belongsTo(SystemStore::class,'store_id');
    }


    /**
     * @param int $storeId
     * @return array
     */
    public static function  getDefaultConfig($storeId=0){

       return [
            'id' => '0',
            'store_id' => $storeId,
            'create_time' => date("Y-m-d"),
            'update_time' => date("Y-m-d"),
            'active_distance_amount' => 1,
            'active_base_amount' => 1,
            'active_weight_amount' => 1,
            'max_distance' => 20,

            'distance_amount_list' => json_encode([
                ['distance'=> '1','amount'=>'0.5'],
                ['distance'=> '5' ,'amount'=>'0.5'],
                ['distance'=> '10','amount'=>'0.5'],
                ['distance'=> '20','amount'=>'0.5'],
                ['distance'=> '50','amount'=>'0.5'],
            ]),
            'weight_amount_list' => json_encode([
                ['weight'=> '1','amount'=>'0.5'],
                ['weight'=> '5' ,'amount'=>'0.5'],
                ['weight'=> '10','amount'=>'0.5'],
                ['weight'=> '20','amount'=>'0.5'],
                ['weight'=> '50','amount'=>'0.5'],
            ]),

            'base_amount' => 2.0,
            'base_distance_amount' => 2.0,
            'base_weight_amount' => 2.0
        ];
    }


    /**
     * @param $storeId
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getOneByStoreId($storeId){
        $config= self::where("store_id",$storeId)->find();
        if (empty( $config)){
            $config= $this->getGlobalConfig();
        }

        return $config;
    }

    /**
     * @return array|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getGlobalConfig(){
        if (Cache::get(self::$GlobalConfigKey)){
            return Cache::get(self::$GlobalConfigKey);
        }else{
            $config= self::where("store_id",0)->find();
            Cache::set(self::$GlobalConfigKey,$config);
            return $config;
        }

    }

}

