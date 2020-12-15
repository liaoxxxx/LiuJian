<?php
namespace app\api\controller\store;


use app\models\store\StoreCouponIssue;
use app\models\store\StoreCouponList;
use app\models\store\StoreProduct;
use app\Request;
use common\services\UtilService;
use app\models\store\StoreCouponUser;

/**
 * 优惠券类
 * Class StoreCouponsController
 * @package app\api\controller\store
 */
class StoreCouponsController
{
    /**
     * 可领取优惠券列表
     * @param Request $request
     * @return mixed
     */
    public function lst(Request $request)
    {
        $data = UtilService::getMore([
            ['page',0],
            ['limit',0]
        ], $request);
        if (!$data['limit']) $data['limit'] = 10;
        return app('json')->successful(StoreCouponIssue::getIssueCouponList($request->uid(),$data['limit'],$data['page']));
    }

    /**
     * 领取优惠券
     *
     * @param Request $request
     * @return mixed
     */
    public function receive(Request $request)
    {
        list($couponId) = UtilService::getMore([['couponId',0]], $request, true);
        if(!$couponId || !is_numeric($couponId)) return app('json')->fail('参数错误!');
        if(StoreCouponIssue::issueUserCoupon($couponId,$request->uid())){
            return app('json')->successful('领取成功');
        }else{
            return app('json')->fail(StoreCouponIssue::getErrorInfo('领取失败!'));
        }
    }

    /**
     * 兑换优惠券
     *
     * @param Request $request
     * @return mixed
     */
    public function exchange(Request $request)
    {
        list($couponCode) = UtilService::postMore([['couponCode','']], $request, true);
        if(!$couponCode) return app('json')->fail('参数错误!');
        if(StoreCouponList::exchangeCoupon($couponCode,$request->uid())){
            return app('json')->successful('兑换成功');
        }else{
            return app('json')->fail(StoreCouponList::getErrorInfo('兑换失败!'));
        }
    }

    /**
     * 用户已领取优惠券
     * @param Request $request
     * @param $types
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function user(Request $request, $types)
    {
        switch ($types) {
            case 0:
                $list = StoreCouponUser::getUserAllCoupon($request->uid());
                break;
            case 1:
                $list = StoreCouponUser::getUserValidCoupon($request->uid());
                break;
            case 2:
                $list = StoreCouponUser::getUserAlreadyUsedCoupon($request->uid());
                break;
            default:
                $list = StoreCouponUser::getUserBeOverdueCoupon($request->uid());
                break;
        }
        foreach ($list as &$v) {
            $v['add_time'] = date('Y/m/d', $v['add_time']);
            $v['end_time'] = date('Y/m/d', $v['end_time']);
        }
        return app('json')->successful($list);
    }

    /**
     * 用户已领取优惠券
     * @param Request $request
     * @param $types
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
//    public function user(Request $request, $types)
//    {
//        switch ($types){
//            case 0:case '':
//                $list = StoreCouponList::getUserAllCoupon($request->uid());
//                break;
//            case 1:
//                $list = StoreCouponList::getUserValidCoupon($request->uid());
//                break;
//            case 2:
//                $list = StoreCouponList::getUserAlreadyUsedCoupon($request->uid());
//                break;
//            default:
//                $list = StoreCouponList::getUserBeOverdueCoupon($request->uid());
//                break;
//        }
//        foreach ($list as &$v){
//            $v['add_time'] = date('Y/m/d',$v['add_time']);
//            $v['expiry_time'] = date('Y/m/d',$v['expiry_time']);
//        }
//        return app('json')->successful($list);
//    }

    /**
     * 批量领取优惠券
     * @param Request $request
     * @return mixed
     */
    public function receive_batch(Request $request)
    {
        list($couponIds) = UtilService::postMore([
            ['couponId',[]],
        ], $request, true);
        if(!count($couponIds)) return app('json')->fail('参数错误');
        $couponIdsError = [];
        $count = 0;
        $msg = '';
        foreach ($couponIds as $key=>&$item){
            if(!StoreCouponIssue::issueUserCoupon($item,$request->uid())){
                $couponIdsError[$count]['id'] = $item;
                $couponIdsError[$count]['msg'] = StoreCouponIssue::getErrorInfo('领取失败');
            }else{
                $couponIdsError[$count]['id'] = $item;
                $couponIdsError[$count]['msg'] = '领取成功';
            }
            $count++;
        }
        foreach ($couponIdsError as $key=>&$value){
            $msg = $msg.StoreCouponIssue::getIssueCouponTitle($value['id']).','.$value['msg'];
        }
        return app('json')->fail($msg);
    }

    /**
     * 优惠券 订单获取
     * @param Request $request
     * @param $price
     * @return mixed
     */
    public function order(Request $request)
    {
        list($productId) = UtilService::postMore(['productId'], $request, true);
        if (!$productId) return app('json')->fail('参数错误!');
        //获取商品信息
        $productInfo = StoreProduct::getValidProducts($productId);

        return app('json')->successful(StoreCouponList::getUsableCouponList($request->uid(), $productInfo));
    }

    /**
     * 优惠券 订单获取
     * @param Request $request
     * @param $price
     * @return mixed
     */
//    public function order(Request $request, $price)
//    {
//        return app('json')->successful(StoreCouponUser::beUsableCouponList($request->uid(), $price));
//    }
}