<template>
	<view class="flex flex-column px-2 py-1">
		<view class="flex justify-between align-center mb-1">
			<view class="flex">
				<view class="mr-2">
					<image :src="data.avatar" class="rounded-circle" style="height: 100rpx; width: 100rpx;" mode=""></image>
				</view>
				<view class="flex flex-column justify-between py-1 font">
					<view>
						<text>{{data.nickname}}</text>
					</view>
					<view>
						<!-- icon -->
						<text v-for="item in Number(data.star)" :key="item" class="iconfont" style="color: #f4ea2a;">&#xe626;</text>
					</view>
				</view>
			</view>
			<view class="font-sm text-grey">
				<text>{{'2010-12-12'}}</text>
			</view>
		</view>
		<view class="font mb-2">
			{{data.comment}}
		</view>
		<view  class="flex justify-between" >
			<image v-for="(item, index) in imgArr" @tap="showImgList(item)" style="height: 150rpx; width: 150rpx;" :key="index" :src="item" mode=""></image>
		</view>
	</view>
</template>

<script>
	export default {
		props: {
			data: {
				type: Object
			}
		},
		data() {
			return {
				imgArr: [
					'/static/index/goods/area_1.png',
					'/static/index/goods/area_1.png',
					'/static/index/goods/area_1.png',
					'/static/index/goods/area_1.png'
				]
			}
		},
		methods: {
			showImgList(item) {
				uni.previewImage({
					current: this.imgArr.item ,
					urls: this.imgArr,
					success() {} ,
				})
			}
		}
	}
</script>

<style>
</style>
