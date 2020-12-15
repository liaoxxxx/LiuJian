<?php

namespace app\delivery\controller\finance;

use app\delivery\controller\DeliveryBasic;
use app\models\delivery\DeliverymanWithdraw;
use common\services\JsonService;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\App;
use think\Request;

/**
 * 配送员提现
 * Class UserController
 * @package app\api\controller\store
 */
class WithdrawController extends DeliveryBasic
{

    protected $withdrawModel;

    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->withdrawModel = new DeliverymanWithdraw();
    }


    /**
     * 配送员发起
     * @param Request $request
     * @return void
     */
    public function apply(Request $request)
    {
        $orderIdList = $request->param('deliveryOrderIdList', []);
        if (!is_array($orderIdList) || count($orderIdList) == 0) {
            return JsonService::fail('参数错误：orderIdList');
        }
        try {
            $this->withdrawModel->apply($orderIdList, $this->deliveryMan->id);
        } catch (DataNotFoundException $e) {
        } catch (ModelNotFoundException $e) {
        } catch (DbException $e) {
        }


    }

    /**
     *提现列表json
     * @return void
     * @throws DataNotFoundException
     * @throws DbException
     * @throws ModelNotFoundException
     */
    public function list()
    {
        $isPaid = $this->request->param('is_paid', null);
        $isRefused = $this->request->param('is_refused', null);
        $createAtStart = $this->request->param('create_at_start', null);
        $createAtEnd = $this->request->param('create_at_end', null);
        $page = $this->request->param('page', 1);
        $limit = $this->request->param('limit', 10);
        $list = $this->withdrawModel->getList($page, $limit, $isPaid, $isRefused, $this->deliveryMan->id,[$createAtStart,$createAtEnd]);
        return JsonService::success('获取提现列表成功', [
            'count' => $list['count'],
            'list' => $list['list']
        ]);

    }

    /**
     *提现详情json
     */
    public function detail()
    {
        $withdrawId = $this->request->param('withdrawId', null);
        if (!is_numeric($withdrawId)) {
            return JsonService::fail('参数:withdrawId格式错误');
        }
        $withdrawInfo = $this->withdrawModel->where('id', $withdrawId)->find();
        if (empty($withdrawInfo)) {
            return JsonService::fail('提现记录不存在');
        }
        $deliverOrderIds = json_decode($withdrawInfo['delivery_order_ids'], true);
        try {
            $list = $this->withdrawModel->withdrawOrderList($deliverOrderIds);
            JsonService::success('获取提现详情成功', [
                'withdrawInfo' => $withdrawInfo,
                'deliveryOrderList' => $list
            ]);
        } catch (DataNotFoundException $e) {
            return JsonService::fail('提现记录不存在');
        } catch (DbException $e) {
            return JsonService::fail('操作失败');
        }
    }


}