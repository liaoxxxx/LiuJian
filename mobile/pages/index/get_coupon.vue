<template>
	<view class="w-100 page">
		<view class="border-box py-2 px-2">
			<view class="position-relative" v-for="item in list" :key="item.id">
				<image src="/static/index/get%20coupons_bg@2x.png" style="height: 200rpx; width: 710rpx;" mode=""></image>
				<view class="flex position-absolute top-0 p-1" style="height: 200rpx; width: 710rpx;z-index: 10;">
					<view class="flex-2 text-white justify-center align-center flex flex-column">
						<view class="flex align-center">
							<text>{{'¥'}}</text>
							<text class="font-weight-bolder" style="font-size: 80rpx;">{{item.coupon_price}}</text>
						</view>
						<view class="flex font" style="max-width: 200rpx;">
							<text>{{item.type | checkType}}</text>
						</view>
					</view>
					<view class="flex-5 flex-column p-2 justify-between flex">
						<view class="flex align-center font">
							<view class="mr-2">
								<tag border color="#e56" rounded="circle" bgColor="#fff" :content="item.product_range_type | checkRangeType"></tag>
							</view>
							<text>{{item.title}}</text>
						</view>
						<view class="flex align-center font-sm text-grey">
							<text>{{item.notes}}</text>
						</view>
						<view class="flex align-center pr-3 justify-between">
							<text class="text-grey font-sm">{{item.start_time + ' - ' + item.expiry_time}}</text>
							<tag v-if="item.used" @handleTap="getCoupon(item.id)" content="已领取" hover rounded="circle" bgColor="#e0e0e0" :size="24" color="#fff"></tag>
							<tag v-else @handleTap="getCoupon(item.id)" content="立即领取" hover rounded="circle" bgColor="#e66" :size="24" color="#fff"></tag>
						</view>
					</view>
				</view>

			</view>
		</view>
	</view>
</template>

<script>
	import tag from '@/components/tag.vue'
	import {
		vuexData
	} from '@/common/commonMixin.js'
	export default {
		components: {
			tag
		},
		filters: {
			checkRangeType(val) {
				let rangeTypeList = [
					'全部商品',
					'指定分类',
					'指定商品'
				]
				let rangeType = rangeTypeList[val]
				return rangeType
			},
			checkType(val) {
				let typeList = [
					'无门槛卷',
					'满减卷'
				]
				return typeList[val]
			}
		},
		mixins: [vuexData],
		data() {
			return {
				list: []
			}
		},
		computed: {
			userCouId() {
				let userCouId = this.userCoupon.map(item => {
					return item.id
				})
				return userCouId
			},
		},
		methods: {
			async getCoupon(id) {
				console.log(id)
				let result = await this.$api.usrAddCoupon({
					couponId: id
				})
				this.checkRes(result, '优惠卷已领取～～')
			}
		},
		async onReady() {
			let result = await this.$api.getCouponList()
			let userCoupon = await this.$api.getUserCoupon(0)
			this.getUserCoupon(userCoupon)

			if (result.status == 200) {
				let {
					data
				} = result
				this.list = data
			}
		}
	}
</script>

<style scoped>
	.page {
		height: 100vh;
		background-color: #eee;
	}

	.bg_img {
		background-image: url('~@/static/index/get%20coupons_bg@2x.png');
		background-size: 100% 100%;
		background-repeat: no-repeat;
		background-position: left top;
	}
</style>
