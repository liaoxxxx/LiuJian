{extend name="public/container"}
{block name="head_top"}

{/block}
{block name="content"}
<div class="layui-fluid">
    <div class="layui-row layui-col-space15" id="app">
        <!--搜索条件-->
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">搜索条件</div>
                <div class="layui-card-body">
                    <div class="layui-carousel layadmin-carousel layadmin-shortcut" lay-anim="" lay-indicator="inside"
                         lay-arrow="none" style="background:none">
                        <div class="layui-card-body">
                            <div class="layui-row layui-col-space10 layui-form-item">


                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">创建时间:</label>
                                    <div class="layui-input-block" data-type="data" v-cloak="">
                                        <button class="layui-btn layui-btn-sm" type="button" v-for="item in dataList"
                                                @click="setData(item)"
                                                :class="{'layui-btn-primary':where.data!=item.value}">{{item.name}}
                                        </button>
                                        <button class="layui-btn layui-btn-sm" type="button" ref="time"
                                                @click="setData({value:'zd',is_zd:true})"
                                                :class="{'layui-btn-primary':where.data!='zd'}">自定义
                                        </button>
                                        <button type="button" class="layui-btn layui-btn-sm layui-btn-primary"
                                                v-show="showtime==true" ref="date_time">{$year.0} - {$year.1}
                                        </button>
                                    </div>
                                </div>
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">提现金额:</label>
                                    <div class="layui-input-block" data-type="data" v-cloak="">
                                        <button class="layui-btn layui-btn-sm" type="button" v-for="item in amountList"
                                                @click="setAmount(item)"
                                                :class="{'layui-btn-primary':where.amount!=item.value}">{{item.name}}
                                        </button>

                                    </div>
                                </div>
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">拒绝:</label>
                                    <div class="layui-input-block" data-type="data" v-cloak="">
                                        <button class="layui-btn layui-btn-sm" type="button" v-for="item in refusedStatusList"
                                                @click="setRefused(item)"
                                                :class="{'layui-btn-primary':where.pay_status!==item.value}">{{item.name}}
                                        </button>
                                    </div>
                                </div>
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">支付:</label>
                                    <div class="layui-input-block" data-type="data" v-cloak="">
                                        <button class="layui-btn layui-btn-sm" type="button" v-for="item in payStatusList"
                                                @click="setPay(item)"
                                                :class="{'layui-btn-primary':where.pay_status!==item.value}">{{item.name}}
                                        </button>

                                    </div>
                                </div>
                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">配送员:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="deliveryman" style="width: 50%"
                                               v-model="where.real_name" placeholder="请输入姓名、电话"
                                               class="layui-input">
                                    </div>
                                </div>

                                <div class="layui-col-lg6">
                                    <label class="layui-form-label">提现单号:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="whithrawId" style="width: 50%"
                                               v-model="where.withdrawId" placeholder="提现单号"
                                               class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-col-lg12">
                                    <div class="layui-input-block">
                                        <button @click="search" type="button"
                                                class="layui-btn layui-btn-sm layui-btn-normal">
                                            <i class="layui-icon layui-icon-search"></i>搜索
                                        </button>
                                        <button @click="excel" type="button"
                                                class="layui-btn layui-btn-warm layui-btn-sm export" type="button">
                                            <i class="fa fa-floppy-o" style="margin-right: 3px;"></i>导出
                                        </button>
                                        <button @click="refresh" type="reset"
                                                class="layui-btn layui-btn-primary layui-btn-sm">
                                            <i class="layui-icon layui-icon-refresh"></i>刷新
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!--列表-->
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">提现列表</div>
                <div class="layui-card-body">
                    <div class="layui-btn-container" id="container-action">
                        <button class="layui-btn layui-btn-sm" data-type="del_order">批量删除订单</button>
                        <button class="layui-btn layui-btn-sm layui-btn-warm" data-type="write_order">订单核销</button>
                    </div>
                    <table class="layui-hide" id="List" lay-filter="List"></table>

                    <!--配送员信息-->
                    <script type="text/html" id="deliverymanTpl">
                        <div style="overflow: hidden" class="layui-col-xs3 layui-col-sm3 layui-col-md3">
                            <img style="margin:0;cursor: pointer;width: 60px;height: 60px"
                                 src="{{d.deliveryman.avatar}}">
                        </div>
                        <div class="layui-col-xs9 layui-col-sm9 layui-col-md9">
                            <p class="text-center">{{d.deliveryman.real_name}} </p>
                            <p class="text-center"> 电话：{{d.deliveryman.phone}}</p>
                        </div>
                    </script>
                    <script type="text/html" id="act">
                        <a class="btn btn-danger btn-xs" type="button">
                            <i class="fa fa-gear"></i> 拒绝提现</a>
                        <button class="btn btn-info btn-xs" type="button"
                                onclick="$eb.createModalFrame('订单记录','{:Url('order_list')}?withdrawId={{d.id}}')">
                            <i class="fa fa-gear"></i> 查看提现订单
                        </button>
                        <button class="btn btn-primary btn-xs" type="button"
                                lay-event='pay'>
                            <i class="fa fa-gear"></i> 支付
                        </button>
                        <button type="button" class="layui-btn layui-btn-xs" onclick="dropdown(this)">操作 <span
                                    class="caret"></span></button>
                        <ul class="layui-nav-child layui-anim layui-anim-upbit">
                            <li>
                                <a lay-event='pay' href="javascript:void(0);">
                                    <i class="fa fa-paste"></i> 支付
                                </a>
                            </li>
                        </ul>
                    </script>
                </div>
            </div>
        </div>
    </div>
    <!--end-->
