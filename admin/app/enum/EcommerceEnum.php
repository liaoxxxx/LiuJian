<?php


namespace app\enum;


/**
 *
 * 用于配置 商城端 及相关的 枚举常量
 * Class EcommerceEnum
 * @package app\enum
 */
class EcommerceEnum
{
    /**
     * 订单状态
     */
    const  ORDER_STATUS_MAP = [
        10 => '待付款',
        20 => '已支付待发货',
        30 => '已发货待收货',
        40 => '已收货,待评价',
        50 => '已完成',
        60 => '退款成功',

        0 => '已取消',
        -20 => '退款申请中',

        90=>'已向第三方提交退款请求、等待回调中',
        91=>'第三方退款失败',

    ];

    /**
     * 优惠券类型
     */
    const  COUPON_TYPE_MAP = [
        0 => '无门槛',
        1 => '满减',
    ];


    /**
     * 优惠券使用范围类型
     */
    const  PRODUCT_RANGE_TYPE_MAP = [
        0 => '全部商品',
        1 => '指定分类',
        2 => '指定商品',
    ];
}