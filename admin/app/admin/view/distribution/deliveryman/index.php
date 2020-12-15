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
                            <label class="layui-form-label">姓名编号：</label>
                            <div class="layui-input-inline">
                                <input type="text" name="nickname" lay-verify="nickname" style="width: 100%" autocomplete="off" placeholder="请输入姓名、编号、手机号" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-inline">
                            <label class="layui-form-label">状　　态：</label>
                            <div class="layui-input-inline">
                                <select name="status" lay-verify="status">
                                    <option value="">全部</option>
                                    <option value="1">正常</option>
                                    <option value="0">锁定</option>
                                </select>
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">性　　别：</label>
                            <div class="layui-input-inline">
                                <select name="sex" lay-verify="sex">
                                    <option value="">全部</option>
                                    <option value="1">男</option>
                                    <option value="2">女</option>
                                    <option value="0">保密</option>
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
                    </div>
                    <div class="layui-inline">
                        <label class="layui-form-label">访问情况：</label>
                        <div class="layui-input-inline">
                            <select name="user_time_type" lay-verify="user_time_type">
                                <option value="">全部</option>
                                <option value="visitno">时间段未访问</option>
                                <option value="visit">时间段访问过</option>
                                <option value="add_time">首次访问</option>
                            </select>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">选择时间：</label>
                            <div class="layui-input-inline">
                                <input type="text" class="layui-input time-w" name="user_time" lay-verify="user_time"  id="user_time" placeholder=" - ">
                            </div>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">
                            <button class="layui-btn layui-btn-sm layui-btn-normal" lay-submit="" lay-filter="search" >
                                <i class="layui-icon layui-icon-search layuiadmin-button-btn"></i>搜索</button>
                        </label>
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
                <!--<button type="button" class="btn btn-w-m btn-primary" onclick="$eb.createModalFrame(this.innerText,'{:Url('create',array('cid'=>$where.cid))}',{w:1100,h:760})">添加配送员</button>-->
                <div style="margin-top: 2rem"></div>
                <div class="row">
                    <div class="m-b m-l">
                        <form action="" class="form-inline">
                            <div class="input-group">
                                <input type="text" name="title" value="{$where.title}" placeholder="请输入关键词" class="input-sm form-control"> <span class="input-group-btn"><button type="submit" class="btn btn-sm btn-primary"> <i class="fa fa-search" ></i>搜索</button> </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="ibox-content">
                <table class="footable table table-striped  table-bordered " data-page-size="20">
                    <thead>
                    <tr>
                        <th class="text-center" width="5%">id</th>
                        <th class="text-center" width="10%">头像</th>
                        <th class="text-center" width="10%">姓名</th>
                        <th class="text-center" width="10%">昵称</th>
                        <th class="text-center" width="10%">证件号</th>
                        <th class="text-center" width="5%">余额</th>
                        <th class="text-center" width="5%">接单状态</th>
                        <th class="text-center" width="8%">接单总数</th>
                        <th class="text-center" width="8%">接单总额</th>
                        <th class="text-center" width="8%">审核状态</th>
                        <th class="text-center" width="15%">备注</th>
                        <th class="text-center" width="15%">添加时间</th>
                        <th class="text-center" width="20%">操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    {volist name="list" id="vo"}
                    <tr>
                        <td>{$vo.id}</td>
                        <td>
                            <img src="{$vo.avatar}"/>
                        </td>
                        <td>{$vo.real_name}</td>
                        <td>{$vo.nickname}</td>
                        <td>{$vo.card_id}</td>
                        <td>{$vo.now_money}￥</td>
                        <td>{$vo.is_receiving}</td>
                        <td>{$vo.receiving_count}单</td>
                        <td>{$vo.status}￥</td>
                        <td>{$vo.is_receiving}</td>
                        <td>{$vo.mark}</td>
                        <td>{$vo.add_time|date="Y-m-d H:i:s"}</td>

                        <td class="text-center">
                            <button style="margin-top: 5px;" class="btn btn-info btn-xs" type="button"  onclick="$eb.createModalFrame('编辑','{:Url('edit',array('id'=>$vo['id'],'cid'=>$where.cid))}',{w:1100,h:760})"><i class="fa fa-paste"></i> 编 辑</button>
                            <button  style="margin-top: 5px;" class="btn btn-warning btn-xs del_news_one" data-id="{$vo.id}" type="button" data-url="{:Url('delete',array('id'=>$vo['id']))}" ><i class="fa fa-warning"></i> 删除</button>
                        </td>
                    </tr>
                    {/volist}
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>
<script src="{__ADMIN_PATH}js/layuiList.js"></script>
<script src="{__FRAME_PATH}js/content.min.js?v=1.0.0"></script>
{/block}
{block name="script"}
<script>
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
    layList.tableList('userList',"{:Url('get_user_list')}",function () {
        return [
            {type:'checkbox'},
            {field: 'uid', title: '编号',event:'uid',width:'4%',align:'center'},
            {field: 'avatar', title: '头像', event:'open_image', width: '6%',align:'center', templet: '<p lay-event="open_image"><img class="avatar" style="cursor: pointer" class="open_image" data-image="{{d.avatar}}" src="{{d.avatar}}" alt="{{d.nickname}}"></p>'},
            {field: 'nickname', title: '姓名',templet:'#nickname',align:'center'},
            {field: 'phone', title: '手机号',align:'center',width:'8%'},
            {field: 'now_money', title: '余额',width:'6%',sort:true,event:'now_money',align:'center'},
            {field: 'pay_count', title: '购买次数',align:'center',width:'6%'},
            {field: 'extract_count_price', title: '累计提现',align:'center',width:'6%'},
            {field: 'integral', title: '积分',width:'6%',sort:true,event:'integral',align:'center'},
            {field: 'spread_uid_nickname', title: '推荐人',align:'center'},
            {field: 'sex', title: '性别',width:'4%',align:'center'},
            {field: 'data_time', title: '访问日期',align:'center',width:'12%',templet:'#data_time'},
            {field: 'status', title: '状态',templet:"#checkboxstatus",width:'6%',align:'center'},
            {field: 'user_type', title: '用户类型',width:'6%',align:'center'},
            {field: 'operate', title: '操作', width: '10%', align: 'center', toolbar: '#barDemo'}
        ];
    });
    layList.date('last_time');
    layList.date('add_time');
    layList.date('user_time');
    layList.date('time');
    //监听并执行 uid 的排序
    layList.sort(function (obj) {
        var layEvent = obj.field;
        var type = obj.type;
        switch (layEvent){
            case 'uid':
                layList.reload({order: layList.order(type,'u.uid')},true,null,obj);
                break;
            case 'now_money':
                layList.reload({order: layList.order(type,'u.now_money')},true,null,obj);
                break;
            case 'integral':
                layList.reload({order: layList.order(type,'u.integral')},true,null,obj);
                break;
        }
    });
    //监听并执行 uid 的排序
    layList.tool(function (event,data,obj) {
        var layEvent = event;
        switch (layEvent){
            case 'edit':
                $eb.createModalFrame('编辑',layList.Url({a:'edit',p:{uid:data.uid}}));
                break;
            case 'see':
                $eb.createModalFrame(data.nickname+'-会员详情',layList.Url({a:'see',p:{uid:data.uid}}));
                break;
            case 'del_level':
                $eb.$swal('delete',function(){
                    $eb.axios.get(layList.U({a:'del_level',q:{uid:data.uid}})).then(function(res){
                        if(res.status == 200 && res.data.code == 200) {
                            $eb.$swal('success',res.data.msg);
                            obj.update({vip_name:false});
                            layList.reload();
                        }else
                            return Promise.reject(res.data.msg || '删除失败')
                    }).catch(function(err){
                        $eb.$swal('error',err);
                    });
                },{
                    title:'您确定要清除【'+data.nickname+'】的会员等级吗？',
                    text:'清除后无法恢复请谨慎操作',
                    confirm:'是的我要清除'
                })
                break;
            case 'give_level':
                $eb.createModalFrame(data.nickname+'-赠送会员',layList.Url({a:'give_level',p:{uid:data.uid}}),{w:500,h:200});
                break;
            case 'money':
                $eb.createModalFrame(data.nickname+'-积分余额修改',layList.Url({a:'edit_other',p:{uid:data.uid}}));
                break;
            case 'open_image':
                $eb.openImage(data.avatar);
                break;
        }
    });
    //layList.sort('uid');
    //监听并执行 now_money 的排序
    // layList.sort('now_money');
    //监听 checkbox 的状态
    layList.switch('status',function (odj,value,name) {
        if(odj.elem.checked==true){
            layList.baseGet(layList.Url({a:'set_status',p:{status:1,uid:value}}),function (res) {
                layList.msg(res.msg);
            });
        }else{
            layList.baseGet(layList.Url({a:'set_status',p:{status:0,uid:value}}),function (res) {
                layList.msg(res.msg);
            });
        }
    });
    layList.search('search',function(where){
        if(where['user_time_type'] != '' && where['user_time'] == '') return layList.msg('请选择选择时间');
        if(where['user_time_type'] == '' && where['user_time'] != '') return layList.msg('请选择访问情况');
        layList.reload(where,true);
    });

    var action={
        set_status_f:function () {
            var ids=layList.getCheckData().getIds('uid');
            if(ids.length){
                layList.basePost(layList.Url({a:'set_status',p:{is_echo:1,status:0}}),{uids:ids},function (res) {
                    layList.msg(res.msg);
                    layList.reload();
                });
            }else{
                layList.msg('请选择要封禁的会员');
            }
        },
        set_status_j:function () {
            var ids=layList.getCheckData().getIds('uid');
            if(ids.length){
                layList.basePost(layList.Url({a:'set_status',p:{is_echo:1,status:1}}),{uids:ids},function (res) {
                    layList.msg(res.msg);
                    layList.reload();
                });
            }else{
                layList.msg('请选择要解封的会员');
            }
        },
        set_grant:function () {
            var ids=layList.getCheckData().getIds('uid');
            if(ids.length){
                var str = ids.join(',');
                $eb.createModalFrame('发送优惠券',layList.Url({c:'ump.store_coupon',a:'grant',p:{id:str}}),{'w':800});
            }else{
                layList.msg('请选择要发送优惠券的会员');
            }
        },
        set_template:function () {
            var ids=layList.getCheckData().getIds('uid');
            if(ids.length){
                var str = ids.join(',');
            }else{
                layList.msg('请选择要发送模板消息的会员');
            }
        },
        set_info:function () {
            var ids=layList.getCheckData().getIds('uid');
            if(ids.length){
                var str = ids.join(',');
                $eb.createModalFrame('发送站内信息',layList.Url({c:'user.user_notice',a:'notice',p:{id:str}}),{'w':1200});
            }else{
                layList.msg('请选择要发送站内信息的会员');
            }
        },
        set_custom:function () {
            var ids=layList.getCheckData().getIds('uid');
            if(ids.length){
                var str = ids.join(',');
                $eb.createModalFrame('发送客服图文消息',layList.Url({c:'wechat.wechat_news_category',a:'send_news',p:{id:str}}),{'w':1200});
            }else{
                layList.msg('请选择要发送客服图文消息的会员');
            }
        },
        refresh:function () {
            layList.reload();
        }
    };
    $('.conrelTable').find('button').each(function () {
        var type=$(this).data('type');
        $(this).on('click',function () {
            action[type] && action[type]();
        })
    })
    $(document).on('click',".open_image",function (e) {
        var image = $(this).data('image');
        $eb.openImage(image);
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
                'top': - ($(that).parent('td').height() / 2 + $(that).height() + $(that).next('ul').height()/2),
                'left':offset.left-$(that).parents('td').offset().left-20,
                'min-width': 'inherit',
                'position': 'absolute'
            }).toggle();
        }else{
            $(that).next('ul').css({
                'padding': 10,
                'top':$(that).parent('td').height() / 2 + $(that).height(),
                'left':offset.left-$(that).parents('td').offset().left-20,
                'min-width': 'inherit',
                'position': 'absolute'
            }).toggle();
        }
    }

</script>
{/block}
