<?php

/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/11/02
 */

namespace app\models;

use app\admin\model\distribution\Deliveryman;
use app\admin\model\order\StoreOrder;
use app\admin\model\system\SystemStore;
use app\enum\DeliveryEnum;
use app\models\store\StoreCart;
use app\models\user\User;
use common\services\DeliveryService;
use common\services\MapService;
use common\services\TmapService;
use common\traits\ModelTrait;
use common\basic\BaseModel;
use common\utils\ArrayUtil;
use common\utils\LngLat;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Model;

/**
 * 图文管理 Model
 * Class WechatNews
 * @package app\admin\model\wechat
 */
class OrderDelivery extends BaseModel
{

    use ModelTrait;

    protected $pk = 'id';

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
        return $this->belongsTo(User::class, 'user_id');//->field('uid,real_name,user_phone,user_address');
        //->find();
    }

    public function address()
    {
        return $this->belongsTo(StoreOrder::class, 'user_id');//->field('uid,real_name,user_phone,user_address');
        //->find();
    }


    /**
     *
     * //通过 $order_id 获取 订单配送记录
     * @param string $orderId | 订单的idd
     * @param bool $withUser | 是否关联 用户
     * @param bool $withStore |是否关联 门店
     * @param bool $withDeliveryman |是否关联 配送员
     * @return array|Model|null
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function getByOrderId($orderId, $withUser = false, $withStore = false, $withDeliveryman = false)
    {
        $withArray = [];
        if ($withUser) {
            $withArray[] = 'user';
        }
        if ($withStore) {
            $withArray[] = 'store';
        }
        if ($withDeliveryman) {
            $withArray[] = 'deliveryman';
        }
        return self::where("order_id", $orderId)->with($withArray)->find();
    }


    /**
     *     //创建单条 订单的基础配送信息
     * @param $orderId | 订单的全局id
     * @param int $storeId | 商店id
     * @param array $userAddress | 收货地址
     * @param array $cartInfo | 购物车信息
     * @return int 0 生成失败 | value > 0 生成成功
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @throws \think\exception\DataNotFoundException
     * @throws \think\exception\DbException
     */
    public function createRow($orderId, $storeId, $userAddress, $cartInfo):int
    {
        $mark=[];
        //1.如果收货地址不是选择现有地址  即地址不存在数据库中  需要通过 地图服务获取  坐标
        $TmapService = (new TmapService());
        if (empty($userAddress['id']) || !$userAddress['longitude'] || $userAddress['latitude'] ) {
            //1.得到地址文字
            $addressStr = $userAddress['province'] . ' ' . $userAddress['city'] . ' ' . $userAddress['district'] . ' ' . $userAddress['detail'];
            //2.调用地图服务获取  坐标
            $location = $TmapService->getLngLatByAddress($addressStr);
            $userAddress['longitude'] = $location->getLng();
            $userAddress['latitude'] = $location->getLat();
            $mark[]=DeliveryEnum::DELIVERY_WARRING_MAP['temporary_user_address'];
        }
        //2.有没有选择门店 无
        $store = null;
        if ($storeId == 0) {
            $mark[]=DeliveryEnum::DELIVERY_WARRING_MAP['not_select_store'];
            //市区内的门店
            $cityStoreList = self::getStoresByCity($userAddress['province'],$userAddress['city']);
            if (count($cityStoreList) == 0) {
                return app('json')->fail('您所在的城市区域没有在配送范围内!');
            }
            $from=new LngLat($userAddress['longitude'], $userAddress['latitude']);
            //市区内的门店 配送半径 未覆盖
            $store = self::getShortestDistanceStore( $from,$cityStoreList,$userAddress['district']);
        } else { //有
            $store = (new SystemStore())->with("config")->where('id', $storeId)->find();
        }


        //3.获取门店的配置
        $storeDeliveryConfig = $store['config'];

        //4.配送直线距离
        $from= new LngLat($store['longitude'], $store['latitude']);
        $to=new LngLat($userAddress['longitude'], $userAddress['latitude']) ;

        $linearDistance = MapService::getDistance($from,$to,true);

        try {
            $routeDistance = $TmapService->getDistance($from,$to);
        } catch (\Exception $e) {
            $routeDistance = 0.00;
        }
        if ($routeDistance ==0){
            $mark[]=DeliveryEnum::DELIVERY_WARRING_MAP['abnormal_router_distance'];
        }
        //商品总数量总重量
        $weight = StoreCart::getWeightFromCart($cartInfo,$storeDeliveryConfig['singleton_product_weight']);

        //todo到重量计算了
        $deliveryAmount = DeliveryService::calculateDeliverAmount($storeDeliveryConfig, $weight, $routeDistance);
        $deliveryData = [
            'order_id' => $orderId,
            'store_id' => $storeId,
            'create_time' => date('Y-m-d H:i:s'),
            'update_time' => date('Y-m-d H:i:s'),
            'start_lat' => $store['latitude'],
            'start_lng' => $store['longitude'],
            'end_lat' => $userAddress['latitude'],
            'end_lng' => $userAddress['longitude'],
            'linear_distance' => $linearDistance,
            'route_distance' => $routeDistance,
            'delivery_amount' => $deliveryAmount,
            'delivery_status' => 0
        ];

        $deliveryId = self::insertGetId($deliveryData);

        if ($deliveryId) {
            //todo  发送信息 websocket


            return (int) $deliveryId;
        }
        else{
            return 0;
        }


    }


    /**
     * 通过城市  获取门店
     * @param $city
     * @return array
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public static function  getStoresByCity($province,$city)
    {
        $cityPrefix = mb_substr($city, 0, 2);
        $provincePrefix=mb_substr($province, 0, 2);
        //
        $storeList = \app\models\system\SystemStore::with('config')->where([
            'is_del' => 0,
            'status' => 1,
            'is_show' => 1
        ])
            ->where('province', 'like', '%' . $provincePrefix . '%')
            ->where("city",'like', '%' . $cityPrefix . '%')
            ->select()->toArray();
        if ($storeList){
            return  $storeList;
        }else{
            return [];
        }
    }

    /**
     * 通过城市  获取最短距离门店
     * @param LngLat $from | 用户地址坐标
     * @param $storeList  |  门店列表
     * @param $district
     * @return array
     */
    public function getShortestDistanceStore(LngLat $from, $storeList,$district)
    {
        $storeAllList = [];  //普通
        $districtStoreList=[]; //同区域


        foreach ($storeList as $store) {
            //门店有配置坐标
            if (isset( $store['longitude']) && isset($store['latitude'])&& isset( $store['config']['max_distance'] )) {
                $distance = MapService::getDistance($from, new LngLat($store['longitude'], $store['latitude']),true);
                //不超出配送范围
                if ($distance <= $store['config']['max_distance']){
                    $store['distance']=$distance;
                    $storeAllList[] =  $store;
                    //区级相同
                    if ( isset($store['district']) &&  mb_substr($store['district'],0,2)==mb_substr($district,0,2)){
                        $districtStoreList[]=$store;
                    }
                }
            }
        }
        //先选  同区域的
        if (count($districtStoreList)> 0){
             $list= ArrayUtil::sortByValue($districtStoreList,'distance',SORT_ASC);
             return $list[0];
        }elseif(count($storeAllList)> 0){  //所有
            $list= ArrayUtil::sortByValue($storeAllList,'distance',SORT_ASC);
            return $list[0];
        }else{  //默认门店 处理
            $store=SystemStore::getHeadStore();
        }

        return $store;
    }



}