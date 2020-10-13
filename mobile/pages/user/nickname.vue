<template>
	<!-- 修改昵称页面 -->
	<view class="content">
		<view class="nickbox">
			<view class="nick">昵称</view>
			<input style="height: 98rpx;color: #666;flex: 1;" placeholder="请输入您的昵称" type="text"/>
		</view>
		<view class="bottom_txt">
			4~20个字符，可由数字、中英文、"_"、"."组成
		</view>
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
	
	.nickbox{
		display: flex;
		flex-direction: row;
		align-items: center;
		border: solid #e1e1e1 1rpx;
		height: 100rpx;
		margin: 0 30rpx;
		font-size: 32rpx;
		padding: 0 30rpx;
	}
	.nick{
		padding-right: 30rpx;
	}
	input{
		font-size: 30rpx;
		color: #ababab;
	}
	.bottom_txt{
		font-size: 26rpx;
		color: #ff8d3f;
		padding: 30rpx;
	}
</style>
