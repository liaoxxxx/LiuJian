<?php

namespace app\admin\controller\distribution;

use app\admin\controller\AuthController;
use app\admin\model\order\StoreOrder as StoreOrderModel;
use app\admin\model\distribution\DeliverymanWithdraw;
use common\services\JsonService;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\App;

/**
 * 图文管理
 * Class WechatNews
 * @package app\admin\controller\wechat
 */
class Withdraw extends AuthController
{


    /**
     * @var DeliverymanWithdraw
     */
    protected $withdrawModel;


    public function __construct(App $app)
    {
        parent::__construct($app);

        $this->withdrawModel = new DeliverymanWithdraw();
    }

    /**
     * 提现列表
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function index()
    {
        $this->assign([
            'year' => getMonth(),
            'real_name' => $this->request->get('real_name', ''),
            'status' => $this->request->param('status', ''),
            'orderCount' => StoreOrderModel::orderCount(),
            'payTypeCount' => StoreOrderModel::payTypeCount(),
        ]);
        return $this->fetch();
    }

    /**
     *提现列表json
     *
     */
    public function withdraw_list()
    {
        $where = [];
        $date=$this->request->param("data");
        $deliverymanName= $this->request->param("real_name");
        $amount=$this->request->param('amount');
        $payStatus=$this->request->param('pay_status',null);
        $refusedStatus=$this->request->param('refused_status',null);
        $page = $this->request->param('page', 1);
        $limit = $this->request->param('limit', 10);



        try {
            $list = $this->withdrawModel->List($page, $limit,$payStatus,$refusedStatus);
            JsonService::successlayui($list['count'], $list['list']);
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }
    }

    /**
     * 提现的所有配送订单
     * @param int $withdrawId
     * @return string
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function order_list(int $withdrawId)
    {
        $withdrawInfo = $this->withdrawModel->where('id', $withdrawId)->find();
        if (empty($withdrawInfo)) {
            return JsonService::fail('提现记录不存在');
        }
        $deliverOrderIds=json_decode( $withdrawInfo['delivery_order_ids'],true);
        $orderDeliveryList = $this->withdrawModel->withdrawOrderList($deliverOrderIds);
        $this->assign(compact('orderDeliveryList','withdrawInfo'));
        return $this->fetch();
    }

    /**
     * 后台拒绝提现申请
     * @param int $withdrawId
     * @param string $refusedMark
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function refused(int $withdrawId, string $refusedMark = '')
    {
        $this->withdrawModel->refused($withdrawId,$refusedMark);
    }


    /**
     * 批量提现
     * @param string $withdrawIds 
     */
    public function pay(string $withdrawIds){

    }
    /**
     * 批量提现
     * @param string $withdrawIds
     */
    public function payMulity(string $withdrawIds){

    }
    
}