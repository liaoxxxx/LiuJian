<?php

namespace app\admin\controller\distribution;

use app\admin\controller\AuthController;
use app\admin\model\distribution\OrderDelivery;
use app\admin\model\order\StoreOrder;
use app\admin\model\order\StoreOrder as StoreOrderModel;
use app\admin\model\system\SystemStore;
use app\enum\DeliveryEnum;
use app\admin\model\distribution\Deliveryman;
use app\models\delivery\DeliverymanWithdraw;
use app\models\delivery\OrderDeliveryTrace;
use common\services\JsonService;
use common\services\JsonService as Json;
use common\services\UtilService as Util;
use think\facade\App;
use think\Facade\Session;

/**
 * 图文管理
 * Class WechatNews
 * @package app\admin\controller\wechat
 */
class Order extends AuthController
{

    /**
     * @var OrderDelivery
     */
    protected $deliveryModel = null;


    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->deliveryModel = new OrderDelivery();
    }

    /**
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        $deliveryStatus = DeliveryEnum::DELIVERY_STATUS_MAP;
        $deliveryStatusList = [];
        foreach ($deliveryStatus as $index => $item) {
            $deliveryStatusList[] = ['name' => $item, 'value' => $index];
        }


        //$deliverymanList=Deliveryman::getListByAdminBindStore($storeIds);

        $this->assign([
            'year' => getMonth(),
            'deliveryman' => $this->request->get('deliveryman', ''),
            'status' => $this->request->param('status', ''),
            'orderCount' => StoreOrderModel::orderCount(),
            'delivery_status' => $deliveryStatusList,
            'payTypeCount' => StoreOrderModel::payTypeCount(),
        ]);
        return $this->fetch();
    }

    /**
     * 获取订单列表
     * return json
     * @throws \think\db\exception\DbException
     */
    public function order_list()
    {
        $where = Util::getMore([
            ['data', 0],
            ['status', 0],
            ['excel', null],
            ['storeId', ''],
            ['deliverymanId', ''],
            ['order_id', 0],
            ['delivery_amount', []],
            ['page', 1],
            ['limit', 10],
        ]);
        JsonService::successlayui(OrderDelivery::orderDeliveryList($where));
    }

    /**
     * 订单操作轨迹记录
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function delivery_trace()
    {
        $orderId = $this->request->get('orderId', 0);
        if ($orderId == 0) {
            exit('错误的订单号');
        }
        $traceList = (new OrderDeliveryTrace())->with('deliveryman')
            ->where('order_id', $orderId)
            ->select();
        if ($traceList) {
            $statusMap = DeliveryEnum::DELIVERY_STATUS_MAP;
            foreach ($traceList as $index => &$item) {
                $item['delivery_step_str'] = isset($statusMap[$item['delivery_step']]) ? $statusMap[$item['delivery_step']] : '未知';
                $item['deliveryman_str'] = isset($item['deliveryman'])
                    ?
                    $item['deliveryman']['real_name'] . '/ 电话：' . $item['deliveryman']['phone'] : '未知';
            }
        }
        $this->assign(['traceList' => $traceList]);
        return $this->fetch();
    }


    /**
     * @param $id
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \Exception
     */
    public function editStatus($id)
    {
        $deliveryInfo = $this->deliveryModel->with(["store", 'deliveryman'])
            ->where('id', $id)
            ->find();
        if ($this->request->isPost()) {
            $post = $this->request->post();
            $deliveryInfo->setAttr('delivery_status', $post['delivery_status']);
            $res = $deliveryInfo->save();
            if ($res) {
                return app('json')->success('更改订单配送状态成功');
            } else {
                return app('json')->fail('更改订单配送状态失败');
            }
        }
        $deliveryStatus = DeliveryEnum::DELIVERY_STATUS_MAP;
        $deliveryStatusList = [];
        foreach ($deliveryStatus as $index => $item) {
            $deliveryStatusList[] = [
                'value' => $index,
                'label' => $item
            ];

        }
        if (empty($deliveryInfo)) {
            $this->exception('未找到订单配送信息');
        }
        $this->assign(compact('deliveryInfo', 'deliveryStatusList'));

        return $this->fetch();

    }

    /**
     *  指定 重新分配 配送员 去配送订单的商品
     *
     */
    public function reDelivery()
    {
        $orderId = $this->request->get('orderId', 0);
        $storeId = $this->request->get('storeId', 0);
        if ($orderId == 0 || $storeId == 0) {
            exit('错误的订单号或门店编号');
        }
        //查询订单 归属的 门店
        $existOrder = (new StoreOrder())->where('id', $orderId)->where('store_id', $storeId)->count();
        if ($existOrder) {
            exit('订单号不存在');
        }
        //门店
        $store = (new SystemStore())->where('id', $storeId)->find();
        //找到门店 下所有 在工作状态 的配送员
        $deliverymanList = (new Deliveryman())->alias('d')
            ->where('d.work_store_id', $storeId)
            ->where('d.status', 1)
            ->where('d.is_receiving', 1)
            ->select();
        //配送信息
        $orderDeliveryInfo = (new OrderDelivery())->with(["store", 'deliveryman'])->where('order_id', $orderId)->find();
        if (empty($deliverymanList)) {
            echo('<h2 class="text-center">该门店暂无正在工作的配送员!</h2>');
            exit();
        }
        $key = sysConfig('tengxun_map_key');
        if (!$key) return $this->failed('请前往设置->系统设置->物流配置 配置腾讯地图KEY', '#');
        $this->assign(compact('key'));
        $this->assign(
            [
                'deliverymanList' => $deliverymanList,
                'store' => $store,
                'orderDeliveryInfo' => $orderDeliveryInfo
            ]);
        $this->assign([]);
        return $this->fetch();
    }

    public function reDeliverySubmit()
    {
        $orderId = $this->request->post('orderId', 0);
        $storeId = $this->request->post('deliverymanId', 0);
        if ($orderId == 0 || $storeId == 0) {
            Json::successful('错误的订单号或配送员编号!');
            exit();
        }

        $updateRes = (new OrderDelivery())->reDelivery($orderId, $storeId);
    }


    public function delete()
    {
        $ids = util::postMore(['ids'])['ids'];

        if (!count($ids)) {
            return JsonService::fail('请选择需要删除的订单');
        }
        $count = OrderDelivery::where('is_delete', 0)->where('delivery_status', 'in', DeliveryEnum::DELiVERING_STATUS_MAP)->count();
        if ($count) {
            return JsonService::fail('您选择的的订单存在仍在配送中的订单，删除失败');
        }
        $res = OrderDelivery::where('id', 'in', $ids)->update(['is_delete' => 1]);
        if ($res)
            return JsonService::successful('删除成功');
        else
            return JsonService::fail('删除失败');
    }


}