{extend name="public/container"}
{block name="content"}
<style>
    .sum-amount-text{
        font-size: 16px;
        font-weight: bold;
    }
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox">
            <div class="ibox-content">
                <div class="table-responsive">
                    <table class="table table-striped  table-bordered">
                        <thead>
                        <tr>
                            <th class="text-center">配送订单编号</th>
                            <th class="text-center">配送距离 直线/路线</th>
                            <th class="text-center">配送金额</th>
                            <th class="text-center">创建时间</th>
                        </tr>
                        </thead>
                        <tbody class="">
                        {volist name="orderDeliveryList" id="vo"}
                        <tr>
                            <td class="text-center">
                                {$vo.id}
                            </td>
                            <td class="text-center">
                                {$vo.linear_distance} / {$vo.route_distance}
                            </td>
                            <td class="text-center">
                                ￥ {$vo.delivery_amount}
                            </td>
                            <td class="text-center">
                                {$vo.create_time|date='Y-m-d H:i:s'}
                            </td>
                        </tr>
                        {/volist}
                        </tbody>
                    </table>
                    <div>
                       <span class="sum-amount-text"> 合计: ￥  {$withdrawInfo.withdraw_amount}</span>
                    </div>
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
