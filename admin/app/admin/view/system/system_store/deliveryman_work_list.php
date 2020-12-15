{extend name="public/container"}
{block name="head_top"}

{/block}
{block name="content"}
<style>
    tr td img{height: 50px;}
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>工作申请记录搜索</h5>
                <div class="ibox-tools">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                </div>
            </div>
            <div class="ibox-content" style="display: block;">
                <form class="layui-form">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">姓名编号：</label>
                            <div class="layui-input-inline">
                                <input type="text" name="nickname" lay-verify="nickname" style="width: 100%" autocomplete="off" placeholder="请输入姓名、编号、手机号" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-inline">
                            <label class="layui-form-label">处理结果：</label>
                            <div class="layui-input-inline">
                                <select name="status" lay-verify="status">
                                    <option value="">全部</option>
                                    <option value="1">已通过</option>
                                    <option value="0">未通过</option>
                                </select>
                            </div>
                        </div>

                        <div class="layui-inline">
                            <label class="layui-form-label">关联时间：</label>
                            <div class="layui-input-inline">
                                <input type="text" class="layui-input time-w" name="user_time" lay-verify="user_time"
                                       id="user_time" placeholder=" - ">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">
                                <button class="layui-btn layui-btn-sm layui-btn-normal" lay-submit="" lay-filter="search">
                                    <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>搜索
                                </button>
                            </label>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12 ">
        <div class="ibox">
            <div class="ibox-content">
                <table class="footable table table-striped  table-bordered " id="workList" lay-filter="workListFilter" data-page-size="20">
                </table>
            </div>
        </div>

    </div>

</div>
<script src="{__ADMIN_PATH}js/layuiList.js"></script>
<script src="{__FRAME_PATH}js/content.min.js?v=1.0.0"></script>


<script type="text/html" id="status">
    <input type='checkbox' name='id' lay-skin='switch' value="{{d.id}}" lay-filter='status' lay-text='启用|禁用'  {{ d.status == 1 ? 'checked' : '' }}>
</script>

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
            {{# if(d.store.image){ }}
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


<script type="text/html" id="handleTpl">
    <button class="layui-btn layui-btn-xs" lay-event='del'>
        <i class="fa fa-warning"></i> 删除
    </button>
</script>
{/block}
{block name="script"}
<script>
    layui.use('table', function () {
        var table = layui.table,form =layui.form
        //第一个实例
        let tableIn= table.render({
            elem: '#workList'
            , url: 'deliverymanWorkListTable' //数据接口
            , method: 'post'
            , page: true //开启分页
            , cols: [[ //表头
                {type: 'checkbox'},
                {field: 'id', title: 'id', event: 'uid', width: '5%', align: 'center'},
                {field: 'storeName', title: '店名', width: '25%', align: 'center', templet: "#storeTpl"},
                {field: 'deliverymanName', title: '配送员', align: 'center', width: '25%', templet: "#deliverymanTpl"},
                {field: 'status', title: '处理状态', width: '180', align: 'center', templet: '#status'},
                {field: 'create_time', title: '申请时间', align: 'center', width: '10%'},
                {field: 'update_time', title: '更新时间', align: 'center', width: '10%'},
                {field: 'apply_status', title: '操作', templet: "#handleTpl", width: '300', align: 'center'},

            ]]
        })

        form.on('switch(status)', function (obj) {
            loading = layer.load(1, {shade: [0.1, '#fff']});
            $.post(
                "workStatus",
                {
                    'id': obj.value,
                },
                function (res) {
                    console.log(res)
                    layer.close(loading);
                    if (res.code === 200) {
                        layer.msg(res.msg, {time: 1000, icon: 1});

                    } else {
                        layer.msg(res.msg, {time: 1000, icon: 2});
                    }
                },
                'json'
            )
        });

        table.on('tool(workListFilter)', function (obj) { //注：tool 是工具条事件名，test 是 table 原始容器的属性 lay-filter="对应的值"
                var data = obj.data;
                if (obj.event === 'del') {
                    layer.confirm('您确定要删除门店与配送员的工作关联吗？', function (index) {
                        var loading = layer.load(1, {shade: [0.1, '#fff']});
                        $.post("{:url('delWorkRelationship')}",
                            {id: data.id, flag: 'del'},
                            function (res) {
                                console.log(res)
                                layer.close(loading);
                                if (res.code === 200) {
                                    layer.msg('操作成功,' + res.msg, {time: 1000, icon: 1});
                                    tableIn.reload({});
                                } else {
                                    layer.msg('操作失败！' + res.msg, {time: 1000, icon: 2});
                                }
                            },
                            'json'
                        );
                        layer.close(index);
                    });
                }
            }
        )

    })

    layList.date('user_time');

</script>
{/block}
