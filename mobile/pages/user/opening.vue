<template>
	<view class="content">
		<!-- 用户信息 -->
		<view class="flex pt-3 pb-4 pl-3" style="background-color: #1C1C1C;">
			<view class="rounded-circle mr-3">
				<image class="rounded-circle" :src="userCenter.avatar" mode="" style="height: 100rpx; width: 100rpx;"></image>
			</view>
			<view class="flex flex-column text-white">
				<text class="mb-2"> {{userCenter.nickname}} </text>
				<text class="font-sm"> {{vipTime}} </text>
			</view>
		</view>
		<!-- 套餐选择 -->
		<view>
			
		</view>
	</view>
</template>

<script>
	import {vuexData} from '@/common/commonMixin.js'
	export default {
		mixins: [vuexData],
		data() {
			return {
				menus: []
			}
		},
		methods: {
			
		},
		computed: {
			vipTime() {
				return '2020-11-22'
			}
		},
		async onLoad() {
			let vipMenu = await this.$api.getRechargeMenu()
			let flag = checkRes(vipMenu)
			if (flag) {
				let {data} = vipMenu
				this.menus = data
			}
			
		}
	}
</script> 

<style>
	page {
		height: 100vh;
		width: 100%;
		background-color: #F8F9FA;
	}
		
	.content {
			height: 100vh;
			width: 100%;
			background-color: #F8F9FA;
		}
	
</style>
