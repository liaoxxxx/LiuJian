<template>
	<!-- 关于我们页面 -->
	<view class="content">
		<view class="logo">
			<image class="logo_img" src="../../static/logo.png" mode="widthFix"></image>
		</view>
		<view class="app_name">酒来来</view>
		<view class="about_txt">
			简介简介简介简介简介简介简介简介简介简介简介简介
			简介简介简介简介简介简介简介简介简介简介简介简介
			简介简介简介简介简介简介简介简介简介简介简介简介
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
		display: flex;
		flex-direction: column;
		align-items: center;
	}
	.logo{
		padding: 30rpx 0;
	}
	.logo_img{
		width: 250rpx;
		height: 250rpx;
		border-radius: 50%;
	}
	.app_name{
		font-size: 60rpx;
		/* font-weight: bold; */
		padding-bottom: 200rpx;
	}
	.about_txt{
		text-indent:56rpx;
		font-size: 26rpx;
		padding: 0rpx 30rpx;
		color: #999;
	}
</style>
