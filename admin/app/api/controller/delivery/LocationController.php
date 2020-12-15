<?php


namespace app\api\controller\delivery;

use app\models\OrderDelivery;
use app\Request;
use common\services\DeliveryService;
use common\utils\Redis;


/**微信小程序授权类
 * Class AuthController
 * @package app\api\controller
 */

class LocationController
{


    /**
     * 通过orderId 获取配送员的 当前位置
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getLocation(Request $request)
    {

        $orderId=$request->param('orderId',null);
        if ($orderId==null) return app('json')->fail("参数错误");
        $deliverOrder=(new OrderDelivery)->getByOrderId($orderId);
        $deliverymanId= $deliverOrder['deliveryman_id'];

        if ($deliverOrder==null || $deliverymanId==0 || $deliverymanId ==null)  return app('json')->fail("该订单还没有开始配送");

        $deliverySvc=new DeliveryService();

        $location= $deliverySvc->getDeliverymanLocation($deliverymanId);
       var_dump($location); die;

    }
}