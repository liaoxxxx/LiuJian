{extend name="public/container"}
{block name="head_top"}

{/block}
{block name="content"}
<div class="layui-fluid">
    <div class="layui-row layui-col-space15"  id="app">
        <!--搜索条件-->
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">搜索条件</div>
                <div class="layui-card-body">
                    <div class="layui-carousel layadmin-carousel layadmin-shortcut" lay-anim="" lay-indicator="inside" lay-arrow="none" style="background:none">
                        <div class="layui-card-body">
                            <div class="layui-row layui-col-space10 layui-form-item">
                                <div class="layui-col-lg12">
                                    <label class="layui-form-label">订单状态:</label>
                                    <div class="layui-input-block" v-cloak="">
                                        <button class="layui-btn layui-btn-sm" :class="{'layui-btn-primary':where.status!==item.value}" @click="where.status = item.value" type="button" v-for="item in orderStatus">{{item.name}}
                                            <span v-if="item.count!=undefined" :class="item.class!=undefined ? 'layui-badge': 'layui-badge layui-bg-gray' ">{{item.count}}</span></button>
                                    </div>
                                </div>
                                <br>
                                <div class="layui-col-lg12">
                                    <label class="layui-form-label">创建时间:</label>
                                    <div class="layui-input-block" data-type="data" v-cloak="">
                                        <button class="layui-btn layui-btn-sm" type="button" v-for="item in dataList" @click="setData(item)" :class="{'layui-btn-primary':where.data!=item.value}">{{item.name}}</button>
                                        <button class="layui-btn layui-btn-sm" type="button" ref="time" @click="setData({value:'zd',is_zd:true})" :class="{'layui-btn-primary':where.data!='zd'}">自定义</button>
                                        <button type="button" class="layui-btn layui-btn-sm layui-btn-primary" v-show="showtime==true" ref="date_time">{$year.0} - {$year.1}</button>
                                    </div>
                                </div>
                                <div class="layui-col-lg4">
                                    <label class="layui-form-label">订单号:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="real_name" style="width: 50%" v-model="where.order_id" placeholder="请输入订单编号,如：wx1599750*******" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-col-lg4">
                                    <label class="layui-form-label">门店:</label>
                                    <div class="layui-input-block">
                                        <select v-model="where.storeId"  style="width: 300px;height: 30px;border:1px solid #E9E7E7;border-radius: 2px" lay-search="" lay-verify="required">
                                            <option v-for="item in storeList" :value="item.id">   {{item.name}}&nbsp;&nbsp;&nbsp;&nbsp; {{item.detailed_address}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-col-lg4 ">
                                    <label class="layui-form-label">配送员</label>
                                    <div class="layui-input-block">
                                        <select v-model="where.deliverymanId"  style="width: 300px;height: 30px;border:1px solid #E9E7E7;border-radius: 2px" lay-search="" lay-verify="required">
                                            <option v-for="item in deliverymanList" :value="item.id">   {{item.real_name}}&nbsp;&nbsp;&nbsp;&nbsp; 手机:{{item.phone}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="layui-col-lg3">
                                    <label class="layui-form-label">配送金额:</label>
                                    <div class="layui-input-block">
                                        <input type="text" name="delivery_amount[]" style="width: 50%" v-model="where.delivery_amount.min" placeholder="请输入最小配送金额" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-col-lg3">
                                    <div class="layui-input-block">
                                        <input type="text" name="delivery_amount[]" style="width: 50%" v-model="where.delivery_amount.max" placeholder="请输入最大配送金额" class="layui-input">
                                    </div>
                                </div>
                                <div class="layui-col-lg12">
                                    <div class="layui-input-block">
                                        <button @click="search" type="button" class="layui-btn layui-btn-sm layui-btn-normal">
                                            <i class="layui-icon layui-icon-search"></i>搜索</button>
                                        <button @click="excel" type="button" class="layui-btn layui-btn-warm layui-btn-sm export" type="button">
                                            <i class="fa fa-floppy-o" style="margin-right: 3px;"></i>导出</button>
                                        <button @click="refresh" type="reset" class="layui-btn layui-btn-primary layui-btn-sm">
                                            <i class="layui-icon layui-icon-refresh" ></i>刷新</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end-->
        <!-- 中间详细信息-->
        <div :class="item.col!=undefined ? 'layui-col-sm'+item.col+' '+'layui-col-md'+item.col:'layui-col-sm6 layui-col-md3'" v-for="item in badge" v-cloak="" v-if="item.count > 0">
            <div class="layui-card">
                <div class="layui-card-header">
                    {{item.name}}
                    <span class="layui-badge layuiadmin-badge" :class="item.background_color">{{item.field}}</span>
                </div>
                <div class="layui-card-body">
                    <p class="layuiadmin-big-font">{{item.count}}</p>
                    <p v-show="item.content!=undefined">
                        {{item.content}}
                        <span class="layuiadmin-span-color">{{item.sum}}<i :class="item.class"></i></span>
                    </p>
                </div>
            </div>
        </div>
        <!--enb-->
    </div>
    <!--列表-->
    <div class="layui-row layui-col-space15" >
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">订单列表</div>
                <div class="layui-card-body">
                    <div class="layui-btn-container" id="container-action">
                        <button class="layui-btn layui-btn-sm" data-type="del_order">批量删除订单</button>
                        <button class="layui-btn layui-btn-sm layui-btn-warm" data-type="write_order">订单核销</button>
                    </div>
                    <table class="layui-hide" id="List" lay-filter="List"></table>
                    <!--订单-->
                    <script type="text/html" id="order_id">
                        {{d.order_id}}<br/>
                        <span style="color: {{d.color}};">{{d.order_id}}</span><br/>　
                        {{#  if(d.is_del == 1){ }}<span style="color: {{d.color}};">订单已删除</span>{{# } }}　
                    </script>
                    <!--门店信息-->
                    <script type="text/html" id="storeTpl">
                        <div style="overflow: hidden" class="layui-col-xs3 layui-col-sm3 layui-col-md3">
                            <img style="margin:0;cursor: pointer;width: 60px;height: 60px" src="{{d.store.image}}">
                        </div>
                        <div class="layui-col-xs9 layui-col-sm9 layui-col-md9">
                            <p class="text-center" > {{d.store.name==null ? '暂无信息':d.store.name}} </p>
                            <p class="text-center" >{{d.store.phone}}</p>
                            <p class="text-center" >{{d.store.detailed_address}}</p>
                        </div>
                    </script>
                    <!--用户信息-->
                    <script type="text/html" id="userinfo">
                        {{#  if(d.user == null){ }}<span style="color: {{d.color}};">用户已删除</span>{{# } }}　
                        {{#  if(d.user !== null){ }}
                        <p class="text-center"> {{d.user.real_name==null ? '暂无信息':d.user.real_name}}</p>
                        <p class="text-center"> {{d.user.user_phone}}</p>
                        <p class="text-center">{{d.user.user_address}}</p>
                        {{# } }}　

                    </script>
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
                    <!--配送状态-->
                    <script type="text/html" id="deliveryStatusTpl">
                        <p style="font-weight: bold;font-size: large">{{d.delivery_status_str}}</p>
                    </script>
                    <script type="text/html" id="act">
                        <button class="btn btn-danger btn-xs" type="button"  onclick="$eb.createModalFrame('订单记录','{:Url('editStatus')}?id={{d.id}}')">
                            <i class="fa fa-gear"></i> 编辑配送状态</button>
                        <button type="button" class="layui-btn layui-btn-xs" onclick="dropdown(this)">操作 <span class="caret"></span></button>
                        <ul class="layui-nav-child layui-anim layui-anim-upbit">
                            <li>
                                <a href="javascript:void(0);" onclick="$eb.createModalFrame('订单记录','{:Url('reDelivery')}?orderId={{d.order_id}}&storeId={{d.store_id}}')">
                                    <i class="fa fa-file-text"></i> 指派配送员 配送
                                </a>
                            </li>
                            <li>
                                <a lay-event='marke' href="javascript:void(0);" >
                                    <i class="fa fa-paste"></i> 订单备注
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0);" onclick="$eb.createModalFrame('订单记录','{:Url('delivery_trace')}?orderId={{d.order_id}}')">
                                    <i class="fa fa-newspaper-o"></i> 订单配送轨迹
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
    var deliveryStatusMap = {:json_encode($delivery_status)};
    //console.log(deliveryStatusMap)
    layList.tableList('List',"{:Url('order_list')}",function (){
        return [
            {type:'checkbox'},
            {field: 'order_id', title: '订单号', sort: true,event:'order_id',width:'10%',templet:'#order_id'},
            {field: '', title: '用户信息',templet:'#userinfo',width:'15%',align:'center'},
            {field: 'store_id', title: '门店信息',templet:'#storeTpl',width:'15%',align:'center'},
            {field: 'deliveryman_id', title: '配送员信息',templet:"#deliverymanTpl",width:'15%',height: 'full-20'},
            {field: 'delivery_amount', title: '配送金额',width:'8%',align:'center'},
            {field: 'delivery_status', title: '配送状态',templet:'#deliveryStatusTpl',width:'8%',align:'center'},
            {field: 'system_confirm', title: '系统确认',width:'5%',sort: true,align:'center'},
            {field: 'create_time', title: '下单时间',width:'10%',sort: true,align:'center'},
            {field: 'right', title: '操作',align:'center',toolbar:'#act',width:'10%'},
        ];
    });
    layList.tool(function (event,data,obj) {
        switch (event) {
            case 'marke':
                var url =layList.U({c:'order.store_order',a:'remark'}),
                    id=data.id,
                    make=data.remark;
                $eb.$alert('textarea',{title:'请修改内容',value:make},function (result) {
                    if(result){
                        $.ajax({
                            url:url,
                            data:'remark='+result+'&id='+id,
                            type:'post',
                            dataType:'json',
                            success:function (res) {
                                if(res.code == 200) {
                                    $eb.$swal('success',res.msg);
                                }else
                                    $eb.$swal('error',res.msg);
                            }
                        })
                    }else{
                        $eb.$swal('error','请输入要备注的内容');
                    }
                });
                break;
            case 'danger':
                var url =layList.U({c:'order.store_order',a:'take_delivery',p:{id:data.id}});
                $eb.$swal('delete',function(){
                    $eb.axios.get(url).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success',res.data.msg);
                        }else
                            return Promise.reject(res.data.msg || '收货失败')
                    }).catch(function(err){
                        $eb.$swal('error',err);
                    });
                },{'title':'您确定要修改收货状态吗？','text':'修改后将无法恢复,请谨慎操作！','confirm':'是的，我要修改'})
                break;
            case 'order_info':
                $eb.createModalFrame(data.nickname+'订单详情',layList.U({a:'order_info',q:{oid:data.id}}));
                break;
        }
    })




    var action={
        del_order:function () {
            var ids=layList.getCheckData().getIds('id');

            if(ids.length){
                var url =layList.U({c:'distribution.order',a:'delete'});
                $eb.$swal('delete',function(){
                    $eb.axios.post(url,{ids:ids}).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success',res.data.msg);
                            window.location.reload();
                        }else
                            return Promise.reject(res.data.msg || '删除失败')
                    }).catch(function(err){
                        $eb.$swal('error',err);
                    });
                },{'title':'您确定要修删除订单吗？','text':'删除后将无法恢复,请谨慎操作！','confirm':'是的，我要删除'})
            }else{
                layList.msg('请选择要删除的订单');
            }
        },
        write_order:function () {
            return $eb.createModalFrame('订单核销',layList.U({a:'write_order'}),{w:500,h:400});
        },
    };
    $('#container-action').find('button').each(function () {
        $(this).on('click',function(){
            var act = $(this).data('type');
            action[act] && action[act]();
        });
    })
    //下拉框
    $(document).click(function (e) {
        $('.layui-nav-child').hide();
    })
    function dropdown(that){
        var oEvent = arguments.callee.caller.arguments[0] || event;
        oEvent.stopPropagation();
        var offset = $(that).offset();
        var top=offset.top-$(window).scrollTop();
        var index = $(that).parents('tr').data('index');
        $('.layui-nav-child').each(function (key) {
            if (key != index) {
                $(this).hide();
            }
        })
        if($(document).height() < top+$(that).next('ul').height()){
            $(that).next('ul').css({
                'padding': 10,
                'top': - ($(that).parents('td').height() / 2 + $(that).height() + $(that).next('ul').height()/2),
                'min-width': 'inherit',
                'position': 'absolute'
            }).toggle();
        }else{
            $(that).next('ul').css({
                'padding': 10,
                'top':$(that).parents('td').height() / 2 + $(that).height(),
                'min-width': 'inherit',
                'position': 'absolute'
            }).toggle();
        }
    }
    var deliveryman='<?=$deliveryman?>';
    var orderCount=<?=json_encode($orderCount)?>,payTypeCount=<?=json_encode($payTypeCount)?>,status=<?=$status ? $status : "''"?>;
    require(['vue'],function(Vue) {
        new Vue({
            el: "#app",
            data: {
                badge: [],
                //订单配送的状态
                orderStatus: deliveryStatusMap,

                deliverymanList:[
                    {

                    }
                ],
                storeList:[
                    {

                    }
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
                where:{
                    data:'',
                    status:0,
                    deliverymanId:0 ,
                    excel:0,
                    storeId:'',
                    order_id:'',
                    delivery_amount:{
                        min:0,
                        max:200
                    }
                },
                showtime: false,
            },
            watch: {
                'where.status':function () {
                    //this.getBadge();
                    layList.reload(this.where,true);
                },
                'where.data':function () {
                    //this.getBadge();
                    layList.reload(this.where,true);
                },
                'where.storeId':function () {
                    //this.getBadge();
                    layList.reload(this.where,true);
                },
                'where.deliverymanId':function () {
                    //this.getBadge();
                    layList.reload(this.where,true);
                }
            },
            methods: {
                setData:function(item){
                    var that=this;
                    if(item.is_zd==true){
                        that.showtime=true;
                        this.where.data=this.$refs.date_time.innerText;
                    }else{
                        this.showtime=false;
                        this.where.data=item.value;
                    }
                },

                getDeliverymanListByAdminBindStore:function () {
                    let that=this;
                    layList.basePost(layList.Url({c:'distribution.deliveryman',a:'getListByAdminBindStore'}),{},function (res) {
                        that.deliverymanList=  res.data.deliverymanList
                    });
                } ,


                getStoreListByAdminBindStore:function () {
                    let that=this;
                    layList.basePost(layList.Url({c:'system.system_store',a:'getListByAdminBindStore'}),{},function (res) {
                        that.storeList=  res.data.storeList
                    });
                } ,

                getBadge:function() {
                    var that=this;
                    layList.basePost(layList.Url({c:'order.store_order',a:'getBadge'}),this.where,function (rem) {
                        that.badge=rem.data;
                    });
                },
                search:function () {
                    this.where.excel=0;
                    //this.getBadge();
                    layList.reload(this.where,true);
                },
                refresh:function () {
                    layList.reload();
                    //this.getBadge();
                },
                excel:function () {
                    this.where.excel=1;
                    location.href=layList.U({c:'order.store_order',a:'order_list',q:this.where});
                }
            },
            mounted:function () {
                var that=this;
                //that.getBadge();
                window.formReload = this.search;
                layList.laydate.render({
                    elem:this.$refs.date_time,
                    trigger:'click',
                    eventElem:this.$refs.time,
                    range:true,
                    change:function (value){
                        that.where.data=value;
                    }
                });

                that.getDeliverymanListByAdminBindStore()
                that.getStoreListByAdminBindStore()

            }
        })
    });
</script>
{/block}