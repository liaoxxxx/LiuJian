<template>
	<view class="content">
		<view class="us_top">
			<view class="navbar">个人中心</view>
			<view class="us_info">
				<view class="us_info_left">
					<view>
						<image class="head_img" :src="userCenter.avatar" mode="widthFix"></image>
					</view>
					<view>
						<!-- <view class="us_login" @click="openlogin">登录</view> -->
						<view class="us_login">{{userCenter.nickname}}</view>
						<view class="grow_up"  @click="openvip">成长值 0 ></view>
					</view>
					<view style="margin-top: -60rpx;">
						<image class="vip_img" v-if="userCenter.vip" @tap="openvip" src="../../static/user/vip_2.png" mode="widthFix"></image>
						<text class="font-sm ml-2 check_vip" v-else @tap="opening" > 开通 vip>></text>
					</view>
				</view>
				<view class="us_info_right" @click="openset">
					<image class="set_img" src="../../static/user/set-s.png" mode="widthFix"></image>
				</view>
			</view>
			<view class="us_menu">
				<view class="menu_box" @click="openintegral">
					<view class="num">{{userCenter.integral}}</view>
					<view>积分</view>
				</view>
				<view class="menu_box" @click="opendiscount">
					<view class="num"> {{ userCenter.couponCount }}</view>
					<view>优惠券</view>
				</view>
				<view class="menu_box" @click="opencollect">
					<view class="num">{{userCenter.like}}</view>
					<view>收藏</view>
				</view>
				<view class="menu_box" @click="openfootprint">
					<view class="num">0</view>
					<view>足迹</view>
				</view>
			</view>
		</view>
		
		<view class="us_order">
			<view class="us_order_top">
				<view>我的订单</view>
				<view class="all_order">
					全部订单
					<image class="more_img" src="../../static/user/more.png" mode="widthFix"></image>
				</view>
			</view>
			<view class="order_menu">
				<view class="order_menu_list" @tap="goOrderList(0)">
					<view v-if="userCenter.orderStatusNum && userCenter.orderStatusNum.unpaid_count > 0" class="quan">{{userCenter.orderStatusNum.unpaid_count}}</view>
					<view>
						<image class="us_1" src="../../static/user/us_1.png" mode="widthFix"></image>
					</view>
					<view>待付款</view>
				</view>
				<view class="order_menu_list" @tap="goOrderList(1)">
					<view v-if="userCenter.orderStatusNum && userCenter.orderStatusNum.unshipped_count > 0" class="quan">{{userCenter.orderStatusNum.unshipped_count}}</view>
					<view>
						<image class="us_1" src="../../static/user/us_2.png" mode="widthFix"></image>
					</view>
					<view>待发货</view>
				</view>
				<view class="order_menu_list" @tap="goOrderList(2)">
					<view v-if="userCenter.orderStatusNum && userCenter.orderStatusNum.received_count > 0" class="quan">{{userCenter.orderStatusNum.received_count}}</view>
					<view>
						<image class="us_1" src="../../static/user/us_3.png" mode="widthFix"></image>
					</view>
					<view>待收货</view>
				</view>
				<view class="order_menu_list" @tap="goOrderList(3)">
					<view v-if="userCenter.orderStatusNum && userCenter.orderStatusNum.evaluated_count > 0" class="quan">{{userCenter.orderStatusNum.evaluated_count}}</view>
					<view>
						<image class="us_1" src="../../static/user/us_4.png" mode="widthFix"></image>
					</view>
					<view>待评价</view>
				</view>
			</view>
		</view>
		
		<view class="us_util">
			<view class="us_util_list" @click="openexchange">
				<view class="us_util_left">
					<image class="util_img" src="../../static/user/youhui.png" mode="widthFix"></image>
					<view>兑换优惠</view>
				</view>
				<view class="us_util_right">
					<image class="more_img_2" src="../../static/user/more.png" mode="widthFix"></image>
				</view>
			</view>
			<view class="us_util_list" @click="openadd">
				<view class="us_util_left">
					<image class="util_img" src="../../static/user/dizhi.png" mode="widthFix"></image>
					<view>地址管理</view>
				</view>
				<view class="us_util_right">
					<image class="more_img_2" src="../../static/user/more.png" mode="widthFix"></image>
				</view>
			</view>
			<view class="us_util_list" @click="openinvoice">
				<view class="us_util_left">
					<image class="util_img" src="../../static/user/fapiao.png" mode="widthFix"></image>
					<view>发票管理</view>
				</view>
				<view class="us_util_right">
					<image class="more_img_2" src="../../static/user/more.png" mode="widthFix"></image>
				</view>
			</view>
			<view class="us_util_list" @click="openhelp">
				<view class="us_util_left">
					<image class="util_img" src="../../static/user/help.png" mode="widthFix"></image>
					<view>帮助反馈</view>
				</view>
				<view class="us_util_right">
					<image class="more_img_2" src="../../static/user/more.png" mode="widthFix"></image>
				</view>
			</view>
		</view>
		
		<!-- #ifndef APP-PLUS -->
		<footer-tabbar></footer-tabbar>
		<!-- #endif -->
	</view>
</template>

