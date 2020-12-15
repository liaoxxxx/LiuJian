{extend name="public/container"}
{block name="content"}
<div class="row">
    <div class="col-sm-12">
        <div class="ibox">
            <div class="ibox-title">
                <button type="button" class="btn btn-w-m btn-primary" onclick="$eb.createModalFrame('添加发现商品','{:Url('store.storeProduct/index')}')">去添加商品</button>
                <div class="ibox-tools">

                </div>
            </div>
            <div class="ibox-content">
                <div class="row">
                    <div class="m-b m-l">

                        <form action="" class="form-inline">
                            <div class="input-group">
                                <input type="text" name="store_name" value="{$where.store_name}" placeholder="请输入商品名称" class="input-sm form-control"> <span class="input-group-btn">
                                    <button type="submit" class="btn btn-sm btn-primary"> <i class="fa fa-search" ></i>搜索</button> </span>
                            </div>
                        </form>
                    </div>

                </div>
                <div class="table-responsive">
                    <table class="table table-striped  table-bordered">
                        <thead>
                        <tr>
                            <th class="text-center">编号</th>
                            <th class="text-center">商品id</th>
                            <th class="text-center">商品图片</th>
                            <th class="text-center">商品名称</th>
                            <th class="text-center">排序</th>
                            <th class="text-center">状态</th>
                            <th class="text-center">操作</th>
                        </tr>
                        </thead>
                        <tbody class="">
                        {volist name="list" id="vo"}
                        <tr>
                            <td class="text-center">
                                {$vo.id}
                            </td>
                            <td class="text-center">
                                {$vo.product_id}
                            </td>
                            <td class="text-center">
                                <img class="image" data-image="{$vo.image}" width="45" height="45" src="{$vo.image}" />
                            </td>
                            <td class="text-center">
                                {$vo.store_name}
                            </td>
                            <td class="text-center">
                                {$vo.sort}
                            </td>
                            <td class="text-center">
                                <?php if(!$vo['is_show']){ ?>
                                <span class="label label-warning">隐藏</span>
                                <?php }else{ ?>
                                <span class="label label-primary">显示</span>
                                <?php } ?>
                            </td>
                            <td class="text-center">
                                <button class="btn btn-info btn-xs" type="button"  onclick="$eb.createModalFrame('编辑','{:Url('edit',array('id'=>$vo['id']))}')"><i class="fa fa-paste"></i> 编辑</button>
                                <button class="btn btn-danger btn-xs" data-url="{:Url('delete',array('id'=>$vo['id']))}" type="button"><i class="fa fa-warning"></i> 删除
                            </td>
                        </tr>
                        {/volist}
                        </tbody>
                    </table>
                </div>
                {include file="public/inner_page"}
            </div>
        </div>
    </div>
</div>
<script>
    $('.btn-danger').on('click',function(){
        var _this = $(this),url =_this.data('url');
        $eb.$swal('delete',function(){
            $eb.axios.get(url).then(function(res){
                if(res.status == 200 && res.data.code == 200) {
                    $eb.$swal('success',res.data.msg);
                        _this.parents('tr').remove();
                }else
                    return Promise.reject(res.data.msg || '删除失败')
            }).catch(function(err){
                $eb.$swal('error',err);
            });
        },{'title':'您确定要删除吗？','text':'删除后将无法恢复,请谨慎操作！','confirm':'是的，我要删除'})
    });

    $(".image").on('click',function (e) {
        var images = $(this).data('image');
        $eb.openImage(images);
    })
</script>
{/block}
