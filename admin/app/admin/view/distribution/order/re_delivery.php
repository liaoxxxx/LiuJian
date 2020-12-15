<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <link href="/system/frame/css/bootstrap.min.css?v=3.4.0" rel="stylesheet">
    <link href="/system/frame/css/style.min.css?v=3.0.0" rel="stylesheet">
    <link href="/system/css/layui-admin.css" rel="stylesheet">
    <link href="/system/css/layui.css" rel="stylesheet">
    <link href="/static/plug/layer/theme/default/layer.css" rel="stylesheet">
    <link href="/static/plug/layui/css/layui.css" rel="stylesheet">
    <link href="/static/plug/layui2/css/layui.css" rel="stylesheet">
    <script src="{__FRAME_PATH}js/jquery.min.js"></script>
    <script src="/system/frame/js/bootstrap.min.js"></script>
    <script src="/static/plug/layui/layui.all.js"></script>
    <script src="/system/js/layuiList.js"></script>

    <title>{$title|default=''}</title>
    <style>

        /********************** 地图 **********************/
        #map-container {
            background-color: #323131;
            width: 75%;
            float: right;
        }

        #map-block {
            padding-right: 15px;
            padding-left: 15px;
            border-radius: 10px;
        }


        #deliveryman-select {
            width: 200px;
            height: 50px;
        }

        #order-info-panel {
            display: none;
            padding: 10px;
            text-align: center;
            position: absolute;
            top: 20px;
            left: 20px;
            width: auto;
            color: white;
            height: 120px;
            background-color: rgba(255, 106, 74, 0.8);
            border-radius: 10px;
        }


        #rider-info-panel {
            display: none;

            padding: 10px;
            text-align: center;
            position: absolute;
            top: 20px;
            left: 20px;
            width: 300px;
            height: auto;
            background-color: rgba(98, 155, 255, 0.8);
            border-radius: 10px;
            color: white;
        }


        /* 订单 地图标注 */

        .store-icon {
            background-color: #fff;
            width: 0;
            height: 0;
            display: block;
            text-align: center;
            border-radius: 5px;
            color: black;
            padding: 2px;
            box-shadow: 10px 10px 5px rgba(150, 150, 150, 0.8);
            position: relative;
            left: -40px;
            top: -20px;
        }

        .store-icon img {
            width: 50px;
            height: 50px;
        }


        .customer-icon {
            background-color: rgba(150, 150, 150, 0.8);
            width: 0;
            height: 0;
            display: block;
            text-align: center;
            border-radius: 5px;
            color: black;
            padding: 2px;
            box-shadow: 10px 10px 5px rgba(150, 150, 150, 0.8);
            position: relative;
            left: -30px;
            top: -18px;
        }

        .customer-icon img {
            width: 50px;
            height: 50px;
        }


        /* 骑手 地图标注 */
        .rider-circle {
            background-color: rgba(150, 150, 150, 0.8);
            width: 0;
            height: 0;
            display: block;
            text-align: center;
            border-radius: 5px;
            color: black;
            padding: 2px;
            box-shadow: 10px 10px 5px rgba(33, 33, 33, 0.8);
            position: relative;
            left: -60px;
            top: -50px;
        }

        .rider-circle img {
            width: 50px;
            height: 50px;
        }

        .rider-circle:hover {
            zoom: 1;
            background-color: #d5c345;
            transform: scale(1.2);
            z-index: 200
        }


        /********************  选择框 ******************/

        .deliveryman-select-panel{
            overflow: hidden;
            float: left;
            margin-bottom: 20px;
            width: 25%;
            height: 100%;
            text-align: center;
        }


        #deliverymanId{
            display: none;
        }



        .delivery-block{
            margin: 50px auto;
        }
        .deliveryman-avatar {
            overflow: hidden;
            width: 80px;
            height: 80px;
            border-radius: 40px;
            text-align: center;
            margin: 0 auto;
            border: 2px solid grey;
        }
        .deliveryman-avatar img{
            margin: 0 auto;
            border: 2px solid whitesmoke;
        }
        .delivery-infos{
            line-height: 42px;
            margin: 30px auto;
        }

        .submit-block{
            width: 100%;
        }
        
        .user-name{
            background-color: #afadad;
            width: 80%;
            margin: 30px auto;
            color: white;
            border-radius: 5px;
        }
        .phone{
            background-color: #afadad;
            width: 80%;
            margin: 20px auto;
            color: white;
            border-radius: 5px;
        }


    </style>
</head>
<body>
<div>
    <blockquote class="layui-elem-quote layui-text">
        点击配送员图标，分配配送任务
    </blockquote>

</div>

<div class="deliveryman-select-panel">
    <input type="text" id="deliverymanId"  class="deliverymanId">
    <div class="delivery-block" style="">
        {if $orderDeliveryInfo.deliveryman}
        <div class="deliveryman-avatar" style="">
            <img src="{:$orderDeliveryInfo.deliveryman.avatar}" width="80" width="80" alt="">
        </div>
        <div class="delivery-infos">
            <p class="user-name">姓名:{:$orderDeliveryInfo.deliveryman.real_name}</p>
            <p class="phone">电话:{:$orderDeliveryInfo.deliveryman.phone}</p>
        </div>
        {else /}
        <div class="deliveryman-avatar">
            <img  src="/system/images/unknown.png" width="80" width="80" alt="">
        </div>
        <div class="delivery-infos">
            <p class="user-name">姓名: --</p>
            <p class="phone">电话: --</p>
        </div>
        {/if}
    </div>
    <div class="submit-block" >
        <button type="button" class="layui-btn layui-btn-normal" id="submit">提交</button>
    </div>
