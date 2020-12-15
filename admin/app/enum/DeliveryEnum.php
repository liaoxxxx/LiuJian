<?php


namespace app\enum;


/**
 *
 * 用于配置 配送端 及相关的 枚举常量
 * Class DeliveryEnum
 * @package app\enum
 */
class DeliveryEnum
{


    /**
     * 配送状态
     */
    const  DELIVERY_STATUS_MAP = [
        -10 => '呼叫失败',
        -20 => '配送异常',
        -30 => '取消失败',
        -40 => '分配超时',
        -50 => '取件超时',
        -60 => '配送异常',
        -70 => '取消配送',
        -80 => '等待重新配送',

        0 => '待接单',
        01 => '已接单',

        10 => '已到店',
        11 => '扫码出仓',

        20 => '开始配送',
        21 => '配送中',
        22 => '到达目的地',

        30 => '已收件',
        31 => '确认收货',
        32 => '客户已评价',

        40 => '系统确认完成'

    ];

    /**
     * 商品配送 取消的原因
     */
    const ORDER_DELIVERY_CANCEL_REASON_MAP = [
        101 => '超时未接单',
        102 => '地址无法配送',
        103 => '商品售罄',
        104 => '重复订单',
        105 => '联系不上用户',
        106 => '门店繁忙',
        107 => '门店错送漏送',
        108 => '门店休息中',
        109 => '商品受损',
        201 => '用户取消',
        202 => '用户测试',
        203 => '支付超时',
        204 => '用户下错单',
        301 => '配送延迟',
        302 => '配送异常',
        401 => '系统异常',
        402 => '调试或测试单',
        403 => '平台取消',
        501 => '其它原因', //,（选其他时需要传描述）
    ];


    /**
     * //配送员的操作 映射
     */
    const DELIVERY_STEPS_MAP = [
        'ORDER_RECEIVING' => '01',  //接单

        'ARRIVE_STORE' => '10',  //已到店,
        "OUTPUT_STORAGE" => '11', //'扫码出仓'

        'DELIVERY_BEGIN' => '20',  // '开始配送',
        //21 => '配送中',
        'ARRIVE_DESTINATION' => '22',//'到达目的地',

        'DELIVERY_COMPLETE' => '30',  //'派送完成 客户已收件',

        'DELIVERY_CANCEL' => '-80'  //取消配送,等待重新配送
    ];

    /**
     * 配送中状态 映射
     */
    const DELiVERING_STATUS_MAP = [
        -80 => '等待重新配送',

        0 => '待接单',
        01 => '已接单',

        10 => '已到店',
        11 => '扫码出仓',

        20 => '开始配送',
        21 => '配送中',
        22 => '到达目的地',

        30 => '已收件',
        31 => '确认收货',
        32 => '客户已评价',
    ];


    /**
     * 配送订单可以提现 的映射
     */
    const ENABLE_WITHDRAW_MAP = [
        'is_withdraw_finish' => 0,
        'is_withdraw_apply' => 0,
        'is_delete' => 0,
    ];


    /**
     * 配送订单 报警信息 的映射
     */
    const DELIVERY_WARRING_MAP = [
        'temporary_user_address' => '临时性的用户地址',
        'not_select_store' => '未选择门店',
        'out_all_store_range' => '选择的地址超出所有门店的配送距离,需要处理',
        "abnormal_router_distance" => "配送(导航)距离异常"
    ];

    /**
     *  存储 总店的 key
     */
    const HEADER_STORE_KEY="HEADER_STORE_KEY";


}