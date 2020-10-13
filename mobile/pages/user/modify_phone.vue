<template>
	<!-- 修改手机号码 -->
	<view class="content">
		<view class="phoneblock">
			<view class="phonebox">
				<view class="phone">新手机</view>
				<input style="height: 98rpx;color: #666;flex: 1;" placeholder="请输入新手机号码" type="text"/>
			</view>
			<view class="phonebox" style="margin-top: 30rpx;">
				<input style="height: 98rpx;color: #666;flex: 1;" placeholder="请输入手机验证码" type="text"/>
				<view class="">获取验证码</view>
			</view>
		</view>
		
		<button>提交</button>
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

		}
	}
</script>

<style>
	page{
		background: #fff;
	}
	.phoneblock{
		padding-top: 30rpx;
	}
	button::after{
		border: none;
		border-radius: 0;
	}
	button{
		background: linear-gradient(to right,#ff4b2d,#fd4341);
		border-radius: 0;
		color: #fff;
		font-size: 34rpx;
		position: absolute;
		bottom: 0;
		width: 100%;
	}
	
	.phonebox{
		display: flex;
		flex-direction: row;
		align-items: center;
		border: solid #e1e1e1 1rpx;
		height: 100rpx;
		margin: 0 30rpx;
		font-size: 32rpx;
		padding: 0 30rpx;
	}
	.phone{
		padding-right: 30rpx;
	}
	input{
		font-size: 30rpx;
		color: #ababab;
	}
</style>