</div>
<div id="map-block">
    <div id="map-container"></div>
</div>
<script charset="utf-8" src="https://map.qq.com/api/js?v=2.exp&key={$key}"></script>
<script>

    let mapContainer = document.getElementById("map-container")
    $('#map-container').css('height', $(window).height() * 1);
    //去掉腾讯地图logo等小部件
    $("#map-container").bind("DOMNodeInserted", function (e) {
        let length = $("#map-container div:first-child").children('div').length;
        $("#map-container > div > div").not(":first").remove();
    });


    //加载地图
    var map = new qq.maps.Map(mapContainer, {
        center: new qq.maps.LatLng('{$store.latitude}', '{$store.longitude}'),  //中心点
        zoom: 13
    });
    //骑手数据
    window.deliverymanList = JSON.parse('{:json_encode($deliverymanList)}');

    loadRiderIconOnMap(window.deliverymanList)
    loadStoreIcon()
    loadUserIcon()


    window.onload = onInit


    //---------------------------------  方法   ---------------------------------------
    //加载门店的图标
    function loadStoreIcon() {
        var centerR = new qq.maps.LatLng('{$store.latitude}', '{$store.longitude}');
        var iconHtml = '<div class="store-icon fa fa-shopping-cart">' + '<img src="/system/images/store.png" title="{$store.name}" lt="{$store.name}">' + '</div>'
        var label = new qq.maps.Label({
            position: centerR,
            map: map,
            content: iconHtml
        });
    }

    //在地图上加载骑手 图标
    function loadRiderIconOnMap(riderInfoArr) {
        // 骑手图标
        for (let i = 0; i < riderInfoArr.length; i++) {
            var centerR = new qq.maps.LatLng(riderInfoArr[i].current_lat, riderInfoArr[i].current_lng);
            let riderItem = riderInfoArr[i];
            let statusContent = '状态: 空闲';
            /*console.log(riderItem);
            if ('order_list' in riderItem && riderItem.order_list.length > 0) {
                statusContent = '配送订单:' + riderItem.order_list.length + '单'
            }*/
            let content = '<div class="rider-circle"  data-index="' + i + '"  data-lat="' + riderItem.current_lat +
                '" data-lng="' + riderItem.current_lng + '" >' + '<img src="/system/images/deliveryman.png" >' + '</div>'
            console.log(content)
            var label = new qq.maps.Label({
                position: centerR,
                map: map,
                content: content
            });
        }
    }

    //在地图上加载用戶 图标
    function loadUserIcon() {
        let centerR = new qq.maps.LatLng('{$orderDeliveryInfo.end_lat}', '{$orderDeliveryInfo.end_lng}');
        let iconHtml = '<div class="customer-icon fa fa-shopping-cart">' + '<img src="/system/images/customer.png" >' + '</div>'
        let label = new qq.maps.Label({
            position: centerR,
            map: map,
            content: iconHtml
        });
    }

    //缩放地图  到19級
    function zoomMapToLevel19(latLng) {
        if (!map) {
            alert('map object  is lost')
        }
        map.panTo(new qq.maps.LatLng(latLng.lat, latLng.lng));
        map.zoomTo(19)
    }

    //移除边框
    function removeIconBorder() {
        $('.rider-circle').each(function (e) {
            $(this).parent().css("border", 'none')
            $(this).parent().css("background-color", 'rgba(255,255,255,0.01)')
        });

        $('.order-circle').each(function (e) {
            $(this).parent().css("border", 'none')
            $(this).parent().css("background-color", 'rgba(255,255,255,0.01)')
        });
    }

    //绑定事件
    function bindEvent() {
        $('#map-block').on('dblclick', '.rider-circle', function (e) {
            zoomMapToLevel19({
                lat: $(this).data('lat'),
                lng: $(this).data('lng')
            }, 3,)
        });

        $('#map-block').on('click', '.rider-circle', function (e) {

            let index = $(this).data('index');
            let deliveryman = (window.deliverymanList[index]);
            console.log(deliveryman)
            $('.user-name').html(deliveryman.real_name);
            $('.phone').html(deliveryman.phone);
            $('.deliverymanId').val(deliveryman.id);
            $(".avatar img").attr('src', deliveryman.avatar);

        });
    }

    //提交
    $('#submit').click(function () {
        let deliverymanId = $('#deliverymanId').val()
        if (deliverymanId === '') {
            parent.layer.msg('请先点击选择骑手');

        }
        $.post("{:Url('reDeliverySubmit')}",
            {orderId: '{$orderDeliveryInfo.order_id}', deliverymanId: deliverymanId},
            function (res) {
                console.log(res)
                if (res.code === 200) {
                    parent.layer.msg(res.msg, {icon: 1});
                    let index = parent.layer.getFrameIndex(window.name);
                    parent.layer.close(index);
                } else {
                    parent.layer.msg(res.msg, {icon: 2});
                }
            },
            'json'
        )

    })

    function onInit() {
        removeIconBorder()
        bindEvent()
    }
</script>