<script>
	import {vuexData} from '@/common/commonMixin.js'
	export default {
		data() {
			return {
			}
		},
		mixins: [vuexData],
		onLoad() {
			
		},
		methods: {
			//登录
			openlogin(){
				uni.navigateTo({
					url:'../login/login'
				})
			},
			//我的会员
			openvip(){
				uni.navigateTo({
					url:'my_vip'
				})
			},
			// 设置
			openset(){
				uni.navigateTo({
					url:'set'
				})
			},
			//积分
			openintegral(){
				uni.navigateTo({
					url:'integral'
				})
			},
			//我的优惠券
			opendiscount(){
				uni.navigateTo({
					url:'discount'
				})
			},
			//我的收藏
			opencollect(){
				uni.navigateTo({
					url:'collection'
				})
			},
			//我的足迹
			openfootprint(){
				uni.navigateTo({
					url:'footprint'
				})
			},
			//帮助反馈
			openhelp(){
				uni.navigateTo({
					url:'help'
				})
			},
			openadd(){
				uni.navigateTo({
					url:'/pages/user/address_list?form=forUser'
				})
			},
			// 发票管理
			openinvoice(){
				uni.navigateTo({
					url:'invoice'
				})
			},
			// 兑换优惠
			openexchange(){
				uni.navigateTo({
					url:'exchange'
				})
			},
			goOrderList(type) {
				// #ifdef APP-PLUS
				uni.switchTab({
					url: '/pages/order/order?type=' + type
				})
				// #endif
				// #ifndef APP-PLUS
				uni.navigateTo({
					url: '/pages/order/order?type=' + type
				})
				// #endif
			},
			opening() {
				uni.navigateTo({
					url: '/pages/user/opening'
				})
			}
			// getUserCenter(){
			// 	listIn(getUser)
			// 	.then((res)=>{
			// 		this.userData = res[1].data
			// 		console.log(this.userData)
			// 	})
			// 	.catch((err)=>{
			// 		console.log(err)
			// 	})
			// }
			
		},
		async onShow() {
			let userCenter = await this.$api.getUserCenter()
			this.getUserCenter(userCenter)
		},
		async onReady() {
			// let userCenter = await this.$api.getUserCenter()
			// this.getUserCenter(userCenter)
		},
		mounted() {
			// this.getUserCenter()
		}
	}
</script>

<style>
	page{
		background: #fff;
	}
	.content{
		background: #f5f5f5;
	}
	.us_top{
		background: linear-gradient(to right,#ff4b2d,#fd4341);
		height: 500rpx;
		color: #fff;
	}
	.navbar {
		height: 128rpx;
		/* background-color: #000; */
		line-height: 182rpx;
		text-align: center;
		font-size: 36rpx;
		color: #fff;
	}
	.us_info{
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		padding: 30rpx;
	}
	.us_info_left{
		display: flex;
		flex-direction: row;
		align-items: center;
		line-height: 52rpx;
	}
	.grow_up{
		font-size: 26rpx;
		color: #fff;
	}
	.head_img{
		border-radius: 50%;
		width: 110rpx;
		height: 110rpx;
		margin-right: 15rpx;
	}
	.vip_img{
		width: 58rpx;
		height: 24rpx;
	}
	.check_vip {
		text-decoration: underline;
	}
	.set_img{
		width: 50rpx;
		height: 50rpx;
	}
	.us_menu{
		display: flex;
		flex-direction: row;
		font-size: 24rpx;
	}
	.menu_box{
		flex: 1;
		text-align: center;
	}
	.num{
		font-size: 36rpx;
	}
	
	
	/* 我的订单 */
	.us_order{
		background: #fff;
		margin: 0 30rpx;
		margin-top: -50rpx;
		padding-bottom: 30rpx;
		border-radius: 20rpx;
		margin-bottom: 30rpx;
	}
	.us_order_top{
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		font-size: 30rpx;
		font-weight: bold;
		padding-bottom: 30rpx;
		padding: 30rpx;
	}
	.all_order{
		display: flex;
		flex-direction: row;
		align-items: center;
		font-size: 26rpx;
		color: #9d9d9d;
		font-weight: normal;
	}
	.more_img{
		width: 26rpx;
		height: 30rpx;
	}
	.more_img_2{
		width: 34rpx;
		height: 36rpx;
	}
	.order_menu{
		display: flex;
		flex-direction: row;
		font-size: 26rpx;
		color: #9d9d9d;
		padding-bottom: 20rpx;
	}
	.order_menu_list{
		flex: 1;
		text-align: center;
	}
	.us_1{
		width: 60rpx;
		height: 60rpx;
	}
	.quan{
		width: 46rpx;
		height: 30rpx;
		background: #f05b5c;
		color: #fff;
		font-size: 20rpx;
		border-top-left-radius: 14rpx;
		border-top-right-radius: 8rpx;
		border-bottom-right-radius: 14rpx;
		position: absolute;
		margin-left: 104rpx;
		margin-top: -10rpx;
	}
	
	
	
	
	.us_util{
		height: 100%;
		background: #fff;
	}
	.us_util_list{
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 1rpx;
		height: 100rpx;
		margin: 0 30rpx;
		font-size: 30rpx;
		font-weight: bold;
		border-bottom: solid #e1e1e1 1rpx;
	}
	.us_util_left{
		display: flex;
		flex-direction: row;
		align-items: center;
	}
	.util_img{
		width: 60rpx;
		height: 60rpx;
		margin-right: 20rpx;
	}
</style>
