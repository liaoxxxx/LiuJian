{extend name="public/container"}
{block name="head_top"}
<!-- 全局js -->
<script src="{__PLUG_PATH}echarts/echarts.common.min.js"></script>
<script src="{__PLUG_PATH}echarts/theme/macarons.js"></script>
<script src="{__PLUG_PATH}echarts/theme/westeros.js"></script>
<script charset="utf-8" src="https://map.qq.com/api/js?v=2.exp&key={$key}"></script>
<script charset="utf-8" src="https://map.qq.com/api/gljs?v=1.exp&key={$key}&libraries=visualization"></script>
<style>
    .box {
        width: 0px;
    }

    .store-image {
        height: 20px;
        width: 20px;
        overflow: hidden;
    }

    .store-image img {
        height: 20px;
        width: 20px;
    }

    #map .ibox-title {
        height: 60px;
    }

    #map-block {
        overflow: hidden;
        background-color: rgba(255, 255, 255, 0.86);
    }

    #map-container {
        float: left;
    }

    #checkbox-container {
        float: left;
    }

    #map .header-select {
        width: 20%;
        float: left;

    }

    #map .header-select:nth-child(2) {
        margin-left: 300px;

    }

    #map .header-select select {
        width: 200px;
        height: 30px;
        border-radius: 2px;
        border: 1px solid darkgrey;
        padding: 0 5px;
    }

    #map .header-select select option {
        padding: 0 5px;
    }

    #map .check-group {
        float: left;
        width: 200px;
        border: 1px solid darkgrey;
        margin: 20px;
        min-height: 300px;
        padding: 10px;
        background-color: #fff;
        box-shadow: 0px 0px 5px grey;
    }

    #deliveryman-group .online {
        background-color: #0089f1;
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(90, 120, 150, 1.5);
        border-radius: 8px;
    }

    #deliveryman-group .offline {
        background-color: #59606f;
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(230, 240, 240, 0.8);
        border-radius: 8px;
    }
    #store-stat-group{
        margin-left: 20px;
    }
    #store-stat-group>.style-column>div {
        float: left;
        margin-left: 10px;
    }
    #store-stat-group .style-circle{
        display:inline-block ;
        width: 10px;
        height: 10px;
        border-radius: 5px;
    }
    #store-stat-group .store-style-on{
        background-color: #10a162;
    }
    #store-stat-group .store-style-off{
        background-color: #ff6224;
    }
    #store-stat-group .store-column{
        float: left;
    }


    #store-stat-group .deliveryman-column{
        float: left;
        margin-left: 20px;
    }
    #store-stat-group .deliveryman-style-on{
        background-color: #0d8def;
    }
    #store-stat-group .deliveryman-style-off{
        background-color: #575352;
    }


