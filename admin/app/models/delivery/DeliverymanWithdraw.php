<?php

/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/11/02
 */

namespace app\models\delivery;

use app\enum\DeliveryEnum;
use app\models\OrderDelivery;
use common\services\JsonService;
use common\traits\ModelTrait;
use common\basic\BaseModel;

/**
 * 配送员提现 Model
 * Class WechatNews
 * @package app\admin\model\wechat
 */
class DeliverymanWithdraw extends BaseModel
{

    use ModelTrait;

    protected $pk = 'id';

    protected $name = 'deliveryman_withdraw';


    /**
     * 关联
     * @return \think\model\relation\HasOne
     */
    public function deliveryman()
    {
        return $this->hasOne(Deliveryman::class, 'id', 'deliveryman_id');
    }

    /**
     * @param $value
     * @return false|string
     */
    public function getPayTimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }


    /**
     * @param $value
     * @return false|string
     */
    public function getCreateAtAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * @param $value
     * @return false|string
     */
    public function getUpdateAtAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }


    /**
     * @param int $page
     * @param int $limit
     * @param null $isPaid
     * @param null $isRefused
     * @param null $deliverymanId
     * @param array $createAt
     * @param string $orderBy
     * @param string $orderByType
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getList( $page = 1, $limit = 10,$isPaid = null, $isRefused = null,  $deliverymanId = null, $createAt = [],$orderBy = 'id', $orderByType = "DESC")
    {
        $self=new self();
        //合并查询 map
        if ($deliverymanId != null) {
            $self= $self->where('deliveryman_id', $deliverymanId);
        }else{
            $self= $self->with(['deliveryman']);
        }
        //已支付
        if (isset($isPaid) && $isPaid != null) {
            $self= $self->where('is_paid', $isPaid);
        }
        //被拒绝
        if (isset($isRefused) && $isRefused != null) {
            $self= $self->where('is_refused', $isRefused);
        }
        //创建时间
        if (isset($createAt) && count($createAt) == 2) {
            $self= $self->where('create_at', 'between', $createAt);
        }
        $count = $self->count();
        $list =  $self ->limit(($page - 1) * $limit, $limit)
            ->order($orderBy, $orderByType)
            ->select();

        $list = collect($list)->toArray();
        foreach ($list as $index => &$item) {
            $item['is_paid_str'] = $item['is_paid'] ? '已支付' : '未支付';
            $item['is_refused_str'] = $item['is_refused'] ? '已拒绝' : '未拒绝';
        }
        return compact('count', 'list');
    }


    /***
     *  添加申请提现
     * @param array $orderIdList | 申请提现的订单数组
     * @param int $deliverymanId |
     */
    public function apply(array $orderIdList, int $deliverymanId)
    {
        //1.找到所有订单的配送信息
        $orderDeliveryModel = new OrderDelivery();

        $orderDeliveryModel = $orderDeliveryModel->where('deliveryman_id', $deliverymanId)
            ->whereIn('id', $orderIdList)
            ->where('deliveryman_id', $deliverymanId)
            ->where(DeliveryEnum::ENABLE_WITHDRAW_MAP);


        //2.可提现金额
        $withdrawAmount = $orderDeliveryModel->sum('delivery_amount');
        ///可提现配送订单的信息
        $orderDeliveryIdList = $orderDeliveryModel->field('id')->select()->toArray();
        $orderDeliveryIdsTemp = array_column($orderDeliveryIdList, 'id');
        if ($withdrawAmount > 0) {

            BaseModel::beginTrans();
            //3.保存
            try {
                $insertWithdraw = $this->insertGetId([
                    'deliveryman_id' => $deliverymanId,
                    'delivery_order_ids' => json_encode($orderDeliveryIdsTemp),
                    'withdraw_amount' => $withdrawAmount,
                    'create_at' => time(),
                    'update_at' => time(),
                ]);

                //4.标记 配送订单 已经正在申请提现状态
                $updateRes = OrderDelivery::whereIn('id', implode(',', $orderDeliveryIdsTemp))->update([
                    'is_withdraw_apply' => 1,
                    'update_time' => date('Y-m-d H:i:s')
                ]);
                // echo json_encode( $updateRes = $orderDeliveryModel->whereIn('id', implode(',', $orderDeliveryIdsTemp))->select());die;
            } catch (\Exception $e) {
                BaseModel::rollbackTrans();
                return JsonService::fail($e->getMessage());
            }

            BaseModel::checkTrans($insertWithdraw && $updateRes);
            //5.返回
            if ($insertWithdraw && $updateRes) {
                return JsonService::success('申请成功', ['deliveryOrderIdList' => $orderDeliveryIdsTemp]);
            } else {
                return JsonService::fail('操作失败', ['deliveryOrderIdList' => $orderIdList]);
            }
        } else {
            return JsonService::fail('申请的配送订单未包含可以提现的订单', ['deliveryOrderIdList' => $orderIdList]);
        }


    }




    /**
     * @param int $withdrawId
     * @param int $deliverymanId
     */
    public function detail(int $withdrawId, int $deliverymanId = 0)
    {
        $map = ['id', $withdrawId];
        if ($deliverymanId != 0) {
            $map = array_merge($map, ['deliveryman_id' => $deliverymanId]);
        }

        $withdrawInfo = $this->where($map)->find()->toArray();
    }

    /**
     * @param array $deliverOrderIds
     * @return \think\Collection|void |array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function withdrawOrderList(array $deliverOrderIds)
    {
        $orderDeliveryModel = new \app\models\OrderDelivery();
        return $orderDeliveryModel->whereIn('id', implode(',', $deliverOrderIds))->select();
    }


}