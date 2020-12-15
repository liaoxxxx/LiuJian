<?php

namespace common\services;

use app\admin\model\store\StoreDeliveryConfig;
use app\models\delivery\Deliveryman;
use app\models\OrderDelivery;
use app\models\system\SystemStore;
use common\utils\LngLat;
use think\console\Command;


/**
 * 配送服务
 * Class DeliveryService
 * @package common\services
 */
class DeliveryService
{

    /**
     * @var string $LocationField | string   配送员的缓存对象 中的位置的映射字段
      */
    public static $LocationField='location';


    /**
     * @var string $LocationExpireField | string   配送员的缓存对象 中的位置过期 的映射字段
     */
    public static $LocationExpireField='locationExpire';

    /**
     * @var string $LocationExpireField | string   配送员的缓存对象 中的位置过期时间
     */
    public static $LocationExpireTime= 50 *60;

    /**
     * @param StoreDeliveryConfig |array $deliveryConfig | 门店的配送设置
     * @param float $productWeight | 产品总的重量
     * @param int $distance |  配送距离
     * @return float
     */
    public static function calculateDeliverAmount( $deliveryConfig, float $productWeight,  $distance = 1)
    {
        //计算基础价
        $baseAmount = abs($deliveryConfig['base_amount']);
        $distanceBaseAmount = abs($deliveryConfig['base_distance_amount']);
        $weightBaseAmount = abs($deliveryConfig['base_weight_amount']);

        //启用 重量阶梯价
        if ($deliveryConfig['active_weight_amount']) {
            $weightAmountList = json_decode($deliveryConfig['weight_amount_list'], true);
            $weightList = [];
            //根据重量排序
            foreach ($weightAmountList as $index => &$item) {
                unset($weightAmountList[$index]);
                $weightList[$item['weight']] = $item['amount'];
            }
            $weightAmountList = $weightList;
            //删掉无效的 阶梯 配置
            foreach ($weightAmountList as $index => $item) {
                if ($item == 0 || $index == 0) {
                    unset($weightAmountList[$index]);
                }
            }
            //排序
            krsort($weightAmountList);
            //移除  高于 当前重量的 阶梯位
            foreach ($weightAmountList as $index => $item){
                if ($productWeight <= $index){
                    unset($weightAmountList[$index]);
                }
            }

            //计价

           foreach ($weightAmountList as $index =>$item){
               $weightBaseAmount+= abs($productWeight - floatval($index)) *  floatval($item);
               $productWeight -= (float)$index;
           }


        }
        #####################################
        //启用 距离 阶梯价
        if ($deliveryConfig['active_distance_amount']) {
            $distanceAmountList = json_decode($deliveryConfig['distance_amount_list'], true);
            $distanceListTemp = [];
            //根据重量排序
            foreach ($distanceAmountList as $index => &$item) {
                // $temp = [$item['weight'] => $item['amount']];
                unset($distanceAmountList[$index]);
                $distanceListTemp[$item['distance']] = $item['amount'];
            }

            $distanceAmountList = $distanceListTemp;
            //删掉无效的 阶梯 配置
            foreach ($distanceAmountList as $index => $item) {
                if ($item == 0 || $index == 0) {
                    unset($distanceAmountList[$index]);
                }
            }
            //排序
            krsort($distanceAmountList);
            //移除  高于 当前重量的 阶梯位
            foreach ($distanceAmountList as $index => $item) {
                if ($distance <= $index) {
                    unset($distanceAmountList[$index]);
                }
            }

            //计价
            foreach ($distanceAmountList as $index => $item) {
                //dump( "$distanceBaseAmount += ( $distance -$index) * $item" );
                $distanceBaseAmount += abs($distance - $index) * $item;
                $distance -= (float)$index;

            }
        }

        //

        return $baseAmount + $distanceBaseAmount + $weightBaseAmount;
    }


    public function sendToDeliveryman($data,$openId){
        //手机信息
       // WechatTemplateService::sendTemplate($openId, WechatTemplateService::ORDER_DELIVER_SUCCESS, $group, $url);
        //微信模板消息


    }



    public function sendToUser($data,$openId){

    }




    public function sendOrderCreate($storeId){
        //1.找到 门店下的工作状态下 的配送员
        $storeModel=new SystemStore();
        //$storeModel->__construct()



    }


    /**
     *
     * 发货时给用户提醒
     * @param $orderId
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function sendDeliveryNotice($orderId){
        $orderDeliveryItem =(new OrderDelivery())->getByOrderId($orderId,true);

       $orderDeliveryItem['user'];


    }


    /**
     * 生成 配送员的 缓存 key
     * @param $deliverymanId
     * @return string
     */
    public static function  getDeliverymanCacheKey($deliverymanId){
        return "_deliveryman_cache_".$deliverymanId;
    }


    /**
     * 处理缓存和数据库中配送员 的位置  超时
     * @param $deliverymanId
     */
    public static function handleLocationExpire($deliverymanId){
        //todo

    }

    /**
     * 获取 配送员的 位置
     * @param $deliverymanId
     * @return LngLat
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getDeliverymanLocation($deliverymanId):LngLat
    {

         //1.查找缓存
         $cacheLocal=app('redis')->hGet(self::getDeliverymanCacheKey($deliverymanId),self::$LocationField);
        //1.查找缓存
        $expire=app('redis')->hGet(self::getDeliverymanCacheKey($deliverymanId),self::$LocationExpireField);
        if ($cacheLocal){

                $cache=unserialize($cacheLocal);
             var_dump($cache); die();
         }


         //1.缓存穿透
         if ( empty($cache) || $cache ==null) {
            $dbLocation=Deliveryman::where('id',$deliverymanId)->field('id,current_lat,current_lng')->find();
            if (empty( $dbLocation['current_lng'])|| empty($dbLocation['current_lat']))
            {
                 return app('json')->fail('配送位置异常');
            }
            $lngLat=new LngLat($dbLocation['current_lng'],$dbLocation['current_lat']);
            self::setDeliverymanLocation($deliverymanId,$lngLat);
            self::handleLocationExpire($deliverymanId);
            return $lngLat;
         }else{
             //处理
            self::handleLocationExpire($cache);
            return  $cache;
         }

    }


    /**
     * 设置配送员的 位置
     * @param int $deliverymanId
     * @param LngLat $location
     */
    public function setDeliverymanLocation(int $deliverymanId,LngLat $location){
        $res=app('redis')->hset(self::getDeliverymanCacheKey($deliverymanId),self::$LocationField,serialize($location));
        $res=app('redis')->hset(self::getDeliverymanCacheKey($deliverymanId),self::$LocationExpireField,time() +self::$LocationExpireTime);
    }




}