</style>
{/block}
{block name="content"}
<div class="row">
    <div class="col-sm-3 ui-sortable">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-danger pull-right">急</span>
                <h5>订单</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{$topData.orderDeliveryNum}</h1>
                <small><a href="javascript:;" class="opFrames" data-name="订单管理"
                          data-href="{:Url('order.store_order/index',['status'=>1])}">待发货</a> </small>
            </div>
        </div>
    </div>
    <div class="col-sm-3 ui-sortable">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-info pull-right">待</span>
                <h5>订单</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{$topData.orderRefundNum}</h1>
                <small><a href="javascript:;" class="opFrames" data-name="订单管理"
                          data-href="{:Url('order.store_order/index',['status'=>-1])}">退换货</a></small>
            </div>
        </div>
    </div>
    <div class="col-sm-3 ui-sortable">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-danger pull-right">急</span>
                <h5>商品</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{$topData.stockProduct}</h1>
                <small><a href="javascript:;" class="opFrames" data-name="商品管理"
                          data-href="{:Url('store.store_product/index',array('type'=>5))}">库存预警</a></small>
            </div>
        </div>
    </div>
    <div class="col-sm-3 ui-sortable">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-danger pull-right">待</span>
                <h5>待提现</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{$topData.treatedExtract}</h1>
                <small><a href="javascript:;" class="opFrames" data-name="提现盛情"
                          data-href="{:Url('finance.user_extract/index')}">待提现</a></small>
            </div>
        </div>
    </div>
    <div class="col-sm-3 ui-sortable">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-info pull-right">昨</span>
                <h5>订单</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{$first_line.d_num.data}</h1>
                <div class="stat-percent font-bold text-navy">
                    {$first_line.d_num.percent}%
                    {if condition='$first_line.d_num.is_plus egt 0'}<i
                            class="fa {if condition='$first_line.d_num.is_plus eq 1'}fa-level-up{else /}fa-level-down{/if}"></i>{/if}
                </div>
                <small><a href="javascript:;" class="opFrames" data-name="订单管理"
                          data-href="{:Url('order.store_order/index')}?data=yesterday">昨日支付订单数</a></small>
            </div>
        </div>
    </div>
    <div class="col-sm-3 ui-sortable">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-info pull-right">昨</span>
                <h5>交易</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{$first_line.d_price.data}</h1>
                <div class="stat-percent font-bold text-info">
                    {$first_line.d_price.percent}%
                    {if condition='$first_line.d_price.is_plus egt 0'}<i
                            class="fa {if condition='$first_line.d_price.is_plus eq 1'}fa-level-up{else /}fa-level-down{/if}"></i>{/if}
                </div>
                <small><a href="javascript:;" class="opFrames" data-name="订单管理"
                          data-href="{:Url('order.store_order/index')}?data=yesterday">昨日交易额</a></small>
            </div>
        </div>
    </div>
    <div class="col-sm-3 ui-sortable">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-info pull-right">今</span>
                <h5>粉丝</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{$first_line.day.data}</h1>
                <div class="stat-percent font-bold text-info">
                    {$first_line.day.percent}%
                    {if condition='$first_line.day.is_plus egt 0'}<i
                            class="fa {if condition='$first_line.day.is_plus eq 1'}fa-level-up{else /}fa-level-down{/if}"></i>{/if}
                </div>
                <small><a href="javascript:;" class="opFrames" data-name="会员管理" data-href="{:Url('user.user/index')}">今日新增粉丝</a></small>
            </div>
        </div>
    </div>
    <div class="col-sm-3 ui-sortable">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <span class="label label-info pull-right">月</span>
                <h5>粉丝</h5>
            </div>
            <div class="ibox-content">
                <h1 class="no-margins">{$first_line.month.data}</h1>
                <div class="stat-percent font-bold text-info">
                    {$first_line.month.percent}%
                    {if condition='$first_line.month.is_plus egt 0'}<i
                            class="fa {if condition='$first_line.month.is_plus eq 1'}fa-level-up{else /}fa-level-down{/if}"></i>{/if}
                </div>
                <small><a href="javascript:;" class="opFrames" data-name="会员管理" data-href="{:Url('user.user/index')}">本月新增粉丝</a></small>
            </div>
        </div>
    </div>

