<template>
	<!-- 我的会员界面 -->
	<view class="content">
		<view class="top_info" style="background: url(../../static/user/vip_bg_2.png)no-repeat;background-size: 100% 100%;">
			<!-- <view>
				<image class="vip_bg" src="../../static/user/vip_bg.png" mode="widthFix"></image>
			</view> -->
			<view class="top_box">
				<image class="head_img" src="../../static/logo.png" mode="widthFix"></image>
				<view class="username">李天霸</view>
				<image class="vip_img" src="../../static/user/vip_2.png" mode="widthFix"></image>
			</view>
			<!-- 进度条 -->
			<view class="progress">
					<view class="progress_title">已拥有45成长值，还差666成长值可升级成高级VIP</view>
					<view class="progress_list">
						<progress percent="80" activeColor="red" active stroke-width="6" />
					</view>
			</view>
		</view>
		
		
		<view class="xian"></view>
		<view class="grow">
			<view class="grow_top">我的成长值</view>
			
			<view class="grow_box" v-for="(item,index) in growlist" :key="index">
				<view class="grow_box_left">{{item.kinds}}</view>
				<view class="grow_box_right">+{{item.integral}}</view>
			</view>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				growlist:[
					{kinds:'购买商品',integral:'199'},
					{kinds:'购买商品',integral:'199'},
					{kinds:'购买商品',integral:'199'}
				]
			}
		},
		onLoad() {

		},
		methods: {

		}
	}
</script>

<style>
	page{
		width: 100%;
		height: 100%;
	}
	.top_info{
		width: 100%;
		height: 300rpx;
	}
	.progress_list{
		width: 70%;
		margin: 0 auto;
		border-radius: 40rpx;
		overflow: hidden;
	}
	.progress_title{
		padding: 26rpx 0;
	}
	.top_box{
		display: flex;
		flex-direction: row;
		align-items: center;
		padding: 30rpx;
		/* z-index: 9999; */
	}
	.username{
		color: #fff;
		font-size: 34rpx;
		z-index: 9999;
		padding: 0 20rpx;
	}
	.progress{
		font-size: 22rpx;
		color: #272727;
		text-align: center;
	}
	.head_img{
		width: 90rpx;
		height: 90rpx;
		border: solid #fff 6rpx;
		border-radius: 50%;
	}
	.vip_img{
		width: 90rpx;
		height: 90rpx;
	}
	
	.xian{
		background: #07C160;
		height: 30rpx;
	}
	
	
	
	.grow_top{
		width: 160rpx;
		height: 70rpx;
		line-height: 70rpx;
		border-bottom: red solid 8rpx;
		margin: 0 auto;
		font-size: 30rpx;
		text-align: center;
		margin-bottom: 50rpx;
	}
	.grow{
		padding: 0 30rpx;
	}
	.grow_box{
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		align-items: center;
		padding: 0 30rpx;
		height: 90rpx;
		line-height: 90rpx;
		border-bottom: #e1e1e1 solid 1rpx;
		font-size: 30rpx;
	}
	.grow_box_right{
		color: #ff3743;
	}
</style>
