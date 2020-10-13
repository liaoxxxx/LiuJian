<template>
	<view class="beer_box">
		<view class="beer_box_bg">
			<image class="beer_img" :src="src" mode="widthFix"></image>
			<view class="beer_info u-line-1">{{name}}</view>
		</view>
		<view class="beer_kinds u-line-1">{{content}}</view>
		<view class="beer_price">￥{{price}}</view>
	</view>
</template>

<script>
	export default {
		props: {
			src: {
				type: String
			},
			name: {
				type: String
			},
			content: {
				type: String
			},
			price: {
				type: [String, Number]
			}
		}
	}
</script>

<style scoped>
	.beer_box {
		margin: 0rpx 15rpx 15rpx 15rpx;
		width: 250rpx;
		position: relative;
	}
	
	.beer_box_bg {
		width: 250rpx;
		height: 250rpx;
		background: #e75543;
		border-radius: 10rpx;
		overflow: hidden;
	}
	
	.u-line-1 {
		text-overflow: -o-ellipsis-lastline;
		overflow: hidden;
		text-overflow: ellipsis;
		display: -webkit-box;
		-webkit-line-clamp: 1;
		line-clamp: 1;
		-webkit-box-orient: vertical;
	}
	
	.beer_info {
		font-size: 20rpx;
		height: 66rpx;
		line-height: 56rpx;
		color: #fff;
		padding: 0 10rpx;
	}
	
	.beer_kinds {
		font-size: 22rpx;
		color: #868686;
	}
	
	.beer_price {
		color: #ef0012;
		font-size: 30rpx;
		font-weight: bold;
	}
	
	.beer_img {
		width: 100%;
		height: 100%;
	}
</style>
