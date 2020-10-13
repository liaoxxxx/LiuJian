<template>
	<!-- 修改密码页面 -->
	<view class="content">
		<view class="passbox">
			<view class="pass">新密码</view>
			<input style="height: 98rpx;color: #666;flex: 1;" placeholder="请输入新密码" type="text"/>
		</view>
		<view class="passbox" style="margin-top: 30rpx;">
			<view class="pass">确认密码</view>
			<input style="height: 98rpx;color: #666;flex: 1;" placeholder="请输入新密码" type="text"/>
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
	.content{
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
	
	.passbox{
		display: flex;
		flex-direction: row;
		align-items: center;
		border: solid #e1e1e1 1rpx;
		height: 100rpx;
		margin: 0 30rpx;
		font-size: 32rpx;
		padding: 0 30rpx;
	}
	.pass{
		padding-right: 30rpx;
	}
	input{
		font-size: 30rpx;
		color: #ababab;
	}
</style>