</div>
<script src="{__ADMIN_PATH}js/layuiList.js"></script>
{/block}
{block name="script"}
<script>
    layList.tableList('List', "{:Url('withdraw_list',['real_name'=>$real_name])}", function () {
        return [
            {type: 'checkbox'},
            {field: 'deliveryman_id', title: '配送员信息', templet: "#deliverymanTpl", width: '15%', height: 'full-20'},
            {field: 'withdraw_amount', title: '提现金额(￥)', width: '10%', align: 'center'},
            {field: 'is_refused_str', title: '拒绝支付', width: '10%', sort: false, align: 'center'},
            {field: 'is_paid_str', title: '是否支付', width: '10%', sort: false, align: 'center'},
            {field: 'pay_time', title: '支付时间', width: '10%', sort: true, align: 'center'},
            {field: 'create_at', title: '创建时间', width: '10%', sort: true, align: 'center'},
            {field: 'update_at', title: '更新时间', width: '10%', sort: true, align: 'center'},
            {field: 'right', title: '操作', align: 'center', toolbar: '#act', width: '20%'},
        ];
    });
    layList.tool(function (event, data, obj) {
        switch (event) {
            case 'pay':
                var payUrl = layList.U({a: 'pay'});
                let id = data.id;

                $.ajax({
                    url: payUrl,
                    data: 'withdrawIds=' + id,
                    type: 'post',
                    dataType: 'json',
                    success: function (res) {
                        if (res.code == 200) {
                            $eb.$swal('success', res.msg);
                        } else
                            $eb.$swal('error', res.msg);
                    }
                })
                break;

            case'danger':
                var url = layList.U({c: 'order.store_order', a: 'take_delivery', p: {id: data.id}});
                $eb.$swal('delete', function () {
                    $eb.axios.get(url).then(function (res) {
                        if (res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success', res.data.msg);
                        } else
                            return Promise.reject(res.data.msg || '收货失败')
                    }).catch(function (err) {
                        $eb.$swal('error', err);
                    });
                }, {'title': '您确定要修改收货状态吗？', 'text': '修改后将无法恢复,请谨慎操作！', 'confirm': '是的，我要修改'})
                break;

            case'order_info':
                $eb.createModalFrame(data.nickname + '订单详情', layList.U({a: 'order_info', q: {oid: data.id}}));
                break;

            case 'refused':
                let refusedUrl = layList.U({c: 'distribution.withdraw', a: 'refused'});
                id = data.id,
                    make = data.remark;
                $eb.$alert('textarea', {
                    title: '拒绝提现？输入拒绝的备注信息',
                    text: '您确定要拒绝提现吗,请谨慎操作！',
                    value: make
                }, function (result) {
                    if (result) {
                        $.ajax({
                            url: refusedUrl,
                            data: 'refusedMark=' + result + '&withdrawId=' + id,
                            type: 'post',
                            dataType: 'json',
                            success: function (res) {
                                if (res.code == 200) {
                                    $eb.$swal('success', res.msg);
                                } else
                                    $eb.$swal('error', res.msg);
                            }
                        })
                    } else {
                        $eb.$swal('error', '请输入要备注的内容');
                    }
                });
                break;
        }
    })


    var action = {
        del_order: function () {
            var ids = layList.getCheckData().getIds('id');

            if (ids.length) {
                var url = layList.U({c: 'distribution.order', a: 'delete'});
                $eb.$swal('delete', function () {
                    $eb.axios.post(url, {ids: ids}).then(function (res) {
                        if (res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success', res.data.msg);
                            window.location.reload();
                        } else
                            return Promise.reject(res.data.msg || '删除失败')
                    }).catch(function (err) {
                        $eb.$swal('error', err);
                    });
                }, {'title': '您确定要修删除订单吗？', 'text': '删除后将无法恢复,请谨慎操作！', 'confirm': '是的，我要删除'})
            } else {
                layList.msg('请选择要删除的订单');
            }
        },
        write_order: function () {
            return $eb.createModalFrame('订单核销', layList.U({a: 'write_order'}), {w: 500, h: 400});
        },
    };
    $('#container-action').find('button').each(function () {
        $(this).on('click', function () {
            var act = $(this).data('type');
            action[act] && action[act]();
        });
    })
    //下拉框
    $(document).click(function (e) {
        $('.layui-nav-child').hide();
    })

    function dropdown(that) {
        var oEvent = arguments.callee.caller.arguments[0] || event;
        oEvent.stopPropagation();
        var offset = $(that).offset();
        var top = offset.top - $(window).scrollTop();
        var index = $(that).parents('tr').data('index');
        $('.layui-nav-child').each(function (key) {
            if (key != index) {
                $(this).hide();
            }
        })
        if ($(document).height() < top + $(that).next('ul').height()) {
            $(that).next('ul').css({
                'padding': 10,
                'top': -($(that).parents('td').height() / 2 + $(that).height() + $(that).next('ul').height() / 2),
                'min-width': 'inherit',
                'position': 'absolute'
            }).toggle();
        } else {
            $(that).next('ul').css({
                'padding': 10,
                'top': $(that).parents('td').height() / 2 + $(that).height(),
                'min-width': 'inherit',
                'position': 'absolute'
            }).toggle();
        }
    }

    var real_name = '<?=$real_name?>';
    var orderCount =<?=json_encode($orderCount)?>, payTypeCount =<?=json_encode($payTypeCount)?>,
        status =<?=$status ? $status : "''"?>;
    require(['vue'], function (Vue) {
        new Vue({
            el: "#app",
            data: {
                amountList:
                    [
                        {name: '全部', value: ''},
                        {name: '0-50', value: [0,50]},
                        {name: '50-200', value: [50,200]},
                        {name: '200-500', value: [200,500]},
                        {name: '500-2000', value: [500,200]},
                        {name: '2000-10000', value: [2000,10000]},
                        {name: '10000-不限', value: [10000,99999999]},
                    ],
                dataList: [
                    {name: '全部', value: ''},
                    {name: '今天', value: 'today'},
                    {name: '昨天', value: 'yesterday'},
                    {name: '最近7天', value: 'lately7'},
                    {name: '最近30天', value: 'lately30'},
                    {name: '本月', value: 'month'},
                    {name: '本年', value: 'year'},
                ],
                payStatusList:[
                    {name: '全部', value: -1},
                    {name: '已支付', value: 1},
                    {name: '未支付', value: 0},
                ],
                refusedStatusList:[
                    {name: '全部', value: -1},
                    {name: '已拒绝', value: 1},
                    {name: '未拒绝', value: 0},
                ],
                where: {
                    data: '',
                    amount:[],
                    status: status,
                    type: '',
                    pay_status: -1,
                    refused_status:-1,
                    real_name: real_name || '',
                    excel: 0,
                    withdrawId:'',
                },
                showtime: false,
            },
            watch: {},
            methods: {
                setData: function (item) {
                    var that = this;
                    if (item.is_zd == true) {
                        that.showtime = true;
                        this.where.data = this.$refs.date_time.innerText;
                    } else {
                        this.showtime = false;
                        this.where.data = item.value;
                    }
                },

                setAmount: function (item) {
                    let that = this;
                    that.where.amount=item.value
                    console.log(that.where.amount)
                },

                setPay: function (item) {
                    let that = this;
                    that.where.pay_status=item.value
                },

                setRefused: function (item) {
                    let that = this;
                    that.where.refused_status=item.value
                    console.log(that.where.refused_status)
                    console.log(that.where.pay_status)
                },

                search: function () {
                    this.where.excel = 0;
                    layList.reload(this.where, true);
                },
                refresh: function () {
                    layList.reload();
                },
                excel: function () {
                    this.where.excel = 1;
                    location.href = layList.U({c: 'order.store_order', a: 'order_list', q: this.where});
                }
            },
            mounted: function () {
                var that = this;
                window.formReload = this.search;
                layList.laydate.render({
                    elem: this.$refs.date_time,
                    trigger: 'click',
                    eventElem: this.$refs.time,
                    range: true,
                    change: function (value) {
                        that.where.data = value;
                    }
                });
            }
        })
    });
</script>
{/block}