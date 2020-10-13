<template>
	<view class="news">
		<view class="news_left">
			<image src="/static/index/xiaoxi.gif" mode="widthFix"></image>
		</view>
		<swiper class="news_right" :vertical="true" :circular="true" :autoplay="true" :interval="4000" :duration="500">
			<swiper-item class="flex justify-center align-center" v-for="item in list" :key="item.id">
				<text class="text-grey font-sm">{{item.info}}</text>
			</swiper-item>
		</swiper>
	</view>
</template>

<script>
	export default {
		props: {
			list: {
				type: Array,
				required: true
			}
		}
	}
</script>

<style scoped>
	.news {
		display: flex;
		flex-direction: row;
		align-items: center;
	}
	
	.news_left image {
		width: 60rpx;
		height: 60rpx;
		margin-left: 10rpx;
	}
	
	.news_right {
		height: 60rpx;
		width: 690rpx;
		color: #333;
	}
</style>
