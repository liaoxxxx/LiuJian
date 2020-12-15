{extend name="public/container"}
{block name="content"}
<div class="row">
    <div class="col-sm-12">
        <div class="ibox">
            <div class="ibox-content">
                <div class="table-responsive">
                    <table class="table table-striped  table-bordered">
                        <thead>
                        <tr>
                            <th class="text-center">订单编号</th>
                            <th class="text-center">配送员</th>
                            <th class="text-center">操作记录</th>
                            <th class="text-center">操作时间</th>
                        </tr>
                        </thead>
                        <tbody class="">
                        {volist name="traceList" id="vo"}
                        <tr>
                            <td class="text-center">
                                {$vo.id}
                            </td>
                            <td class="text-center">
                                {$vo.deliveryman_str}
                            </td>
                            <td class="text-center">
                                {$vo.delivery_step_str}
                            </td>
                            <td class="text-center">
                                {$vo.create_time|date='Y-m-d H:i:s'}
                            </td>
                        </tr>
                        {/volist}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
{/block}
{block name="script"}
<script>

</script>
{/block}
