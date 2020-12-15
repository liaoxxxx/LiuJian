<?php

namespace app\delivery\controller\order;

use common\services\JsonService;
use app\delivery\controller\DeliveryBasic;
use app\enum\DeliveryEnum;
use common\repositories\OrderRepository;
use app\models\store\{StoreBargainUser,
    StoreCart,
    StoreOrder,
    StoreOrderCartInfo,
    StoreOrderStatus,
    StorePink,
    StoreProductReply
};
use app\delivery\model\StoreOrder as StoreOrderModel;
use app\Request;
use common\services\{
    CacheService,
    ExpressService,
    UtilService
};
use app\models\OrderDelivery;
use think\facade\App;

/**
 * 订单类
 * Class StoreOrderController
 * @package app\api\controller\order
 */
class StoreOrderController extends DeliveryBasic
{
    protected $field = 'id,order_id,uid,real_name,user_phone,user_address,freight_price,total_num,' .
    'total_price,total_postage,pay_price,pay_postage,paid,pay_time,pay_type,add_time,status,refund_status,' .
    'refund_reason_wap_explain,refund_reason,refund_price,delivery_name,delivery_id,delivery_man_id,mark,unique,' .
    'remark,mer_id,verify_code,store_id,shipping_type';


    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->storeOrderM = new StoreOrderModel();
    }

    protected $storeOrderM;


    /**
     * 获取订单
     * @param int $status
     * @param bool $isSelf
     * @param int $storeId
     * @param int $startTime
     * @param int $endTime
     * @param int $page
     * @param int $pageSize
     */
    public function getAll($status = -100, $isSelf = false, $storeId = 0, $startTime = 0, $endTime = 0, $page = 0, $pageSize = 10)
    {
        $orderModel = (new StoreOrder())
            //->field($this->field)
            ->with(['user', 'orderDelivery'])
            ->order('id', 'DESC');
        if ($isSelf) {
            $orderModel = $orderModel->where('delivery_man_id', $this->deliveryMan->id);
        }

        //过滤状态
        if ($status != -100) {
            $orderModel = $orderModel->where('status', $status);
        }
        //过滤门店
        if ($storeId) {
            $orderModel = $orderModel->where('store_id', $storeId);
        }
        //時間
        if ($startTime == 0) {
            $startTime = strtotime(date("Y-m-d 00:00:00"));
        } else {
            if (!is_numeric($startTime)) {  //不是時間戳
                $startTime = strtotime($startTime);
            }
        }
        if ($endTime == 0) {
            $endTime = strtotime(date("Y-m-d 23:59:59"));
        } else {
            if (!is_numeric($endTime)) {  //不是時間戳
                $endTime = strtotime($endTime);
            }
        }
        $orderModel = $orderModel->whereBetweenTime('add_time', $startTime, $endTime);
        //分頁
        if ($page) {
            $orderModel = $orderModel->limit($page - 1, $pageSize);
        } else {
            $orderModel = $orderModel->limit(0, $pageSize);
        }
        $orderList = $orderModel->select();
        foreach ($orderList as &$item) {
            $item['add_time_str'] = date("Y-m-d H:i:s", $item['add_time']);
            $item['pay_time_str'] = date("Y-m-d H:i:s", $item['pay_time']);
        }

        if (isset($orderList)) {
            //echo (count($orderList));die;
            return app('json')->success('获取订单成功', ['orderList' => $orderList]);
        }
        return JsonService::fail('获取订单失败');
    }

    /**
     * 获取订单详情
     * @param int $id
     * @param string $orderId
     * @param string $unique
     */
    public function orderDetail($id = 0, $orderId = null, $unique = null)
    {
        $orderModel = (new StoreOrder())->where('delivery_man_id', $this->deliveryMan->id)
            ->field($this->field)
            ->with(['user', 'orderDelivery']);
        $orderItem = [];
        if ($id !=0) {
            $orderItem = $orderModel->where('id', $id)->find();
        } elseif ($orderId) {

            $orderItem = $orderModel->where('order_id', $orderId)->find();
        } elseif ($unique) {
            $orderItem = $orderModel->where('unique', $unique)->find();
        } else {
            return JsonService::fail('未传入有效的参数');
        }

        //结果
        if (!empty($orderItem)) {
            return app('json')->successful('找到订单', ['orderDetail' => $orderItem]);
        } else {
            return JsonService::fail('未找到订单');
        }

    }

    /**
     * 订单操作处理  接单 | 配送 |完成 等操作
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \Exception
     */
    public function deliveryHandle(Request $request)
    {
        $option =$request->post('option','');
        $orderId =$request->post('orderId',0);
        $lng =$request->post('lng',0);
        $lat =$request->post('lat',0);

        //判断参数是否合理
        $this->catchDeliveryHandleInputError($option, $orderId, $lng, $lat);
        switch ($option) {
            case "ORDER_RECEIVING":
                $this->storeOrderM->receiving($orderId, $this->deliveryMan->id, $lng, $lat);
                break;
            case "ARRIVE_STORE":
                $this->storeOrderM->arriveStore($orderId, $this->deliveryMan->id, $lng, $lat);
                break;
            case "OUTPUT_STORAGE":
                $this->storeOrderM->outputStorage($orderId, $this->deliveryMan->id, $lng, $lat);
                break;
            case "DELIVERY_BEGIN":
                $this->storeOrderM->deliveryBegin($orderId, $this->deliveryMan->id, $lng, $lat);
                break;
            case "ARRIVE_DESTINATION":
                $this->storeOrderM->arriveDestination($orderId, $this->deliveryMan->id, $lng, $lat);
                break;
            case "DELIVERY_COMPLETE":
                $this->storeOrderM->deliveryComplete($orderId, $this->deliveryMan->id, $lng, $lat);
                break;
            case "DELIVERY_CANCEL":
                $this->storeOrderM->deliveryCancel($orderId, $this->deliveryMan->id, $lng, $lat);
                break;
        }
        return app('json')->successful('操作成功');
    }


    /**判断 提交的操作步骤是否重复
     * @param $orderDeliveryStatus
     * @param $optionDeliveryStatus
     * @param null $msg
     */
    public function catchRepetitiveOperation($orderDeliveryStatus,$optionDeliveryStatus,$msg=null){
        if ($orderDeliveryStatus==$optionDeliveryStatus){
            if ($msg==null){
                return JsonService::fail('请勿提交重复的操作步骤');
            }
            return JsonService::fail($msg);

        }
    }


    /**
     * @param string $option
     * @param int $orderId
     * @param float $lng
     * @param float $lat
     * @param array $orderInfo
     * @return mixed
     * @throws \Exception
     */
    //catch  订单操作处理  接单 | 配送 |完成 等操作 输入错误
    protected function catchDeliveryHandleInputError(string $option, int $orderId, float $lng, float $lat)
    {
        if (!isset(DeliveryEnum::DELIVERY_STEPS_MAP[$option])) {
            return JsonService::fail('参数错误，option参数丢失或操作不合法');
        }
        if (empty($orderId)) {
            return JsonService::fail('参数错误，order参数丢失');
        }
        if (empty($lng)) {
            return JsonService::fail('参数错误，lng参数丢失');
        }
        if (empty($lat)) {
            return JsonService::fail('参数错误，lat参数丢失');
        }
        //判断订单 是否存在
        $orderInfo=$this->storeOrderM->field('id,delivery_man_id')->where('id',$orderId)->find()->toArray();
        if (empty($orderInfo)){
            return JsonService::fail('订单不存在');
        }
        //除了接单操作都应该判断是否有操作该订单的权限
        if ($option=='ORDER_RECEIVING' && $orderInfo['delivery_man_id'] !=$this->deliveryMan->id){
            return JsonService::fail('处理失败，不能操作不属于您配送的订单');
        }
        if ($option!='ORDER_RECEIVING'){
            //查找配送信息
            $deliveryInfo=(new OrderDelivery())->where('order_id',$orderId)->find();
            $this->catchRepetitiveOperation(
                $deliveryInfo['delivery_status'],
                DeliveryEnum::DELIVERY_STEPS_MAP[$option],
                '请勿提交重复的操作订单步骤'
            );
        }
    }


    /**
     * 计算订单金额
     * @param Request $request
     * @param $key
     * @return mixed
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function computedOrder(Request $request, $key)
    {

//        $priceGroup = StoreOrder::getOrderPriceGroup($cartInfo);
        if (!$key) return JsonService::fail('参数错误!');
        $uid = $request->uid();
        if (StoreOrder::be(['order_id|unique' => $key, 'uid' => $uid, 'is_del' => 0]))
            return app('json')->status('extend_order', '订单已生成', ['orderId' => $key, 'key' => $key]);
        list($addressId, $couponId, $payType, $useIntegral, $mark, $combinationId, $pinkId, $seckill_id, $formId, $bargainId, $shipping_type) = UtilService::postMore([
            'addressId', 'couponId', ['payType', 'yue'], ['useIntegral', 0], 'mark', ['combinationId', 0], ['pinkId', 0], ['seckill_id', 0], ['formId', ''], ['bargainId', ''],
            ['shipping_type', 1],
        ], $request, true);
        $payType = strtolower($payType);
        if ($bargainId) {
            $bargainUserTableId = StoreBargainUser::getBargainUserTableId($bargainId, $uid);//TODO 获取用户参与砍价表编号
            if (!$bargainUserTableId)
                return JsonService::fail('砍价失败');
            $status = StoreBargainUser::getBargainUserStatusEnd($bargainUserTableId);
            if ($status == 3)
                return JsonService::fail('砍价已支付');
            StoreBargainUser::setBargainUserStatus($bargainId, $uid); //修改砍价状态
        }
        if ($pinkId) {
            if (StorePink::getIsPinkUid($pinkId, $request->uid()))
                return app('json')->status('ORDER_EXIST', '订单生成失败，你已经在该团内不能再参加了', ['orderId' => StoreOrder::getStoreIdPink($pinkId, $request->uid())]);
            if (StoreOrder::getIsOrderPink($pinkId, $request->uid()))
                return app('json')->status('ORDER_EXIST', '订单生成失败，你已经参加该团了，请先支付订单', ['orderId' => StoreOrder::getStoreIdPink($pinkId, $request->uid())]);
        }
        $priceGroup = StoreOrder::cacheKeyCreateOrder($request->uid(), $key, $addressId, $payType, (int)$useIntegral, $couponId, $mark, $combinationId, $pinkId, $seckill_id, $bargainId, true, 0, $shipping_type);
        if ($priceGroup)
            return app('json')->status('NONE', 'ok', $priceGroup);
        else
            return JsonService::fail(StoreOrder::getErrorInfo('计算失败'));
    }


    /**
     * 订单列表
     * @param Request $request
     * @return mixed
     */
    public function list(Request $request)
    {
        list($type, $page, $limit, $search) = UtilService::getMore([
            ['type', ''],
            ['page', 0],
            ['limit', ''],
            ['search', ''],
        ], $request, true);
        return app('json')->successful(StoreOrder::getUserOrderSearchList($request->uid(), $type, $page, $limit, $search));
    }


    /**
     * 订单 查看物流
     * @param Request $request
     * @param $uni
     * @return mixed
     */
    public function express(Request $request, $uni)
    {
        if (!$uni || !($order = StoreOrder::getUserOrderDetail($request->uid(), $uni))) return JsonService::fail('查询订单不存在!');
        if ($order['delivery_type'] != 'express' || !$order['delivery_id']) return JsonService::fail('该订单不存在快递单号!');
        $cacheName = $uni . $order['delivery_id'];
        $result = CacheService::get($cacheName, null);
        if ($result === NULL) {
            $result = ExpressService::query($order['delivery_id']);
            if (is_array($result) &&
                isset($result['result']) &&
                isset($result['result']['deliverystatus']) &&
                $result['result']['deliverystatus'] >= 3)
                $cacheTime = 0;
            else
                $cacheTime = 1800;
            CacheService::set($cacheName, $result, $cacheTime);
        }
        $orderInfo = [];
        $cartInfo = StoreOrderCartInfo::where('oid', $order['id'])->column('cart_info', 'unique') ?? [];
        $info = [];
        $cartNew = [];
        foreach ($cartInfo as $k => $cart) {
            $cart = json_decode($cart, true);
            $cartNew['cart_num'] = $cart['cart_num'];
            $cartNew['truePrice'] = $cart['truePrice'];
            $cartNew['productInfo']['image'] = $cart['productInfo']['image'];
            $cartNew['productInfo']['store_name'] = $cart['productInfo']['store_name'];
            $cartNew['productInfo']['unit_name'] = $cart['productInfo']['unit_name'] ?? '';
            array_push($info, $cartNew);
            unset($cart);
        }
        $orderInfo['delivery_id'] = $order['delivery_id'];
        $orderInfo['delivery_name'] = $order['delivery_name'];
        $orderInfo['delivery_type'] = $order['delivery_type'];
        $orderInfo['cartInfo'] = $info;
        return app('json')->successful(['order' => $orderInfo, 'express' => $result ? $result : []]);
    }

    /**
     * 订单评价
     * @param Request $request
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function comment(Request $request)
    {
        $group = UtilService::postMore([
            ['unique', ''], ['comment', ''], ['pics', ''], ['product_score', 5], ['service_score', 5]
        ], $request);
        $unique = $group['unique'];
        unset($group['unique']);
        if (!$unique) return JsonService::fail('参数错误!');
        $cartInfo = StoreOrderCartInfo::where('unique', $unique)->find();
        $uid = $request->uid();
        if (!$cartInfo) return JsonService::fail('评价产品不存在!');
        $orderUid = StoreOrder::getOrderInfo($cartInfo['oid'], 'uid')['uid'];
        if ($uid != $orderUid) return JsonService::fail('评价产品不存在!');
        if (StoreProductReply::be(['oid' => $cartInfo['oid'], 'unique' => $unique]))
            return JsonService::fail('该产品已评价!');
        $group['comment'] = htmlspecialchars(trim($group['comment']));
        if ($group['product_score'] < 1) return JsonService::fail('请为产品评分');
        else if ($group['service_score'] < 1) return JsonService::fail('请为商家服务评分');
        if ($cartInfo['cart_info']['combination_id']) $productId = $cartInfo['cart_info']['product_id'];
        else if ($cartInfo['cart_info']['seckill_id']) $productId = $cartInfo['cart_info']['product_id'];
        else if ($cartInfo['cart_info']['bargain_id']) $productId = $cartInfo['cart_info']['product_id'];
        else $productId = $cartInfo['product_id'];
        if ($group['pics']) $group['pics'] = json_encode(is_array($group['pics']) ? $group['pics'] : explode(',', $group['pics']));
        $group = array_merge($group, [
            'uid' => $uid,
            'oid' => $cartInfo['oid'],
            'unique' => $unique,
            'product_id' => $productId,
            'add_time' => time(),
            'reply_type' => 'product'
        ]);
        StoreProductReply::beginTrans();
        $res = StoreProductReply::reply($group, 'product');
        if (!$res) {
            StoreProductReply::rollbackTrans();
            return JsonService::fail('评价失败!');
        }
        try {
            StoreOrder::checkOrderOver($cartInfo['oid']);
        } catch (\Exception $e) {
            StoreProductReply::rollbackTrans();
            return JsonService::fail($e->getMessage());
        }
        StoreProductReply::commitTrans();
        event('UserCommented', $res);
        event('AdminNewPush');
        return app('json')->successful();
    }

    /**
     * 订单统计数据
     * @param Request $request
     * @return mixed
     */
    public function data(Request $request)
    {
        return app('json')->successful(StoreOrder::getOrderData($request->uid()));
    }


    /**
     * 门店核销
     * @param Request $request
     */
    public function order_verific(Request $request)
    {
        list($verify_code, $is_confirm) = UtilService::postMore([
            ['verify_code', ''],
            ['is_confirm', 0]
        ], $request, true);
        if (!$verify_code) return JsonService::fail('缺少核销码');
        $orderInfo = StoreOrder::where('verify_code', $verify_code)->where('paid', 1)->where('refund_status', 0)->find();
        if (!$orderInfo) return JsonService::fail('核销的订单不存在或未支付或已退款');
        if ($orderInfo->status > 0) return JsonService::fail('订单已经核销');
        if ($orderInfo->combination_id && $orderInfo->pink_id) {
            $res = StorePink::where('id', $orderInfo->pink_id)->where('status', '<>', 2)->count();
            if ($res) return JsonService::fail('拼团订单暂未成功无法核销！');
        }
        if (!$is_confirm) {
            $orderInfo['image'] = StoreCart::getProductImage($orderInfo->cart_id);
            return app('json')->success($orderInfo->toArray());
        }
        StoreOrder::beginTrans();
        try {
            $orderInfo->status = 2;
            if ($orderInfo->save()) {
                OrderRepository::storeProductOrderTakeDeliveryAdmin($orderInfo);
                StoreOrderStatus::status($orderInfo->id, 'take_delivery', '已核销');
                event('ShortMssageSend', [$orderInfo['order_id'], 'Receiving']);
                StoreOrder::commitTrans();
                return app('json')->success('核销成功');
            } else {
                StoreOrder::rollbackTrans();
                return JsonService::fail('核销失败');
            }
        } catch (\PDOException $e) {
            StoreOrder::rollbackTrans();
            return JsonService::fail($e->getMessage());
        }

    }

}