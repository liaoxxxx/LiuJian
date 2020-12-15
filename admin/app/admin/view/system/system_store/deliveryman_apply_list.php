{extend name="public/container"}
{block name="content"}
<style>
</style>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15"  id="app">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">工作申请记录搜索</div>
                <div class="layui-card-body">
                <form class="layui-form layui-form-pane">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">姓名：</label>
                            <div class="layui-input-inline">
                                <input type="text" name="real_name" lay-verify="real_name" style="width: 100%" autocomplete="off" placeholder="请输入姓名" class="layui-input">
                            </div>
                        </div>
<!--
                        <div class="layui-inline">
                            <label class="layui-form-label">申请结果：</label>
                            <div class="layui-input-block">
                                <select name="apply_status" lay-verify="apply_status">
                                    <option value="">全部</option>
                                    <option value="1">已处理</option>
                                    <option value="0">未处理</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">处理状态:</label>
                            <div class="layui-input-block">
                                <select name="is_handled" lay-verify="is_handled">
                                    <option value="">全部</option>
                                    <option value="1">已通过</option>
                                    <option value="0">未通过</option>
                                </select>
                            </div>
                        </div>-->
                        <div class="layui-inline">
                            <label class="layui-form-label">申请时间：</label>
                            <div class="layui-input-inline">
                                <input type="text" class="layui-input time-w" name="create_time" lay-verify="create_time"
                                       id="create_time" placeholder=" - ">
                            </div>
                        </div>
                        <div class="layui-inline">

                                <button class="layui-btn layui-btn-sm layui-btn-normal" lay-submit="" lay-filter="search">
                                    <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>搜索
                                </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">分类列表</div>
                <div class="layui-card-body">
                    <table id="applyList" class="footable table table-striped  table-bordered " data-page-size="20" lay-filter="applyListFilter"></table>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="{__ADMIN_PATH}js/layuiList.js"></script>
<script src="{__FRAME_PATH}js/content.min.js?v=1.0.0"></script>
{/block}
{block name="script"}
<script type="text/html" id="deliverymanTpl">
    <div class="layui-row">
        <div class="layui-col-xs6 layui-col-sm6 layui-col-md6">
            <img src="{{d.deliveryman.avatar}}" alt="">
        </div>
        <div class="layui-col-xs6 layui-col-sm6 layui-col-md6">
            <span>{{d.deliveryman.real_name}}</span>
            <span>{{d.deliveryman.phone}}</span>
        </div>
    </div>
</script>


<script type="text/html" id="storeTpl">
    <div class="layui-row">
        <div class="layui-col-xs6 layui-col-sm6 layui-col-md6">
            {{# if(d.pic){ }}
            <img src="{{d.store.image}}" alt="">
            {{# }else{ }}
            暂无图片
            {{# } }}
        </div>
        <div class="layui-col-xs6 layui-col-sm6 layui-col-md6">
            <span>{{d.store.name}}</span>
            <br>
            <span>{{d.store.detailed_address}}</span>
        </div>
    </div>
</script>


<script type="text/html" id="applyStatusTpl">
    {{#  if(d.apply_status ==1 ){ }}
    <span class="success">已通过</span>
    {{#  } else { }}
    <span class="error">未通过</span>
    {{#  } }}
</script>


<script type="text/html" id="isHandledTpl">
        {{#  if(d.is_handled ==1 ){ }}
        <span class="success">已处理</span>
        {{#  } else { }}
        <span class="error">未处理</span>
        {{#  } }}
</script>

<script type="text/html" id="handleTpl">
    {{#  if(d.is_handled ==0 ){ }}
    <a class="layui-btn " lay-event="passApply">通过申请</a>

    <a class="layui-btn layui-btn-dange" lay-event="refuseApply">拒绝申请</a>
    {{#  } else { }}
    <span style="font-weight: bold">已处理，无法操作</span>
    {{#  } }}


</script>
<script>
    layui.use('table',function () {
        var table = layui.table;
        //第一个实例
        let tableIn= table.render({
            elem: '#applyList'
            , url: 'deliverymanApplyListTable' //数据接口
            , method: 'post'
            , page: true //开启分页
            , cols: [[ //表头
                {type: 'checkbox'},
                {field: 'id', title: 'id', event: 'uid', width: '4%', align: 'center'},
                {field: 'storeName', title: '店名', width: '300', align: 'center', templet: "#storeTpl"},
                {field: 'deliverymanName', title: '配送员', align: 'center', width: '250', templet: "#deliverymanTpl"},
                {field: 'applyStatusStr', title: '申请结果', align: 'center', width: '100', templet: '#applyStatusTpl'},
                {field: 'isHandledStr', title: '处理状态', width: '100', align: 'center', templet: '#isHandledTpl'},
                {field: 'deliveryman_mark', title: '申请备注', align: 'center', width: '350'},
                {field: 'create_time', title: '申请时间', align: 'center', width: '180'},
                {field: '', title: '操作', templet: "#handleTpl", width: '300', align: 'center'},

            ]]
        })


        $('#search').on('click', function () {
            let create_time = $('#create_time').val();
            let real_name = $('#real_name').val();
            let where= {create_time: create_time, real_name: real_name,}
            console.log(where)
            tableIn.reload({where:where});
            return false
        });

        table.on('tool(applyListFilter)', function(obj){ //注：tool 是工具条事件名，test 是 table 原始容器的属性 lay-filter="对应的值"
            var data = obj.data;
            if(obj.event === 'passApply'){
                layer.confirm('您确定要通过该申请吗？', function(index){
                    var loading = layer.load(1, {shade: [0.1, '#fff']});
                    $.post("{:url('passApply')}",
                        {id: data.id, flag: 'pass'},
                        function (res) {
                            console.log(res)
                            layer.close(loading);
                            if (res.code === 200) {
                                layer.msg('操作成功,' + res.msg, {time: 1000, icon: 1});
                                tableIn.reload({where: {catid: '{:input("catid")}'}});
                            } else {
                                layer.msg('操作失败！' + res.msg, {time: 1000, icon: 2});
                            }
                        },
                        'json'
                    );
                    layer.close(index);
                });
            }
            if(obj.event === 'refuseApply'){
                layer.confirm('您确定要拒绝该申请吗？', function(index){
                    var loading = layer.load(1, {shade: [0.1, '#fff']});
                    $.post(
                        "{:url('passApply')}",
                        {id: data.id, flag: 'refuse'},
                        function (res) {
                            console.log(res)
                            layer.close(loading);
                            if (res.code === 200) {
                                layer.msg('操作成功,'+res.msg, {time: 1000, icon: 1});
                                tableIn.reload({where: {catid: '{:input("catid")}'}});
                            } else {
                                layer.msg('操作失败！'+res.msg,{time: 1000, icon: 2});
                            }
                        },
                        'json'
                    );
                    layer.close(index);
                });
            }



        })
    })
    layList.date('create_time');


</script>
{/block}
{/block}
