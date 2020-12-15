<?php

/**
 *
 * @author: xaboy<365615158@qq.com>
 * @day: 2017/11/02
 */

namespace app\admin\model\distribution;

use app\enum\DeliveryEnum;
use app\models\OrderDelivery;
use common\services\JsonService;
use common\traits\ModelTrait;
use common\basic\BaseModel;
use app\models\delivery\DeliverymanWithdraw as DwModel;

/**
 * 配送员提现 Model
 * Class WechatNews
 * @package app\admin\model\wechat
 */
class DeliverymanWithdraw extends DwModel
{

    use ModelTrait;

    protected $pk = 'id';

    protected $name = 'deliveryman_withdraw';


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
    public function List( $page = 1, $limit = 10,$isPaid = null, $isRefused = null,  $deliverymanId = null, $createAt = [],$orderBy = 'id', $orderByType = "DESC")
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





    /**
     * 拒绝提现申请
     * @param int $withdrawId
     * @param string $refusedMark
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function refused(int $withdrawId, string $refusedMark = '')
    {

        $withdrawInfo = $this->where('id', $withdrawId)->find();
        if (empty($withdrawInfo)) {
            return JsonService::fail('提现记录不存在');
        }
        $deliveryIds = $withdrawInfo->getAttr('delivery_order_ids');
        $deliveryIds = json_decode($deliveryIds, true);
        $orderDeliveryModel = new \app\models\OrderDelivery();
        //更新提现的记录
        $withdrawInfo->setAttr('update_at', time());
        $withdrawInfo->setAttr('is_refused', 1);
        $withdrawInfo->setAttr('refused_mark', $refusedMark);

        BaseModel::beginTrans();
        $refusedUpdate = $withdrawInfo->save();

        //标记 为未申请提现
        $deliveryUpdate = $orderDeliveryModel->whereIn('id', implode(',', $deliveryIds))->update([
            'is_withdraw_apply' => 0,
            'update_time' => date('Y-m-d H:i:s')
        ]);

        BaseModel::checkTrans($deliveryIds && $refusedUpdate);
        if ($deliveryUpdate && $refusedUpdate) {
            return JsonService::success('已拒绝提现');
        } else {
            return JsonService::fail('拒绝提现失败');
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

    public function pay()
    {
        /*$app->transfer->toBalance([
            'partner_trade_no' => '1233455', // 商户订单号，需保持唯一性(只能是字母或者数字，不能包含有符号)
            'openid' => 'oxTWIuGaIt6gTKsQRLau2M0yL16E',
            'check_name' => 'FORCE_CHECK', // NO_CHECK：不校验真实姓名, FORCE_CHECK：强校验真实姓名
            're_user_name' => '王小帅', // 如果 check_name 设置为FORCE_CHECK，则必填用户真实姓名
            'amount' => 10000, // 企业付款金额，单位为分
            'desc' => '理赔', // 企业付款操作说明信息。必填
        ]);*/
    }
}