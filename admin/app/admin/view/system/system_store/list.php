{extend name="public/container"}
{block name="head_top"}
<link href="{__MODULE_PATH}wechat/news/css/index.css" type="text/css" rel="stylesheet">
{/block}
{block name="content"}
<style>
    tr td img{height: 50px;}
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>配送员员搜索</h5>
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
                            <label class="layui-form-label">店名编号：</label>
                            <div class="layui-input-inline">
                                <input type="text" name="storeName" id="storeName" lay-verify="storeName" style="width: 100%" autocomplete="off" placeholder="请输入店名" class="layui-input">
                            </div>
                        </div>



                        <div class="layui-inline">
                            <label class="layui-form-label">显　　示：</label>
                            <div class="layui-input-inline">
                                <select name="is_show" id="is_show" lay-verify="status">
                                    <option value="">全部</option>
                                    <option value="1">显示</option>
                                    <option value="0">隐藏</option>
                                </select>
                            </div>
                        </div>

                        <div class="layui-inline">
                            <label class="layui-form-label">状　　态：</label>
                            <div class="layui-input-inline">
                                <select name="status" id="status" lay-verify="status">
                                    <option value="">全部</option>
                                    <option value="1">启用</option>
                                    <option value="0">禁用</option>
                                </select>
                            </div>
                        </div>



                        <div class="layui-inline" id="province-div">
                            <label class="layui-form-label">省　　份：</label>
                            <div class="layui-input-inline">
                                <select name="province" lay-verify="province" lay-filter='province' id="province">
                                    <option value="" id="province-top">请选择省</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-inline" id="city-div">
                            <label class="layui-form-label">市　　区：</label>
                            <div class="layui-input-inline">
                                <select name="city" lay-verify="city"  lay-filter='city' id="city">
                                    <option value="" id="city-top">请选择市</option>
                                </select>
                            </div>
                        </div>



                        <div class="layui-inline">
                            <label class="layui-form-label">选择添加时间：</label>
                            <div class="layui-input-inline">
                                <input type="text" class="layui-input time-w" name="add_time" lay-verify="add_time"  id="add_time" placeholder=" - ">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">
                                <button class="layui-btn layui-btn-sm layui-btn-normal" id="search" lay-submit=""
                                        lay-filter="search">
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
            <div class="ibox-title">
                <button type="button" class="btn btn-w-m btn-primary" onclick="$eb.createModalFrame(this.innerText,'{:Url('create')}',{w:1100,h:760})">添加门店</button>
                <div style="margin-top: 2rem"></div>
            </div>
            <div class="ibox-content">
                <table class="footable table table-striped  table-bordered " id="storeList" lay-filter="storeListFilter" data-page-size="20"></table>
            </div>
        </div>
    </div>
</div>
<script src="{__ADMIN_PATH}js/layuiList.js"></script>
<script src="{__FRAME_PATH}js/content.min.js?v=1.0.0"></script>
{/block}
{block name="script"}

<!--开关模板 -->
<script type="text/html" id="isShowTpl">
    <input type='checkbox' name='isShowTpl' lay-skin='switch' value="{{d.id}}" lay-filter='is_show' lay-text='显示|隐藏'  {{ d.is_show == 1 ? 'checked' : '' }}>
</script>
<script type="text/html" id="statusTpl">
    <input type='checkbox' name='status' lay-skin='switch' value="{{d.id}}" lay-filter='status' lay-text='启用|禁用'  {{ d.status == 1 ? 'checked' : '' }}>
</script>

