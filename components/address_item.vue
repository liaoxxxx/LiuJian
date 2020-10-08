<template>
	<view @tap="$emit('handleTap')" :class="{shadow: shadow}" class="border position-relative rounded flex align-center justify-between py-2">
		<view class="flex-2 flex justify-center align-center">
			<slot name="left">
				<text class="iconfont text-grey" style="font-size: 50rpx;">&#xe60e;</text>
			</slot>
		</view>
		<view class="flex-6">
			<view class="flex align-center mb-2">
				<text class="font-weight-bolder font mr-2">{{address.real_name}}</text>
				<text class="font-weight-bolder font mr-2">{{address.phone}}</text>
				<tag v-if="address.is_default" content="默认" class="mr-2" bold></tag>
				<tag content="家" bold bgColor="#3A78F1"></tag>
			</view>
			<view class="flex">
				<text class="font-sm text-grey">{{address.province}} {{address.city}} {{address.district}}
					{{address.detail}}</text>
			</view>
		</view>
		<view class="flex-1 flex align-center justify-center">
			<slot name="right">
				<text class="iconfont font">&#xe618;</text>
			</slot>
		</view>
		<image v-if="showImg" class="bottom_img position-absolute" style="bottom: 6rpx;" src="/static/address.png"></image>
	</view>
</template>

<script>
	import tag from '@/components/tag.vue'
	export default {
		components: {
			tag
		},
		props: {
			address: {
				type: Object,
				required: true
			},
			shadow: {
				type: Boolean,
				default: false
			},
			showImg: {
				type: Boolean,
				default: false
			}
		}
	}
</script>

<style>
	.bottom_img {
		width: 100%;
		height: 10rpx;
	}
	.shadow {
		box-shadow: 4px 4px 2px #dedede;
	}
</style>
