<template>
	<view class="content">
		<view v-if="orderDetail.id">
			<list-item v-for="item in orderDetail.cartInfo" :showContent="false">
				<view slot="left" class="flex">
					<view class="flex-2">
						<image :src="item.productInfo.image" mode="" style="height: 200rpx; width: 200rpx;"></image>
					</view>
					<view class="flex-4 flex flex-column">
						<text class="u-line-2 font-md font-weight-bold mb-2"> {{item.productInfo.store_name}} </text>
						<text class="text-grey"> {{item.cart_num}} {{item.productInfo.unit_name}}</text>
					</view>
				</view>
			</list-item>
		</view>
		<view v-else>
			<list-item>
				<view slot="left">
					<image src="" mode=""></image>
				</view>
			</list-item>
		</view>
		<halving-line bgColor="#F9F9FA"></halving-line>
		<view>
			<list-item :showContent="false" border="bottom">
				<view slot="left" class="flex" hover-class="bg-hover" @tap="toRefundRea(1)">
					<view class="mr-3">
						<text class="iconfont font-lg" style="color: #FF854A; font-size: 50rpx;">&#xe63d;</text>
					</view>
					<view class=" flex flex-column">
						<text class="u-line-2 font-md font-weight-bold mb-2"> 我要退货退款 </text>
						<text class="text-grey font"> 已收到货，需要退还已收到的货物</text>
					</view>
				</view>
			</list-item>
			<list-item :showContent="false">
				<view slot="left" class="flex" hover-class="bg-hover" @tap="toRefundRea(2)">
					<view class="mr-3">
						<text class="iconfont" style="color: #FF854A; font-size: 50rpx;">&#xe64c;</text>
					</view>
					<view class="flex flex-column">
						<text class="u-line-2 font-md font-weight-bold mb-2"> 我要退款（无需退货） </text>
						<text class="text-grey font"> 未收到货，或与商家协商之后申请</text>
					</view>
				</view>
			</list-item>
		</view>
	</view>
</template>

<script>
	import listItem from '@/components/list_item.vue'
	import {
		vuexData
	} from '@/common/commonMixin.js'
	export default {
		components: {
			listItem
		},
		mixins: [vuexData],
		data() {
			return {
				uni: null,
				goodsId: null,
				orderId: null
			}
		},
		async onLoad(option) {
			// 两种情况 整单退款和单商品退款
			let {
				orderId,
				goodsId,
				uni
			} = option
			if (orderId) {
				this.orderId = orderId
				this.clearCartAndOrder('orderDetail')
				this.getOrderDet()
			}
			if (goodsId) {
				this.goodsId = goodsId
				this.uni = uni
				this.clearGoods('goodsDetail')
				this.getGoodDet()
			}
			// let res = await this.$api.getRefund()
			// console.log(res)
		},
		methods: {
			/**
			 * @author				前端小能手
			 * getOrderDet			获取订单详情
			 * getGoodDet			获取商品详情	
			 * toRefundRea			前往选择退款理由		
			 */
			async getOrderDet() {
				uni.showLoading({
					title: '努力加载中...'
				})
				let res = await this.$api.getOrderDetail(this.orderId)
				uni.hideLoading()
				this.getOrderDetail(res)
			},
			async getGoodDet() {
				uni.showLoading({
					title: '努力加载中...'
				})
				let detail = await this.$api.getProductDetail(`${this.goodsId}`)
				uni.hideLoading()
				this.getGoodsDetail(detail)
			},
			toRefundRea(type) {
				uni.navigateTo({
					url: '/pages/order/refund_reason?type=' + type + '&id=' + this.orderId
				})
			}
		}
	}
</script>

<style scoped>
	page {
		height: 100vh;
		width: 100%;
	}

	.content {
		height: 100vh;
		width: 100%;
		background-color: #F9F9FA;
	}
</style>