</div>
<div id="app">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>订单</h5>
                    <div class="pull-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-xs btn-white"
                                    :class="{'active': active == 'thirtyday'}" v-on:click="getlist('thirtyday')">30天
                            </button>
                            <button type="button" class="btn btn-xs btn-white" :class="{'active': active == 'week'}"
                                    v-on:click="getlist('week')">周
                            </button>
                            <button type="button" class="btn btn-xs btn-white" :class="{'active': active == 'month'}"
                                    v-on:click="getlist('month')">月
                            </button>
                            <button type="button" class="btn btn-xs btn-white" :class="{'active': active == 'year'}"
                                    v-on:click="getlist('year')">年
                            </button>
                        </div>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-lg-9">
                            <div class="flot-chart-content echarts" ref="order_echart" id="flot-dashboard-chart1"></div>
                        </div>
                        <div class="col-lg-3">
                            <ul class="stat-list">
                                <li>
                                    <h2 class="no-margins ">{{pre_cycleprice}}</h2>
                                    <small>{{precyclename}}销售额</small>
                                </li>
                                <li>
                                    <h2 class="no-margins ">{{cycleprice}}</h2>
                                    <small>{{cyclename}}销售额</small>
                                    <div class="stat-percent text-navy" v-if='cycleprice_is_plus ===1'>
                                        {{cycleprice_percent}}%
                                        <i class="fa fa-level-up"></i>
                                    </div>
                                    <div class="stat-percent text-danger" v-else-if='cycleprice_is_plus === -1'>
                                        {{cycleprice_percent}}%
                                        <i class="fa fa-level-down"></i>
                                    </div>
                                    <div class="stat-percent" v-else>
                                        {{cycleprice_percent}}%
                                    </div>
                                    <div class="progress progress-mini">
                                        <div :style="{width:cycleprice_percent+'%'}" class="progress-bar box"></div>
                                    </div>
                                </li>
                                <li>
                                    <h2 class="no-margins ">{{pre_cyclecount}}</h2>
                                    <small>{{precyclename}}订单总数</small>
                                </li>
                                <li>
                                    <h2 class="no-margins">{{cyclecount}}</h2>
                                    <small>{{cyclename}}订单总数</small>
                                    <div class="stat-percent text-navy" v-if='cyclecount_is_plus ===1'>
                                        {{cyclecount_percent}}%
                                        <i class="fa fa-level-up"></i>
                                    </div>
                                    <div class="stat-percent text-danger" v-else-if='cyclecount_is_plus === -1'>
                                        {{cyclecount_percent}}%
                                        <i class="fa fa-level-down"></i>
                                    </div>
                                    <div class="stat-percent " v-else>
                                        {{cyclecount_percent}}%
                                    </div>
                                    <div class="progress progress-mini">
                                        <div :style="{width:cyclecount_percent+'%'}" class="progress-bar box"></div>
                                    </div>
                                </li>


                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>用户</h5>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="flot-chart">
                                <div class="flot-chart-content" ref="user_echart" id="flot-dashboard-chart2"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-12 map-block" id="map">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5 class="header-select">全网视图</h5>

                <div class="header-select">
                    <div class="layui-form-item">
                        <label class="">省份选择:</label>
                        <select v-model="province" name="province" lay-verify="required">
                            <option v-for="item in provinceList" :value="item"> {{item}}</option>
                        </select>
                    </div>
                </div>


                <div class="header-select">
                    <div class="layui-form-item">
                        <label for="">城市选择:</label>
                        <select v-model="city" name="city" lay-verify="required">
                            <option v-for="(item,index) in cityList" :value="item.name"> {{item.name}}</option>
                        </select>
                    </div>
                </div>


                <div class="header-select">
                    <div class="layui-form-item">
                        <label for="">门店选择:</label>
                        <select v-model="storeId" name="storeId" lay-verify="required">
                            <option v-for="item in storeList" :value="item.id"> {{item.name}}&nbsp;&nbsp;&nbsp;&nbsp;{{item.address}}</option>
                        </select>
                    </div>
                </div>
            </div>



            <div class="ibox-content" id="map-block">
                <div id="map-container"></div>

                <div id="checkbox-container">
                    <div id="city-group" class="check-group">
                        <label for="">城市分布</label>
                        <hr>
                        <div v-for="item in cityList">
                            <label for="check1">{{item.name}}</label>
                            <input type="checkbox" :checked="item.check" @click="checkCity(item)">
                        </div>

                    </div>
                    <div id="store-group" class="check-group">
                        <label for="">城市门店</label>
                        <hr>
                        <div v-for="item in storeList">
                            <label for="">{{item.name}}</label>
                            <input type="checkbox" :checked="item.check" @click="checkStore(item)">
                        </div>

                    </div>
                    <div id="deliveryman-group" class="check-group">
                        <label for="">骑手统计</label>
                        <hr>
                        <div>
                            <div class="online"></div>
                            在线{{deliverymanStat.online}}人
                            <br>
                            <div class="offline"></div>
                            离线{{deliverymanStat.offline}}人
                        </div>
                    </div>
                </div>
            </div>
            <div id="store-stat-group">
                <div class="store-column  style-column">
                    <div>店铺状态</div>
                    <div>
                        <div>
                            <span class="store-style-on style-circle"></span> <span>营业中</span>
                        </div>
                        <div>
                            <span class="store-style-off style-circle"></span> <span>暂停营业</span>
                        </div>
                    </div>
                </div>
                <div class="deliveryman-column style-column">
                    <div>骑手状态</div>
                    <div>
                        <div>
                            <span class="deliveryman-style-on style-circle"></span> <span >在线</span>
                        </div>
                        <div>
                            <span class="deliveryman-style-off style-circle" ></span> <span>离线</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
{/block}
{block name="script"}
<script>
    require(['vue', 'axios', 'layer'], function (Vue, axios, layer) {
        new Vue({
            el: "#app",
            data: {
                option: {},
                myChart: {},
                active: 'thirtyday',
                cyclename: '最近30天',
                precyclename: '上个30天',
                cyclecount: 0,
                cycleprice: 0,
                cyclecount_percent: 0,
                cycleprice_percent: 0,
                cyclecount_is_plus: 0,
                cycleprice_is_plus: 0,
                pre_cyclecount: 0,
                pre_cycleprice: 0,

            },
            methods: {
                //
                // getAdminStores: function () {
                //     var that = this;
                //     axios.get("/admin/setting.system_admin/getBindingStore").then((res) => {
                //         that.store_list = res.data.data.list
                //
                //     });
                // },

                info: function () {
                    var that = this;
                    axios.get("{:Url('userchart')}").then((res) => {
                        that.myChart.user_echart.setOption(that.userchartsetoption(res.data.data));
                    });
                },
                getlist: function (e) {
                    var that = this;
                    var cycle = e != null ? e : 'thirtyday';
                    axios.get("{:Url('orderchart')}?cycle=" + cycle).then((res) => {
                        that.myChart.order_echart.clear();
                        that.myChart.order_echart.setOption(that.orderchartsetoption(res.data.data));
                        that.active = cycle;
                        switch (cycle) {
                            case 'thirtyday':
                                that.cyclename = '最近30天';
                                that.precyclename = '上个30天';
                                break;
                            case 'week':
                                that.precyclename = '上周';
                                that.cyclename = '本周';
                                break;
                            case 'month':
                                that.precyclename = '上月';
                                that.cyclename = '本月';
                                break;
                            case 'year':
                                that.cyclename = '去年';
                                that.precyclename = '今年';
                                break;
                            default:
                                break;
                        }
                        var data = res.data.data;
                        if (data) {
                            that.cyclecount = data.cycle.count.data;
                            that.cyclecount_percent = data.cycle.count.percent;
                            that.cyclecount_is_plus = data.cycle.count.is_plus;
                            that.cycleprice = data.cycle.price.data;
                            that.cycleprice_percent = data.cycle.price.percent;
                            that.cycleprice_is_plus = data.cycle.price.is_plus;
                            that.pre_cyclecount = data.pre_cycle.count.data;
                            that.pre_cycleprice = data.pre_cycle.price.data;
                        }
                    });
                },
                orderchartsetoption: function (data) {
                    data = data == undefined ? {} : data;
                    this.option = {
                        tooltip: {
                            trigger: 'axis',
                            axisPointer: {
                                type: 'cross',
                                crossStyle: {
                                    color: '#999'
                                }
                            }
                        },
                        toolbox: {
                            feature: {
                                dataView: {show: true, readOnly: false},
                                magicType: {show: true, type: ['line', 'bar']},
                                restore: {show: false},
                                saveAsImage: {show: true}
                            }
                        },
                        legend: {
                            data: data.legend || []
                        },
                        grid: {
                            x: 70,
                            x2: 50,
                            y: 60,
                            y2: 50
                        },
                        xAxis: [
                            {
                                type: 'category',
                                data: data.xAxis,
                                axisPointer: {
                                    type: 'shadow'
                                },
                                axisLabel: {
                                    interval: 0,
                                    rotate: 40
                                }


                            }
                        ],
                        yAxis: [{type: 'value'}],
//                            yAxis: [
//                                {
//                                    type: 'value',
//                                    name: '',
//                                    min: 0,
//                                    max: data.yAxis.maxprice,
////                                    interval: 0,
//                                    axisLabel: {
//                                        formatter: '{value} 元'
//                                    }
//                                },
//                                {
//                                    type: 'value',
//                                    name: '',
//                                    min: 0,
//                                    max: data.yAxis.maxnum,
//                                    interval: 5,
//                                    axisLabel: {
//                                        formatter: '{value} 个'
//                                    }
//                                }
//                            ],
                        series: data.series
                    };
                    return this.option;
                },
                userchartsetoption: function (data) {
                    this.option = {
                        tooltip: {
                            trigger: 'axis',
                            axisPointer: {
                                type: 'cross',
                                crossStyle: {
                                    color: '#999'
                                }
                            }
                        },
                        toolbox: {
                            feature: {
                                dataView: {show: false, readOnly: false},
                                magicType: {show: true, type: ['line', 'bar']},
                                restore: {show: false},
                                saveAsImage: {show: false}
                            }
                        },
                        legend: {
                            data: data.legend
                        },
                        grid: {
                            x: 70,
                            x2: 50,
                            y: 60,
                            y2: 50
                        },
                        xAxis: [
                            {
                                type: 'category',
                                data: data.xAxis,
                                axisPointer: {
                                    type: 'shadow'
                                }
                            }
                        ],
                        yAxis: [
                            {
                                type: 'value',
                                name: '人数',
                                min: 0,
                                max: data.yAxis.maxnum,
                                interval: 5,
                                axisLabel: {
                                    formatter: '{value} 人'
                                }
                            }
                        ],
//                        series: data.series
                        series: [{
                            name: '人数',
                            type: 'bar',
                            barWidth: '50%',
                            itemStyle: {
                                normal: {
                                    label: {
                                        show: true, //开启显示
                                        position: 'top', //在上方显示
                                        textStyle: { //数值样式
                                            color: '#666',
                                            fontSize: 12
                                        }
                                    }
                                }
                            },
                            data: data.series
                        }]

                    };
                    return this.option;
                },
                setChart: function (name, myChartname) {
                    this.myChart[myChartname] = echarts.init(name, 'macarons');//初始化echart
                }
            },
            mounted: function () {
                const self = this;
                this.setChart(self.$refs.order_echart, 'order_echart');//订单图表
                this.setChart(self.$refs.user_echart, 'user_echart');//用户图表
                this.info();
                this.getlist();
                //this.getAdminStores()
                $('.opFrames').on('click', function () {
                    parent.addframes($(this).data('href'), '', $(this).data('name'));
                });
            }
        });

        //门店站点 的 地图实例
        const mapVue = new Vue({
                el: "#map",
                data: {
                    provinceList: [
                        '加载中...'
                    ],
                    province: '请选择',
                    cityList: [],
                    city: '请选择',

                    storeList: [],
                    storeId: '',
                    storeIdList: [],
                    storeCircleList:[],

                    deliverymanList: [],
                    deliverymanStat:{
                        online:0,
                        offline:0
                    },

                    //地图对象
                    mapInstance: {},
                    //圆点 层
                    dotLayer: null,
                    //点样式
                    dotStyle: {
                        'storeOnStyle': {
                            type: "circle", //圆形样式
                            fillColor: "#10a162", //填充颜色
                            strokeColor: "#FFFFFF",//边线颜色
                            strokeWidth: 0, //边线宽度
                            radius: 8 //原型半径
                        },
                        'storeOffStyle': {
                            type: "circle", //圆形样式
                            fillColor: "#ff6224", //填充颜色
                            strokeColor: "#FFFFFF",//边线颜色
                            strokeWidth: 0, //边线宽度
                            radius: 8 //原型半径
                        },
                        'deliverymanOnStyle': {
                            type: "circle", //圆形样式
                            fillColor: "#0d8def", //填充颜色
                            strokeColor: "#FFFFFF",//边线颜色
                            strokeWidth: 0, //边线宽度
                            radius: 8 //原型半径
                        },
                        'deliverymanOffColor': {
                            type: "circle", //圆形样式
                            fillColor: "#575352", //填充颜色
                            strokeColor: "#5e5858",//边线颜色
                            strokeWidth: 0, //边线宽度
                            radius: 8 //原型半径
                        },

                    },
                    //点的列表
                    dotList: [{lat: 39.984104, lng: 116.307503, styleId: "greenStyleColor"}]
                },
                watch: {
                    //监听 province
                    province: function (val) {
                        this.province = val;
                        console.log("watch: province")
                        this.clearDataList()

                        this.getCityList(this.province)
                    },
                    //监听 city
                    city: function (val) {
                        console.log("watch: city")
                        console.log(val)
                        this.city = val;
                        this.clearStoreDataList()
                        for (const cityKey in this.cityList) {
                            this.cityList[cityKey].check = this.cityList[cityKey].name === val;
                        }

                        this.getStoreList(this.province, this.city)
                    },

                    //监听 cityList
                    cityList: function (val) {
                        console.table(this.cityList)
                        this.getStoreList();
                    },

                    //监听 storeId
                    storeId: function (val) {
                        console.log("watch: storeId")
                        for (const storeKey in this.storeList) {
                            this.storeList[storeKey].check = this.storeList[storeKey].id === val;
                        }
                        this.storeId = val;
                        this.storeIdList=[val]
                        this.getDeliverymanList()
                        this.clearDeliverymanDataList()
                        this.loadStoreDeliveryRangeCircle()
                        //this.getStoreList(this.province, this.city)
                    },
                    storeIdList: function (val) {
                            console.log("watch: storeIdList")
                            this.loadStoreDeliveryRangeCircle()
                            //this.getStoreList(this.province, this.city)
                        },
                    deliverymanList:function () {
                        this.reloadDot()
                        this.computedDeliverymanStat()
                    }
                },
                methods: {
                    //获取省级列表
                    getProvinceList: function (e) {
                        console.log("methods: getProvinceList")
                        let that = this;
                        axios.get("{:Url('/admin/system.SystemStore/getProvincesOfBindStore')}").then((res) => {
                            res = res.data
                            if (res.code === 200) {
                                that.provinceList = res.data.provinceList
                                that.province = that.provinceList[0]
                                //that.provinceList.push('请选择')
                            } else {
                                //啥也不做
                            }

                        });
                    },
                    //获取城市列表
                    getCityList: function (province) {
                        console.log("methods: getCityList")
                        let that = this;
                        axios.get('/admin/system.SystemStore/getCityOfBindStore?province=' + province).then((res) => {
                            let cityList = res.data.data.cityList
                            console.log(cityList)
                            if (res.data.code === 200) {
                                //console.log('=======')
                                for (let cityIndex in cityList) {
                                    that.cityList.push({
                                        name: cityList[cityIndex],
                                        check: false
                                    })
                                }
                                that.cityList[0].check = true
                                that.city = that.cityList[0].name
                                //console.log(that.cityList)
                                //that.cityList.push('请选择')
                            } else {
                                //啥也不做
                            }

                        });
                    },
                    //获取门店站点列表
                    getStoreList: function () {
                        console.log("methods: getStoreList")
                        let that = this;
                        axios.post('/admin/system.SystemStore/getStoreByCityAndProvince', {
                            province: that.province,
                            city: this.cityList
                        }).then((res) => {
                            let storeList = res.data.data.storeList
                            console.log(storeList)
                            if (res.data.code === 200) {
                                that.storeList = storeList
                                that.storeList[0].check = true

                                let firstStore = that.storeList[0]
                                let firstStoreCenter = new TMap.LatLng(firstStore.latitude, firstStore.longitude);
                                that.setMapCenter(firstStoreCenter)
                                that.setZoomLevel(14)

                                that.storeId = that.storeList[0].id
                                that.storeIdList.push(that.storeList[0].id)

                            } else {
                                //啥也不做
                            }
                        });
                    },
                    //获取配送员列表
                    getDeliverymanList: function () {
                        console.log("methods: getDeliverymanList")
                        let that = this;
                        axios.post('/admin/distribution.deliveryman/getListBySelectStore', {selectStoreIds: that.storeIdList}).then((res) => {
                            res = res.data
                            console.log(res.data.deliverymanList)
                            if (res.code === 200) {
                                that.deliverymanList = res.data.deliverymanList
                                //that.cityList.push('请选择')
                            } else {
                                //啥也不做
                            }
                        });
                    },

                    //初始化地图
                    initMap: function () {

                        $('#map-container').css('height', 400);
                        $('#map-container').css('width', 700);
                        //初始化地图
                        var center = new TMap.LatLng(39.984104, 116.307503);
                        return  new TMap.Map("map-container", {
                            zoom: 8,//设置地图缩放级别
                            center: center,//设置地图中心点坐标
                            // mapStyleId: "DARK" //个性化样式
                        })
                    },
                    //重加载 地图点标记
                    reloadDot: function () {
                        let dotList = []
                        //1.门店站点标记
                        this.storeList.forEach(function (storeItem, index) {
                            let dot = {
                                lat: parseFloat(storeItem.latitude.slice(0, 9)),
                                lng: parseFloat(storeItem.longitude.slice(0, 9)),
                                styleId: ""
                            }
                            if (storeItem.check) {
                                if (storeItem.status === 1) {
                                    dot.styleId = "storeOnStyle"
                                } else {
                                    dot.styleId = "storeOffStyle"
                                }
                                dotList.push(dot)
                            }

                        })
                        //2.门店配送距离标记
                        //3.配送员的点标记
                        this.deliverymanList.forEach(function (dItem, index) {
                            let dot = {
                                lat: parseFloat(dItem.current_lat.slice(0, 9)),
                                lng: parseFloat(dItem.current_lng.slice(0, 9)),
                                styleId: ""
                            }
                            if (dItem.is_receiving === 1) {
                                dot.styleId = "deliverymanOnStyle"
                            } else {
                                dot.styleId = "deliverymanOffStyle"
                            }
                            dotList.push(dot)
                        })
                        console.table(dotList)
                        //4.加载
                        let center = this.buildCenter(dotList[0].lat, dotList[0].lng);
                        let dotLayerNew = new TMap.visualization.Dot({  //初始化散点图并添加至map图层
                            styles: this.dotStyle,
                            faceTo: "screen",//散点固定的朝向
                        }).addTo(this.mapInstance).setData(dotList)
                        if (this.dotLayer) {
                            this.dotLayer.destroy()  //销毁旧的【点 layer】
                        }
                        this.setMapCenter(center) //设置地图中心
                        this.setZoomLevel(11)
                        this.dotLayer = dotLayerNew
                   },
                    //计算配送员在线离线数量
                    computedDeliverymanStat:function(){
                        this.deliverymanStat={
                            online:0,
                            offline:0
                        }
                        let that=this
                        this.deliverymanList.forEach(function (item,index) {
                            if (item.is_receiving===1){
                                that.deliverymanStat.online++
                            }else {
                                that.deliverymanStat.offline++
                            }
                        })
                    },

                    //设置地图中心
                    buildCenter: function (lat, lng) {
                        return new TMap.LatLng(lat, lng);
                    },


                    //设置地图中心
                    setMapCenter: function (Center) {
                        this.mapInstance.setCenter(Center);

                    },
                    ////去掉腾讯地图logo等小部件
                    removeMapControlComponent: function () {
                        //去掉腾讯地图logo等小部件
                        $("#map-container").bind("DOMNodeInserted", function (e) {
                            let length = $("#map-container div:first-child").children('div').length;
                            $("#map-container > div > div").not(":first").remove();
                        })
                    },
                    //设置地图缩放等级
                    setZoomLevel: function (zoomLevel) {
                        this.mapInstance.setZoom(zoomLevel)
                    },
                    //加载门店 配送范围圆圈
                    loadStoreDeliveryRangeCircle() {
                        this.clearStoreCircleData()
                        let that =this
                        this.storeList.forEach(function (item,index) {
                            if (item.check){
                                let distance=1000
                                                //1000米  =1000 个单位
                                if (item.max_distance){
                                    console.log(item.max_distance)
                                    distance = item.max_distance*1000
                                }else {
                                    console.log('门店：'+item.name+'的最远配送距离未填写')
                                }
                                let center = that.buildCenter(parseFloat(item.latitude.slice(0, 9)), parseFloat(item.longitude.slice(0, 9)));
                                let circle = new TMap.MultiCircle({
                                    map:that.mapInstance,
                                    styles: { // 设置圆形样式
                                        'circle': new TMap.CircleStyle({
                                            'color': 'rgba(246,108,0,0.2)',
                                            'showBorder': true,
                                            'borderColor': 'rgb(255,159,41)',
                                            'borderWidth': 2,
                                        }),
                                    },
                                    geometries: [{
                                        styleId: 'circle',
                                        center: center,
                                        radius: distance,
                                    }],
                                });
                                that.storeCircleList.push(circle)
                            }
                        })
                        console.log(this.storeCircleList)
                    },

                    //门店站点 组 checkBox 操作
                    checkStore: function (storeItem) {
                        //处理门店站点列表
                        for (let storekey in this.storeList) {
                            if (this.storeList[storekey].id === storeItem.id) {
                                this.storeList[storekey].check = this.storeList[storekey].check !== true;
                            }
                        }
                        //处理门店站点id列表
                        this.storeIdList=Array.from( new Set(this.storeIdList))  //去重
                        let hasIndex=this.storeIdList.indexOf(storeItem.id)
                        if (hasIndex===-1){
                            this.storeIdList.push(storeItem.id)
                        }else {
                            this.storeIdList.splice(hasIndex,1)
                        }
                        this.getDeliverymanList()
                    },
                    //城市组 checkBox 操作
                    checkCity: function (cityItem) {
                        for (let cityKey in this.cityList) {
                            if (this.cityList[cityKey].name === cityItem.name) {
                                if (this.cityList[cityKey].check === true) {
                                    console.log(' ->   false')
                                    this.cityList[cityKey].check = false
                                    console.log(this.cityList[cityKey].check)
                                } else {
                                    console.log(' ->   true')
                                    this.cityList[cityKey].check = true
                                    console.log(this.cityList[cityKey].check)
                                }
                            }
                        }
                        this.getStoreList()
                        console.table(this.cityList)
                    },
                    //清除所有及相关数据
                    clearDataList: function () {
                        this.cityList = []
                        this.storeList = []
                        this.storeIdList = []
                        this.deliverymanList = []
                    },
                    //清除门店及相关数据
                    clearStoreDataList: function () {
                        this.storeList = []
                        this.storeIdList = []
                        this.deliverymanList = []
                    },
                    //清除配送员数据
                    clearDeliverymanDataList: function () {
                        this.deliverymanList = []
                    },
                    //清除门店配送圆圈数据
                    clearStoreCircleData:function (){
                        console.log('clearStoreCircleData')
                        if (this.storeCircleList.length){
                            for (const cKey in this.storeCircleList) {
                                console.log('clearStoreCircleData  for in')
                                let cArr=new Array(this.storeCircleList[cKey].id)
                                console.log(cArr)
                                this.storeCircleList[cKey].remove(cArr)
                            }
                        }

                    },


                },
                mounted: function () {
                    this.getProvinceList()
                    this.mapInstance = this.initMap()
                },
            })
        ;
    })
    ;
</script>
<script>
    window.onload = function () {

        //去掉腾讯地图logo等小部件
        $("#map-container").bind("DOMNodeInserted", function (e) {
            let length = $("#map-container div:first-child").children('div').length;
            $("#map-container > div > div").not(":first").remove();
        });


    }


</script>
{/block}
