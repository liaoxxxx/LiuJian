<template>
	<view>
		<!-- <view v-if="type==0">
			
		</view> -->
		<view v-if="loading" class="w-100 flex justify-center align-center" style="height: 450rpx;">
			<my-loading></my-loading>
		</view>
		<template v-else>
			<!-- 顶部物流  收货地址 -->
			<view class="px-2">
				<list-item :showContent="false" border="bottom">
					<view class="" slot="left" class="flex align-center">
						<text class="iconfont font-lg mr-2 text-grey mr-3" style="font-size: 58rpx;">&#xe70e;</text>
						<view v-if="type == 0" class="flex">
							<text class="mr-2">订单未支付，暂无物流信息</text>
							<text class="" style="color: #007BFF"> 去支付>> </text>
						</view>
						<view v-else class="flex flex-column text-grey">
							<text class="font mb-1" style="color: #4D9932;">{{orderDetail._status._msg}}</text>
							<text class="font-sm">2017-01-09 13:22:09</text>
						</view>
					</view>
					<view slot="right" v-if="orderDetail.status > 0" class="ml-4">
						<text class="iconfont text-grey font-lg">&#xe618;</text>
					</view>
				</list-item>
				<list-item :showContent="false">
					<view class="" slot="left" class="flex align-center">
						<text class="iconfont font-lg mr-2 text-grey mr-3" style="font-size: 66rpx;">&#xe655;</text>
						<view class="flex flex-column">
							<text class="font-lg mb-1 font-weight-bolder">{{orderDetail.real_name}} {{orderDetail.user_phone}}</text>
							<text class="font text-grey">{{orderDetail.user_address}}</text>
						</view>
					</view>
				</list-item>
			</view>
			<halving-line bgColor="#f0f0f0"></halving-line>
			<!-- 商品列表 -->
			<view class="px-2" v-if="orderDetail.cartInfo">
				<list-item :showContent="false" v-for="item in orderDetail.cartInfo" :key="item.id">
					<view slot="left" class="flex">
						<view class="flex justify-center align-center mr-2">
							<image style="height: 200rpx; width: 200rpx;" :src="item.productInfo.image"></image>
						</view>
						<view class="flex flex-column justify-between">
							<view class="flex">
								<text class="flex-6 u-line-2 font"> {{item.productInfo.store_name}} </text>
								<view class="flex-1 flex flex-column align-end justify-between">
									<text class="font">¥{{item.truePrice}}</text>
									<text class="text-grey">X{{item.cart_num}}</text>
								</view>
							</view>
							<view class="flex justify-end" v-if="type != 0">
								<my-btn bgColor="#fff" :py="1" border content="申请退款"></my-btn>
							</view>
						</view>
					</view>
				</list-item>
				<list-item title="商品金额:" :leftFont="{fontSize: '30rpx', color: '#999999',fontWeight: 400}" :contentFont="{fontSize: '30rpx', color: '#999999',fontWeight: 400}"
				 :content="'¥' + orderDetail.pay_price">
				</list-item>
				<list-item title="总计:" :leftFont="{fontSize: '30rpx', color: '#999999',fontWeight: 400}" :contentFont="{fontSize: '30rpx', color: '#999999',fontWeight: 400}"
				 :content="'¥' + orderDetail.pay_price">
				</list-item>
				<list-item title="应付金额:" :leftFont="{fontSize: '30rpx', color: '#999999',fontWeight: 400}" :contentFont="{fontSize: '30rpx', color: '#EA4149',fontWeight: 500}"
				 :content="'¥' + orderDetail.pay_price">
				</list-item>
			</view>
			<halving-line bgColor="#f0f0f0"></halving-line>
			<!-- 订单号 时间 -->
			<view class="px-2">
				<list-item :title="'订单编号：' + orderDetail.order_id" :leftFont="{fontSize: '30rpx', color: '#999999',fontWeight: 400}"
				 :showContent="false">
					<view slot="right">
						<my-btn @handleTap="copyOdd" bgColor="#fff" :py="1" border content="复制"></my-btn>
					</view>
				</list-item>
				<list-item :showContent="false" :title="'下单时间：' + orderDetail._add_time" :leftFont="{fontSize: '30rpx', color: '#999999',fontWeight: 400}">
				</list-item>
			</view>

      <halving-line bgColor="#f0f0f0"></halving-line>
      <view v-if="orderInfoUserPreCommitList !=null ">
        <view>预约信息</view>
        <view>
          <user-pre-commit class="order-info-user-pre-commit-list" :orderInfoUserPreCommitList="orderInfoUserPreCommitList"></user-pre-commit>
        </view>
      </view>



      <halving-line bgColor="#f0f0f0" v-for="item in 8" :key="item"></halving-line>
			<view class="position-fixed bottom-0 flex justify-end w-100 py-2 bg-white">
				<view class="mr-2" v-if="type == 0">
					<my-btn bgColor="#fff" :py="1" border content="取消订单"></my-btn>
				</view>
				<view class="mr-2" v-if="type == 0">
					<my-btn bgColor="#fff" :py="1" border content="立即支付"></my-btn>
				</view>
				<view class="mr-2" v-if="type == 1">
					<my-btn bgColor="#fff" :py="1" border content="提醒发货"></my-btn>
				</view>
				<view class="mr-2" v-if="type == 2">
					<my-btn bgColor="#fff" :py="1" border content="查看物流"></my-btn>
				</view>
				<view class="mr-2" v-if="type == 2">
					<my-btn bgColor="#fff" :py="1" border content="确认收货"></my-btn>
				</view>
				<view class="mr-2" v-if="type == 2 || type == 1 || type == 3 || type == 4">
					<my-btn bgColor="#fff" :py="1" border content="申请退款"></my-btn>
				</view>
				<view class="mr-2" v-if="type == 3">
					<my-btn bgColor="#fff" :py="1" border content="立即评价"></my-btn>
				</view>
				<view class="mr-2" v-if="type == -1">
					<my-btn bgColor="#fff" :py="1" border content="查看进度"></my-btn>
				</view>
			</view>
		</template>
	</view>
</template>

<script>
	import {
		vuexData
	} from '@/common/commonMixin.js'
	import listItem from '@/components/list_item.vue'
  import recGoodsItem from "../../components/recGoodsItem";
  import userPreCommit from "../../components/userPreCommit";


  export default {
		mixins: [vuexData],
		components: {
			listItem,
      recGoodsItem,
      userPreCommit
		},
		data() {
			return {
				id: '',
				loading: true,
				type: 0,
			}
		},
		methods: {
			/**
			 * @author			前端小能手
			 * copyOdd			复制单号
			 */
			copyOdd() {
				console.log(this.orderDetail.order_id)
				uni.setClipboardData({
					data: this.orderDetail.order_id.toString(),
					success: (data) => {
						uni.showToast({
							title: '已复制订单号到剪贴板',
							icon: 'none'
						})
					}
				})
			},
		},
		computed: {

		},
		async onLoad(option) {
			/**
			 * @author			前端小能手
			 *	根据传入订单编号获取订单详情信息
			 */
			this.loading = true
			let {
				id, type
			} = option
			this.id = id
			this.type = type ? type : 0
			if (this.id != '') {
				let res = await this.$api.getOrderDetail(this.id)
				this.getOrderDetail(res)
			}
			this.loading = false
		}
	}
</script>

<style>
</style>
