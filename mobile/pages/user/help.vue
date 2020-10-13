<template>
	<!-- 帮助反馈页面 -->
	<view class="content">
		<view class="help_top">
			<image class="top_img" src="../../static/user/helptop.png" mode="widthFix"></image>
			<view>欢迎您的反馈和建议~</view>
			<button class="btn" @click="openfeedback">我要反馈</button>
		</view>
		
		<view class="help_menu">
			<view class="help_menu_txt">有问题？试试快速找答案~</view>
			
			<view class="help_menu_1">
				<view class="menu_box">
					<image class="menu_img" src="../../static/index/menu/menu_4.png" mode="widthFix"></image>
					<view>购物流程</view>
				</view>
				<view class="menu_box">
					<image class="menu_img" src="../../static/index/menu/menu_4.png" mode="widthFix"></image>
					<view>发票说明</view>
				</view>
				<view class="menu_box">
					<image class="menu_img" src="../../static/index/menu/menu_4.png" mode="widthFix"></image>
					<view>联系客服</view>
				</view>
				<view class="menu_box">
					<image class="menu_img" src="../../static/index/menu/menu_4.png" mode="widthFix"></image>
					<view>配送费用</view>
				</view>
			</view>
			<view class="help_menu_1">
				<view class="menu_box">
					<image class="menu_img" src="../../static/index/menu/menu_4.png" mode="widthFix"></image>
					<view>常见问题</view>
				</view>
				<view class="menu_box">
					<image class="menu_img" src="../../static/index/menu/menu_4.png" mode="widthFix"></image>
					<view>咨询投诉</view>
				</view>
				<view class="menu_box">
					<image class="menu_img" src="../../static/index/menu/menu_4.png" mode="widthFix"></image>
					<view>积分使用</view>
				</view>
				<view class="menu_box">
					<image class="menu_img" src="../../static/index/menu/menu_4.png" mode="widthFix"></image>
					<view>诚聘英才</view>
				</view>
			</view>
		</view>
		
		
		<view class="help_list_txt">大家都在问</view>
		<view class="help_list">
			<view class="help_list_box">
				<view class="list_box_left">线下体验店</view>
				<view class="list_box_right">
					<image class="more_img" src="../../static/user/more.png" mode="widthFix"></image>
				</view>
			</view>
			<view class="help_list_box">
				<view class="list_box_left">售后政策</view>
				<view class="list_box_right">
					<image class="more_img" src="../../static/user/more.png" mode="widthFix"></image>
				</view>
			</view>	
			<view class="help_list_box">
				<view class="list_box_left">配送时间&时效</view>
				<view class="list_box_right">
					<image class="more_img" src="../../static/user/more.png" mode="widthFix"></image>
				</view>
			</view>
			<view class="help_list_box">
				<view class="list_box_left">怎么绑定手机</view>
				<view class="list_box_right">
					<image class="more_img" src="../../static/user/more.png" mode="widthFix"></image>
				</view>
			</view>
			<view class="help_list_box">
				<view class="list_box_left">退换货流程</view>
				<view class="list_box_right">
					<image class="more_img" src="../../static/user/more.png" mode="widthFix"></image>
				</view>
			</view>
		</view>
		
		
		<view class="help_bottom">联系客服</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
			}
		},
		onLoad() {

		},
		methods: {
			openfeedback(){
				uni.navigateTo({
					url:'feedback'
				})
			}
		}
	}
</script>

<style>
	page{
		background: #fff;
	}
	
	.help_top{
		background: linear-gradient(to right,#ff4b2d,#fd4341);
		padding-bottom: 60rpx;
		padding-top: 50rpx;
		font-size: 32rpx;
		text-align: center;
		color: #fff;
	}
	.top_img{
		width: 100rpx;
		height: 100rpx;
	}
	.btn{
		width: 400rpx;
		color: #f9a013;
		font-size: 28rpx;
		height: 90rpx;
		line-height: 90rpx;
		margin-top: 30rpx;
	}
	.help_bottom{
		color: #f9a013;
		font-size: 28rpx;
		padding-top: 130rpx;
		text-align: center;
		padding-bottom: 10rpx;
	}
	
	
	
	
	.help_menu{
		padding: 30rpx;
	}
	.help_menu_txt{
		color: #999;
		font-size: 30rpx;
	}
	.help_menu_1{
		display: flex;
		flex-direction: row;
	}
	.menu_box{
		flex: 1;
		text-align: center;
		font-size: 28rpx;
		padding: 18rpx 0;
		color: #666;
		font-size: 26rpx;
	}
	.menu_img{
		width: 84rpx;
		height: 84rpx;
	}
	
	
	
	.help_list{
		padding: 0 30rpx;
	}
	.help_list_txt{
		padding-left: 30rpx;
		height: 100rpx;
		line-height: 100rpx;
		background: #f5f5f5;
		color: #999;
		font-size: 30rpx;
	}
	.help_list_box{
		display: flex;
		flex-direction: row;
		justify-content: space-between;
		align-items: center;
		height: 100rpx;
		font-size: 30rpx;
		border-bottom: solid #e1e1e1 1rpx;
	}
	.more_img{
		width: 36rpx;
		height: 36rpx;
	}
</style>
