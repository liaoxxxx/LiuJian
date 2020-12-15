<?php


namespace app\delivery\controller;


use app\admin\model\store\StoreDeliveryConfig;
use app\models\OrderDelivery;
use app\models\user\UserAddress;
use common\services\DeliveryService;
use common\services\HttpService;
use common\services\MapService;
use common\services\TmapService;
use common\basic\BaseController;
use common\utils\LngLat;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;

/**
 * 微信公众号
 * Class TestController
 * @package app\api\controller\wechat
 */
class TestController extends BaseController
{


    /**
     * 测试
     * @return void
     */
    public function index()
    {

       // $deliveryConfig=StoreDeliveryConfig::get(14);
       // $deliveryAmount= DeliveryService::calculateDeliverAmount($deliveryConfig,10,85);


         //DeliveryService::sendDeliveryNotice(2);
        $addressInfo=UserAddress::find(2);


        $orderDeliveryRes = (int) (new OrderDelivery())->createRow('wx159974961666964209', 0, $addressInfo, []);

        var_dump($orderDeliveryRes);die;
    }



}