<script type="text/html" id="storeTpl">
    <div class="layui-row">
        <div class="layui-col-xs4 layui-col-sm4 layui-col-md4">
            {{# if(d.image){ }}
            <img src="{{d.image}}" alt="">
            {{# }else{ }}
            暂无图片
            {{# } }}
        </div>
        <div class="layui-col-xs8 layui-col-sm8 layui-col-md8">
            <span>{{d.name}}</span>
            <br>
            <span>{{d.address}}<br>{{d.detailed_address}}</span>
        </div>
    </div>
</script>


<script type="text/html" id="phoneTpl">
    <div class="layui-row">
        <div class="layui-col-xs12 layui-col-sm12 layui-col-md12">
            {{d.phone}}
        </div>
        <div class="layui-col-xs12 layui-col-sm12 layui-col-md12">
            {{d.day_time}}
        </div>
    </div>
</script>

<script type="text/html" id="optionTpl">
    <div class="layui-row">
        <button style="margin-top: 5px;" class="btn btn-info btn-xs" type="button"  onclick="$eb.createModalFrame('编辑','{:Url('admin/system.system_store/edit')}?id={{d.id}}',{w:1100,h:760})">
            <i class="fa fa-paste"></i> 编 辑</button>
        <button style="margin-top: 5px;" class="btn btn-info btn-xs" type="button"  onclick="$eb.createModalFrame('配送配置','{:Url('admin/system.system_store/delivery_config')}?store_id={{d.id}}',{w:1100,h:760})">
            <i class="fa fa-paste"></i>配送配置</button>
        <button style="margin-top: 5px;" class="btn btn-info btn-xs" lay-event="delOne" type="button"> <i class="fa fa-warning"></i> 删除</button>
    </div>
</script>


<script>



   layui.use('table', function () {
       var table = layui.table, form = layui.form
       //第一个实例
       const tableIn = table.render({
           elem: '#storeList',
           url: 'store_list',  //    ?$where&is_show={:$where['is_show']??''}&status={:$where['status']??''}&add_time={:$where['add_time']??''}', //数据接口,
           method: 'post',
           page: true, //开启分页,
           cols: [[ //表头
               {field: 'id', title: 'id', event: 'uid', width: '10%', align: 'center'},
               {field: 'storeName', title: '门店信息', width: '30%', align: 'center', templet: "#storeTpl"},
               {field: 'phone', title: '营业信息', align: 'center', width: '10%', templet: '#phoneTpl'},
               {field: 'status', title: '显示/隐藏', width: '10%', align: 'center', templet: '#isShowTpl'},
               {field: 'status', title: '启用/禁用', width: '10%', align: 'center', templet: '#statusTpl'},
               {field: 'add_time', title: '添加时间', align: 'center', width: '10%'},
               {field: 'update_time', title: '更新时间', align: 'center', width: '10%'},
               {field: '', title: '操作', templet: "#optionTpl", align: 'center'},

           ]]
       })

       //启用开关
       form.on('switch(status)', function (obj) {
           loading = layer.load(1, {shade: [0.1, '#fff']});
           $.post(
               "storeStatus",
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

       //显示开关
       form.on('switch(is_show)', function (obj) {
           loading = layer.load(1, {shade: [0.1, '#fff']});
           $.post(
               "storeShow",
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

       table.on('tool(storeListFilter)', function (obj) { //注：tool 是工具条事件名，test 是 table 原始容器的属性 lay-filter="对应的值"
           var data = obj.data;
           if (obj.event === 'delOne') {
               layer.confirm('您确定要删除门店与配送员的工作关联吗？', function (index) {
                   var loading = layer.load(1, {shade: [0.1, '#fff']});
                   $.post("{:url('delete')}",
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
       })




        $('#search').on('click', function () {
            let add_time = $('#add_time').val();
            let status = $('#status').val();
            let storeName = $('#storeName').val();
            let is_show = $('#is_show').val();
            let where= {status: status, add_time: add_time,storeName:storeName,is_show:is_show}
            console.log(where)
            tableIn.reload({where:where});
            return false
        });


    })


        layList.date('add_time')

    $('#province-div').hide();
    $('#city-div').hide();
    layList.select('country',function (odj,value,name) {
        var html = '';
        $.each(city,function (index,item) {
            html += '<option value="'+item.label+'">'+item.label+'</option>';
        })
        if(odj.value == 'domestic'){
            $('#province-div').show();
            $('#city-div').show();
            $('#province-top').siblings().remove();
            $('#province-top').after(html);
            $('#province').val('');
            layList.form.render('select');
        }else{
            $('#province-div').hide();
            $('#city-div').hide();
        }
        $('#province').val('');
        $('#city').val('');
    });
    layList.select('province',function (odj,value,name) {
        var html = '';
        $.each(city,function (index,item) {
            if(item.label == odj.value){
                $.each(item.children,function (indexe,iteme) {
                    html += '<option value="'+iteme.label+'">'+iteme.label+'</option>';
                })
                $('#city').val('');
                $('#city-top').siblings().remove();
                $('#city-top').after(html);
                layList.form.render('select');
            }
        })
    });
    layList.form.render();





    //layList.sort('uid');
    //监听并执行 now_money 的排序
    // layList.sort('now_money');




</script>
{/block}
