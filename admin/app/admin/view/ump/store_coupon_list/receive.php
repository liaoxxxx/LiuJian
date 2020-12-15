{extend name="public/container"}
{block name="content"}
<div class="row">
    <div class="col-sm-12">
        <div class="ibox">
            <div class="ibox-content">
                <div class="row">
                    <div class="m-b m-l">

                        <form action="" class="form-inline">
                            <select name="status" aria-controls="editable" class="form-control input-sm">
                                <option value="">状态</option>
                                <option value="1" {eq name="where.status" value="1"}selected="selected"{/eq}>正常</option>
                                <option value="0" {eq name="where.status" value="0"}selected="selected"{/eq}>未开启</option>
                                <option value="2" {eq name="where.status" value="2"}selected="selected"{/eq}>已过期</option>
                                <option value="2" {eq name="where.status" value="2"}selected="selected"{/eq}>已失效</option>
                            </select>
                            <div class="input-group">
                                <input type="text" name="coupon_code" value="{$where.coupon_code}" placeholder="请输入优惠券兑换码" class="input-sm form-control"> <span class="input-group-btn">
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
                            <th class="text-center">优惠券名称</th>
                            <th class="text-center">优惠券面值</th>
                            <th class="text-center">优惠券最低消费</th>
                            <th class="text-center">优惠券开始使用时间</th>
                            <th class="text-center">优惠券结束使用时间</th>
                            <th class="text-center">状态</th>
                        </tr>
                        </thead>
                        <tbody class="">
                        {volist name="list" id="vo"}
                        <tr>
                            <td class="text-center">
                                {$vo.id}
                            </td>
                            <td class="text-center">
                                {$vo.coupon_title}
                            </td>
                            <td class="text-center">
                                {$vo.coupon_price}
                            </td>
                            <td class="text-center">
                                {$vo.use_min_price}
                            </td>
                            <td class="text-center">
                                {$vo.start_time|date='Y-m-d H:i:s'}
                            </td>
                            <td class="text-center">
                                {$vo.expiry_time|date='Y-m-d H:i:s'}
                            </td>
                            <td class="text-center">
                                {if condition="$vo['status'] eq 1"}
                                占用中
                                {elseif condition="$vo['status'] eq 2"/}
                                已使用
                                {elseif condition="$vo['status'] eq 3"/}
                                已作废
                                {elseif condition="$vo['status'] eq 4"/}
                                已过期
                                {else/}
                                未使用
                                {/if}
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
{/block}
